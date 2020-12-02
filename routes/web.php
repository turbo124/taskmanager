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


Route::get('setup', 'SetupController@welcome')->name('setup.welcome');
Route::get('setup/requirements', 'SetupController@requirements')->name('setup.requirements');
Route::get('setup/permissions', 'SetupController@permissions')->name('setup.permissions');
Route::get('setup/environment', 'SetupController@environmentMenu')->name('setup.environment');
Route::get('setup/database', 'SetupController@database')->name('setup.database');
Route::get('setup/user', 'SetupController@user')->name('setup.user');
Route::get('setup/final', 'SetupController@finish')->name('setup.final');
Route::get('setup/environmentWizard', 'SetupController@environmentWizard')->name('setup.environment-wizard');
Route::post('setup/user/save', 'SetupController@saveUser')->name('setup.saveUser');
Route::post('setup/environment/save', 'SetupController@saveWizard')->name('setup.environment-save-wizard');
Route::get('setup/environmentClassic', 'SetupController@environmentClassic')->name('setup.environment-classic');
Route::get('dashboard', 'DashboardController@index');
Route::get('pay_now/{invoice_id}', 'PaymentController@buyNow');
Route::view('/{path?}', 'app');