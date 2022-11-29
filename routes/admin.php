<?php

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
/* Login Page*/

// Route::get('/', function() {
//     return redirect()->route('admin.login');
// });
// 
Route::get('/','InitController@LandingPage');

Route::namespace('Admin')->prefix('admin')->name('admin.')->group(function() {

    Route::namespace('Auth')->middleware('guest:admin')->group(function() {

        Route::get('login', 'LoginController@showLoginForm')->name('login');
        Route::post('login', 'LoginController@login')->name('login');

        Route::get('reset-password/{token}/{email}', 'ResetPasswordController@showResetForm')->name('password.reset');
        Route::post('reset-password/change', 'ResetPasswordController@reset')->name('password.change');

        Route::get('forgot-password', 'ForgotPasswordController@showLinkRequestForm')->name('password.request');
        Route::post('forgot-password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('password.email');

    });

    Route::middleware('auth:admin')->group(function() {

        Route::namespace('Auth')->group(function() {

            Route::get('logout', 'LoginController@logout')->name('logout');

        });

        Route::get('', 'DashboardController@index')->name('dashboard');

        Route::post('fetch/address-position', '\App\Http\Controllers\GoogleAPIController@fetchAddressPosition')->name('google.fetch.address-position');

        /**
         * @Count Fetch Controller
         */
        Route::post('count/notifications', 'CountFetchController@fetchNotificationCount')->name('counts.fetch.notifications');
        Route::post('count/sample-items', 'CountFetchController@fetchSampleItemCount')->name('counts.fetch.sample-items.pending');
        
        /**
         * @Analytics
         */
        Route::namespace('Analytics')->group(function() {

            Route::post('analytics/dashboard', 'DashboardAnalyticsController@fetch')->name('analytics.fetch.dashboard');

        });

        Route::namespace('Profiles')->group(function() {

            /**
             * @Admin Profiles
             */
            Route::get('profile', 'ProfileController@show')->name('profiles.show');
            Route::post('profile/update', 'ProfileController@update')->name('profiles.update');
            Route::post('profile/change-password', 'ProfileController@changePassword')->name('profiles.change-password');

            Route::post('profile/fetch', 'ProfileController@fetch')->name('profiles.fetch');

        });

        /*
        |--------------------------------------------------------------------------
        | @Invoices Routes
        |--------------------------------------------------------------------------
        */      
        Route::namespace('Invoices')->group(function() {
            
            Route::get('invoices/index', 'InvoiceController@index')->name('invoices.index');
            Route::get('invoices/show/{invoiceNumber}/{id}', 'InvoiceController@show')->name('invoices.show');
            Route::post('invoices/update/{id?}', 'InvoiceController@update')->name('invoices.update');
            Route::post('invoices/{id}/archive', 'InvoiceController@archive')->name('invoices.archive');
            Route::post('invoices/{id}/restore', 'InvoiceController@restore')->name('invoices.restore');
            Route::post('invoices/export', 'InvoiceController@export')->name('invoices.export');
            Route::get('invoices/{invoiceNumber}/{id}/print', 'InvoiceController@printInvoice')->name('invoices.print-invoice');

            /*
            |--------------------------------------------------------------------------
            | @Invoices Failed Transaction Routes
            |--------------------------------------------------------------------------
            */
            Route::get('invoices/{invoiceNumber}/{id}/failed-transaction', 'InvoiceController@failedTransactionForm')->name('invoices.failed-transaction-form');
            Route::get('invoices/{invoiceNumber}/{id}/failed-transaction?type={type?}', 'InvoiceController@failedTransactionForm')->name('invoices.failed-transaction');

            Route::post('invoices/{id}/{type}/failed-transaction', 'InvoiceController@failedTransactionSubmit')->name('invoices.failed-transaction.submit');


            /*
            |--------------------------------------------------------------------------
            | @Invoices | Fetch Routes
            |--------------------------------------------------------------------------
            */
            Route::post('invoices/fetch', 'InvoiceFetchController@fetch')->name('invoices.fetch');
            Route::post('invoices/fetch?secretary=1&id={$id}', 'InvoiceFetchController@fetch')->name('invoices.fetch-by-secretary');
            Route::post('invoices/fetch?archived=1', 'InvoiceFetchController@fetch')->name('invoices.fetch-archive');
            Route::post('invoices/fetch-item/{id?}', 'InvoiceFetchController@fetchView')->name('invoices.fetch-item');


            /*
            |--------------------------------------------------------------------------
            | @InvoicsItems | Fetch Routes
            |--------------------------------------------------------------------------
            */
            Route::post('invoice-items/fetch', 'InvoiceItemFetchController@fetch')->name('invoice-items.fetch');
            Route::post('invoice-items/fetch?invoice={id}', 'InvoiceItemFetchController@fetch')->name('invoice-items-fetch-items');

        });

        /*
        |--------------------------------------------------------------------------
        | @Status Types Routes
        |--------------------------------------------------------------------------
        */      
        Route::namespace('StatusTypes')->group(function() {
            
            Route::get('status-types/get', 'StatusTypeController@get')->name('status-types.get');
            Route::get('status-types/index', 'StatusTypeController@index')->name('status-types.index');
            Route::post('status-types/reorder/submit', 'StatusTypeController@reOrder')->name('status-types.reorder-submit');            
            Route::get('status-types/create', 'StatusTypeController@create')->name('status-types.create');
            Route::post('status-types/store', 'StatusTypeController@store')->name('status-types.store');
            Route::get('status-types/show/{id}', 'StatusTypeController@show')->name('status-types.show');
            Route::post('status-types/update/{id}', 'StatusTypeController@update')->name('status-types.update');
            Route::post('status-types/{id}/archive', 'StatusTypeController@archive')->name('status-types.archive');
            Route::post('status-types/{id}/restore', 'StatusTypeController@restore')->name('status-types.restore');

            /*
            |--------------------------------------------------------------------------
            | @Status Types | Fetch Routes
            |--------------------------------------------------------------------------
            */
            Route::post('status-types/fetch', 'StatusTypeFetchController@fetch')->name('status-types.fetch-status');
            Route::post('status-types/fetch?nopagination=1', 'StatusTypeFetchController@fetch')->name('status-types.fetch');
            Route::post('status-types/fetch?nopagination=1&archived=1', 'StatusTypeFetchController@fetch')->name('status-types.fetch-archive');
            Route::post('status-types/fetch-item/{id?}', 'StatusTypeFetchController@fetchView')->name('status-types.fetch-item');

        });


        /*
        |--------------------------------------------------------------------------
        | @Products Routes
        |--------------------------------------------------------------------------
        */      
        Route::namespace('Products')->group(function() {
            
            Route::get('products/index', 'ProductController@index')->name('products.index');
            Route::get('products/create', 'ProductController@create')->name('products.create');
            Route::post('products/store/{id?}', 'ProductController@store')->name('products.store');
            Route::get('products/show/{id}', 'ProductController@show')->name('products.show');
            Route::post('products/update/{id}', 'ProductController@update')->name('products.update');
            Route::post('products/{id}/archive', 'ProductController@archive')->name('products.archive');
            Route::post('products/{id}/restore', 'ProductController@restore')->name('products.restore');
            Route::get('products/upload', 'ProductController@upload')->name('products.upload');
            Route::post('products/upload', 'ProductController@uploadProducts')->name('products.batch.upload');

            Route::get('products/{id}/variants', 'ProductController@variants')->name('products.all.variants');
            Route::get('products/{productID}/{name}/create/variant', 'ProductController@create')->name('products.create.variant');

            /*
            |--------------------------------------------------------------------------
            | @Products | Fetch Routes
            |--------------------------------------------------------------------------
            */
            Route::post('products/fetch', 'ProductFetchController@fetch')->name('products.fetch');
            Route::post('products/fetch?parent=1&parent_id={id}', 'ProductFetchController@fetch')->name('products.fetch.by.parent');
            Route::post('products/fetch?archived=1', 'ProductFetchController@fetch')->name('products.fetch-archive');
            Route::post('products/fetch-item/{id?}', 'ProductFetchController@fetchView')->name('products.fetch-item');

        });

        Route::namespace('ProductParents')->group(function() {
            Route::get('product-parents', 'ProductParentController@index')->name('product-parents.index');
            Route::get('product-parents/create', 'ProductParentController@create')->name('product-parents.create');
            Route::post('product-parents/store', 'ProductParentController@store')->name('product-parents.store');
            Route::get('product-parents/show/{id?}', 'ProductParentController@show')->name('product-parents.show');
            Route::post('product-parents/update/{id}', 'ProductParentController@update')->name('product-parents.update');
            Route::post('product-parents/{id}/archive', 'ProductParentController@archive')->name('product-parents.archive');
            Route::post('product-parents/{id}/restore', 'ProductParentController@restore')->name('product-parents.restore');

            Route::post('product-parents/fetch', 'ProductParentFetchController@fetch')->name('product-parents.fetch');
            Route::post('product-parents/fetch?archived=1', 'ProductParentFetchController@fetch')->name('product-parents.fetch-archive');
            Route::post('product-parents/fetch-item/{id?}', 'ProductParentFetchController@fetchView')->name('product-parents.fetch-item');
            Route::post('product-parents/fetch-pagination/{id}', 'ProductParentFetchController@fetchPagePagination')->name('product-parents.fetch-pagination');
        });

        /*
        |--------------------------------------------------------------------------
        | @Inventories Routes
        |--------------------------------------------------------------------------
        */      
        Route::namespace('Inventories')->group(function() {
            
            Route::get('inventories/index', 'InventoryController@index')->name('inventories.index');
            Route::get('inventories/show/{id}', 'InventoryController@show')->name('inventories.show');
            Route::post('inventories/update/{id}', 'InventoryController@update')->name('inventories.update');
            Route::get('inventories/get/status-report', 'InventoryController@getStatusReport')->name('inventories.get-status-report');

            /*
            |--------------------------------------------------------------------------
            | @Inventories | Fetch Routes
            |--------------------------------------------------------------------------
            */
            Route::post('inventories/fetch', 'InventoryFetchController@fetch')->name('inventories.fetch');
            Route::post('inventories/fetch-item/{id?}', 'InventoryFetchController@fetchView')->name('inventories.fetch-item');

        });


        /*
        |--------------------------------------------------------------------------
        | @Specializations Routes
        |--------------------------------------------------------------------------
        */      

        Route::namespace('Specializations')->group(function() {
            
            Route::get('specializations/index', 'SpecializationController@index')->name('specializations.index');
            Route::get('specializations/create', 'SpecializationController@create')->name('specializations.create');
            Route::post('specializations/store', 'SpecializationController@store')->name('specializations.store');
            Route::get('specializations/show/{id}', 'SpecializationController@show')->name('specializations.show');
            Route::post('specializations/update/{id}', 'SpecializationController@update')->name('specializations.update');
            Route::post('specializations/{id}/archive', 'SpecializationController@archive')->name('specializations.archive');
            Route::post('specializations/{id}/restore', 'SpecializationController@restore')->name('specializations.restore');

            Route::post('specializations/reorder/submit', 'SpecializationController@reOrder')->name('specializations.reorder-submit');  
            /*
            |--------------------------------------------------------------------------
            | @Specializations | Fetch Routes
            |--------------------------------------------------------------------------
            */
            Route::post('specializations/fetch', 'SpecializationFetchController@fetch')->name('specializations.fetch');
            Route::post('specializations/fetch?archived=1', 'SpecializationFetchController@fetch')->name('specializations.fetch-archive');
            Route::post('specializations/fetch-item/{id?}', 'SpecializationFetchController@fetchView')->name('specializations.fetch-item');

        });




        /**
         * @AdminUsers
         */
        Route::namespace('AdminUsers')->group(function() {

            /**
             * @AdminUsers
             */
            Route::get('admin-users', 'AdminUserController@index')->name('admin-users.index');
            Route::get('admin-users/create', 'AdminUserController@create')->name('admin-users.create');
            Route::post('admin-users/store', 'AdminUserController@store')->name('admin-users.store');
            Route::get('admin-users/show/{id}', 'AdminUserController@show')->name('admin-users.show');
            Route::post('admin-users/update/{id}', 'AdminUserController@update')->name('admin-users.update');
            Route::post('admin-users/{id}/archive', 'AdminUserController@archive')->name('admin-users.archive');
            Route::post('admin-users/{id}/restore', 'AdminUserController@restore')->name('admin-users.restore');

            Route::post('admin-users/fetch', 'AdminUserFetchController@fetch')->name('admin-users.fetch');
            Route::post('admin-users/fetch?archived=1', 'AdminUserFetchController@fetch')->name('admin-users.fetch-archive');
            Route::post('admin-users/fetch-item/{id?}', 'AdminUserFetchController@fetchView')->name('admin-users.fetch-item');
            Route::post('admin-users/fetch-pagination/{id}', 'AdminUserFetchController@fetchPagePagination')->name('admin-users.fetch-pagination');

        });

        /**
         * @Users
         */
        Route::namespace('Users')->group(function() {

            /**
             * @AdminUsers
             */
            Route::get('users', 'UserController@index')->name('users.index');
            Route::get('users/get', 'UserController@get')->name('users.get');
            Route::get('users/create', 'UserController@create')->name('users.create');
            Route::post('users/store', 'UserController@store')->name('users.store');
            Route::get('users/show/{id}', 'UserController@show')->name('users.show');
            Route::post('users/update/{id}', 'UserController@update')->name('users.update');
            Route::post('users/{id}/archive', 'UserController@archive')->name('users.archive');
            Route::post('users/{id}/restore', 'UserController@restore')->name('users.restore');
            Route::post('users/{id}/approve', 'UserController@approve')->name('users.approve');
            Route::post('users/{id}/deny', 'UserController@deny')->name('users.deny');

            Route::get('users/{id}/manage-credits', 'UserController@manageCreditsForm')->name('users.manage-credits');
            Route::post('users/{id}/update-credits', 'UserController@updateCredits')->name('users.update-credits');

            Route::post('users/fetch', 'UserFetchController@fetch')->name('users.fetch');
            Route::post('users/fetch?archived=1', 'UserFetchController@fetch')->name('users.fetch-archive');
            Route::post('users/fetch-item/{id?}', 'UserFetchController@fetchView')->name('users.fetch-item');
            Route::post('users/fetch-pagination/{id}', 'UserFetchController@fetchPagePagination')->name('users.fetch-pagination');

        });
        Route::namespace('Users')->group(function() {
            Route::post('user-vouchers/fetch', 'UserVoucherFetchController@fetch')->name('user-vouchers.fetch');            
            Route::post('user-vouchers/fetch?voucher=1&id={id}', 'UserVoucherFetchController@fetch')->name('user-vouchers.fetch');
        });
        /**
         * CMS Pages
         */
        Route::namespace('Pages')->group(function() {

            Route::get('pages', 'PageController@index')->name('pages.index');
            Route::get('pages/create', 'PageController@create')->name('pages.create');
            Route::post('pages/store', 'PageController@store')->name('pages.store');
            Route::get('pages/show/{id}', 'PageController@show')->name('pages.show');
            Route::post('pages/update/{id}', 'PageController@update')->name('pages.update');
            Route::post('pages/{id}/archive', 'PageController@archive')->name('pages.archive');
            Route::post('pages/{id}/restore', 'PageController@restore')->name('pages.restore');

            Route::post('pages/fetch', 'PageFetchController@fetch')->name('pages.fetch');
            Route::post('pages/fetch?archived=1', 'PageFetchController@fetch')->name('pages.fetch-archive');
            Route::post('pages/fetch-item/{id?}', 'PageFetchController@fetchView')->name('pages.fetch-item');
            Route::post('pages/fetch-pagination/{id}', 'PageFetchController@fetchPagePagination')->name('pages.fetch-pagination');

            Route::get('page-items', 'PageItemController@index')->name('page-items.index');
            Route::get('page-items/create', 'PageItemController@create')->name('page-items.create');
            Route::post('page-items/store', 'PageItemController@store')->name('page-items.store');
            Route::get('page-items/show/{id}', 'PageItemController@show')->name('page-items.show');
            Route::post('page-items/update/{id}', 'PageItemController@update')->name('page-items.update');
            Route::post('page-items/{id}/archive', 'PageItemController@archive')->name('page-items.archive');
            Route::post('page-items/{id}/restore', 'PageItemController@restore')->name('page-items.restore');

            Route::post('page-items/fetch', 'PageItemFetchController@fetch')->name('page-items.fetch');
            Route::post('page-items/fetch?archived=1', 'PageItemFetchController@fetch')->name('page-items.fetch-archive');
            Route::post('page-items/fetch?page_id={id}', 'PageItemFetchController@fetch')->name('page-items.fetch-page-items');
            Route::post('page-items/fetch-item/{id?}', 'PageItemFetchController@fetchView')->name('page-items.fetch-item');
            Route::post('page-items/fetch-pagination/{id}', 'PageItemFetchController@fetchPagePagination')->name('page-items.fetch-pagination');

        });

        /**
         * @Roles
         */
        Route::namespace('Roles')->group(function() {

            Route::get('roles', 'RoleController@index')->name('roles.index');
            Route::get('roles/create', 'RoleController@create')->name('roles.create');
            Route::post('roles/store', 'RoleController@store')->name('roles.store');
            Route::get('roles/{id}', 'RoleController@show')->name('roles.show');
            Route::post('roles/{id}/update', 'RoleController@update')->name('roles.update');
            Route::post('roles/{id}/archive', 'RoleController@archive')->name('roles.archive');
            Route::post('roles/{id}/restore', 'RoleController@restore')->name('roles.restore');

            Route::post('roles/{id}/update-permission', 'RoleController@updatePermissions')->name('roles.update-permissions');

            Route::post('roles/fetch', 'RoleFetchController@fetch')->name('roles.fetch');
            Route::post('roles/fetch?archived=1', 'RoleFetchController@fetch')->name('roles.fetch-archive');
            Route::post('roles/fetch-item/{id?}', 'RoleFetchController@fetchView')->name('roles.fetch-item');
            Route::post('role/fetch-pagination/{id}', 'RoleFetchController@fetchPagePagination')->name('roles.fetch-pagination');

        });

        /**
         * @Permissions
         */
        Route::namespace('Permissions')->group(function() {

            Route::post('permissions-fetch/{id?}', 'PermissionFetchController@fetch')->name('permissions.fetch');

        });

        Route::namespace('Notifications')->group(function() {

            Route::get('notifications', 'NotificationController@index')->name('notifications.index');
            Route::post('notifications/all/mark-as-read', 'NotificationController@readAll')->name('notifications.read-all');
            Route::post('notifications/{id}/read', 'NotificationController@read')->name('notifications.read');
            Route::post('notifications/{id}/unread', 'NotificationController@unread')->name('notifications.unread');
            
            Route::post('notifications-fetch', 'NotificationFetchController@fetch')->name('notifications.fetch');
            Route::post('notifications-fetch?read=1', 'NotificationFetchController@fetch')->name('notifications.fetch-read');

        });

        Route::namespace('ActivityLogs')->group(function() {

            Route::get('activity-logs', 'ActivityLogController@index')->name('activity-logs.index');
            Route::get('activity-logs/recent', 'ActivityLogController@getMostRecent')->name('activity-logs.recent');
            
            Route::post('activity-logs/fetch', 'ActivityLogFetchController@fetch')->name('activity-logs.fetch');

            Route::post('activity-logs/fetch?id={id}&sample=1', 'ActivityLogFetchController@fetch')->name('activity-logs.fetch.sample-items');

            Route::post('activity-logs/fetch?id={id}&admin=1', 'ActivityLogFetchController@fetch')->name('activity-logs.fetch.admin-users');
            Route::post('activity-logs/fetch?id={id}&user=1', 'ActivityLogFetchController@fetch')->name('activity-logs.fetch.users');

            Route::post('activity-logs/fetch?profile=1', 'ActivityLogFetchController@fetch')->name('activity-logs.fetch.profiles');

            Route::post('activity-logs/fetch?id={id}&roles=1', 'ActivityLogFetchController@fetch')->name('activity-logs.fetch.roles');

            Route::post('activity-logs/fetch?id={id}&pagecontents=1', 'ActivityLogFetchController@fetch')->name('activity-logs.fetch.pages');
            Route::post('activity-logs/fetch?id={id}&pageitems=1', 'ActivityLogFetchController@fetch')->name('activity-logs.fetch.page-items');

            Route::post('activity-logs/fetch?id={id}&articles=1', 'ActivityLogFetchController@fetch')->name('activity-logs.fetch.articles');

            Route::post('activity-logs/fetch?id={id}&shipping-standards=1', 'ActivityLogFetchController@fetch')->name('activity-logs.fetch.shipping-standards');
            Route::post('activity-logs/fetch?id={id}&shipping-expresses=1', 'ActivityLogFetchController@fetch')->name('activity-logs.fetch.shipping-expresses');
            Route::post('activity-logs/fetch?id={id}&medreps=1', 'ActivityLogFetchController@fetch')->name('activity-logs.fetch.medreps');
            Route::post('activity-logs/fetch?id={id}&bank-details=1', 'ActivityLogFetchController@fetch')->name('activity-logs.fetch.bank-details');

            /** Invoices Activity Logs Route */
            Route::post('activity-logs/fetch?id={id}&invoices=1', 'ActivityLogFetchController@fetch')->name('activity-logs.fetch.invoices');

            /** Products Activity Logs Route */
            Route::post('activity-logs/fetch?id={id}&products=1', 'ActivityLogFetchController@fetch')->name('activity-logs.fetch.products');

            /** Inventories Activity Logs Route */
            Route::post('activity-logs/fetch?id={id}&inventories=1', 'ActivityLogFetchController@fetch')->name('activity-logs.fetch.inventories');

            /** Specialization Activity Logs Route */
            Route::post('activity-logs/fetch?id={id}&specializations=1', 'ActivityLogFetchController@fetch')->name('activity-logs.fetch.specializations');

            /** Status types Activity Logs Route */
            Route::post('activity-logs/fetch?id={id}&status-types=1', 'ActivityLogFetchController@fetch')->name('activity-logs.fetch.status-types');


            /** Rewards Activity Logs Route */
            Route::post('activity-logs/fetch?id={id}&rewards=1', 'ActivityLogFetchController@fetch')->name('activity-logs.fetch.rewards');

            Route::post('activity-logs/fetch?id={id}&provinces=1', 'ActivityLogFetchController@fetch')->name('activity-logs.fetch.provinces');

            Route::post('activity-logs/fetch?id={id}&cities=1', 'ActivityLogFetchController@fetch')->name('activity-logs.fetch.cities');
            
            Route::post('activity-logs/fetch?id={id}&target-calls=1', 'ActivityLogFetchController@fetch')->name('activity-logs.fetch.target-calls');

            Route::post('activity-logs/fetch?id={id}&users=1', 'ActivityLogFetchController@fetch')->name('activity-logs.fetch.users');

            Route::post('activity-logs/fetch?id={id}&doctors=1', 'ActivityLogFetchController@fetch')->name('activity-logs.fetch.doctors');
            
            Route::post('activity-logs/fetch?id={id}&calls=1', 'ActivityLogFetchController@fetch')->name('activity-logs.fetch.calls');

            Route::post('activity-logs/fetch?id={id}&sponsorships=1', 'ActivityLogFetchController@fetch')->name('activity-logs.fetch.sponsorships');

            Route::post('activity-logs/fetch?id={id}&prescriptions=1', 'ActivityLogFetchController@fetch')->name('activity-logs.fetch.prescriptions');

            Route::post('activity-logs/fetch?id={id}&faqs=1', 'ActivityLogFetchController@fetch')->name('activity-logs.fetch.faqs');

            Route::post('activity-logs/fetch?id={id}&faq-categories=1', 'ActivityLogFetchController@fetch')->name('activity-logs.fetch.faq-categories');

            Route::post('activity-logs/fetch?id={id}&announcement-types=1', 'ActivityLogFetchController@fetch')->name('activity-logs.fetch.announcement-types');

            Route::post('activity-logs/fetch?id={id}&announcements=1', 'ActivityLogFetchController@fetch')->name('activity-logs.fetch.announcements');

            Route::post('activity-logs/fetch?id={id}&article-categories=1', 'ActivityLogFetchController@fetch')->name('activity-logs.fetch.article-categories');

            Route::post('activity-logs/fetch?id={id}&areas=1', 'ActivityLogFetchController@fetch')->name('activity-logs.fetch.areas');
            Route::post('activity-logs/fetch?id={id}&shipping-matrixes=1', 'ActivityLogFetchController@fetch')->name('activity-logs.fetch.shipping-matrixes');
            Route::post('activity-logs/fetch?id={id}&product-parents=1', 'ActivityLogFetchController@fetch')->name('activity-logs.fetch.product-parents');
            //Route for Credit Packages Activity Logs
            Route::post('activity-logs/fetch?id={id}&credit-packages=1', 'ActivityLogFetchController@fetch')->name('activity-logs.fetch.credit-packages');     
            //Route for Payout Activity Logs
            Route::post('activity-logs/fetch?id={id}&payouts=1', 'ActivityLogFetchController@fetch')->name('activity-logs.fetch.payouts');
            //Route for Pharmacy Activity Logs
            Route::post('activity-logs/fetch?id={id}&pharmacies=1', 'ActivityLogFetchController@fetch')->name('activity-logs.fetch.pharmacies');

            //Route for Vouchers Activity Logs
            Route::post('activity-logs/fetch?id={id}&vouchers=1', 'ActivityLogFetchController@fetch')->name('activity-logs.fetch.vouchers');
        });

        Route::namespace('Samples')->group(function() {
            Route::get('sample-items', 'SampleItemController@index')->name('sample-items.index');
            Route::get('sample-items/create', 'SampleItemController@create')->name('sample-items.create');
            Route::post('sample-items/store', 'SampleItemController@store')->name('sample-items.store');
            Route::get('sample-items/show/{id}', 'SampleItemController@show')->name('sample-items.show');
            Route::post('sample-items/update/{id}', 'SampleItemController@update')->name('sample-items.update');
            Route::post('sample-items/{id}/archive', 'SampleItemController@archive')->name('sample-items.archive');
            Route::post('sample-items/{id}/restore', 'SampleItemController@restore')->name('sample-items.restore');
            Route::post('sample-items/{id}/remove-image', 'SampleItemController@removeImage')->name('sample-items.remove-image');

            Route::post('sample-items/export', 'SampleItemController@export')->name('sample-items.export');

            Route::post('sample-items/{id}/approve', 'SampleItemController@approve')->name('sample-items.approve');
            Route::post('sample-items/{id}/deny', 'SampleItemController@deny')->name('sample-items.deny');

            Route::post('sample-items/fetch', 'SampleItemFetchController@fetch')->name('sample-items.fetch');
            Route::post('sample-items/fetch?archived=1', 'SampleItemFetchController@fetch')->name('sample-items.fetch-archive');
            Route::post('sample-items/fetch-item/{id?}', 'SampleItemFetchController@fetchView')->name('sample-items.fetch-item');
            Route::post('sample-items/fetch-pagination/{id}', 'SampleItemFetchController@fetchPagePagination')->name('sample-items.fetch-pagination');
        });

        Route::namespace('Articles')->group(function() {
            Route::get('articles', 'ArticleController@index')->name('articles.index');
            Route::get('articles/create', 'ArticleController@create')->name('articles.create');
            Route::post('articles/store', 'ArticleController@store')->name('articles.store');
            Route::get('articles/show/{id}', 'ArticleController@show')->name('articles.show');
            Route::post('articles/update/{id}', 'ArticleController@update')->name('articles.update');
            Route::post('articles/{id}/archive', 'ArticleController@archive')->name('articles.archive');
            Route::post('articles/{id}/restore', 'ArticleController@restore')->name('articles.restore');

            Route::get('articles/{id}/comments', 'ArticleController@showComments')->name('articles.comments');
            Route::post('articles/{id}/comments/{commentId}/archive', 'ArticleController@archiveComment')->name('articles.comments.archive');

            Route::post('articles/fetch', 'ArticleFetchController@fetch')->name('articles.fetch');
            Route::post('articles/fetch?archived=1', 'ArticleFetchController@fetch')->name('articles.fetch-archive');
            Route::post('articles/fetch-item/{id?}', 'ArticleFetchController@fetchView')->name('articles.fetch-item');
            Route::post('articles/fetch-pagination/{id}', 'ArticleFetchController@fetchPagePagination')->name('articles.fetch-pagination');
        });

        Route::namespace('Articles')->group(function() {
            Route::get('article-categories', 'ArticleCategoryController@index')->name('article-categories.index');
            Route::get('article-categories/create', 'ArticleCategoryController@create')->name('article-categories.create');
            Route::post('article-categories/store', 'ArticleCategoryController@store')->name('article-categories.store');
            Route::get('article-categories/show/{id?}', 'ArticleCategoryController@show')->name('article-categories.show');
            Route::post('article-categories/update/{id}', 'ArticleCategoryController@update')->name('article-categories.update');
            Route::post('article-categories/{id}/archive', 'ArticleCategoryController@archive')->name('article-categories.archive');
            Route::post('article-categories/{id}/restore', 'ArticleCategoryController@restore')->name('article-categories.restore');

            Route::post('article-categories/fetch', 'ArticleCategoryFetchController@fetch')->name('article-categories.fetch');
            Route::post('article-categories/fetch?archived=1', 'ArticleCategoryFetchController@fetch')->name('article-categories.fetch-archive');
            Route::post('article-categories/fetch-item/{id?}', 'ArticleCategoryFetchController@fetchView')->name('article-categories.fetch-item');
            Route::post('article-categories/fetch-pagination/{id}', 'ArticleCategoryFetchController@fetchPagePagination')->name('article-categories.fetch-pagination');
        });

        Route::namespace('Comments')->group(function() {
            Route::post('comments/update/{id}', 'CommentController@update')->name('comments.update');
            Route::post('comments/{id}/archive', 'CommentController@archive')->name('comments.archive');
            Route::post('comments/{id}/restore', 'CommentController@restore')->name('comments.restore');
        });

        Route::namespace('Replies')->group(function() {
            Route::post('replies/update/{id}', 'ReplyController@update')->name('replies.update');
            Route::post('replies/{id}/archive', 'ReplyController@archive')->name('replies.archive');
            Route::post('replies/{id}/restore', 'ReplyController@restore')->name('replies.restore');
        });

        Route::namespace('Provinces')->group(function() {
            Route::get('provinces', 'ProvinceController@index')->name('provinces.index');
            Route::get('provinces/create', 'ProvinceController@create')->name('provinces.create');
            Route::post('provinces/store', 'ProvinceController@store')->name('provinces.store');
            Route::get('provinces/show/{id}', 'ProvinceController@show')->name('provinces.show');
            Route::post('provinces/update/{id}', 'ProvinceController@update')->name('provinces.update');
            Route::post('provinces/{id}/archive', 'ProvinceController@archive')->name('provinces.archive');
            Route::post('provinces/{id}/restore', 'ProvinceController@restore')->name('provinces.restore');

            Route::post('provinces/fetch', 'ProvinceFetchController@fetch')->name('provinces.fetch');
            Route::post('provinces/fetch?archived=1', 'ProvinceFetchController@fetch')->name('provinces.fetch-archive');
            Route::post('provinces/fetch-item/{id?}', 'ProvinceFetchController@fetchView')->name('provinces.fetch-item');
            Route::post('provinces/fetch-pagination/{id}', 'ProvinceFetchController@fetchPagePagination')->name('provinces.fetch-pagination');
        });

        Route::namespace('ShippingFees')->group(function() {

            /*
            |--------------------------------------------------------------------------
            | Standard Model
            |--------------------------------------------------------------------------
            */
           
            Route::get('shipping-standards', 'StandardController@index')->name('shipping-standards.index');
            Route::get('shipping-standards/create', 'StandardController@create')->name('shipping-standards.create');
            Route::post('shipping-standards/store', 'StandardController@store')->name('shipping-standards.store');
            Route::get('shipping-standards/show/{id}', 'StandardController@show')->name('shipping-standards.show');
            Route::post('shipping-standards/update/{id}', 'StandardController@update')->name('shipping-standards.update');
            Route::post('shipping-standards/{id}/archive', 'StandardController@archive')->name('shipping-standards.archive');
            Route::post('shipping-standards/{id}/restore', 'StandardController@restore')->name('shipping-standards.restore');

            Route::post('shipping-standards/fetch', 'StandardFetchController@fetch')->name('shipping-standards.fetch');
            Route::post('shipping-standards/fetch?archived=1', 'StandardFetchController@fetch')->name('shipping-standards.fetch-archive');
            Route::post('shipping-standards/fetch-item/{id?}', 'StandardFetchController@fetchView')->name('shipping-standards.fetch-item');
            Route::post('shipping-standards/fetch-pagination/{id}', 'StandardFetchController@fetchPagePagination')->name('shipping-standards.fetch-pagination');

            /*
            |--------------------------------------------------------------------------
            | Express Model
            |--------------------------------------------------------------------------
            */
           
           Route::get('shipping-express', 'ExpressController@index')->name('shipping-expresses.index');
           Route::get('shipping-express/create', 'ExpressController@create')->name('shipping-expresses.create');
           Route::post('shipping-express/store', 'ExpressController@store')->name('shipping-expresses.store');
           Route::get('shipping-express/show/{id}', 'ExpressController@show')->name('shipping-expresses.show');
           Route::post('shipping-express/update/{id}', 'ExpressController@update')->name('shipping-expresses.update');
           Route::post('shipping-express/{id}/archive', 'ExpressController@archive')->name('shipping-expresses.archive');
           Route::post('shipping-express/{id}/restore', 'ExpressController@restore')->name('shipping-expresses.restore');

           Route::post('shipping-express/fetch', 'ExpressFetchController@fetch')->name('shipping-expresses.fetch');
           Route::post('shipping-express/fetch?archived=1', 'ExpressFetchController@fetch')->name('shipping-expresses.fetch-archive');
           Route::post('shipping-express/fetch-item/{id?}', 'ExpressFetchController@fetchView')->name('shipping-expresses.fetch-item');
           Route::post('shipping-express/fetch-pagination/{id}', 'ExpressFetchController@fetchPagePagination')->name('shipping-expresses.fetch-pagination');
        });


        /*
        |--------------------------------------------------------------------------
        | @Medical Representative Targets Routes
        |--------------------------------------------------------------------------
        */
        Route::namespace('MedRepTargets')->group(function() {
            Route::post('medreps-targets/{id}/store', 'MedRepTargetController@store')->name('medrep-targets.store');
           Route::post('medreps-targets/update/{id}', 'MedRepTargetController@update')->name('medrep-targets.update');
           Route::post('medreps-targets/{id}/archive', 'MedRepTargetController@archive')->name('medrep-targets.archive');
           Route::post('medreps-targets/{id}/restore', 'MedRepTargetController@restore')->name('medrep-targets.restore');


            /*
            |--------------------------------------------------------------------------
            | @Medical Representative Fetch Routes
            |--------------------------------------------------------------------------
            */

            Route::post('medreps-targets/fetch', 'MedRepTargetFetchController@fetch')->name('medreps-targets.fetch');
            Route::post('medreps-targets/fetch?medrep={id}', 'MedRepTargetFetchController@fetch')->name('medrep-targets.fetch-by-medrep');

        });

        /*
        |--------------------------------------------------------------------------
        | @Medical Representative Routes
        |--------------------------------------------------------------------------
        */
        Route::namespace('MedicalRepresentatives')->group(function() {
            Route::get('representatives', 'MedicalRepresentativeController@index')->name('medreps.index');
            Route::get('representatives/get', 'MedicalRepresentativeController@get')->name('medreps.get');
            Route::get('representatives/create', 'MedicalRepresentativeController@create')->name('medreps.create');
            Route::post('representatives/store', 'MedicalRepresentativeController@store')->name('medreps.store');
            Route::get('representatives/show/{id?}', 'MedicalRepresentativeController@show')->name('medreps.show');
            Route::post('representatives/update/{id}', 'MedicalRepresentativeController@update')->name('medreps.update');
            Route::post('representatives/{id}/archive', 'MedicalRepresentativeController@archive')->name('medreps.archive');
            Route::post('representatives/{id}/restore', 'MedicalRepresentativeController@restore')->name('medreps.restore');
            Route::post('representatives/{id}/reports', 'MedicalRepresentativeController@reports')->name('medreps.reports');

            Route::get('representatives/location/logs', 'MedicalRepresentativeController@locationLogs')->name('medreps.location.tracker');

            /*
            |--------------------------------------------------------------------------
            | @Medical Representative Routes Fetch
            |--------------------------------------------------------------------------
            */
            Route::post('representatives/fetch', 'MedicalRepresentativeFetchController@fetch')->name('medreps.fetch');
            Route::post('representatives/fetch?archived=1', 'MedicalRepresentativeFetchController@fetch')->name('medreps.fetch-archive');
            Route::post('representatives/fetch-item/{id?}', 'MedicalRepresentativeFetchController@fetchView')->name('medreps.fetch-item');
            Route::post('representatives/fetch-pagination/{id}', 'MedicalRepresentativeFetchController@fetchPagePagination')->name('medreps.fetch-pagination');
        });


        Route::namespace('Areas')->group(function() {
            Route::get('areas', 'AreaController@index')->name('areas.index');
            Route::get('areas/create', 'AreaController@create')->name('areas.create');
            Route::post('areas/store', 'AreaController@store')->name('areas.store');
            Route::get('areas/show/{id?}', 'AreaController@show')->name('areas.show');
            Route::post('areas/update/{id}', 'AreaController@update')->name('areas.update');
            Route::post('areas/{id}/archive', 'AreaController@archive')->name('areas.archive');
            Route::post('areas/{id}/restore', 'AreaController@restore')->name('areas.restore');

            Route::post('areas/fetch', 'AreaFetchController@fetch')->name('areas.fetch');
            Route::post('areas/fetch?archived=1', 'AreaFetchController@fetch')->name('areas.fetch-archive');
            Route::post('areas/fetch-item/{id?}', 'AreaFetchController@fetchView')->name('areas.fetch-item');
            Route::post('areas/fetch-pagination/{id}', 'AreaFetchController@fetchPagePagination')->name('areas.fetch-pagination');
        });

        Route::namespace('ShippingMatrixes')->group(function() {
            Route::get('shipping-matrixes', 'ShippingMatrixController@index')->name('shipping-matrixes.index');
            Route::get('shipping-matrixes/create', 'ShippingMatrixController@create')->name('shipping-matrixes.create');
            Route::post('shipping-matrixes/store', 'ShippingMatrixController@store')->name('shipping-matrixes.store');
            Route::get('shipping-matrixes/show/{id?}', 'ShippingMatrixController@show')->name('shipping-matrixes.show');
            Route::post('shipping-matrixes/update/{id}', 'ShippingMatrixController@update')->name('shipping-matrixes.update');
            Route::post('shipping-matrixes/{id}/archive', 'ShippingMatrixController@archive')->name('shipping-matrixes.archive');
            Route::post('shipping-matrixes/{id}/restore', 'ShippingMatrixController@restore')->name('shipping-matrixes.restore');

            Route::post('shipping-matrixes/fetch', 'ShippingMatrixFetchController@fetch')->name('shipping-matrixes.fetch');
            Route::post('shipping-matrixes/fetch?archived=1', 'ShippingMatrixFetchController@fetch')->name('shipping-matrixes.fetch-archive');
            Route::post('shipping-matrixes/fetch-item/{id?}', 'ShippingMatrixFetchController@fetchView')->name('shipping-matrixes.fetch-item');
            Route::post('shipping-matrixes/fetch-pagination/{id}', 'ShippingMatrixFetchController@fetchPagePagination')->name('shipping-matrixes.fetch-pagination');
        });

        Route::namespace('Doctors')->group(function() {
            Route::get('doctors', 'DoctorController@index')->name('doctors.index');
            Route::get('doctors/get', 'DoctorController@get')->name('doctors.get');
            Route::get('doctors/create', 'DoctorController@create')->name('doctors.create');
            Route::post('doctors/store', 'DoctorController@store')->name('doctors.store');
            Route::get('doctors/show/{id}', 'DoctorController@show')->name('doctors.show');
            Route::post('doctors/update/{id}', 'DoctorController@update')->name('doctors.update');
            Route::post('doctors/{id}/archive', 'DoctorController@archive')->name('doctors.archive');
            Route::post('doctors/{id}/restore', 'DoctorController@restore')->name('doctors.restore');

            Route::post('doctors/{id}/approve', 'DoctorController@approve')->name('doctors.approve');
            Route::post('doctors/{id}/reject', 'DoctorController@reject')->name('doctors.reject');
            Route::post('doctors/{id}/send/reset/password', 'DoctorController@sendPasswordReset')->name('doctors.send.password.reset');
            Route::get('doctors/{id}/download', 'DoctorController@downloadQR')->name('doctors.download.qr');

            Route::get('doctors/{id}/manage-credits', 'DoctorController@manageCreditsForm')->name('doctors.manage-credits');
            Route::post('doctors/{id}/update-credits', 'DoctorController@updateCredits')->name('doctors.update-credits');

            Route::post('doctors/fetch', 'DoctorFetchController@fetch')->name('doctors.fetch');
            Route::post('doctors/fetch?archived=1', 'DoctorFetchController@fetch')->name('doctors.fetch-archive');
            Route::post('doctors/fetch-item/{id?}', 'DoctorFetchController@fetchView')->name('doctors.fetch-item');
            Route::post('doctors/fetch-pagination/{id}', 'DoctorFetchController@fetchPagePagination')->name('doctors.fetch-pagination');
        });

        Route::namespace('Cities')->group(function() {
            Route::get('cities', 'CityController@index')->name('cities.index');
            Route::get('cities/create', 'CityController@create')->name('cities.create');
            Route::post('cities/store', 'CityController@store')->name('cities.store');
            Route::get('cities/show/{id?}', 'CityController@show')->name('cities.show');
            Route::post('cities/update/{id}', 'CityController@update')->name('cities.update');
            Route::post('cities/{id}/archive', 'CityController@archive')->name('cities.archive');
            Route::post('cities/{id}/restore', 'CityController@restore')->name('cities.restore');

            Route::post('cities/fetch', 'CityFetchController@fetch')->name('cities.fetch');
            Route::post('cities/fetch?archived=1', 'CityFetchController@fetch')->name('cities.fetch-archive');
            Route::post('cities/fetch-item/{id?}', 'CityFetchController@fetchView')->name('cities.fetch-item');
            Route::post('cities/fetch-pagination/{id}', 'CityFetchController@fetchPagePagination')->name('cities.fetch-pagination');
        });

        Route::namespace('BankDetails')->group(function() {
            Route::get('bank-details', 'BankDetailController@index')->name('bank-details.index');
            Route::get('bank-details/create', 'BankDetailController@create')->name('bank-details.create');
            Route::post('bank-details/store', 'BankDetailController@store')->name('bank-details.store');
            Route::get('bank-details/show/{id?}', 'BankDetailController@show')->name('bank-details.show');
            Route::post('bank-details/update/{id}', 'BankDetailController@update')->name('bank-details.update');
            Route::post('bank-details/{id}/archive', 'BankDetailController@archive')->name('bank-details.archive');
            Route::post('bank-details/{id}/restore', 'BankDetailController@restore')->name('bank-details.restore');

            Route::post('bank-details/fetch', 'BankDetailFetchController@fetch')->name('bank-details.fetch');
            Route::post('bank-details/fetch?archived=1', 'BankDetailFetchController@fetch')->name('bank-details.fetch-archive');
            Route::post('bank-details/fetch-item/{id?}', 'BankDetailFetchController@fetchView')->name('bank-details.fetch-item');
            Route::post('bank-details/fetch-pagination/{id}', 'BankDetailFetchController@fetchPagePagination')->name('bank-details.fetch-pagination');
        });
        
        Route::namespace('TargetCalls')->group(function() {
            Route::get('target-calls', 'TargetCallController@index')->name('target-calls.index');
            Route::get('target-calls/create', 'TargetCallController@create')->name('target-calls.create');
            Route::post('target-calls/store', 'TargetCallController@store')->name('target-calls.store');
            Route::get('target-calls/show/{id?}', 'TargetCallController@show')->name('target-calls.show');
            Route::post('target-calls/update/{id}', 'TargetCallController@update')->name('target-calls.update');
            Route::post('target-calls/{id}/archive', 'TargetCallController@archive')->name('target-calls.archive');
            Route::post('target-calls/{id}/restore', 'TargetCallController@restore')->name('target-calls.restore');

            Route::post('target-calls/fetch', 'TargetCallFetchController@fetch')->name('target-calls.fetch');
            Route::post('target-calls/fetch?archived=1', 'TargetCallFetchController@fetch')->name('target-calls.fetch-archive');
            Route::post('target-calls/fetch-item/{id?}', 'TargetCallFetchController@fetchView')->name('target-calls.fetch-item');
            Route::post('target-calls/fetch-pagination/{id}', 'TargetCallFetchController@fetchPagePagination')->name('target-calls.fetch-pagination');
        });

        Route::namespace('Calls')->group(function() {
            Route::get('calls', 'CallController@index')->name('calls.index');
            Route::get('calls/create', 'CallController@create')->name('calls.create');
            Route::post('calls/store', 'CallController@store')->name('calls.store');
            Route::get('calls/show/{id}', 'CallController@show')->name('calls.show');
            Route::post('calls/update/{id}', 'CallController@update')->name('calls.update');
            Route::post('calls/{id}/archive', 'CallController@archive')->name('calls.archive');
            Route::post('calls/{id}/restore', 'CallController@restore')->name('calls.restore');

            Route::post('calls/approve', 'CallController@approve')->name('calls.approve');
            Route::post('calls/reject', 'CallController@reject')->name('calls.reject');

            Route::post('calls/fetch', 'CallFetchController@fetch')->name('calls.fetch');
            Route::post('calls/fetch?archived=1', 'CallFetchController@fetch')->name('calls.fetch-archive');
            Route::post('calls/fetch-item/{id?}', 'CallFetchController@fetchView')->name('calls.fetch-item');
            Route::post('calls/fetch-pagination/{id}', 'CallFetchController@fetchPagePagination')->name('calls.fetch-pagination');
        });

        Route::namespace('Prescriptions')->group(function() {
            Route::get('prescriptions', 'PrescriptionController@index')->name('prescriptions.index');
            Route::get('prescriptions/show/{id}', 'PrescriptionController@show')->name('prescriptions.show');
            Route::post('prescriptions/update/{id?}', 'PrescriptionController@update')->name('prescriptions.update');
            Route::post('prescriptions/{id}/archive', 'PrescriptionController@archive')->name('prescriptions.archive');

            Route::post('prescriptions/fetch', 'PrescriptionFetchController@fetch')->name('prescriptions.fetch');
            Route::post('prescriptions/fetch?archived=1', 'PrescriptionFetchController@fetch')->name('prescriptions.fetch-archive');
            Route::post('prescriptions/fetch-item/{id?}', 'PrescriptionFetchController@fetchView')->name('prescriptions.fetch-item');
            Route::post('prescriptions/fetch-pagination/{id}', 'PrescriptionFetchController@fetchPagePagination')->name('prescriptions.fetch-pagination');
        });

        Route::namespace('Points')->group(function() {
            Route::get('points', 'PointController@index')->name('points.index');

            Route::post('points/fetch', 'PointFetchController@fetch')->name('points.fetch');
            Route::post('points/fetch-pagination/{id}', 'PointFetchController@fetchPagePagination')->name('points.fetch-pagination');
        });

        /*
        |--------------------------------------------------------------------------
        | @Redemption Routes
        |--------------------------------------------------------------------------
        */      
        Route::namespace('Redeems')->group(function() {
            
            Route::get('redeems', 'RedeemController@index')->name('redeems.index');

            Route::post('redeems/{id}/approve', 'RedeemController@approve')->name('redeems.approve');
            Route::post('redeems/{id}/reject', 'RedeemController@reject')->name('redeems.reject');

            /*
            |--------------------------------------------------------------------------
            | @Redemption | Fetch Routes
            |--------------------------------------------------------------------------
            */
            Route::post('redeems/fetch', 'RedeemFetchController@fetch')->name('redeems.fetch');

        });

        Route::namespace('Rewards')->group(function() {
            Route::get('rewards', 'RewardController@index')->name('rewards.index');
            Route::get('rewards/create', 'RewardController@create')->name('rewards.create');
            Route::post('rewards/store', 'RewardController@store')->name('rewards.store');
            Route::get('rewards/show/{id?}', 'RewardController@show')->name('rewards.show');
            Route::post('rewards/update/{id}', 'RewardController@update')->name('rewards.update');
            Route::post('rewards/{id}/archive', 'RewardController@archive')->name('rewards.archive');
            Route::post('rewards/{id}/restore', 'RewardController@restore')->name('rewards.restore');

            Route::post('rewards/fetch', 'RewardFetchController@fetch')->name('rewards.fetch');
            Route::post('rewards/fetch?archived=1', 'RewardFetchController@fetch')->name('rewards.fetch-archive');
            Route::post('rewards/fetch-item/{id?}', 'RewardFetchController@fetchView')->name('rewards.fetch-item');
            Route::post('rewards/fetch-pagination/{id}', 'RewardFetchController@fetchPagePagination')->name('rewards.fetch-pagination');
        });

        Route::namespace('Sponsorships')->group(function() {
            Route::get('sponsorships', 'SponsorshipController@index')->name('sponsorships.index');
            Route::get('sponsorships/create', 'SponsorshipController@create')->name('sponsorships.create');
            Route::post('sponsorships/store', 'SponsorshipController@store')->name('sponsorships.store');
            Route::get('sponsorships/show/{id}', 'SponsorshipController@show')->name('sponsorships.show');
            Route::post('sponsorships/update/{id}', 'SponsorshipController@update')->name('sponsorships.update');
            Route::post('sponsorships/{id}/archive', 'SponsorshipController@archive')->name('sponsorships.archive');
            Route::post('sponsorships/{id}/restore', 'SponsorshipController@restore')->name('sponsorships.restore');

            Route::post('sponsorships/{id}/approve', 'SponsorshipController@approve')->name('sponsorships.approve');
            Route::post('sponsorships/{id}/reject', 'SponsorshipController@reject')->name('sponsorships.reject');

            Route::post('sponsorships/fetch', 'SponsorshipFetchController@fetch')->name('sponsorships.fetch');
            Route::post('sponsorships/fetch?archived=1', 'SponsorshipFetchController@fetch')->name('sponsorships.fetch-archive');
            Route::post('sponsorships/fetch-item/{id?}', 'SponsorshipFetchController@fetchView')->name('sponsorships.fetch-item');
            Route::post('sponsorships/fetch-pagination/{id}', 'SponsorshipFetchController@fetchPagePagination')->name('sponsorships.fetch-pagination');
        });

        Route::namespace('Sales')->group(function() {
            Route::get('sales', 'SalesController@get')->name('sales');

            Route::get('reports', 'SalesController@index')->name('reports.index');
            Route::post('reports/generate', 'SalesController@generateReport')->name('reports.generate');
        });

        Route::namespace('Faqs')->group(function() {
            Route::get('faqs', 'FaqController@index')->name('faqs.index');
            Route::get('faqs/create', 'FaqController@create')->name('faqs.create');
            Route::post('faqs/store', 'FaqController@store')->name('faqs.store');
            Route::get('faqs/show/{id?}', 'FaqController@show')->name('faqs.show');
            Route::post('faqs/update/{id}', 'FaqController@update')->name('faqs.update');
            Route::post('faqs/{id}/archive', 'FaqController@archive')->name('faqs.archive');
            Route::post('faqs/{id}/restore', 'FaqController@restore')->name('faqs.restore');

            Route::post('faqs/fetch', 'FaqFetchController@fetch')->name('faqs.fetch');
            Route::post('faqs/fetch?archived=1', 'FaqFetchController@fetch')->name('faqs.fetch-archive');
            Route::post('faqs/fetch-item/{id?}', 'FaqFetchController@fetchView')->name('faqs.fetch-item');
            Route::post('faqs/fetch-pagination/{id}', 'FaqFetchController@fetchPagePagination')->name('faqs.fetch-pagination');
        });

        Route::namespace('FaqCategories')->group(function() {
            Route::get('faq-categories', 'FaqCategoryController@index')->name('faq-categories.index');
            Route::get('faq-categories/create', 'FaqCategoryController@create')->name('faq-categories.create');
            Route::post('faq-categories/store', 'FaqCategoryController@store')->name('faq-categories.store');
            Route::get('faq-categories/show/{id?}', 'FaqCategoryController@show')->name('faq-categories.show');
            Route::post('faq-categories/update/{id}', 'FaqCategoryController@update')->name('faq-categories.update');
            Route::post('faq-categories/{id}/archive', 'FaqCategoryController@archive')->name('faq-categories.archive');
            Route::post('faq-categories/{id}/restore', 'FaqCategoryController@restore')->name('faq-categories.restore');

            Route::post('faq-categories/fetch', 'FaqCategoryFetchController@fetch')->name('faq-categories.fetch');
            Route::post('faq-categories/fetch?archived=1', 'FaqCategoryFetchController@fetch')->name('faq-categories.fetch-archive');
            Route::post('faq-categories/fetch-item/{id?}', 'FaqCategoryFetchController@fetchView')->name('faq-categories.fetch-item');
            Route::post('faq-categories/fetch-pagination/{id}', 'FaqCategoryFetchController@fetchPagePagination')->name('faq-categories.fetch-pagination');
        });

        Route::namespace('Announcements')->group(function() {
            Route::get('announcement-types', 'AnnouncementTypeController@index')->name('announcement-types.index');
            Route::get('announcement-types/create', 'AnnouncementTypeController@create')->name('announcement-types.create');
            Route::post('announcement-types/store', 'AnnouncementTypeController@store')->name('announcement-types.store');
            Route::get('announcement-types/show/{id?}', 'AnnouncementTypeController@show')->name('announcement-types.show');
            Route::post('announcement-types/update/{id}', 'AnnouncementTypeController@update')->name('announcement-types.update');
            Route::post('announcement-types/{id}/archive', 'AnnouncementTypeController@archive')->name('announcement-types.archive');
            Route::post('announcement-types/{id}/restore', 'AnnouncementTypeController@restore')->name('announcement-types.restore');

            Route::post('announcement-types/fetch', 'AnnouncementTypeFetchController@fetch')->name('announcement-types.fetch');
            Route::post('announcement-types/fetch?archived=1', 'AnnouncementTypeFetchController@fetch')->name('announcement-types.fetch-archive');
            Route::post('announcement-types/fetch-item/{id?}', 'AnnouncementTypeFetchController@fetchView')->name('announcement-types.fetch-item');
            Route::post('announcement-types/fetch-pagination/{id}', 'AnnouncementTypeFetchController@fetchPagePagination')->name('announcement-types.fetch-pagination');
        });

        Route::namespace('Announcements')->group(function() {
            Route::get('announcements', 'AnnouncementController@index')->name('announcements.index');
            Route::get('announcements/create', 'AnnouncementController@create')->name('announcements.create');
            Route::post('announcements/store', 'AnnouncementController@store')->name('announcements.store');
            Route::get('announcements/show/{id?}', 'AnnouncementController@show')->name('announcements.show');
            Route::post('announcements/update/{id}', 'AnnouncementController@update')->name('announcements.update');
            Route::post('announcements/{id}/archive', 'AnnouncementController@archive')->name('announcements.archive');
            Route::post('announcements/{id}/restore', 'AnnouncementController@restore')->name('announcements.restore');

            Route::post('announcements/fetch', 'AnnouncementFetchController@fetch')->name('announcements.fetch');
            Route::post('announcements/fetch?archived=1', 'AnnouncementFetchController@fetch')->name('announcements.fetch-archive');
            Route::post('announcements/fetch-item/{id?}', 'AnnouncementFetchController@fetchView')->name('announcements.fetch-item');
            Route::post('announcements/fetch-pagination/{id}', 'AnnouncementFetchController@fetchPagePagination')->name('announcements.fetch-pagination');
        });

        /*
        |--------------------------------------------------------------------------
        | @Credit Package Routes
        |--------------------------------------------------------------------------
        */ 
        Route::namespace('CreditPackages')->group(function() {
            Route::get('credit-packages', 'CreditPackageController@index')->name('credit-packages.index');
            Route::get('credit-packages/create', 'CreditPackageController@create')->name('credit-packages.create');
            Route::post('credit-packages/store', 'CreditPackageController@store')->name('credit-packages.store');
            Route::get('credit-packages/show/{id?}', 'CreditPackageController@show')->name('credit-packages.show');
            Route::post('credit-packages/update/{id}', 'CreditPackageController@update')->name('credit-packages.update');
            Route::post('credit-packages/{id}/archive', 'CreditPackageController@archive')->name('credit-packages.archive');
            Route::post('credit-packages/{id}/restore', 'CreditPackageController@restore')->name('credit-packages.restore');

            Route::post('credit-packages/fetch', 'CreditPackageFetchController@fetch')->name('credit-packages.fetch');
            Route::post('credit-packages/fetch?archived=1', 'CreditPackageFetchController@fetch')->name('credit-packages.fetch-archive');
            Route::post('credit-packages/fetch-item/{id?}', 'CreditPackageFetchController@fetchView')->name('credit-packages.fetch-item');
            Route::post('credit-packages/fetch-pagination/{id}', 'CreditPackageFetchController@fetchPagePagination')->name('credit-packages.fetch-pagination');
        });

        /*
        |--------------------------------------------------------------------------
        | @Payout Routes
        |--------------------------------------------------------------------------
        */
         Route::namespace('Payouts')->group(function() {       
            Route::get('payouts', 'PayoutController@index')->name('payouts.index');
            Route::get('payouts/{id}/show', 'PayoutController@show')->name('payouts.show');
            Route::post('payouts/{id}/disapprove', 'PayoutController@disapprove')->name('payouts.disapprove');
            Route::post('payouts/{id}/archive', 'PayoutController@archive')->name('payouts.archive');
            Route::post('payouts/{id}/restore', 'PayoutController@restore')->name('payouts.restore');

            Route::post('payouts/{id}/approve', 'PayoutController@approve')->name('payouts.approve');
            // Route::post('payouts/{id}/disapprove', 'PayoutController@disapprove')->name('payouts.disapprove');
            Route::get('payouts/{id}/disapproval-form', 'PayoutController@disapprovalForm')->name('payouts.disapproval-form');

            Route::post('payouts/fetch', 'PayoutFetchController@fetch')->name('payouts.fetch');
            Route::post('payouts/fetch?archived=1', 'PayoutFetchController@fetch')->name('payouts.fetch-archive');
            Route::post('payouts/fetch-item/{id?}', 'PayoutFetchController@fetchView')->name('payouts.fetch-item');
        });

        /*
        |--------------------------------------------------------------------------
        | @Refund Routes
        |--------------------------------------------------------------------------
        */
         Route::namespace('Refunds')->group(function() {       
            Route::get('refunds', 'RefundController@index')->name('refunds.index');
            Route::get('refunds/{id}/show', 'RefundController@show')->name('refunds.show');
            Route::post('refunds/{id}/update', 'RefundController@update')->name('refunds.update');
            Route::post('refunds/{id}/archive', 'RefundController@archive')->name('refunds.archive');
            Route::post('refunds/{id}/restore', 'RefundController@restore')->name('refunds.restore');

            Route::post('refunds/{id}/approve', 'RefundController@approve')->name('refunds.approve');
            Route::get('refunds/{id}/disapprove', 'RefundController@disapprovalForm')->name('refunds.disapproval-form');

            Route::post('refunds/fetch', 'RefundFetchController@fetch')->name('refunds.fetch');
            Route::post('refunds/fetch?archived=1', 'RefundFetchController@fetch')->name('refunds.fetch-archive');
            Route::post('refunds/fetch-item/{id?}', 'RefundFetchController@fetchView')->name('refunds.fetch-item');
            Route::post('refunds/fetch-pagination/{id}', 'RefundFetchController@fetchPagePagination')->name('refunds.fetch-pagination');
        });

        /*
        |--------------------------------------------------------------------------
        | @Credit Invoices Routes
        |--------------------------------------------------------------------------
        */ 
        Route::namespace('CreditInvoices')->group(function() {
            Route::get('credit-invoices', 'CreditInvoiceController@index')->name('credit-invoices.index');
            Route::post('credit-invoices/{id}/archive', 'CreditInvoiceController@archive')->name('credit-invoices.archive');
            Route::post('credit-invoices/{id}/restore', 'CreditInvoiceController@restore')->name('credit-invoices.restore');

            Route::post('credit-invoices/fetch', 'CreditInvoiceFetchController@fetch')->name('credit-invoices.fetch');
            Route::post('credit-invoices/fetch?archived=1', 'CreditInvoiceFetchController@fetch')->name('credit-invoices.fetch-archive');
            Route::post('credit-invoices/fetch-pagination/{id}', 'CreditInvoiceFetchController@fetchPagePagination')->name('credit-invoices.fetch-pagination');
        });         

        /*
        |--------------------------------------------------------------------------
        | @Pharmacies
        |--------------------------------------------------------------------------
        */ 
        Route::namespace('Pharmacies')->group(function() {
            Route::get('pharmacies', 'PharmacyController@index')->name('pharmacies.index');
            Route::get('pharmacies/create', 'PharmacyController@create')->name('pharmacies.create');
            Route::post('pharmacies/store', 'PharmacyController@store')->name('pharmacies.store');
            Route::get('pharmacies/show/{id?}', 'PharmacyController@show')->name('pharmacies.show');
            Route::post('pharmacies/update/{id}', 'PharmacyController@update')->name('pharmacies.update');
            Route::post('pharmacies/{id}/archive', 'PharmacyController@archive')->name('pharmacies.archive');
            Route::post('pharmacies/{id}/restore', 'PharmacyController@restore')->name('pharmacies.restore');

            Route::post('pharmacies/fetch', 'PharmacyFetchController@fetch')->name('pharmacies.fetch');
            Route::post('pharmacies/fetch?archived=1', 'PharmacyFetchController@fetch')->name('pharmacies.fetch-archive');
            Route::post('pharmacies/fetch-item/{id?}', 'PharmacyFetchController@fetchView')->name('pharmacies.fetch-item');
            Route::post('pharmacies/fetch-pagination/{id}', 'PharmacyFetchController@fetchPagePagination')->name('pharmacies.fetch-pagination');
        });

        /*
        |--------------------------------------------------------------------------
        | @Consultation Routes
        |--------------------------------------------------------------------------
        */ 
        Route::namespace('Consultations')->group(function() {
            Route::get('consultations', 'ConsultationController@index')->name('consultations.index');
            Route::get('consultations/{id}/{consultation_number}/show', 'ConsultationController@show')->name('consultations.show');

            Route::post('consultations/fetch', 'ConsultationFetchController@fetch')->name('consultations.fetch');
            Route::post('consultations/fetch?doctor=1&id={id}', 'ConsultationFetchController@fetch')->name('consultations.fetch-by-doctor');            
            Route::post('consultations/fetch-item/{id?}', 'ConsultationFetchController@fetchView')->name('consultations.fetch-item');

        });        
    });

    /*
    |--------------------------------------------------------------------------
    | @Paynamics Checkout Routes
    |--------------------------------------------------------------------------
    */ 
    Route::namespace('Invoices')->group(function() {
        Route::post('checkout/processPaynamics', 'InvoiceController@processPaynamics')->name('checkout.process_paynamics');
        Route::get('checkout/paynamicsReturn', 'InvoiceController@paynamicsReturn')->name('checkout.paynamics_return');        
        Route::get('checkout/paynamicsCancel', 'InvoiceController@paynamicsCancel')->name('checkout.paynamics-cancel');


        Route::post('credit-package/checkout/processPaynamics', 'CreditInvoiceController@processPaynamics')->name('credit-package.process_paynamics');
        Route::get('credit-package/checkout/paynamicsReturn', 'CreditInvoiceController@paynamicsReturn')->name('credit-package.paynamics_return');        
        Route::get('credit-package/checkout/paynamicsCancel', 'CreditInvoiceController@paynamicsCancel')->name('credit-package.paynamics-cancel');
    });


    /*
    |--------------------------------------------------------------------------
    | @Phase 5 Routes
    |--------------------------------------------------------------------------
    */
   

    /*
    |--------------------------------------------------------------------------
    | @Doctor Reviews
    |--------------------------------------------------------------------------
    */
    Route::namespace('DoctorReviews')->group(function() {
        Route::get('doctor-reviews', 'DoctorReviewController@index')->name('doctor-reviews.index');
        Route::post('doctor-reviews/{id}/archive', 'DoctorReviewController@archive')->name('doctor-reviews.archive');
        Route::post('doctor-reviews/{id}/restore', 'DoctorReviewController@restore')->name('doctor-reviews.restore');


        Route::post('doctor-reviews/fetch', 'DoctorReviewFetchController@fetch')->name('doctor-reviews.fetch');
        Route::post('doctor-reviews/fetch?archived=1', 'DoctorReviewFetchController@fetch')->name('doctor-reviews.fetch-archive');
    });

    /*
    |--------------------------------------------------------------------------
    | @Vouchers
    |--------------------------------------------------------------------------
    */ 
    Route::namespace('Vouchers')->group(function() {
        Route::get('vouchers', 'VoucherController@index')->name('vouchers.index');
        Route::get('vouchers/create', 'VoucherController@create')->name('vouchers.create');
        Route::post('vouchers/store', 'VoucherController@store')->name('vouchers.store');
        Route::get('vouchers/show/{id?}', 'VoucherController@show')->name('vouchers.show');
        Route::post('vouchers/update/{id}', 'VoucherController@update')->name('vouchers.update');
        Route::post('vouchers/{id}/archive', 'VoucherController@archive')->name('vouchers.archive');
        Route::post('vouchers/{id}/restore', 'VoucherController@restore')->name('vouchers.restore');

        Route::post('vouchers/fetch', 'VoucherFetchController@fetch')->name('vouchers.fetch');
        Route::post('vouchers/fetch?archived=1', 'VoucherFetchController@fetch')->name('vouchers.fetch-archive');
        Route::post('vouchers/fetch-item/{id?}', 'VoucherFetchController@fetchView')->name('vouchers.fetch-item');
        Route::post('vouchers/fetch-pagination/{id}', 'VoucherFetchController@fetchPagePagination')->name('vouchers.fetch-pagination');
    });

    /*
    |--------------------------------------------------------------------------
    | @Used Vouchers
    |--------------------------------------------------------------------------
    */
    Route::namespace('UsedVouchers')->group(function() {
        Route::get('used-vouchers', 'UsedVoucherController@index')->name('used-vouchers.index');

        Route::post('used-vouchers/fetch', 'UsedVoucherFetchController@fetch')->name('used-vouchers.fetch');
    });

    /*
    |--------------------------------------------------------------------------
    | @Request Claim Referrals
    |--------------------------------------------------------------------------
    */
    Route::namespace('RequestClaimReferrals')->group(function() {
        Route::get('request-claim-referrals', 'RequestClaimReferralController@index')->name('request-claim-referrals.index');

        Route::post('request-claim-referrals/{id}/archive', 'RequestClaimReferralController@archive')->name('request-claim-referrals.archive');
        Route::post('request-claim-referrals/{id}/restore', 'RequestClaimReferralController@restore')->name('request-claim-referrals.restore');        

        Route::get('request-claim-referrals/{id}/{action}/update', 'RequestClaimReferralController@displayForm')->name('request-claim-referrals.approve');
        Route::post('request-claim-referrals/{id}/{action}/update', 'RequestClaimReferralController@displayForm')->name('request-claim-referrals.reject');

        Route::post('request-claim-referrals/{id}/fetch-item', 'RequestClaimReferralFetchController@fetchView')->name('request-claim-referrals.fetch-item');

        Route::post('request-claim-referrals/{id}/{action}/submit', 'RequestClaimReferralController@updateStatus')->name('request-claim-referrals.submit');

        Route::post('request-claim-referrals/fetch', 'RequestClaimReferralFetchController@fetch')->name('request-claim-referrals.fetch');
        Route::post('request-claim-referrals/fetch?pending=1', 'RequestClaimReferralFetchController@fetch')->name('request-claim-referrals.fetch-pending');
        Route::post('request-claim-referrals/fetch?approved=1', 'RequestClaimReferralFetchController@fetch')->name('request-claim-referrals.fetch-approved');
        Route::post('request-claim-referrals/fetch?rejected=1', 'RequestClaimReferralFetchController@fetch')->name('request-claim-referrals.fetch-rejected');                
        Route::post('request-claim-referrals/fetch?archived=1', 'RequestClaimReferralFetchController@fetch')->name('request-claim-referrals.fetch-archive');

    });


});