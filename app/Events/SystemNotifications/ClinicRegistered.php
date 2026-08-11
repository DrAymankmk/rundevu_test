<?php

namespace App\Events\SystemNotifications;

use App\Contracts\NotifiableSystemEvent;
use App\Models\Clinic;
use App\Models\Package;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ClinicRegistered implements NotifiableSystemEvent
{
    use Dispatchable, SerializesModels;

    public Clinic $clinic;

    public Package $package;

    public array $registrationMeta;

    public function __construct(Clinic $clinic, Package $package, array $registrationMeta = [])
    {
        $this->clinic = $clinic;
        $this->package = $package;
        $this->registrationMeta = $registrationMeta;
    }

    public function notificationEventKey(): string
    {
        return 'clinic.registered';
    }

    public function notificationPayload(): array
    {
        $locale = app()->getLocale();
        $packageName = $locale === 'ar' ? $this->package->name_ar : $this->package->name_en;

        return [
            'clinic' => $this->clinic,
            'package' => $this->package,
            'package_name' => $packageName,
            'meta' => $this->registrationMeta,
        ];
    }
}
