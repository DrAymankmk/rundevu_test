<?php


use App\Events\Notify;
use App\Models\CmsLanguage;
use App\Models\Language;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Artisan;

Route::get('/clear', function () {
//    Artisan::call('optimize:clear');
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');

    return 'cleared';
});

Route::get('/pusher', function () {
//    return view('pusher.pusher');

        $data = [
            'title' => $request->title ?? 'New Notification',
            'message' => $request->message ?? 'Hello from Takafoul!',
            'time' => now()->toDateTimeString(),
        ];

        event(new Notify($data));
    broadcast(new Notify($data));
        return response()->json([
            'success' => true,
            'message' => 'Notification sent successfully.',
        ]);

});

Route::get('test', function () {
    event(new App\Events\StatusLiked('Someone'));
    return "Event has been sent!";
});


Route::get('/changeLanguageAdmin/{lang}', function ($lang) {
    $languages = Language::where('status', 1)->get()->toArray();
    $lang_codes = array_column($languages, 'code');
    if (!in_array($lang, $lang_codes)) {
        $lang = 'en';
    }
//    if (auth()->user()) {
//        $user = auth()->user();
//        $user->lang = $lang;
//        $user->save();
//    }
    if (session()->has('lang')) {
        session()->forget('lang');
    }
    session()->put('lang', $lang);


    App::setLocale($lang);
    // Session
    session()->put('lang', $lang);

    session(['lang' => $lang]);
    app()->setLocale($lang);

    return back();
});

// Route::get('/', function () {
//     return view('welcome');
// });

Route::group(['namespace' => 'Frontend', 'middleware' => 'setlocale' , 'as' => 'frontend.'], function () {
    Route::get('/', 'HomeController@index')->name('home');
    Route::get('/about', 'AboutController@index')->name('about');
    Route::get('/services', 'ServiceController@index')->name('services');
    Route::get('/faq', 'FaqController@index')->name('faq');
    Route::get('/subscription', 'SubscriptionController@index')->name('subscription');
    Route::post('/subscription/register', 'SubscriptionController@registerClinic')->name('subscription.register');
    Route::get('/contact', 'ContactController@index')->name('contact');
    Route::get('/blog', 'BlogController@index')->name('blog');
    Route::get('/blog/load-more', 'BlogController@loadMore')->name('blog.load-more');
    Route::get('/blog/{slug}', 'BlogController@show')->name('blog.show');
    Route::get('/clinics', 'ClinicsController@index')->name('clinics');
    Route::get('/clinics/{id}', 'ClinicsController@show')->name('clinics.show')->whereNumber('id');
    Route::get('/doctors', 'DoctorsController@index')->name('doctors');
    Route::get('/doctors/{id}', 'DoctorsController@show')->name('doctors.show')->whereNumber('id');
    Route::get('/social-media', 'SocialMediaController@index')->name('social');
    Route::post('/book-demo', 'ContactController@bookDemo')->name('book_demo');
    Route::post('/contact', 'ContactController@submitContact')->name('contact.submit');
    Route::get('language/{lang}', function (string $lang) {
        $codes = Language::query()->where('status', 1)->pluck('code')->all();
        if ($codes === [] && Schema::hasTable('cms_languages')) {
            $codes = CmsLanguage::query()->active()->ordered()->pluck('code')->all();
        }
        if ($codes === []) {
            $codes = array_values(array_unique(array_filter([
                (string) config('app.locale'),
                (string) config('app.fallback_locale', 'en'),
            ])));
        }
        $lang = in_array($lang, $codes, true) ? $lang : ($codes[0] ?? 'en');
        session(['lang' => $lang]);
        App::setLocale($lang);

        return redirect()->back();
    })->name('language.switch');
});

Route::post('register', 'RegisterController@register')->name('register');


Route::get('terms', 'AdminPanel\SettingController@terms')->name('terms');

Route::group(['namespace' => 'AdminPanel', 'prefix' => 'admin'], function () {

    Route::get('login', 'LoginController@form_login')->name('admin.login');

    Route::post('login', 'LoginController@login');


});


