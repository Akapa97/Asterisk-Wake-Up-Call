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

Route::get('/', 'Auth\LoginController@showLoginForm');

Auth::routes(['register' => false, 'reset' => false]);

Route::get('cdrs', 'AsteriskController@index')->name('cdrs');

Route::get('wake-up-call', 'AsteriskController@wakeUpCall')->name('wake_up_call');


Route::post('wake-up-call', 'AsteriskController@wakeUpCallStore')->name('wake_up_call.store');

Route::group(['prefix' => 'ajax', 'as'=> 'ajax.'], function() {
    Route::get('cdrs', 'AsteriskController@ajaxIndex')->name('cdrs.index');

    Route::get('wake-up-call', 'AsteriskController@ajaxWakeUpCall')->name('wake_up_call.index');

    Route::delete('wake-up-call/{id}', 'AsteriskController@WakeupCalldestroy')->name('wake_up_call.destroy');
});
