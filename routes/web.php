<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

//Auth
Auth::routes();
Route::get('/logout', 'App\Http\Controllers\Auth\LogoutController@logout');

//Standard
Route::get('/', function () {
    if(Auth::check()) {
        return redirect('/' . Auth::user()->getUserCommand() . '-' . Auth::user()->getUserBranch() . '/dashboard');
    }
    return redirect('/login');
});
Route::get('/about_us/history', 'App\Http\Controllers\HomeController@history');
Route::get('/about_us/mission', 'App\Http\Controllers\HomeController@mission');
Route::get('/about_us/our_team', 'App\Http\Controllers\HomeController@ourTeam');
Route::get('/contact_us', 'App\Http\Controllers\HomeController@contact');
Route::get('/picture_gallery', 'App\Http\Controllers\HomeController@pictureGallery');
Route::get('/tools', 'App\Http\Controllers\HomeController@tools');

//POS

Route::middleware('branch')->group(function () {
    Route::get('/{branch_id}/pos', 'App\Http\Controllers\POSController@index');
    Route::post('/{branch_id}/pos/validate/{pin}/{option}', 'App\Http\Controllers\POSController@validateCashier');
    Route::get('/{branch_id}/pos/menu/{cashier_id}', 'App\Http\Controllers\POSController@menu');
    Route::get('/{branch_id}/pos/kitshop/{cashier_id}', 'App\Http\Controllers\POSController@kitshop');
    Route::get('/{branch_id}/pos/getInventory/{itemID}', 'App\Http\Controllers\POSController@getInventoryCount');
    Route::get('/{branch_id}/pos/inventory/{cashier_id}', 'App\Http\Controllers\POSController@inventoryMenu');
    Route::get('/{branch_id}/pos/inventory/count/{cashier_id}', 'App\Http\Controllers\POSController@fullInventoryCount');
    Route::post('/{branch_id}/pos/pay', 'App\Http\Controllers\POSController@save');
    Route::post('/{branch_id}/pos/invoice/edit', 'App\Http\Controllers\POSController@saveInvoice');
    Route::post('/{branch_id}/pos/inventory', 'App\Http\Controllers\POSController@sellInventory');

    //Dashboard
    Route::get('/{branch_id}/dashboard', 'App\Http\Controllers\DashboardController@index');
    Route::get('/{branch_id}/transactions', 'App\Http\Controllers\DashboardController@transactions');
    Route::get('/{branch_id}/transactions/{firstDay}/{secondDay}', 'App\Http\Controllers\DashboardController@getTransactions');
    Route::get('/{branch_id}/reports/{firstDay}/{secondDay}', 'App\Http\Controllers\DashboardController@getReports');
    Route::get('/{branch_id}/inventory', 'App\Http\Controllers\DashboardController@getInventory');
    Route::get('/{branch_id}/items', 'App\Http\Controllers\DashboardController@items');
    Route::get('/{branch_id}/members', 'App\Http\Controllers\DashboardController@memberList');
});
