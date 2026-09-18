<?php

namespace App\Console\Commands;

use App\Services\Frontend\FrontendPerformanceAuditor;
use Illuminate\Console\Command;

class FrontendPerfCheckCommand extends Command
{
    protected $signature = 'frontend:perf-check
                            {--url= : Optional live URL to fetch (example: http://127.0.0.1:8002)}
                            {--write : Write a markdown report into issues/}
                            {--compress : Compress oversized frontend JPEG backgrounds in place}';

    protected $description = 'Check frontend performance, speed, and SEO rules for this codebase';

    public function handle(FrontendPerformanceAuditor $auditor): int
    {
        if ($this->option('compress')) {
            $this->compressHeavyImages();
        }

        $url = $this->option('url');
        $result = $auditor->audit($url ?: null);

        $this->table(
            ['Status', 'Category', 'Check', 'Detail'],
            collect($result['checks'])->map(function (array $check) {
                return [
                    strtoupper($check['status']),
                    $check['category'],
                    $check['title'],
                    \Illuminate\Support\Str::limit($check['detail'], 80),
                ];
            })->all()
        );

        $failed = collect($result['checks'])->firstWhere('status', 'fail');
        if ($failed) {
            $this->newLine();
            $this->warn('How to fix first failure: ' . $failed['id']);
            $this->line($failed['fix']);
        }

        $summary = $result['summary'];
        $this->newLine();
        $this->info(sprintf(
            'Pass %d / Fail %d / Warn %d',
            $summary['pass'] ?? 0,
            $summary['fail'] ?? 0,
            $summary['warn'] ?? 0
        ));

        if ($this->option('write')) {
            $dir = base_path('issues');
            if (!is_dir($dir)) {
                mkdir($dir, 0775, true);
            }
            $path = $dir . DIRECTORY_SEPARATOR . 'frontend-perf-' . now()->format('Y-m-d_H-i-s') . '.md';
            file_put_contents($path, $auditor->toMarkdown($result));
            $this->info('Wrote ' . $path);
        }

        $this->line('Playbook: docs/FRONTEND_PERFORMANCE.md');

        return ($summary['fail'] ?? 0) > 0 ? Command::FAILURE : Command::SUCCESS;
    }

    private function compressHeavyImages(): void
    {
        if (!function_exists('imagecreatefromjpeg')) {
            $this->error('PHP GD is required for --compress');
            return;
        }

        $targets = [
            'public/frontend/assets/img/bg/breadcumb-bg.jpg',
            'public/frontend/assets/img/bg/breadcumb-about.jpg',
            'public/frontend/assets/img/bg/breadcumb-services.jpg',
            'public/frontend/assets/img/bg/breadcumb-faq.jpg',
            'public/frontend/assets/img/bg/breadcumb-contact.jpg',
            'public/frontend/assets/img/bg/breadcumb-subscription.jpg',
            'public/frontend/assets/img/bg/breadcumb-blog.jpg',
            'public/frontend/assets/img/bg/breadcumb-blog-details.jpg',
            'public/frontend/assets/img/bg/breadcumb-social.jpg',
            'public/frontend/assets/img/bg/breadcumb-doctors.jpg',
            'public/frontend/assets/img/bg/breadcumb-doctor-details.jpg',
            'public/frontend/assets/img/bg/breadcumb-clinic-details.jpg',
            'public/frontend/assets/img/bg/breadcumb-clinics.jpg',
            'public/frontend/assets/img/hero/hero_bg_1_1.jpg',
            'public/frontend/assets/img/hero/hero_bg_5_1.jpg',
            'public/frontend/assets/img/bg/pattern_bg_8.png',
        ];

        foreach ($targets as $relative) {
            $path = base_path($relative);
            if (!is_file($path)) {
                continue;
            }
            $before = filesize($path);
            $this->compressImage($path, 1600, 72);
            $after = filesize($path);
            $this->line(sprintf(
                '%s: %s KB → %s KB',
                $relative,
                round($before / 1024, 1),
                round($after / 1024, 1)
            ));
        }
    }

    private function compressImage(string $path, int $maxWidth, int $quality): void
    {
        $info = @getimagesize($path);
        $mime = is_array($info) ? ($info['mime'] ?? '') : '';

        if ($mime === 'image/png') {
            $image = @imagecreatefrompng($path);
        } elseif ($mime === 'image/webp' && function_exists('imagecreatefromwebp')) {
            $image = @imagecreatefromwebp($path);
        } else {
            $image = @imagecreatefromjpeg($path);
        }

        if (!$image) {
            return;
        }

        $this->resize($image, $maxWidth);

        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        if ($extension === 'png') {
            imagepng($image, $path, 6);
            imagedestroy($image);
            return;
        }

        $truecolor = imagecreatetruecolor(imagesx($image), imagesy($image));
        $white = imagecolorallocate($truecolor, 255, 255, 255);
        imagefilledrectangle($truecolor, 0, 0, imagesx($image), imagesy($image), $white);
        imagecopy($truecolor, $image, 0, 0, 0, 0, imagesx($image), imagesy($image));
        imagedestroy($image);

        imagejpeg($truecolor, $path, $quality);
        imagedestroy($truecolor);
    }

    private function resize(&$image, int $maxWidth): void
    {
        $width = imagesx($image);
        $height = imagesy($image);
        if ($width <= $maxWidth) {
            return;
        }
        $newWidth = $maxWidth;
        $newHeight = (int) round($height * ($maxWidth / $width));
        $resized = imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        imagedestroy($image);
        $image = $resized;
    }
}
