<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Clinic admin modules
    |--------------------------------------------------------------------------
    |
    | Keys listed here can be stored in clinics.enabled_modules (JSON).
    | null on the organization = legacy full access (existing records).
    | Explicit list example: ["points", "clinic_admin"]
    |
    */

    'modules' => [

        'points' => [
            'label_en' => 'Loyalty Points',
            'label_ar' => 'نقاط الولاء',
            'route_patterns' => ['loyalty.*'],
            'login_route_by_app_type' => [
                1 => 'loyalty.dashboard',
                7 => 'loyalty.dashboard',
                11 => 'loyalty.dashboard',
                2 => 'loyalty.redemptions',
            ],
        ],

        // 'reception' => [
        //     'label_en' => 'Reception',
        //     'label_ar' => 'الاستقبال',
        //     'app_types' => [2],
        //     'route_patterns' => [
        //         'appointments',
        //         'add-patient',
        //         'create-patient',
        //         'patients',
        //         'patients.*',
        //         'add-appointment',
        //         'create-appointment',
        //         'attachments',
        //         'invoices',
        //         'invoice-view',
        //         'create-invoice',
        //         'create-invoice-reservation',
        //         'add-invoice',
        //         'bonds',
        //         'create-bond',
        //         'doctors-requests',
        //         'doctors-requests.*',
        //         'cancelReservation',
        //         'WaitingListReservation',
        //         'complete.reservation',
        //         'chatList',
        //     ],
        // ],

        'clinic_admin' => [
            'label_en' => 'Clinic Management',
            'label_ar' => 'إدارة العيادة',
            'app_types' => [1, 7, 11],
            'route_patterns' => [
                'admin.dashboard',
                'admin.doctors.ratings',
                'departments',
                'add-department',
                'edit-department',
                'offers',
                'add-offer',
                'edit-offer',
                'specialties',
                'branches',
                'add-branch',
                'edit-branch',
                'clinic.supervisor',
                'create-supervisor',
                'contactUs',
                'add-reply',
                'reply',
                'delete-message',
                'posts',
                'employee-clinic-shifts',
                'attendance-departure',
                'view-employee',
                'employee-permissions',
            ],
        ],

        // 'doctors' => [
        //     'label_en' => 'Doctors',
        //     'label_ar' => 'الأطباء',
        //     'app_types' => [3],
        //     'route_patterns' => [
        //         'patients-waiting',
        //         'patient-file',
        //         'medical-prescription',
        //         'patient-services',
        //         'doctor-appointment',
        //         'patient-appointment',
        //         'chat',
        //         'add-chat',
        //         'drugs',
        //         'create-drug',
        //         'add-drug',
        //         'drug-sections',
        //         'doctor-attendance-departure',
        //         'request-permission',
        //         'send-request-permission',
        //         'prescription-record',
        //         'doctor-medical-reports',
        //         'visit-page-reservation',
        //     ],
        // ],

        // 'pharmacy' => [
        //     'label_en' => 'Pharmacy',
        //     'label_ar' => 'الصيدلية',
        //     'app_types' => [5],
        //     'route_patterns' => ['pharmacy.*', 'new-prescription', 'diagnosis-display', 'add-drug-pharmacy'],
        // ],

        // 'lab' => [
        //     'label_en' => 'Laboratory',
        //     'label_ar' => 'المختبر',
        //     'app_types' => [4, 25, 26],
        //     'route_patterns' => [
        //         'services-categories',
        //         'create-services-category',
        //         'category-analysis',
        //         'analysis-attributes',
        //         'patient-analysis',
        //         'upload-result',
        //         'send-result-analysis',
        //         'Re-analysis',
        //         'confirm-receipt',
        //     ],
        // ],

        // 'nursing' => [
        //     'label_en' => 'Nursing',
        //     'label_ar' => 'التمريض',
        //     'app_types' => [8],
        //     'route_patterns' => [
        //         'nursing-staff.*',
        //         'nursing-requests.*',
        //         'emergency.*',
        //         'create-emergency.*',
        //         'store-emergency.*',
        //         'new-patient.*',
        //         'save-patient.*',
        //         'save-note.*',
        //         'normal-exit',
        //         'ambulance-exit',
        //     ],
        // ],

        // 'insurance' => [
        //     'label_en' => 'Insurance',
        //     'label_ar' => 'التأمين',
        //     'app_types' => [10],
        //     'route_patterns' => [
        //         'classes.*',
        //         'companies.*',
        //         'services-discount',
        //         'insurance-policy.*',
        //         'insurance-approvals.*',
        //         'insured-invoices-reports',
        //     ],
        // ],

        // 'finance' => [
        //     'label_en' => 'Financial Accounts',
        //     'label_ar' => 'الحسابات',
        //     'app_types' => [9],
        //     'route_patterns' => [
        //         'accounts',
        //         'category-report',
        //         'account-settings',
        //         'restrictions',
        //         'financial-bonds',
        //         'costCenter',
        //         'ledger',
        //         'trial-balance',
        //         'trading',
        //         'profit-and-loss',
        //         'financial-position',
        //         'invoices-reports',
        //         'income',
        //         'reception-income',
        //         'insurance-claim',
        //         'patients-balance',
        //         'doctor-rate',
        //         'pharmacist-rate',
        //         'vat-report',
        //         'expenses-vat',
        //         'accounts-chart',
        //         'salaries',
        //     ],
        // ],

        // 'platform' => [
        //     'label_en' => 'Platform Admin',
        //     'label_ar' => 'إدارة المنصة',
        //     'app_types' => [6],
        //     'route_patterns' => [
        //         'clinics',
        //         'users',
        //         'complains-box',
        //         'main-specialties',
        //         'reports.*',
        //         'points-list',
        //         'loyalty-organizations',
        //         'loyalty-organizations.*',
        //         'admin-supervisor',
        //         'packages.*',
        //         'permissionsTypes.*',
        //         'demo-requests.*',
        //         'emergency-hospitals.*',
        //         'contact-us.*',
        //         'notification-recipients.*',
        //         'appointments-list',
        //     ],
        // ],

        // 'cms' => [
        //     'label_en' => 'CMS',
        //     'label_ar' => 'إدارة المحتوى',
        //     'route_patterns' => ['cms.*'],
        // ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Routes always reachable when logged in (profile, logout, settings)
    |--------------------------------------------------------------------------
    */
    'always_allowed_route_patterns' => [
        'profile',
        'edit-profile',
        'change-password',
        'add-password',
        'admin.logout',
        'setting',
        'update-setting',
        'app-setting',
        'notifications',
        'notificationsList.*',
        'search',
        'get-clinics',
    ],

    /*
    |--------------------------------------------------------------------------
    | Points-only preset (stored in enabled_modules JSON)
    |--------------------------------------------------------------------------
    */
    'points_only_modules' => ['points'],

];
