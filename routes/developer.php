<?php
use App\Helpers\EnvHelpers;

if (EnvHelpers::isDev()) {
	
	Route::namespace('Developers')->prefix('developer')->name('developer.')->middleware('developer')->group(function() {

		Route::post('users/change-account', 'DeveloperController@changeAccount')->name('users.change-account');
		Route::post('users/fetch', 'DeveloperController@fetchUsers')->name('users.fetch');

	});

}