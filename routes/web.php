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




Route::namespace('Web')->name('web.')->group(function() {
	

	Route::namespace('SocialLogins')->name('social.')->group(function() {
		// Route::get('auth/facebook/login', 'FacebookLoginController@login')->name('facebook.login');
		// Route::get('auth/facebook/callback', 'FacebookLoginController@facebookCallback')->name('facebook.callback');

		// Route::post('auth/google/login', 'GoogleLoginController@login')->name('google.login');
	});

    Route::get('account/{email}/verified','InitController@message')->name('account.verified');

	Route::namespace('Auth')->group(function() {

		Route::middleware('guest:web')->group(function() {

	        // Route::get('login', 'LoginController@showLoginForm')->name('login');
	        // Route::post('login', 'LoginController@login');

	        Route::get('reset-password/{token}/{email}', 'ResetPasswordController@showResetForm')->name('password.reset');
	        Route::post('reset-password/change', 'ResetPasswordController@reset')->name('password.change');

	        // Route::get('forgot-password', 'ForgotPasswordController@showLinkRequestForm')->name('password.request');
	        // Route::post('forgot-password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('password.email');

	        Route::get('register', 'RegisterController@showRegistrationForm')->name('register');
	        // Route::post('register', 'RegisterController@register');
		});

		Route::middleware(['guest:doctor', 'cors'])->group(function() {
			Route::post('forgot-password/doctor/email', 'Doctor\ForgotPasswordController@sendResetLinkEmail')->name('doctor.password.email');

			Route::get('reset-password/doctor/{token}/{email}', 'Doctor\ResetPasswordController@showResetForm')->name('doctor.password.reset');
	        Route::post('reset-password/doctor/change', 'Doctor\ResetPasswordController@reset')->name('doctor.password.change');
		});

		// Route::middleware(['guest:med_rep'])->group(function() {
		// 	Route::post('forgot-password/med_rep/email', 'Team\ForgotPasswordController@sendResetLinkEmail')->name('med_rep.password.email');

		// 	Route::get('reset-password/med_rep/{token}/{email}', 'Team\ResetPasswordController@showResetForm')->name('med_rep.password.reset');
	 //        Route::post('reset-password/med_rep/change', 'Team\ResetPasswordController@reset')->name('med_rep.password.change');
		// });

	});
	

	Route::namespace('Pages')->group(function() {

		Route::get('', 'PageController@showHome')->name('home');
		Route::get('/privacy-policy', 'PageController@privacy')->name('home');
		Route::get('/reset/password/success', 'PageController@doctor')->name('doctor.reset.password.success');

	});

	Route::prefix('dashboard')->middleware('guest:web')->group(function() {

		Route::namespace('Auth')->group(function() {

			Route::get('logout', 'LoginController@logout')->name('logout');

	        Route::get('email/reset', 'VerificationController@resend')->name('verification.resend');
	        Route::get('email/verify', 'VerificationController@show')->name('verification.notice');
	        Route::get('email/verify/{id}/{type}', 'VerificationController@verify')->name('verification.verify');

		});

		Route::middleware('verified')->group(function() {

			Route::get('', 'DashboardController@index')->name('dashboard');

			/**
	         * @Count Fetch Controller
	         */
	        Route::post('count/notifications', 'CountFetchController@fetchNotificationCount')->name('counts.fetch.notifications');

			Route::namespace('Samples')->group(function() {

				Route::get('sample-items', 'SampleItemController@index')->name('sample-items.index');
				Route::get('sample-items/create', 'SampleItemController@create')->name('sample-items.create');
				Route::post('sample-items/store', 'SampleItemController@store')->name('sample-items.store');
				Route::get('sample-items/show/{id}', 'SampleItemController@show')->name('sample-items.show');
				Route::post('sample-items/update/{id}', 'SampleItemController@update')->name('sample-items.update');
				Route::post('sample-items/{id}/archive', 'SampleItemController@archive')->name('sample-items.archive');
			    Route::post('sample-items/{id}/restore', 'SampleItemController@restore')->name('sample-items.restore');
			    Route::post('sample-items/{id}/remove-image', 'SampleItemController@removeImage')->name('sample-items.remove-image');

			    Route::post('sample-items/{id}/approve', 'SampleItemController@approve')->name('sample-items.approve');
			    Route::post('sample-items/{id}/deny', 'SampleItemController@deny')->name('sample-items.deny');

				Route::post('sample-items/fetch', 'SampleItemFetchController@fetch')->name('sample-items.fetch');
				Route::post('sample-items/fetch?archived=1', 'SampleItemFetchController@fetch')->name('sample-items.fetch-archive');
				Route::post('sample-items/fetch-item/{id?}', 'SampleItemFetchController@fetchView')->name('sample-items.fetch-item');
				Route::post('sample-items/fetch-pagination/{id}', 'SampleItemFetchController@fetchPagePagination')->name('sample-items.fetch-pagination');

			});

			Route::namespace('Profiles')->group(function() {

	            /**
	             * @Profiles
	             */
	            Route::get('profile', 'ProfileController@show')->name('profiles.show');
	            Route::post('profile/update', 'ProfileController@update')->name('profiles.update');
	            Route::post('profile/change-password', 'ProfileController@changePassword')->name('profiles.change-password');

	            Route::post('profile/fetch', 'ProfileController@fetch')->name('profiles.fetch');

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
	            
	            Route::post('activity-logs/fetch', 'ActivityLogFetchController@fetch')->name('activity-logs.fetch');
	            Route::post('activity-logs/fetch?id={id}&sample=1', 'ActivityLogFetchController@fetch')->name('activity-logs.fetch.sample-items');
	            Route::post('activity-logs/fetch?profile=1', 'ActivityLogFetchController@fetch')->name('activity-logs.fetch.profiles');

	        });

		});

	});

});


