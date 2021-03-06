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

Route::get('/', function () {
    return view('welcome');
});


Route::group(['prefix' => 'shopify'] , function(){

	Route::get('access' , 'ShopifyController@access')->name('shopify.access');
	Route::get('callback' , 'ShopifyController@callback')->name('shopify.callback');
	Route::post('webhook/app_uninstall' , 'WebhookController@app_uninstall');
	Route::post('webhook/order_create' , 'WebhookController@order_create');

});

Route::group(['prefix' => 'billing'] , function(){

    Route::get('/' , 'BillingController@index')->name('billing.index');
    Route::get('charge' , 'BillingController@charge')->name('billing.charge');
    Route::get('callback' , 'BillingController@callback')->name('billing.callback');
    Route::get('declined' , 'BillingController@declined')->name('billing.declined');

});
Route::post('save_data' , 'ShopifyController@save_data');
Route::post('save_license_key' , 'ShopifyController@save_license_key');
Route::post('badges_available' , 'ShopifyController@badges_available');
Route::get('reload' , 'ShopifyController@reload_theme');
Route::get('test_function_for_order' , 'ShopifyController@test_function_for_order');
Route::get('mail/send', 'MailController@send');
Route::get('mail/test_send', 'ShopifyController@test_send');
Route::get('count', 'ShopifyController@count_resold_license_keys');
Route::get('delete_license', 'ShopifyController@delete_license');
Route::post('send_license_email', 'ShopifyController@send_license_email');
