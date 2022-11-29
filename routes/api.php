<?php

use Illuminate\Http\Request;

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

Route::name('api.')->middleware(['cors'])->namespace('API')->group(function() {
    
    Route::group(['middleware' => ['assign.guard:care', 'jwt.auth']], function() {
        Route::post('device-token/store', 'DeviceTokenController@store')->name('store.token');
    });

    Route::group(['middleware' => ['assign.guard:doctor', 'jwt.auth']], function() {
        Route::post('doc/device-token/store', 'DeviceTokenController@store')->name('doc.store.token');
    });
    
    /*
    |--------------------------------------------------------------------------
    | Care Routes
    |--------------------------------------------------------------------------
    */
    
    Route::name('care.')->prefix('care')->namespace('Care')->group( function() {
        
        Route::post('config/fetch', 'ConfigFetchController@fetch')->name('fetch.config');

        Route::namespace('Auth')->group(function() {
            Route::post('/login', 'LoginController@login')->name('login');
            Route::post('/login/google', 'GoogleLoginController@login')->name('google.login');
            Route::get('/login/facebook', 'FacebookLoginController@login')->name('facebook.login');
            Route::post('/login/apple', 'AppleLoginController@login')->name('apple.login');            
            Route::get('/callback/facebook', 'FacebookLoginController@facebookCallback')->name('facebook.callback');
            Route::post('/logout', 'LoginController@logout')->name('logout');
            Route::post('/register', 'RegisterController@register')->name('register');
            Route::post('/fb/register', 'RegisterController@fbRegister')->name('fb.register');
            Route::post('/forgot-password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('password.email');

            Route::post('/verify/qr', 'VerificationController@verifyQr')->name('verify.qr');
            Route::post('/verify/email', 'VerificationController@verifyEmail')->name('verify.email');
            Route::post('/verify/prescription', 'VerificationController@verifyPrescription')->name('verify.prescription');
        });

        Route::group(['middleware' => ['assign.guard:care', 'jwt.auth']], function() {
           

            Route::namespace('Resources')->group(function() {
                Route::post('/home', 'HomeController@resources')->name('home');
                Route::post('/shipping-matrixes/fetch', 'ShippingMatrixController@fetch')->name('shipping-matrixes.fetch');
            });

            Route::namespace('Doctors')->group(function() {
                Route::post('/doctor/fetch', 'DoctorFetchController@fetch')->name('doctor.fetch');
                Route::post('/doctor/scan', 'DoctorController@scanQr')->name('doctor.scan');
                Route::post('/doctor/scan/validate', 'DoctorController@validateQr')->name('doctor.scan.validate');
            });

            Route::namespace('Users')->group(function() {
                Route::post('/profile/update', 'UserController@updateBasicInfo')->name('profile.update');
                Route::post('/password/update', 'UserController@changePassword')->name('password.change');

                Route::post('/profile/setting/update', 'UserSettingController@store')->name('setting.update');

                Route::post('/my-health/update', 'MyHealthController@update')->name('my-health.update');
                Route::post('/my-health/fetch', 'MyHealthController@fetch')->name('my-health.fetch');
                Route::post('/my-health/update/reviewers', 'MyHealthController@updateReviewers')->name('my-health.update-reviewers');                
            });

            Route::namespace('Products')->group(function() {
                Route::post('/product-parents/fetch', 'ProductParentFetchController@fetch')->name('product-parents.fetch');
                Route::post('/products/fetch', 'ProductFetchController@fetch')->name('products.fetch');
                Route::post('/products/fetch/variants', 'ProductFetchController@fetchVariants')->name('products.fetch.variants');
                Route::post('/products/fetch?specializations={id?}&product_name={name?}', 'ProductFetchController@fetch')->name('products.fetch');

                Route::post('product/filter-fetch', 'ProductFetchController@fetchFilter')->name('products-filter.fetch');
            });

            Route::namespace('Articles')->group(function() {
                Route::post('/articles/store', 'ArticleController@store')->name('articles.store');
                Route::post('/articles/fetch', 'ArticleFetchController@fetch')->name('articles.fetch');
                Route::post('/articles/fetch?filter={id?}&title={title?}', 'ArticleFetchController@fetch')->name('articles.fetch');
                Route::post('/comments/fetch', 'ArticleController@comments')->name('articles.comments.fetch');
                Route::post('/related-article/fetch', 'ArticleController@show')->name('articles.related.fetch');

                Route::post('/replies/store', 'ArticleController@addReplyToComment')->name('replies.store');
            });

            Route::namespace('Addresses')->group(function() {
                Route::group(['prefix' => 'address'], function() {
                    Route::post('add', 'AddressController@store')->name('address.add');
                    Route::post('{id}/update', 'AddressController@update')->name('address.update');
                    Route::post('{id}/delete', 'AddressController@delete')->name('address.delete');
                });
            });

            Route::namespace('Carts')->group(function() {
                Route::get('cart/fetch', 'CartController@viewCart')->name('cart.fetch');
                Route::post('cart/add', 'CartController@addToCart')->name('cart.add');
                Route::post('cart-item/update/quantity', 'CartController@updateCartItem')->name('cart.item.update');
                Route::post('cart/items/delete', 'CartController@deleteCartItem')->name('cart.delete');
                Route::post('cart/items/validate', 'CartController@validateQr')->name('cart.validate');
                Route::post('cart/items/upload', 'CartController@uploadPrescription')->name('cart.prescription.upload');

                /*
                |--------------------------------------------------------------------------
                | Phase 5 Routes
                |--------------------------------------------------------------------------
                */

                    /*
                    |--------------------------------------------------------------------------
                    | MD Linking
                    |--------------------------------------------------------------------------
                    */                    
                    Route::post('cart/items/linkedmdupdate', 'CartController@linkedMDUpdate')->name('cart.linked-md.update');
            });

            Route::namespace('Invoices')->group(function() {
                Route::post('invoices', 'InvoiceController@get')->name('invoice.get');
                Route::post('checkout', 'InvoiceController@checkout')->name('invoice.checkout');
                Route::get('checkout/return', 'InvoiceController@return')->name('invoice.return');
                Route::post('checkout/update', 'InvoiceController@update')->name('invoice.update');
                Route::post('upload/deposit', 'InvoiceController@uploadDepositSlip')->name('upload.deposit');
                Route::post('fetch/invoice', 'InvoiceController@fetch')->name('invoice.fetch');
            });
            
            Route::namespace('Faqs')->group(function() {
                Route::get('/faqs', 'FaqController@index')->name('faqs.fetch');
            });

            Route::post('/merchants', 'RewardController@merchants')->name('merchants');
            Route::post('/merchant/details', 'RewardController@details')->name('merchant.details');
            Route::post('/redeem/rewards', 'RewardController@redeem')->name('reward.redeem');
            Route::post('/redeem/history', 'RewardController@history')->name('history.redeem');




        /*
        |--------------------------------------------------------------------------
        | Phase 4 Routes
        |--------------------------------------------------------------------------
        */
           
            /*
            |--------------------------------------------------------------------------
            | Credit Packages Routes
            |--------------------------------------------------------------------------
            */
            Route::namespace('CreditPackages')->group(function() {
                Route::post('history', 'CreditPackageController@history')->name('credit-packages.history');
                Route::post('packages', 'CreditPackageController@fetch')->name('credit-packages.fetch');
            });


            /*
            |--------------------------------------------------------------------------
            | Consultation Routes
            |--------------------------------------------------------------------------
            */
            Route::namespace('Consultations')->group(function() {
                Route::post('consultations/fetch', 'ConsultationController@fetch')->name('consultations.fetch');
                Route::post('consultations/selected', 'ConsultationController@fetchConsultation')->name('consultations.fetch-consultation');
                Route::post('consultations/fetch-history', 'ConsultationController@fetchHistory')->name('consultations.fetch-history');                
                Route::post('consultations/store', 'ConsultationController@store')->name('consultations.store');
                Route::post('consultations/cancel', 'ConsultationController@cancel')->name('consultations.cancel');
                Route::post('consultations/check/pending', 'ConsultationController@checkPending')->name('consultations.check-pending');
                Route::post('consultations/completed', 'ConsultationController@completed')->name('consultations.completed');

                Route::post('consultations/chat/request', 'ConsultationController@chatRequest')->name('consultations.chat-request');
                Route::post('consultations/send/notification', 'ConsultationController@sendNotification')->name('consultations.send.notification');      
            });

            /*
            |--------------------------------------------------------------------------
            | Specialization Route
            |--------------------------------------------------------------------------
            */
            Route::namespace('Specializations')->group(function() {
                Route::post('/specializations/fetch', 'SpecializationController@fetch')->name('specializations.fetch');
                Route::post('/specialization/doctors/fetch', 'SpecializationController@fetchDoctor')->name('specialization-doctors.fetch');
            });

            /*
            |--------------------------------------------------------------------------
            | Doctor Route
            |--------------------------------------------------------------------------
            */
            Route::namespace('Doctors')->group(function() {
                Route::post('/doctors/list', 'DoctorController@doctorList')->name('doctors.list');
            });

            /*
            |--------------------------------------------------------------------------
            | CreditInvoice Route
            |--------------------------------------------------------------------------
            */
            Route::namespace('Invoices')->group(function() {
                Route::post('credit-package/checkout', 'CreditInvoiceController@checkout')->name('credit-package.checkout');
                Route::get('credit-package/return', 'CreditInvoiceController@return')->name('credit-package.return');
            });
            /*
            |--------------------------------------------------------------------------
            | Personal Information Routes
            |--------------------------------------------------------------------------
            */
            Route::namespace('Users')->group(function() {
                Route::post('personal-informations/store', 'PersonalInformationController@store')->name('personal-informations.store');
                Route::post('personal-informations/share', 'PersonalInformationController@share')->name('personal-informations.share');
            });

            /*
            |--------------------------------------------------------------------------
            | Consultation Chat Routes
            |--------------------------------------------------------------------------
            */
            Route::namespace('ConsultationChats')->group(function() {
                Route::post('consultation-chats/fetch', 'ConsultationChatController@fetch')->name('consultation-chats.fetch');
                Route::post('consultation-chats/store', 'ConsultationChatController@store')->name('consultation-chats.store');
            });


            /*
            |--------------------------------------------------------------------------
            | Schedules Routes
            |--------------------------------------------------------------------------
            */

            Route::namespace('Schedules')->group(function() {
                Route::post('schedules/fetch', 'ScheduleController@fetch')->name('schedules.fetch');
            });


            /*
            |--------------------------------------------------------------------------
            | Refunds Routes
            |--------------------------------------------------------------------------
            */            
            Route::namespace('Refunds')->group(function() {
                Route::post('refunds/store', 'RefundController@store')->name('refunds.store');
            });

            /*
            |--------------------------------------------------------------------------
            | Session Routes
            |--------------------------------------------------------------------------
            */ 
           
            Route::namespace('Sessions')->group(function() {
               Route::post('session/store', 'VideoCallSessionController@store')->name('video-call-session.store');
               Route::post('session/show', 'VideoCallSessionController@receive')->name('video-call-session.show');
            });
            
            /*
            |--------------------------------------------------------------------------
            | Medical Prescriptions Routes
            |--------------------------------------------------------------------------
            */            
            Route::namespace('MedicalPrescriptions')->group(function() {
                Route::post('medical-prescriptions/fetch', 'MedicalPrescriptionController@fetch')->name('medical-prescriptions.fetch');
                Route::post('medical-prescriptions/download', 'MedicalPrescriptionController@download')->name('medical-prescriptions.download');

                /*
                |--------------------------------------------------------------------------
                | Phase 5 Routes
                |--------------------------------------------------------------------------
                */
                
                Route::post('medical-prescriptions/check-price', 'MedicalPrescriptionController@checkPrice')->name('medical-prescriptions.check-price');

            });

        /*
        |--------------------------------------------------------------------------
        | Phase 5 Routes
        |--------------------------------------------------------------------------
        */

            /*
            |--------------------------------------------------------------------------
            | Doctor Reviews Routes
            |--------------------------------------------------------------------------
            */            
            Route::namespace('DoctorReviews')->group(function() {
                Route::post('reviews/get-reviews', 'DoctorReviewController@getReviews')->name('doctor-reviews.get-reviews');
                Route::post('reviews/store-review', 'DoctorReviewController@storeReview')->name('doctor-reviews.store-review');
            });

            /*
            |--------------------------------------------------------------------------
            | Request Claim Referral Routes
            |--------------------------------------------------------------------------
            */            
            Route::namespace('RequestClaimReferrals')->group(function() {
                Route::post('request-claim-referrals/request', 'RequestClaimReferralController@request')->name('request-claim-referrals.request');
            });

            /*
            |--------------------------------------------------------------------------
            | My Vouchers Routes
            |--------------------------------------------------------------------------
            */ 
            Route::namespace('Vouchers')->group(function() {
                Route::post('my-vouchers/fetch', 'MyVoucherController@fetch')->name('my-vouchers.fetch');
                Route::post('my-vouchers/redeem', 'MyVoucherController@redeem')->name('my-vouchers.redeem');
            });


            /*
            |--------------------------------------------------------------------------
            | Notification Routes
            |--------------------------------------------------------------------------
            */       

            Route::namespace('Notifications')->group(function() {
                Route::post('notifications', 'NotificationController@notifications')->name('notifications');
                Route::post('notifications/read', 'NotificationController@readNotification')->name('notification.read');
            });


            Route::namespace('Users')->group(function() {
                Route::post('my-doctors/fetch', 'UserController@myDoctors')->name('my-doctors.fetch');
                Route::post('call/manage', 'UserController@manageCall')->name('call.manage-call');
            });
        });
        
    });


    /*
    |--------------------------------------------------------------------------
    | Doctor Routes
    |--------------------------------------------------------------------------
    */
    Route::name('doctor.')->prefix('doctor')->namespace('Doctor')->group( function() {
        
        Route::post('config/fetch', 'ConfigFetchController@fetch')->name('fetch.config');
        // Route::post('device-token/store', 'DeviceTokenController@store')->name('store.token');
        // Route::post('device-token/update', 'DeviceTokenController@update')->name('update.token');

        Route::namespace('Auth')->group(function() {
            Route::post('/login', 'LoginController@login')->name('login');
            Route::post('/logout', 'LoginController@logout')->name('logout');
            Route::post('/register', 'RegisterController@register')->name('register');
        });

        Route::group(['middleware' => ['assign.guard:doctor', 'jwt.auth']], function() {
            Route::get('/home', 'HomeController@resource')->name('home');
            Route::get('/home/notifications', 'HomeController@notifications')->name('notifications');
            Route::get('/notifications/count', 'HomeController@notificationsCount')->name('notifications.count');
            Route::post('/notification/read', 'HomeController@readNotification')->name('notification.read');

            Route::post('/update/profile', 'ProfileController@update')->name('update.profile');
            Route::post('/update/online-status', 'ProfileController@updateOnlineStatus')->name('update.online-status');
            Route::post('/update/password', 'ProfileController@password')->name('update.password');

            Route::post('/merchant/reward/redeem', 'RewardController@redeem')->name('merchant.redeem');

            Route::get('/linked/products', 'ProductController@index')->name('linked.products');
            Route::get('/linked/patients', 'PatientController@index')->name('linked.patients');
            
            Route::get('/articles', 'ArticleController@index')->name('articles');
            Route::post('/comments/fetch', 'ArticleController@show')->name('articles.comments.fetch');
            Route::post('/articles/download', 'ArticleController@download')->name('articles.download');
            Route::post('/articles/store', 'ArticleController@store')->name('articles.store');

            Route::post('/replies/store', 'ArticleController@addReplyToComment')->name('replies.store');

            Route::get('/faqs', 'FaqController@index')->name('faqs.fetch');
            
            Route::post('/patient/records', 'MyHealthController@fetch')->name('patient.records');




            /*
            |--------------------------------------------------------------------------
            | Phase 4 Routes
            |--------------------------------------------------------------------------
            */

                Route::namespace('OnlineConsultations')->group(function() {
                    Route::post('online-consultations/fetch', 'OnlineConsultationController@fetch')->name('online-consultations.fetch');
                });

                /*
                |--------------------------------------------------------------------------
                | Consultations
                |--------------------------------------------------------------------------
                */
                Route::namespace('Consultations')->group(function() {
                    Route::post('consultations/fetch/consultation/history', 'ConsultationController@fetchConsultationHistory')->name('consultations.fetch.consultation.history');
                    Route::post('consultations/fetch/chat/requests', 'ConsultationController@fetchChatRequests')->name('consultations.fetch.chat.requests');
                    Route::post('consultations/update/status', 'ConsultationController@updateStatus')->name('consultations.update.status');                    
                    Route::post('consultations/send/notification', 'ConsultationController@sendNotification')->name('consultations.send.notification');                    
                });

                /*
                |--------------------------------------------------------------------------
                | Payouts Routes
                |--------------------------------------------------------------------------
                */
                Route::namespace('Payouts')->group(function() {
                    Route::post('payouts', 'PayoutController@fetch')->name('payouts.fetch');
                    Route::post('payouts/request', 'PayoutController@request')->name('payouts.request');
                });

                /*
                |--------------------------------------------------------------------------
                | Schedule Routes
                |--------------------------------------------------------------------------
                */
                Route::namespace('Schedules')->group(function() {
                    Route::post('schedules', 'ScheduleFetchController@fetch')->name('schedules.fetch');
                    Route::post('schedules?nopagination=1', 'ScheduleFetchController@fetch')->name('schedules.fetch-nopagination');
                    Route::post('schedules/store', 'ScheduleController@store')->name('schedules.store');
                });

                
                /*
                |--------------------------------------------------------------------------
                | Consultation Chat Routes
                |--------------------------------------------------------------------------
                */
                Route::namespace('ConsultationChats')->group(function() {
                    Route::post('consultation-chats/fetch', 'ConsultationChatController@fetch')->name('consultation-chats.fetch');
                    Route::post('consultation-chats/store', 'ConsultationChatController@store')->name('consultation-chats.store');
                });

                /*
                |--------------------------------------------------------------------------
                | Session Routes
                |--------------------------------------------------------------------------
                */ 
                
                Route::namespace('Sessions')->group(function() {
                   Route::post('session/store', 'VideoCallSessionController@store')->name('video-call-session.store');
                   Route::post('session/show', 'VideoCallSessionController@receive')->name('video-call-session.show');
                });


                /*
                |--------------------------------------------------------------------------
                | Medical Prescription Routes
                |--------------------------------------------------------------------------
                */
               
                Route::namespace('MedicalPrescriptions')->group(function() {
                    Route::post('medical-prescriptions/fetch', 'MedicalPrescriptionController@fetch')->name('medical-prescriptions.fetch');
                    Route::post('medical-prescriptions/store', 'MedicalPrescriptionController@store')->name('medical-prescriptions.store');
                });

                /*
                |--------------------------------------------------------------------------
                | Upload signature Routes
                |--------------------------------------------------------------------------
                */                

                Route::post('/upload/signature', 'ProfileController@uploadSignature')->name('upload.signature');

                /*
                |--------------------------------------------------------------------------
                | Manage call routes
                |--------------------------------------------------------------------------
                */            
                Route::post('call/manage', 'ProfileController@manageCall')->name('call.manage-call');

        });

      
    });


    /*
    |--------------------------------------------------------------------------
    | Team Routes
    |--------------------------------------------------------------------------
    */
    Route::name('team.')->prefix('team')->namespace('Team')->group( function() {

        Route::post('config/fetch', 'ConfigFetchController@fetch')->name('fetch.config');

        Route::namespace('Auth')->group(function() {
            Route::post('/login', 'LoginController@login')->name('login');
        });

        Route::group(['middleware' => ['assign.guard:med_rep', 'jwt.auth']], function() {

            /**
             * Sync data
             */
            Route::post('sync/data', 'ConfigFetchController@syncData')->name('sync');

            /**
             * Config Resources
             */
            Route::post('config/fetch/storable/resources', 'ConfigFetchController@getResources')->name('fetch.config.storable');



            Route::namespace('Resources')->group(function() {
                Route::post('medrep/reports', 'HomeController@reports')->name('medrep.reports');
            });

            Route::namespace('MedRepLocationLogs')->group(function() {
                Route::post('medrep/location/store', 'MedRepLocationLogController@store')->name('medrep.location.store');
            });

            Route::namespace('Users')->group(function() {
                Route::post('/update/profile', 'ProfileController@update')->name('update.profile');
                Route::post('/update/image', 'ProfileController@imageUpdate')->name('update.image');
                Route::post('/update/password', 'ProfileController@password')->name('update.password');
            });

            Route::namespace('Calls')->group(function() {
                Route::group(['prefix' => 'call-plan'], function() {
                    Route::post('/calls/fetch', 'CallFetchController@fetch')->name('call-plan.fetch');
                    Route::post('store', 'CallController@store')->name('call-plan.store');
                    Route::post('{id}/update', 'CallController@update')->name('call-plan.update');
                    Route::post('{id}/delete', 'CallController@delete')->name('call-plan.delete');

                    // Mobile APP
                    Route::put('/calls/edit', 'CallController@edit')->name('call-plan.edit');
                    Route::post('/calls/remove', 'CallController@remove')->name('call-plan.remove');
                });
            });

            Route::namespace('Doctors')->group(function() {
                Route::group(['prefix' => 'doctors'], function() {
                    Route::post('store', 'DoctorController@store')->name('doctors.store');
                });
            });

        });
    });
            
});