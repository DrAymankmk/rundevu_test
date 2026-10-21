<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class GenerateWhatsappQrCodes extends Command
{
    protected $signature = 'frontend:generate-whatsapp-qr';

    protected $description = 'Generate static WhatsApp QR images for the public site footer dock';

    public function handle(): int
    {
        $dir = public_path('frontend/assets/img/qr');
        File::ensureDirectoryExists($dir);

        $targets = [
            'whatsapp-clinic' => 'https://wa.me/message/UZCY4BQEEJYYE1',
            'whatsapp-patient' => 'https://wa.me/message/6OFM5AU3AHNIN1',
        ];

        foreach ($targets as $base => $url) {
            // SVG does not require Imagick; PNG is attempted when Imagick is available.
            $svgPath = $dir . DIRECTORY_SEPARATOR . $base . '.svg';
            QrCode::format('svg')
                ->size(280)
                ->errorCorrection('H')
                ->margin(2)
                ->generate($url, $svgPath);
            $this->info('Wrote ' . $svgPath);

            if (class_exists(\Imagick::class)) {
                $pngPath = $dir . DIRECTORY_SEPARATOR . $base . '.png';
                QrCode::format('png')
                    ->size(280)
                    ->errorCorrection('H')
                    ->margin(2)
                    ->generate($url, $pngPath);
                $this->info('Wrote ' . $pngPath);
            }
        }

        return self::SUCCESS;
    }
}
