<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Organization form fields by app_type (clinics table)
    |--------------------------------------------------------------------------
    |
    | app_type 1 = clinic, 4 = lab, 5 = pharmacy, 7 = branch
    |
    */

    'app_types' => [1, 4, 5, 7],

    'groups' => [

        'account' => [
            'label_en' => 'Account',
            'label_ar' => 'بيانات الحساب',
            'fields' => ['name', 'email', 'phone', 'password', 'status'],
            'app_types' => [1, 4, 5, 7],
        ],

        'organization' => [
            'label_en' => 'Organization',
            'label_ar' => 'بيانات المؤسسة',
            'fields' => ['parent_id', 'city_id', 'address', 'lat', 'lng', 'info', 'specialization'],
            'app_types' => [1, 4, 5, 7],
        ],

        'licensing' => [
            'label_en' => 'Licensing',
            'label_ar' => 'التراخيص',
            'fields' => ['license_number', 'medical_commercial_license', 'alternative_phone'],
            'app_types' => [1, 4, 5],
        ],

        'contact' => [
            'label_en' => 'Contact officer',
            'label_ar' => 'مسؤول التواصل',
            'fields' => ['communication_officer', 'communication_officer_phone'],
            'app_types' => [1],
        ],

        'package' => [
            'label_en' => 'Package',
            'label_ar' => 'الباقة',
            'fields' => ['package_id', 'specialty_id'],
            'app_types' => [1],
        ],

        'media' => [
            'label_en' => 'Image & social',
            'label_ar' => 'الصورة والتواصل الاجتماعي',
            'fields' => ['image', 'facebook_url', 'instagram_url', 'tiktok_url', 'snapchat_url', 'youtube_url'],
            'app_types' => [1, 4, 5, 7],
        ],

        'loyalty' => [
            'label_en' => 'Loyalty & modules',
            'label_ar' => 'النقاط والوحدات',
            'fields' => ['points_enabled', 'points_category', 'enabled_modules'],
            'app_types' => [1, 4, 5, 7],
        ],
    ],

    'field_app_types' => [
        'name' => [1, 4, 5, 7],
        'email' => [1, 4, 5, 7],
        'phone' => [1, 4, 5, 7],
        'password' => [1, 4, 5, 7],
        'status' => [1, 4, 5, 7],
        'parent_id' => [7],
        'city_id' => [1, 4, 5, 7],
        'address' => [1, 4, 5, 7],
        'lat' => [1],
        'lng' => [1],
        'info' => [1, 4, 5],
        'specialization' => [4, 5],
        'license_number' => [1, 4, 5],
        'medical_commercial_license' => [1, 4, 5],
        'alternative_phone' => [1, 4, 5],
        'communication_officer' => [1],
        'communication_officer_phone' => [1],
        'package_id' => [1],
        'specialty_id' => [1],
        'image' => [1, 4, 5, 7],
        'facebook_url' => [1],
        'instagram_url' => [1],
        'tiktok_url' => [1],
        'snapchat_url' => [1],
        'youtube_url' => [1],
        'points_enabled' => [1, 4, 5, 7],
        'points_category' => [1, 4, 5, 7],
        'enabled_modules' => [1, 4, 5, 7],
    ],

];