Route::group(["middleware" => ["auth", "setlocale"], 'prefix' => 'admin', 'namespace' => 'AdminPanel'], function () {



// cms routes
    Route::group(['namespace' => 'CMS', 'prefix' => 'cms', 'as' => 'cms.'], function () {
        Route::get('pages', 'CmsPageController@index')->name('pages.index');
        Route::get('pages/data', 'CmsPageController@data')->name('pages.data');
        Route::get('pages/create', 'CmsPageController@create')->name('pages.create');
        Route::get('pages/builder/create', 'CmsPageBuilderController@create')->name('pages.builder.create');
        Route::post('pages/builder', 'CmsPageBuilderController@store')->name('pages.builder.store');
        Route::post('pages', 'CmsPageController@store')->name('pages.store');
        Route::get('pages/{id}/builder', 'CmsPageBuilderController@edit')->name('pages.builder.edit');
        Route::put('pages/{id}/builder', 'CmsPageBuilderController@update')->name('pages.builder.update');
        Route::get('pages/{id}', 'CmsPageController@show')->name('pages.show');
        Route::get('pages/{id}/edit', 'CmsPageController@edit')->name('pages.edit');
        Route::put('pages/{id}', 'CmsPageController@update')->name('pages.update');
        Route::delete('pages/{page}', 'CmsPageController@destroy')->name('pages.destroy');
        Route::post('pages/{page}/toggle-status', 'CmsPageController@toggleStatus')->name('pages.toggleStatus');
    });

// cms sections
    Route::group(['namespace' => 'CMS', 'prefix' => 'cms', 'as' => 'cms.'], function () {
        Route::get('sections', 'CmsSectionController@index')->name('sections.index');
        Route::get('sections/data', 'CmsSectionController@data')->name('sections.data');
        Route::get('sections/create', 'CmsSectionController@create')->name('sections.create');
        Route::post('sections', 'CmsSectionController@store')->name('sections.store');
        Route::get('sections/{id}', 'CmsSectionController@show')->name('sections.show');
        Route::get('sections/{id}/edit', 'CmsSectionController@edit')->name('sections.edit');
        Route::put('sections/{id}', 'CmsSectionController@update')->name('sections.update');
    });


// cms items
    Route::group(['namespace' => 'CMS', 'prefix' => 'cms', 'as' => 'cms.'], function () {
        Route::get('items', 'CmsItemController@index')->name('items.index');
        Route::get('items/data', 'CmsItemController@data')->name('items.data');
        Route::get('items/create', 'CmsItemController@create')->name('items.create');
        Route::post('items', 'CmsItemController@store')->name('items.store');
        Route::get('items/{id}', 'CmsItemController@show')->name('items.show');
        Route::get('items/{id}/edit', 'CmsItemController@edit')->name('items.edit');
        Route::put('items/{id}', 'CmsItemController@update')->name('items.update');
    });

// cms media
    Route::group(['namespace' => 'CMS', 'prefix' => 'cms', 'as' => 'cms.'], function () {
        Route::get('media', 'MediaController@index')->name('media.index');
        Route::get('media/data', 'MediaController@data')->name('media.data');
        Route::get('media/collections', 'MediaController@getCollections')->name('media.collections');
        Route::post('media/bulk-delete', 'MediaController@bulkDestroy')->name('media.bulk-delete');
        Route::get('media/create', 'MediaController@create')->name('media.create');
        Route::post('media', 'MediaController@store')->name('media.store');
        Route::put('media/{id}', 'MediaController@update')->name('media.update');
        Route::delete('media/{id}', 'MediaController@destroy')->name('media.destroy');
    });

// cms links
    Route::group(['namespace' => 'CMS', 'prefix' => 'cms', 'as' => 'cms.'], function () {
        Route::get('links', 'CmsLinkController@index')->name('links.index');
        Route::get('links/data', 'CmsLinkController@data')->name('links.data');
        Route::get('links/create', 'CmsLinkController@create')->name('links.create');
        Route::post('links', 'CmsLinkController@store')->name('links.store');
        Route::get('links/{id}', 'CmsLinkController@show')->name('links.show');
        Route::get('links/{id}/edit', 'CmsLinkController@edit')->name('links.edit');
        Route::put('links/{id}', 'CmsLinkController@update')->name('links.update');
        Route::delete('links/{id}', 'CmsLinkController@destroy')->name('links.destroy');
        Route::post('links/{id}/toggle-status', 'CmsLinkController@toggleStatus')->name('links.toggleStatus');
    });

// blog module
    Route::group(['namespace' => 'Blog', 'prefix' => 'blog', 'as' => 'blog.'], function () {
        Route::get('categories', 'BlogCategoryController@index')->name('categories.index');
        Route::get('categories/data', 'BlogCategoryController@data')->name('categories.data');
        Route::get('categories/create', 'BlogCategoryController@create')->name('categories.create');
        Route::post('categories', 'BlogCategoryController@store')->name('categories.store');
        Route::get('categories/{id}/edit', 'BlogCategoryController@edit')->name('categories.edit');
        Route::put('categories/{id}', 'BlogCategoryController@update')->name('categories.update');
        Route::delete('categories/{id}', 'BlogCategoryController@destroy')->name('categories.destroy');
        Route::post('categories/{id}/toggle-status', 'BlogCategoryController@toggleStatus')->name('categories.toggleStatus');

        Route::get('posts', 'BlogPostController@index')->name('posts.index');
        Route::get('posts/data', 'BlogPostController@data')->name('posts.data');
        Route::get('posts/create', 'BlogPostController@create')->name('posts.create');
        Route::post('posts', 'BlogPostController@store')->name('posts.store');
        Route::get('posts/{id}/edit', 'BlogPostController@edit')->name('posts.edit');
        Route::put('posts/{id}', 'BlogPostController@update')->name('posts.update');
        Route::delete('posts/{id}', 'BlogPostController@destroy')->name('posts.destroy');
        Route::post('posts/{id}/toggle-status', 'BlogPostController@toggleStatus')->name('posts.toggleStatus');
    });

    Route::group(['namespace' => 'Pharmacy', 'prefix' => 'pharmacy'], function () {


        Route::name('pharmacy.')->group(function () {

            Route::resource('pharmacy', 'ClinicController')->except('destroy', 'show', 'create', 'edit');

            Route::resource('medicine-departments', 'MedicineDepartmentController')
                ->except('create', 'edit', 'destroy', 'show');

            Route::get('search-medicine-departments', 'MedicineDepartmentController@search')
                ->name('medicine-departments.search');

            Route::get('delete-medicine-department-id/{medicine_department_id}', 'MedicineDepartmentController@delete')
                ->name('medicineDepartment.delete');

            Route::get('get-drugs-by-drug_id/{medicine_department_id}', 'DrugController@getDrugsByDrugId')
                ->name('medicineDepartment.getDrugsById');


            Route::resource('drugs', 'DrugController')->except('create', 'destroy', 'show');
            Route::get('delete-drug/{drug_id}', 'DrugController@delete')->name('drugs.delete');
            Route::get('get-alternatives-drugs/{drug_id}', 'DrugController@getAlternativesDrugs')->name('drugs.getAlternativesDrugs');
            Route::put('update-alternative-drug', 'DrugController@updateAlternativeDrug')->name('drugs.updateAlternativeDrug');
            Route::get('delete-alternative-drug/{drug_id}', 'DrugController@deleteAlternativeDrug')
                ->name('drugs.deleteAlternativeDrug');

            Route::get('get-balances-of-drug/{drug_id}', 'DrugController@getBalancesOfDrug')
                ->name('drugs.getBalancesOfDrug');
            Route::get('search-drug', 'DrugController@search')->name('drugs.search');

            // item unit
            Route::resource('item-units', 'ItemUnitController')->except('create', 'edit', 'destroy', 'show');
            Route::get('delete-unit/{item_unit_id}', 'ItemUnitController@delete')->name('itemUnits.delete');
            Route::get('search-unit', 'ItemUnitController@search')->name('units.search');


            Route::resource('customers', 'CustomerController')->except('create', 'destroy', 'show');
            Route::get('delete-customer/{customerId}', 'CustomerController@delete')->name('customers.delete');
            Route::get('search-customer', 'CustomerController@search')->name('customers.search');


            Route::resource('suppliers', 'SupplierController')->except('create', 'destroy', 'show');
            Route::get('delete-supplier/{supplierId}', 'SupplierController@delete')->name('suppliers.delete');
            Route::get('search-supplier', 'SupplierController@search')->name('suppliers.search');


            Route::resource('stores', 'StoreController')->except('create', 'destroy', 'show');
            Route::get('delete-store/{store_id}', 'StoreController@delete')->name('stores.delete');
            Route::get('search-store', 'StoreController@search')->name('stores.search');


            Route::resource('inventory-records', 'InventoryRecordController')->except('create', 'destroy', 'show');
            Route::get('delete-inventory-record/{inventory_record_id}', 'InventoryRecordController@delete')->name('inventoryRecord.delete');
            Route::get('search-inventory-record', 'InventoryRecordController@search')->name('inventory-records.search');


            Route::resource('inventory', 'InventoryController')->except('create', 'destroy', 'show');
            Route::get('delete-inventory/{inventory_id}', 'InventoryController@delete')->name('inventory.delete');
            Route::get('get-item-not-have-inventory', 'InventoryController@getItemsNotHaveInventor')->name('inventory.getItemsNotHaveInventor');
            Route::get('data-item-not-have-inventory', 'InventoryController@dataItemsNotHaveInventor')->name('inventory.dataItemsNotHaveInventor');

            Route::get('search-inventory', 'InventoryController@search')->name('inventory.search');

            Route::resource('receipts-payments', 'ReceiptPaymentBondController')->except('create', 'destroy', 'show');
            Route::get('search-receipts-payments', 'ReceiptPaymentBondController@search')->name('receipts-payments.search');

            Route::get('get-tax-by-id-in-drugs', 'PurchaseInvoiceController@getItemById')->name('getItemById');


            Route::resource('purchase-invoice', 'PurchaseInvoiceController')->except('destroy', 'show');
            Route::get('delete-purchase-invoice/{purchase_invoice_id}', 'PurchaseInvoiceController@delete')->name('PurchaseInvoice.delete');
            Route::get('search-purchase-invoice', 'PurchaseInvoiceController@search')->name('purchase-invoice.search');

            Route::resource('sale-invoice', 'SaleInvoiceController')->except('destroy', 'show');
            Route::get('delete-sale-invoice/{sale_invoice_id}', 'SaleInvoiceController@delete')->name('SaleInvoice.delete');
            Route::get('search-sale-invoice', 'SaleInvoiceController@search')->name('sale-invoice.search');

            Route::resource('patient-sale-invoice', 'PatientSaleInvoiceController')->except('destroy', 'show');
            Route::get('delete-patient-sale-invoice/{patient_sale_invoice_id}', 'PatientSaleInvoiceController@delete')->name('PatientSaleInvoice.delete');
            Route::get('search-patient-sale-invoice', 'PatientSaleInvoiceController@search')->name('patient-sale-invoice.search');
            Route::get('get-drugs-belongs-to-patient', 'PatientSaleInvoiceController@getDrugsBelongsToPatient')
                ->name('patient-sale-invoice.getDrugsBelongsToPatient');

            Route::get('get-drugs-existed-in-store}', 'SaleInvoiceController@getDrugsExistedInStore')
                ->name('sale-invoice.getDrugsExistedInStore');

            Route::group(['namespace' => 'Reports'], function () {
                Route::get('get-suppliers-and-clients-accounts-report', 'ReportController@getSuppliersAndClientsAccountsReport')
                    ->name('reports.getSuppliersAndClientsAccountsReport');

                Route::get('data-suppliers-and-clients-accounts-report', 'ReportController@dataSuppliersAndClientsAccountsReport')
                    ->name('reports.dataSuppliersAndClientsAccountsReport');

                Route::get('get-sales-report', 'ReportController@getSalesReport')
                    ->name('reports.getSalesReport');

                Route::get('data-sales-report', 'ReportController@dataSalesReport')
                    ->name('reports.dataSalesReport');

                Route::get('get-sales-invoice-report', 'ReportController@getSalesInvoiceReport')
                    ->name('reports.getSalesInvoiceReport');

                Route::get('data-sales-invoice-report', 'ReportController@dataSalesInvoiceReport')
                    ->name('reports.dataSalesInvoiceReport');


                Route::get('get-purchase-report', 'ReportController@getPurchaseReport')
                    ->name('reports.getPurchaseReport');

                Route::get('data-purchase-report', 'ReportController@dataPurchaseReport')
                    ->name('reports.dataPurchaseReport');

                Route::get('get-purchase-invoice-report', 'ReportController@getPurchaseInvoiceReport')
                    ->name('reports.getPurchaseInvoiceReport');

                Route::get('data-purchase-invoice-report', 'ReportController@dataPurchaseInvoiceReport')
                    ->name('reports.dataPurchaseInvoiceReport');

                Route::get('get-suppliers-report', 'ReportController@getSuppliersReport')
                    ->name('reports.getSuppliersReport');

                Route::get('data-suppliers-report', 'ReportController@dataSupplierReport')
                    ->name('reports.dataSupplierReport');


                Route::get('get-drugs-report', 'ReportController@getDrugsReport')
                    ->name('reports.getDrugsReport');

                Route::get('data-drugs-report', 'ReportController@dataDrugsReport')
                    ->name('reports.dataDrugsReport');

                Route::get('get-drugs-movement-report', 'ReportController@getDrugsMovementReport')
                    ->name('reports.getDrugsMovementReport');

                Route::get('data-drugs-movement-report', 'ReportController@dataDrugsMovementReport')
                    ->name('reports.dataDrugsMovementReport');


                Route::get('get-pharmacy-invoices-reports', 'ReportController@getPharmacyInvoiceReports')
                    ->name('reports.getPharmacyInvoiceReports');

                Route::get('data-pharmacy-invoices-reports', 'ReportController@dataPharmacyInvoiceReports')
                    ->name('reports.dataPharmacyInvoiceReports');

                Route::get('get-warnings-reports', 'ReportController@getWarningReports')
                    ->name('reports.getWarningReports');

                Route::get('data-warnings-reports', 'ReportController@dataWarningReports')
                    ->name('reports.dataWarningReports');

                Route::get('get-pharmacy-inventory-reports', 'ReportController@getPharmacyInventoryReports')
                    ->name('reports.getPharmacyInventoryReports');

                Route::get('data-pharmacy-inventory-reports', 'ReportController@dataPharmacyInventoryReports')
                    ->name('reports.dataPharmacyInventoryReports');
                Route::get('get-inventory-items-reports/{inventory_record_id}', 'ReportController@getInventoryItemsReports')
                    ->name('reports.getInventoryItemsReports');

            });

        });

    });

    Route::group(['namespace' => 'Reception'], function () {

        // reception
        Route::get('add-patient', 'patientsController@add_patient')->name('add-patient');
        Route::post('create-patient', 'patientsController@create_patient')->name('create-patient');
        Route::get('getInsuranceClasses', 'patientsController@getInsuranceClasses')->name('getInsuranceClasses');
        Route::get('getRegions', 'patientsController@getRegions')->name('getRegions');
        Route::get('patients', 'patientsController@patients')->name('patients');
        Route::get('edit-patient/{id}', 'patientsController@edit_patient')->name('edit-patient');
        Route::get('services/{id}', 'patientsController@services')->name('services');
        Route::post('confirm-service/{id}', 'patientsController@confirm_service')->name('confirm-service');
        Route::post('transfer-service/{id}', 'patientsController@transfer_service')->name('transfer-service');

        Route::get('patients/search', 'patientsController@search')->name('patients.search');

        // attachments
        Route::get('attachments/{patient_id?}', 'AttachmentsController@attachments')->name('attachments');
        Route::post('add-attachment', 'AttachmentsController@add_attachment')->name('add-attachment');
        Route::delete('destroy-attachment/{id}', 'AttachmentsController@destroy_attachment')->name('destroy-attachment');
        Route::get('appointments/{patient_id?}', 'AppointmentsController@appointments')->name('appointments');
        Route::get('add-appointment', 'AppointmentsController@add_appointment')->name('add-appointment');
        Route::get('getDoctorsFromSpecialists', 'AppointmentsController@getDoctorsFromSpecialists')->name('getDoctorsFromSpecialists');
        Route::get('getQrCodeUser', 'AppointmentsController@getQrCodeUser')->name('getQrCodeUser');
        Route::post('create-appointment', 'AppointmentsController@create_appointment')->name('create-appointment');

        Route::delete('cancelReservation/{id}', 'AppointmentsController@cancel_reservation')->name('cancelReservation');
        Route::delete('WaitingListReservation/{id}', 'AppointmentsController@waitingList_reservation')->name('WaitingListReservation');

        Route::put('completeReservation/{id}', 'AppointmentsController@completeReservation')->name('complete.reservation');

// doctors requests
        Route::get('doctors-requests', 'DoctorsRequestsController@index')->name('doctors-requests');
        Route::get('/doctors-requests/filter', 'DoctorsRequestsController@doctor_request_filter')->name('doctors-requests.filter');
        // cofirm doctor request
        Route::delete('confirmService/{id}/{type}', 'DoctorsRequestsController@confirm_service')->name('confirmService');
        //create_invoice
        Route::get('invoices/{patient_id?}', 'InvoicesController@invoices')->name('invoices');
        Route::get('invoice-view/{invoice_id}', 'InvoicesController@invoice_view')->name('invoice-view');
        Route::get('create-invoice', 'InvoicesController@create_invoice')->name('create-invoice');
        Route::get('create-invoice-reservation/{reservation_id}', 'InvoicesController@create_invoice_reservation')->name('create-invoice-reservation');
        Route::post('add-invoice', 'InvoicesController@add_invoice')->name('add-invoice');
        Route::get('getServices', 'InvoicesController@getServices')->name('getServices');
        Route::get('getServicesName', 'InvoicesController@getServicesName')->name('getServicesName');

        Route::get('bonds/{patient_id?}', 'BondsController@bonds')->name('bonds');
        Route::post('create-bond', 'BondsController@create_bond')->name('create-bond');

    });


    Route::get('doctors-ratings', 'DoctorRatingController@index')->name('admin.doctors.ratings');


    Route::get('dashboard', 'DashboardController@Dashboard')->name('admin.dashboard');
    Route::get('logout', 'LoginController@logout')->name('admin.logout');

    //create departments
    Route::get('departments', 'DepartmentsController@index')->name('departments');
    Route::post('add-department', 'DepartmentsController@add_department')->name('add-department');
    Route::post('edit-department/{id}', 'DepartmentsController@edit_department')->name('edit-department');
    Route::get('update-status-department/{id}/{status}', 'DepartmentsController@update_status_department')->name('update-status-department');
    Route::delete('destroy-department/{id}', 'DepartmentsController@destroy_department')->name('destroy-department');


    //create offers
    Route::get('offers', 'OffersController@index')->name('offers');
    Route::post('add-offer', 'OffersController@add_offer')->name('add-offer');
    Route::post('edit-offer/{id}', 'OffersController@edit_offer')->name('edit-offer');
    Route::get('update-status-offer/{id}/{status}', 'OffersController@update_status_offer')->name('update-status-offer');
    Route::delete('destroy-offer/{id}', 'OffersController@destroy_offer')->name('destroy-offer');

    //create posts
    Route::get('posts', 'PostsController@index')->name('posts');
    Route::post('add-post', 'PostsController@add_post')->name('add-post');
    Route::post('edit-post/{id}', 'PostsController@edit_post')->name('edit-post');
    Route::get('update-status-post/{id}/{status}', 'PostsController@update_status_post')->name('update-status-post');
    Route::delete('destroy-post/{id}', 'PostsController@destroy_post')->name('destroy-post');


    // create department shifts
    Route::get('department-shifts/{department_id}', 'DepartmentShiftsController@index')->name('department-shifts');
    Route::post('add-department-shift/{department_id}', 'DepartmentShiftsController@add_department_shift')->name('add-department-shift');
    Route::post('edit-department-shift/{id}', 'DepartmentShiftsController@edit_department_shift')->name('edit-department-shift');
    Route::get('update-status-department-shift/{id}/{status}', 'DepartmentShiftsController@update_status_department_shift')->name('update-status-department-shift');
    Route::delete('destroy-department-shift/{id}', 'DepartmentShiftsController@destroy_department_shift')->name('destroy-department-shift');


    //Add employee inside department
    Route::get('create-department-employee/{department_id}', 'DepartmentEmployeesController@create_department_employee')->name('create-department-employee');
    Route::get('department-employees/{department_id}', 'DepartmentEmployeesController@index')->name('department-employees');
    Route::get('AddSuper', 'DepartmentEmployeesController@AddSuper')->name('admin.AddSuper');
    Route::post('add-department-employee/{department_id}', 'DepartmentEmployeesController@add_department_employee')->name('add-department-employee');
    Route::get('UpdateStatusDepartmentEmployees/{id}/{status}', 'DepartmentEmployeesController@UpdateStatusDepartmentEmployees')->name('UpdateStatusDepartmentEmployees');
    Route::get('department-employee-update/{id}', 'DepartmentEmployeesController@department_employee_update')->name('department-employee-update');
    Route::post('update-department-employee/{id}', 'DepartmentEmployeesController@update_department_employee')->name('update-department-employee');
    Route::delete('destroyDepartmentEmployee/{id}', 'DepartmentEmployeesController@destroyDepartmentEmployee')->name('destroyDepartmentEmployee');


    // create department shifts
    Route::get('employee-shifts/{employee_id}', 'EmployeeShiftsController@index')->name('employee-shifts');
    Route::post('add-employee-shift/{employee_id}', 'EmployeeShiftsController@add_employee_shift')->name('add-employee-shift');
    Route::post('edit-employee-shift/{id}', 'EmployeeShiftsController@edit_employee_shift')->name('edit-employee-shift');
    Route::get('update-status-employee-shift/{id}/{status}', 'EmployeeShiftsController@update_status_employee_shift')->name('update-status-employee-shift');
    Route::delete('destroy-employee-shift/{id}', 'EmployeeShiftsController@destroy_employee_shift')->name('destroy-employee-shift');


    Route::get('/employee/{employee}/calendar', 'EmployeeShiftsController@calendar')->name('employee.calendar');
    Route::post('/employee/shift/{shift}/move', 'EmployeeShiftsController@move')->name('employee.shift.move');


    Route::get('specialties', 'SpecialtiesController@index')->name('specialties');
    Route::post('add-specialty', 'SpecialtiesController@add_specialty')->name('add-specialty');
    Route::post('update-specialty/{id}', 'SpecialtiesController@update_specialty')->name('update-specialty');
    Route::get('update-status-specialty/{id}/{status}', 'SpecialtiesController@update_status_specialty')->name('update-status-specialty');
    Route::delete('destroy-specialty/{id}', 'SpecialtiesController@destroy_specialty')->name('destroy-specialty');
    Route::post('update-bulk-specialties', 'SpecialtiesController@updateBulkSpecialties')->name('update-bulk-specialties');


    // create branch
    Route::get('branches', 'BranchesController@index')->name('branches');
    Route::post('add-branch', 'BranchesController@add_branch')->name('add-branch');
    Route::post('edit-branch/{id}', 'BranchesController@edit_branch')->name('edit-branch');
    Route::get('update-status-branch/{id}/{status}', 'BranchesController@update_status_branch')->name('update-status-branch');
    Route::delete('destroy-branch/{id}', 'BranchesController@destroy_branch')->name('destroy-branch');


    // change password
    Route::get('change-password', 'ProfileController@change_password')->name('change-password');
    Route::post('add-password', 'ProfileController@add_password')->name('add-password');

    // points clinic
    Route::get('points', 'PointsController@index')->name('points');

    Route::group(['prefix' => 'loyalty'], function () {
        Route::get('/', 'LoyaltyDashboardController@index')->name('loyalty.dashboard');
        Route::get('coupons', 'LoyaltyDashboardController@coupons')->name('loyalty.coupons');
        Route::post('coupons', 'LoyaltyDashboardController@storeCoupon')->name('loyalty.coupons.store');
        Route::put('coupons/{id}', 'LoyaltyDashboardController@updateCoupon')->name('loyalty.coupons.update');
        Route::get('coupons/{id}/status/{status}', 'LoyaltyDashboardController@updateCouponStatus')->name('loyalty.coupons.status');
        Route::delete('coupons/{id}', 'LoyaltyDashboardController@destroyCoupon')->name('loyalty.coupons.destroy');
        Route::get('redemptions', 'LoyaltyDashboardController@redemptions')->name('loyalty.redemptions');
        Route::get('redemptions/{id}/send-otp', 'LoyaltyDashboardController@sendRedemptionOtp')->name('loyalty.redemptions.send-otp');
        Route::post('redemptions/{id}/confirm', 'LoyaltyDashboardController@confirmRedemption')->name('loyalty.redemptions.confirm');
        Route::get('transactions', 'LoyaltyDashboardController@transactions')->name('loyalty.transactions');
    });
    // attendance and departure
    Route::get('attendance-departure', 'AttendanceAndDepartureController@attendance_departure')->name('attendance-departure');
    Route::get('view-employee/{employee_id}', 'AttendanceAndDepartureController@view_employee')->name('view-employee');
    Route::get('employee-permissions/{employee_id}', 'AttendanceAndDepartureController@employee_permissions')->name('employee-permissions');
    Route::get('update-status-permission/{id}/{status}', 'AttendanceAndDepartureController@update_status_permission')->name('update-status-permission');

    // ContactUs requests
    Route::get('attendance-setting', 'AttendanceAndDepartureController@attendance_setting')->name('attendance-setting');
    Route::post('add-attendance-setting/{id}', 'AttendanceAndDepartureController@add_attendance_setting')->name('add-attendance-setting');

    // get sub specialist
    Route::get('getSubSpecialist', 'DepartmentEmployeesController@getSubSpecialist')->name('getSubSpecialist');
    // search about patient file
    Route::get('search-patient-file', 'SearchController@search_patient_file')->name('search-patient-file');

    Route::group(['namespace' => 'Doctors'], function () {


        // edit profile
        Route::get('edit-profile', 'ProfileController@index')->name('edit-profile');
        Route::get('request-permission', 'PermissionsController@index')->name('request-permission');
        Route::post('send-request-permission', 'PermissionsController@send_request_permission')->name('send-request-permission');


        //create drug
        Route::get('drugs', 'DrugsController@index')->name('drugs');
        Route::get('create-drug', 'DrugsController@create_drug')->name('create-drug');
        Route::post('add-drug', 'DrugsController@add_drug')->name('add-drug');
        Route::get('update-drug/{id}', 'DrugsController@update_drug')->name('update-drug');
        Route::post('edit-drug/{id}', 'DrugsController@edit_drug')->name('edit-drug');
        Route::get('update-status-drug/{id}/{status}', 'DrugsController@update_status_drug')->name('update-status-drug');
        Route::delete('destroy-drug/{id}', 'DrugsController@destroy_drug')->name('destroy-drug');


        // create drug sections
        Route::get('drug-sections/{department_id}', 'DrugSectionsController@index')->name('drug-sections');
        Route::get('create-drug-section/{department_id}', 'DrugSectionsController@create_drug_section')->name('create-drug-section');
        Route::post('add-drug-section/{department_id}', 'DrugSectionsController@add_drug_section')->name('add-drug-section');
        Route::get('update-drug-section/{id}', 'DrugSectionsController@update_drug_section')->name('update-drug-section');
        Route::post('edit-drug-section/{id}', 'DrugSectionsController@edit_drug_section')->name('edit-drug-section');
        Route::get('update-status-drug-section/{id}/{status}', 'DrugSectionsController@update_status_drug_section')->name('update-status-drug-section');
        Route::delete('destroy-drug-section/{id}', 'DrugSectionsController@destroy_drug_section')->name('destroy-drug-section');
        // patients waiting
        Route::get('patients-waiting', 'PatientsWaitingController@index')->name('patients-waiting');
        Route::get('patient-file/{reservation_id?}', 'PatientsWaitingController@patient_file')->name('patient-file');
        Route::get('medical-prescription/{reservation_id?}', 'PatientsWaitingController@medical_prescription')->name('medical-prescription');


        Route::get('patient-services/{user_id}/{type}', 'PatientsWaitingController@patient_services')->name('patient-services');
        Route::get('getDoctorsFromSpecialist', 'PatientsWaitingController@getDoctorsFromSpecialist')->name('getDoctorsFromSpecialist');

        Route::post('add-status-conversion/{user_id}', 'PatientsWaitingController@add_status_conversion')->name('add-status-conversion');
        Route::delete('reservation-finished/{reservation_id}', 'PatientsWaitingController@reservation_finished')->name('reservation-finished');
        Route::post('replace-reservation/{reservation_id}', 'PatientsWaitingController@replace_reservation')->name('replace-reservation');

        // doctor   patient service file
        Route::get('patient-service-file/{user_id}/{type}', 'PatientFileController@patient_service_file')->name('patient-service-file');
        Route::post('create-patient-service-file/{reservation_id}', 'PatientFileController@create_patient_service_file')->name('create-patient-service-file');
        Route::delete('destroyPatientService/{id}', 'PatientFileController@destroyPatientService')->name('destroyPatientService');

        // patient invoices
        Route::get('patient-invoices/{user_id}', 'PatientFileController@patient_invoices')->name('patient-invoices');
        Route::get('patient-sick-leave/{reservation_id}', 'PatientFileController@patient_sick_leave')->name('patient-sick-leave');
        Route::get('companion-sick-leave/{user_id}', 'PatientFileController@companion_sick_leave')->name('companion-sick-leave');

        // create-sick-leave
        Route::post('create-sick-leave/{reservation_id}', 'PatientFileController@create_sick_leave')->name('create-sick-leave');

        // followup patient
        Route::post('followup_patient/{reservation_id}', 'PatientFileController@followup_patient')->name('followup_patient');
        // add vital_signs
        Route::post('create-vital-signs/{reservation_id}', 'MedicineController@create_vital_signs')->name('create-vital-signs');

        Route::get('/get-available-times', 'MedicineController@getAvailableTimes')->name('get.available.times');
        Route::post('create_schedule_consultation/{reservation_id}', 'MedicineController@create_schedule_consultation')->name('create_schedule_consultation');


        // visit page reservation
        Route::get('/visit-page-reservation/{reservation_id}', 'VisitPageReservationController@visit_page_reservation')->name('visit-page-reservation');

        // delete reservation
        Route::delete('destroyReservation/{id}', 'MedicineController@destroyReservation')->name('destroyReservation');


        // add medicine
        Route::get('patients-waiting', 'PatientsWaitingController@index')->name('patients-waiting');

        // previous-revelations
        Route::get('previous-revelations/{reservation_id}', 'PatientsWaitingController@previous_revelations')->name('previous-revelations');

        // add medicine
        Route::get('add-medicine/{reservation_id}', 'MedicineController@add_medicine')->name('add-medicine');
        // new reservation
        Route::get('new-reservation/{reservation_id}', 'MedicineController@new_reservation')->name('new-reservation');
        Route::post('create-medicine/{reservation_id}', 'MedicineController@create_medicine')->name('create-medicine');

        // reservation
        Route::post('reservation-notes/{reservation_id}', 'MedicineController@reservation_notes')->name('reservation-notes');

        Route::get('/load-chat', 'ChatController@loadChatMessages')->name('load-chat');
        Route::post('sendMessage', 'ChatController@sendMessage')->name('sendMessage');
        Route::get('/booking/{id}/chat', 'ChatController@bookingChat')->name('booking.chat');
        Route::get('mark-as-read', 'ChatController@markAsRead')->name('markAsRead');
        Route::get('chatList', 'ChatController@chatList')->name('chatList');
        Route::get('/appointments/chat/{reservation_id}', 'ChatController@appointments_chat')->name('appointments.chat');

        // attendance and departure
        Route::get('doctor-attendance-departure', 'AttendanceAndDepartureController@attendance_departure')->name('doctor-attendance-departure');
        Route::get('employee-clinic-permissions/{employee_id}', 'AttendanceAndDepartureController@employee_permissions')->name('employee-clinic-permissions');

        Route::get('employee-clinic-shifts', 'AttendanceAndDepartureController@employee_shifts')->name('employee-clinic-shifts');

        // medical reports
        Route::get('doctor-medical-reports', 'MedicalReportsController@medical_reports')->name('doctor-medical-reports');
        Route::get('patient-report/{id}/{type?}', 'MedicalReportsController@patient_report')->name('patient-report');

        // prescription record
        Route::get('prescription-record', 'PrescriptionRecordController@prescription_record')->name('prescription-record');

        // doctor appointment
        Route::get('doctor-appointment', 'AppointmentsController@doctor_appointment')->name('doctor-appointment');
        Route::post('add-doctor-condition', 'AppointmentsController@add_doctor_condition')->name('add-doctor-condition');

        // patient appointment
        Route::get('patient-appointment', 'AppointmentsController@patient_appointment')->name('patient-appointment');
        Route::delete('cancel-reservation/{id}', 'AppointmentsController@cancel_reservation')->name('cancel-reservation');
        Route::post('send-contact-reservation/{id}', 'AppointmentsController@send_contact_reservation')->name('send-contact-reservation');

        // chat
        Route::get('chat', 'ChatController@index')->name('chat');
        Route::get('create-chat', 'ChatController@create_chat')->name('create-chat');
        Route::post('add-chat', 'ChatController@add_chat')->name('add-chat');

    });

    Route::group(['namespace' => 'Pharmacy'], function () {
        // new prescription
        Route::get('new-prescription', 'NewPrescriptionController@new_prescription')->name('new-prescription');
        Route::get('diagnosis-display/{id}', 'NewPrescriptionController@diagnosis_display')->name('diagnosis-display');
        Route::post('add-drug-pharmacy/{reservation_id}', 'NewPrescriptionController@add_drug_pharmacy')->name('add-drug-pharmacy');
    });

    Route::group(['namespace' => 'Lab'], function () {
        //create service category
        Route::get('services-categories', 'ServicesCategoriesController@index')->name('services-categories');
        Route::get('create-services-category', 'ServicesCategoriesController@create_services_category')->name('create-services-category');
        Route::post('add-services-category', 'ServicesCategoriesController@add_services_category')->name('add-services-category');
        Route::get('update-services-category/{id}', 'ServicesCategoriesController@update_services_category')->name('update-services-category');
        Route::post('edit-services-category/{id}', 'ServicesCategoriesController@edit_services_category')->name('edit-services-category');
        Route::get('update-status-services-category/{id}/{status}', 'ServicesCategoriesController@update_status_services_category')->name('update-status-services-category');
        Route::delete('destroy-services-category/{id}', 'ServicesCategoriesController@destroy_services_category')->name('destroy-services-category');


        // create drug sections

        Route::get('category-analysis/{department_id}', 'CategoryAnalysisController@index')->name('category-analysis');
        Route::get('create-category-analysis/{department_id}', 'CategoryAnalysisController@create_category_analysis')->name('create-category-analysis');
        Route::post('add-category-analysis/{department_id}', 'CategoryAnalysisController@add_category_analysis')->name('add-category-analysis');
        Route::get('update-category-analysis/{id}', 'CategoryAnalysisController@update_category_analysis')->name('update-category-analysis');
        Route::post('edit-category-analysis/{id}', 'CategoryAnalysisController@edit_category_analysis')->name('edit-category-analysis');
        Route::get('update-status-category-analysis/{id}/{status}', 'CategoryAnalysisController@update_status_category_analysis')->name('update-status-category-analysis');
        Route::delete('destroy-category-analysis/{id}', 'CategoryAnalysisController@destroy_category_analysis')->name('destroy-category-analysis');


        // analysis attribute
        Route::get('analysis-attributes/{service_id}', 'AnalysisAttributesController@index')->name('analysis-attributes');

        Route::post('create-analysis-attributes/{service_id}', 'AnalysisAttributesController@create_analysis_attributes')->name('create-analysis-attributes');
//        Route::get('update-category-analysis/{id}', 'CategoryAnalysisController@update_category_analysis')->name('update-category-analysis');
//        Route::post('edit-category-analysis/{id}', 'CategoryAnalysisController@edit_category_analysis')->name('edit-category-analysis');
//        Route::get('update-status-category-analysis/{id}/{status}', 'CategoryAnalysisController@update_status_category_analysis')->name('update-status-category-analysis');
//        Route::delete('destroy-category-analysis/{id}', 'CategoryAnalysisController@destroy_category_analysis')->name('destroy-category-analysis');


        Route::get('patient-analysis/{patient_id}', 'AnalysisController@patient_analysis')->name('patient-analysis');
        Route::post('confirm-receipt/{id}', 'AnalysisController@confirm_receipt')->name('confirm-receipt');
        Route::get('upload-result/{test_result_id}', 'AnalysisController@upload_result')->name('upload-result');
        Route::post('send-result-analysis/{id}', 'AnalysisController@send_result_analysis')->name('send-result-analysis');
        // re analysis
        Route::post('Re-analysis/{id}', 'AnalysisController@re_analysis')->name('Re-analysis');

    });
    // aboutUs Settings
    Route::get('setting/{type}', 'AboutUsController@index')->name('setting');
    Route::get('app-setting/{setting_type}/{app_type}', 'AboutUsController@app_setting')->name('app-setting');
    Route::post('update-setting/{id}', 'AboutUsController@update_setting')->name('update-setting');


    // ContactUs requests
    Route::get('contactUs', 'ContactUsController@index')->name('contactUs');
    Route::post('add-reply/{id}', 'ContactUsController@add_reply')->name('add-reply');
    Route::get('reply/{id}', 'ContactUsController@reply')->name('reply');

    Route::delete('delete-message/{id}', 'ContactUsController@delete_message')->name('delete-message');


    // team social media
    Route::get('SocialMedia', 'AttendanceAndDepartureController@social_media')->name('SocialMedia');
    Route::get('update-status-socialMedia/{id}/{status}', 'AttendanceAndDepartureController@update_status_socialMedia');
    Route::post('edit-SocialMedia/{id}/{admin_id}', 'AttendanceAndDepartureController@edit_SocialMedia')->name('edit-SocialMedia');


    //Add Supervisor and make permission each supervisor
    Route::get('create-supervisor', 'SuperVisorController@create_supervisor')->name('create-supervisor');
    Route::get('supervisor', 'SuperVisorController@index')->name('supervisor');
    Route::get('AddSuper', 'SuperVisorController@AddSuper')->name('admin.AddSuper');
    Route::post('AddSupervisor', 'SuperVisorController@AddSupervisor')->name('AddSupervisor');
    Route::get('UpdateStatusSuper/{id}/{status}', 'SuperVisorController@UpdateStatusSuper')->name('UpdateStatusSuper');
    Route::get('supervisor-update/{id}', 'SuperVisorController@supervisor_update')->name('supervisor-update');
    Route::post('EditAccountSupervisor/{id}', 'SuperVisorController@EditAccountSupervisor')->name('EditAccountSupervisor');
    Route::delete('destroyAccount/{id}', 'SuperVisorController@destroyAccount')->name('destroyAccount');

    //Clinic supervisor (without permissions)
    Route::group(['namespace' => 'Clinic', 'prefix' => 'clinic'], function () {
        Route::get('supervisor', 'SuperVisorController@index')->name('clinic.supervisor');
        Route::get('create-supervisor', 'SuperVisorController@create_supervisor')->name('clinic.supervisor.create');
        Route::post('AddSupervisor', 'SuperVisorController@AddSupervisor')->name('clinic.supervisor.store');
        Route::get('supervisor-edit/{id}', 'SuperVisorController@supervisor_update')->name('clinic.supervisor.edit');
        Route::post('supervisor-update/{id}', 'SuperVisorController@EditAccountSupervisor')->name('clinic.supervisor.update');
        Route::get('update-status-supervisor/{id}/{status}', 'SuperVisorController@UpdateStatusSuper')->name('clinic.supervisor.status');
        Route::delete('destroyAccount/{id}', 'SuperVisorController@destroyAccount')->name('clinic.supervisor.destroy');
    });


    //profile admin
    Route::get('profile', 'ProfileController@index')->name('profile');

    Route::post('edit-profile/{id}', 'ProfileController@edit_profile')->name('edit-profile');

    Route::get('financial-reports', 'FinancialReportsController@index')->name('financial-reports.index');

    Route::prefix('clinic-reports')->name('clinic-reports.')->group(function () {
        Route::get('appointments', 'ClinicReportsController@appointments')->name('appointments');
        Route::get('doctors', 'ClinicReportsController@doctors')->name('doctors');
        Route::get('patients', 'ClinicReportsController@patients')->name('patients');
        Route::get('reviews', 'ClinicReportsController@reviews')->name('reviews');
        Route::get('export/{section}', 'ClinicReportsController@export')->name('export');
    });

    Route::prefix('analytics')->name('analytics.')->group(function () {
        Route::get('/', 'AnalyticsReportsController@index')->name('index');
        Route::get('reservations', 'AnalyticsReportsController@reservations')->name('reservations');
        Route::get('by-status', 'AnalyticsReportsController@byStatus')->name('by-status');
        Route::get('time-based', 'AnalyticsReportsController@timeBased')->name('time-based');
        Route::get('by-doctor', 'AnalyticsReportsController@byDoctor')->name('by-doctor');
        Route::get('by-specialty', 'AnalyticsReportsController@bySpecialty')->name('by-specialty');
        Route::get('doctors', 'AnalyticsReportsController@doctors')->name('doctors');
        Route::get('patients', 'AnalyticsReportsController@patients')->name('patients');
        Route::get('ratings', 'AnalyticsReportsController@ratings')->name('ratings');
        Route::get('export/{section}', 'AnalyticsReportsController@export')->name('export');
        Route::post('ratings/{id}/resolve', 'AnalyticsReportsController@resolveReview')->name('ratings.resolve');
    });

    Route::get('reports', 'ReportController@index')->name('reports');
    Route::post('report-result', 'ReportController@report_result')->name('report-result');

    // notifications
    Route::get('notifications', 'NotificationController@index')->name('notifications');
    Route::post('send-messages', 'NotificationController@send_messages')->name('send-messages');
    Route::delete('delete-notification/{id}', 'NotificationController@delete_notification')->name('delete-notification');

    // terms and condition
    Route::get('terms', 'TermsController@index')->name('terms');
    Route::get('update-status-terms/{id}/{status}', 'TermsController@update_status_terms');
    Route::post('add-terms', 'TermsController@create_terms')->name('add-terms');

    Route::post('edit-terms/{id}', 'TermsController@edit_terms')->name('edit-terms');
    Route::delete('delete-terms/{id}', 'TermsController@delete_terms')->name('delete-terms');


    Route::group(['namespace' => 'Nursing'], function () {
        //nursing-staff
        Route::get('nursing-staff', 'NursingStaffController@index')->name('nursing-staff.index');
        Route::post('nursing-staff', 'NursingStaffController@store')->name('nursing-staff.store');
        Route::get('/nursing-staff/edit/{id}', 'NursingStaffController@edit')->name('nursing-staff.edit');
        Route::put('/nursing-staff/update/{id}', 'NursingStaffController@update')->name('nursing-staff.update');
        Route::get('/nursing-staff/search', 'NursingStaffController@search')->name('nursing-staff.search');
        Route::get('/nursing-staff/filter', 'NursingStaffController@filter')->name('nursing-staff.filter');
        Route::get('/nursing-services/{id}/nursingServices', 'NursingStaffController@nursingServices')->name('nursing-services.nursingServices');
        Route::get('/nursing-services/{id}/nursingServicesfilter', 'NursingStaffController@nursingServicesfilter')->name('nursing-services.nursingServicesfilter');
        Route::delete('nursing-staff/{id}', 'NursingStaffController@destroy')->name('nursing-staff.delete');

        //nursing-request
        Route::get('nursing-requests', 'NursingRequestController@index')->name('nursing-requests.index');
        Route::get('nursing-requests/details/{id}', 'NursingRequestController@details')->name('nursing-requests.details');
        Route::get('/nursing-requests/confirmedService', 'NursingRequestController@confirmedService')->name('nursing-requests.confirmedService');
        Route::get('/nursing-requests/vital-signs/{id}', 'NursingRequestController@vitalSigns')->name('nursing-requests.vitalSigns');
        Route::put('/nursing-requests/vital-signs-update/{id}', 'NursingRequestController@vitalSignsUpdate')->name('vital-signs-update.vitalSignsUpdate');
        Route::get('/nursing-requests/search', 'NursingRequestController@search')->name('nursing-requests.search');
        Route::get('/nursing-requests/filter', 'NursingRequestController@filter')->name('nursing-requests.filter');


        //emergency
        Route::get('create-emergency', 'EmergencyController@create')->name('create-emergency.create');
        Route::post('store-emergency', 'EmergencyController@store')->name('store-emergency.store');
        Route::get('emergency', 'EmergencyController@index')->name('emergency.index');
        Route::get('new-patient', 'EmergencyController@newPatient')->name('new-patient.newPatient');
        Route::post('save-patient', 'EmergencyController@savePatient')->name('save-patient.savePatient');
        Route::get('/emergency/edit/{id}', 'EmergencyController@edit')->name('emergency.edit');
        Route::put('/emergency/update/{id}', 'EmergencyController@update')->name('emergency.update');
        Route::post('save-note', 'EmergencyController@saveNote')->name('save-note.saveNote');
        Route::post('normal-exit', 'EmergencyController@normalExit')->name('normal-exit');
        Route::post('ambulance-exit', 'EmergencyController@ambulanceExit')->name('ambulance-exit');
        Route::get('emergency/filter', 'EmergencyController@filter')->name('emergency.filter');
        Route::get('emergency/search', 'EmergencyController@search')->name('emergency.search');
    });


    Route::group(['namespace' => 'Insurance'], function () {
        // new classes
        Route::resource('classes', 'ClassesController')->except(['show', 'create', 'edit']);
        Route::resource('companies', 'CompaniesController')->except(['index', 'create', 'edit']);
        Route::resource('services-discount', 'ServicesDiscountController')->except(['index', 'create', 'edit']);
        Route::get('services-discount/{company_id}/{type}', 'ServicesDiscountController@services')->name('services-discount');
        Route::get('getSpecialties', 'ServicesDiscountController@getSpecialties')->name('getSpecialties');
        Route::resource('insurance-policy', 'InsurancePolicyController')->except(['create', 'edit']);
        Route::resource('insurance-approvals', 'InsuranceApprovalsController')->except(['create', 'edit']);
       Route::get('insured-invoices-reports/{id}/{type}', 'ReportController@insured_invoices_reports')->name('insured-invoices-reports');
//        Route::get('approved-services', 'InsuranceApprovalsController@approved_services')->name('approved-services');

    });

    Route::group(['namespace' => 'FinancialAccounts'], function () {

        Route::get('accounts/{category_id}', 'AccountsController@index')->name('accounts');
        Route::post('create-account-category/{category_id}', 'AccountsController@create_account_category')->name('create-account-category');
//        Route::post('add-account-category', 'AccountsController@add_account_category')->name('add-account-category');
        Route::get('update-account-category/{id}', 'AccountsController@update_account_category')->name('update-account-category');
        Route::post('edit-account-category/{id}', 'AccountsController@edit_account_category')->name('edit-account-category');
        Route::get('update-status-account-category/{id}/{status}', 'AccountsController@update_status_account_category')->name('update-status-account-category');
        Route::delete('destroy-account-category/{id}', 'AccountsController@destroy_account_category')->name('destroy-account-category');


        // report
        Route::get('category-report/{category_id}', 'AccountsController@category_report')->name('category-report');
        Route::get('account-settings', 'AccountsController@account_settings')->name('account-settings');

        Route::get('restrictions', 'RestrictionsController@index')->name('restrictions');
        Route::post('create-daily-entry', 'RestrictionsController@create_daily_entry')->name('create-daily-entry');

        Route::get('financial-bonds', 'financialBondsController@index')->name('financial-bonds');
        Route::post('create-financial-bonds', 'financialBondsController@create_financial_bonds')->name('create-financial-bonds');

        // cost center
        Route::get('costCenter', 'CostCenterController@index')->name('costCenter');
        Route::post('create-cost-center', 'CostCenterController@create_cost_center')->name('create-cost-center');
        Route::post('edit-cost-center/{id}', 'CostCenterController@edit_cost_center')->name('edit-cost-center');
        Route::delete('destroy-cost-center/{id}', 'CostCenterController@destroy_cost_center')->name('destroy-cost-center');

        Route::get('ledger', 'ReportsController@ledger')->name('ledger');
        Route::get('trial-balance', 'ReportsController@trial_balance')->name('trial-balance');
        Route::get('trading', 'ReportsController@trading')->name('trading');
        Route::get('profit-and-loss', 'ReportsController@profit_and_loss')->name('profit-and-loss');
        Route::get('financial-position', 'ReportsController@financial_position')->name('financial-position');
        Route::get('invoices-reports', 'ReportsController@invoices_reports')->name('invoices-reports');
        Route::get('income', 'ReportsController@income')->name('income');
        Route::get('reception-income', 'ReportsController@reception_income')->name('reception-income');
        Route::get('insurance-claim', 'ReportsController@insurance_claim')->name('insurance-claim');
        Route::get('patients-balance', 'ReportsController@patients_balance')->name('patients-balance');
//        Route::get('patients-balance', 'ReportsController@patients_balance')->name('patients-balance');
        Route::get('doctor-rate', 'ReportsController@doctor_rate')->name('doctor-rate');
        Route::get('pharmacist-rate', 'ReportsController@pharmacist_rate')->name('pharmacist-rate');
        Route::get('vat-report', 'ReportsController@vat_report')->name('vat-report');
        Route::get('expenses-vat', 'ReportsController@expenses_vat')->name('expenses-vat');
        Route::get('accounts-chart', 'ReportsController@accounts_chart')->name('accounts-chart');
        Route::get('salaries', 'ReportsController@salaries')->name('salaries');


//        Route::get('accounts', 'AccountsController@index')->name('accounts');
//        Route::post('add-account-category', 'AccountsController@add_account_category')->name('add-account-category');
//        Route::post('edit-account-category/{id}', 'AccountsController@edit_account_category')->name('edit-account-category');
//        Route::get('update-status-account-category/{id}/{status}', 'AccountsController@update_status_account_category')->name('update-status-account-category');
//        Route::delete('destroy-account-category/{id}', 'AccountsController@destroy_account_category')->name('destroy-account-category');


    });

    Route::group(['namespace' => 'MainAdmin'], function () {

        Route::get('appointments-list', 'AppointmentsController@appointments_list')->name('appointments-list');

        Route::get('get-clinics', 'ClinicsController@getAll')->name('get-clinics');
        Route::put('/roles/{role}/permissions/update', 'RolesController@updatePermissions')->name('roles.permissions.update');


        Route::resource('notificationsList', 'NotificationsController');
        Route::resource('points-exchanges', 'PointsExchangesController');
        Route::resource('roles', 'RolesController');
        // clinics
        Route::get('clinics', 'ClinicsController@index')->name('clinics');
        Route::get('clinic-details/{clinic_id}', 'ClinicsController@clinic_details')->name('clinic-details');
        Route::get('doctor-details/{clinic_id}', 'ClinicsController@doctor_details')->name('doctor-details');
        Route::post('update-clinic/{id}', 'ClinicsController@update_clinic')->name('update-clinic');
        Route::post('update-clinic-seo/{id}', 'ClinicsController@update_clinic_seo')->name('update-clinic-seo');
        Route::delete('destroy-clinic/{id}', 'ClinicsController@destroy_clinic')->name('destroy-clinic');
        Route::get('update-status-clinic/{id}/{status}', 'ClinicsController@update_status_clinic')->name('update-status-clinic');
        Route::get('/load-tab-content/{app_id}/{clinic_id}', 'ClinicsController@loadTabContent')->name('load-tab-content');
        Route::get('/app-types/{app_id}', 'ClinicsController@app_types')->name('app-types');



        // users
        Route::get('users', 'UsersController@index')->name('users');
        Route::get('edit-user/{id}', 'UsersController@edit_user')->name('edit-user');
        Route::post('update-user/{id}', 'UsersController@update_user')->name('update-user');
        Route::delete('destroy-user/{id}', 'UsersController@destroy_user')->name('destroy-user');
        Route::delete('delete-user/{id}', 'UsersController@delete_user')->name('delete-user');
        Route::get('complains-box', 'ComplainBoxController@index')->name('complains-box');
        // main specialties
        Route::get('main-specialties', 'SpecialtiesControllerController@index')->name('main-specialties');
        Route::post('add-mainSpecialty', 'SpecialtiesControllerController@add_specialty')->name('add-mainSpecialty');
        Route::post('update-MainSpecialty/{id}', 'SpecialtiesControllerController@update_specialty')->name('update-MainSpecialty');
        Route::get('update-status-main-specialty/{id}/{status}', 'SpecialtiesControllerController@update_status_specialty')->name('update-status-main-specialty');
        Route::delete('destroy-mainSpecialty/{id}', 'SpecialtiesControllerController@destroy_specialty')->name('destroy-mainSpecialty');

        Route::get('SubSpecialties/{id}', 'SpecialtiesControllerController@sub_specialties')->name('SubSpecialties');

        Route::get('cities', 'CityControllerController@index')->name('cities');
        Route::post('add-city', 'CityControllerController@add_city')->name('add-city');
        Route::post('update-city/{id}', 'CityControllerController@update_city')->name('update-city');
        Route::delete('destroy-city/{id}', 'CityControllerController@destroy_city')->name('destroy-city');
        Route::get('/cities/search', 'CityControllerController@search')->name('search-cities');

        Route::resource('reports', 'ReportController');
        Route::resource('packages', 'PackagesController');
        Route::resource('demo-requests', 'DemoRequestController');
        Route::get('emergency-hospitals/{id}/status/{status}', 'EmergencyHospitalController@toggleStatus')->name('emergency-hospitals.status');
        Route::resource('emergency-hospitals', 'EmergencyHospitalController')->except(['show']);
        Route::get('contact-us', 'ContactUsMessageController@index')->name('contact-us.index');
        Route::patch('contact-us/{contactUs}/mark-read', 'ContactUsMessageController@markAsRead')->name('contact-us.mark-read');
        Route::resource('notification-recipients', 'NotificationRecipientController')->except(['show', 'create', 'edit']);
        Route::resource('permissionsTypes', 'PermissionsTypesController');

        Route::get('points-list', 'PointsController@index')->name('points-list');
        Route::post('add-point', 'PointsController@add_point')->name('add-point');
        Route::post('update-point/{id}', 'PointsController@update_point')->name('update-point');
//        Route::get('update-status-point/{id}/{status}', 'SpecialtiesControllerController@update_status_point')->name('update-status-specialty');
        Route::delete('destroy-point/{id}', 'PointsController@destroy_point')->name('destroy-point');

        Route::get('loyalty-organizations', 'LoyaltyOrganizationsController@index')->name('loyalty-organizations');
        Route::post('loyalty-organizations', 'LoyaltyOrganizationsController@store')->name('loyalty-organizations.store');
        Route::put('loyalty-organizations/{id}', 'LoyaltyOrganizationsController@update')->name('loyalty-organizations.update');
        Route::put('loyalty-organizations/{id}/organization', 'LoyaltyOrganizationsController@updateOrganization')->name('loyalty-organizations.update-organization');
        Route::get('loyalty-organizations/{id}/status/{status}', 'LoyaltyOrganizationsController@toggleStatus')->name('loyalty-organizations.toggle');

        Route::get('loyalty-point-rules', 'LoyaltyPointRulesController@index')->name('loyalty-point-rules');
        Route::post('loyalty-point-rules', 'LoyaltyPointRulesController@store')->name('loyalty-point-rules.store');
        Route::put('loyalty-point-rules/{id}', 'LoyaltyPointRulesController@update')->name('loyalty-point-rules.update');
        Route::get('loyalty-point-rules/{id}/status/{status}', 'LoyaltyPointRulesController@toggleStatus')->name('loyalty-point-rules.toggle');
        Route::delete('loyalty-point-rules/{id}', 'LoyaltyPointRulesController@destroy')->name('loyalty-point-rules.destroy');

        Route::get('admin-supervisor', 'SuperVisorController@index')->name('admin-supervisor');
        Route::post('create-supervisor', 'SuperVisorController@create_supervisor')->name('create-supervisor');
        Route::put('update-supervisor/{id}', 'SuperVisorController@update_supervisor')->name('update-supervisor');
        Route::delete('destroy-Account/{id}', 'SuperVisorController@destroyAccount')->name('destroy-Account');



        // create clinic
        Route::get('add-clinic', 'ClinicsController@add_clinic')->name('add-clinic');
        Route::post('create-clinic', 'ClinicsController@create_clinic')->name('create-clinic');

    });


});