<?php

/**
 * Overrides for spatie/laravel-medialibrary (merged with package defaults).
 *
 * External image optimizers (jpegoptim, pngquant, etc.) use Symfony Process / proc_open.
 * Many Windows and shared-hosting PHP builds disable proc_open — uploads then fail.
 */
return [
    'image_optimizers' => [],

    'queue_conversions_by_default' => env('QUEUE_CONVERSIONS_BY_DEFAULT', false),
];
