<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['namespace' => 'API'], function () {

    // get cities in home page
    Route::get('cities', 'CityController@index');

    // register app in all cycle
    Route::post('register', 'AuthController@register');

    // login app
    Route::post('login', 'AuthController@login');

    // check phone exist or not
    Route::post('check-phone', 'AuthController@check_phone');


    // change and forget password
    Route::post('forget-password', 'AuthController@forget_password');

    // setting
    Route::get('setting', 'SettingController@index');

    // notifications
    Route::get('notifications', 'NotificationsController@index');

    Route::get('clinic-contract', 'ClinicContractsController@show');
    Route::post('clinic-contract/update', 'ClinicContractsController@update');

    //complaints box CRUD Routes
    Route::group(['namespace' => 'Clinics', 'prefix' => 'complaints_box'], function () {
        Route::get('/', 'ComplaintsBoxController@index');
        Route::post('/send_reply', 'ComplaintsBoxController@send_reply');
    });

    //posts CRUD Routes
    Route::group(['namespace' => 'Clinics', 'prefix' => 'posts'], function () {
        Route::get('/', 'PostsController@index');
        Route::post('/update_or_create', 'PostsController@updateOrCreate');
        Route::delete('/delete/{id}', 'PostsController@delete_post');
    });

    //departments box CRUD Routes
    Route::group(['namespace' => 'Clinics', 'prefix' => 'departments'], function () {
        Route::get('/', 'DepartmentsController@index');
        Route::post('/update_or_create', 'DepartmentsController@updateOrCreate');
        Route::post('/change_status', 'DepartmentsController@change_status_department');
    });

    //staff  CRUD Routes
    Route::group(['namespace' => 'Clinics', 'prefix' => 'attendance'], function () {
        Route::get('/', 'AttendanceController@filter_staff');
        Route::get('/employee', 'AttendanceController@get_attendance_employee');
    });

    Route::group(['namespace' => 'Clinics'], function () {
        // edit on profile
        Route::post('update-profile', 'ProfileController@edit_profile');
        Route::post('change_password', 'ProfileController@change_password');
    });

    //complaints box CRUD Routes
    Route::group(['namespace' => 'Clinics', 'prefix' => 'shift'], function () {
        Route::get('/', 'ShiftController@index');
        Route::post('/update_or_create', 'ShiftController@updateOrCreate');
    });

    Route::group(['namespace' => 'Clinics', 'prefix' => 'specialists'], function () {
        Route::get('/', 'SpecialtiesController@index');
        Route::post('/update_or_create', 'SpecialtiesController@updateOrCreate');
        Route::delete('/delete/{id}', 'SpecialtiesController@delete_specialty');
    });


    //posts CRUD Routes
    Route::group(['namespace' => 'Clinics', 'prefix' => 'offers'], function () {
        Route::get('/', 'OffersController@index');
        Route::post('/update_or_create', 'OffersController@updateOrCreate');
        Route::delete('/delete/{id}', 'OffersController@delete_offer');
    });

    //posts CRUD Routes
    Route::group(['namespace' => 'Clinics', 'prefix' => 'branches'], function () {
        Route::get('/', 'BranchesController@index');
        Route::post('/update_or_create', 'BranchesController@updateOrCreate');
        Route::post('/change_status', 'BranchesController@change_status_branch');
    });

    //employees CRUD Routes
    Route::group(['namespace' => 'Clinics', 'prefix' => 'employees'], function () {
        Route::get('/', 'NewEmployeeController@index');
        Route::get('/degree-doctor', 'NewEmployeeController@degree_doctor');
        Route::post('/update_or_create', 'NewEmployeeController@updateOrCreate');
        Route::delete('/delete/{id}', 'NewEmployeeController@delete_employee');
        Route::get('/shift', 'NewEmployeeController@shifts');
        Route::post('/shift/updateOrCreate', 'NewEmployeeController@updateOrCreateEmployeeShift');
        Route::get('/permission', 'AttendanceController@permissions_employee');

        // permission types
        Route::get('/permission-types', 'PermissionTypesController@permission_types');

    });

    Route::group(['prefix' => 'employees'], function () {
        // permission types
        Route::get('/permission-types', 'PermissionTypesController@permission_types');
        Route::post('/permission-request', 'PermissionTypesController@permission_request');
        Route::post('/attendance-register', 'AttendanceRegisterController@attendance_register');


    });

    //admin CRUD Routes
    Route::group(['namespace' => 'Clinics', 'prefix' => 'clinics'], function () {
        Route::get('/admin', 'AccountAdminController@index');
        Route::get('/admin/permissions', 'AccountAdminController@admin_permissions');
        Route::get('/admin/special-permissions', 'AdminPermissionsController@special_permissions');
        Route::post('/admin/update_or_create', 'AccountAdminController@updateOrCreate');
        Route::post('/admin/permissions/update_or_create', 'AccountAdminController@updateOrCreateAdminPermissions');
        Route::post('/change-status-permission', 'AttendanceController@change_status_permission');
        // clinic points
        Route::get('/points', 'PointsController@points');

    });


    // user app

    //admin CRUD Routes
    Route::group(['namespace' => 'User', 'prefix' => 'user'], function () {

        // delete account
        Route::delete('delete', 'AuthController@delete_account');

        // register app in all cycle
        Route::post('register', 'AuthController@register');

        // login app
        Route::post('login', 'AuthController@login');

        // check phone exist or not
        Route::post('check-phone', 'AuthController@check_phone');

        // check code with phone
        Route::post('check-code', 'AuthController@check_code');

        // change and forget password
        Route::post('forget-password', 'AuthController@forget_password');

        // refresh token
        Route::post('refresh-firebase-token', 'ProfileController@refresh_firebase_token');

        // update profile
        Route::post('update-profile', 'ProfileController@edit_profile');
        // change password
        Route::post('change_password', 'ProfileController@change_password');

        // get communications
        Route::get('communications', 'CommunicationMessagesController@communications');
        // points
        Route::get('points', 'PointsController@points');
        Route::get('electronic-payment', 'PointsController@electronic_payment');
        Route::group(['prefix' => 'loyalty'], function () {
            Route::get('wallet', 'LoyaltyController@wallet');
            Route::get('rewards', 'LoyaltyController@rewards');
            Route::get('partner-sections', 'LoyaltyPartnersController@sections');
            Route::post('redeem', 'LoyaltyController@redeem');
            Route::get('redemptions', 'LoyaltyController@redemptions');
            Route::post('share-app', 'LoyaltyController@shareApp');
            Route::post('share', 'LoyaltyController@share');
        });
        Route::get('waitingList', 'WaitingListController@waitingList');
        Route::get('emergency-hospitals', 'EmergencyHospitalController@index');
        //packages
        Route::group(['prefix' => 'packages'], function () {
            Route::get('/', 'PackagesController@index');
            Route::post('/subscribe', 'PackagesController@subscribe');
        });

        //members CRUD Routes
        Route::group(['prefix' => 'members'], function () {
            Route::get('/', 'MembersController@index');
            Route::post('/update_or_create', 'MembersController@updateOrCreate');
        });

        Route::group(['namespace' => 'Clinics'], function () {
            // get clinics
            Route::get('/clinics', 'ClinicsController@index');
            Route::get('/specialists', 'ClinicsController@specialists');
            Route::get('/clinic-offers', 'ClinicsController@offers');
            Route::get('/clinic-details', 'ClinicsController@clinic_details');
            Route::get('/clinic-doctors', 'ClinicsController@clinic_doctors');
            Route::post('/clinic-complaint', 'ClinicsController@clinic_complaint');

            Route::get('/doctor-appointments', 'DoctorsController@doctor_appointments');
            Route::get('/get-date-appointments', 'DoctorsController@get_date_appointments');


            // send question to doctor
            Route::post('/clinic/doctor-ask', 'DoctorsController@doctor_ask');
            Route::get('/clinic/rating', 'RatingController@index');
            Route::post('/clinic/make-rate', 'RatingController@make_rate');
        });

        // confirm reservation
        Route::post('/confirm-reservation', 'ReservationsController@confirm_reservation');
        Route::post('/cancel-reservation', 'ReservationsController@cancel_reservation');
        // reservations
        Route::get('/reservations', 'ReservationsController@index');

        Route::group(['prefix' => 'invoices'], function () {
            Route::get('/', 'InvoicesController@index');
            Route::get('/details', 'InvoicesController@details');
            Route::post('/pay', 'InvoicesController@pay');
        });


        Route::group(['prefix' => 'reservation'], function () {
            Route::get('/chat', 'ReservationChatController@index');
            Route::post('/chat/create', 'ReservationChatController@create');
            Route::post('/rate', 'ReservationChatController@rate');
        });

        Route::group(['prefix' => 'test-result'], function () {
            Route::get('/', 'TestResultController@index');
            Route::get('/details', 'TestResultController@details');
        });

        Route::group(['prefix' => 'pharmacy-prescription'], function () {
            Route::get('/', 'PharmacyPrescriptionController@index');
            Route::get('/details', 'PharmacyPrescriptionController@details');
        });


        Route::group(['prefix' => 'medical-reports'], function () {
            Route::get('/', 'MedicalReportsController@index');
            Route::post('/add', 'MedicalReportsController@add_report');
        });

        Route::group(['prefix' => 'coupons'], function () {
            Route::get('/', 'CouponController@index');
        });

    });



});
