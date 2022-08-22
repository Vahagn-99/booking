<?php

use App\Http\Controllers\PagesController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

Route::get('/clear', function() {
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
});

Route::get('/migrate', function() {
    Artisan::call('migrate');
    Artisan::call('db:seed');
});

// Route::get('/organize', [App\Http\Controllers\DBUpdateController::class, 'organize'])->name('organize');

Auth::routes();

Route::any('/webhook-zohobook', [App\Http\Controllers\HomeController::class, 'webhookZohobookApi'])->name('webhook-zohobook');

if(!env('APP_LOCAL') || env('APP_LOCAL') != 'production'){
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/cancellation-policy', [App\Http\Controllers\HomeController::class, 'cancellationPolicy'])->name('cancellation-policy');
    Route::get('/page/{slug}', [App\Http\Controllers\HomeController::class, 'viewPage'])->name('page-view');
    Route::get('/contact', [App\Http\Controllers\HomeController::class, 'contact'])->name('contact');
    Route::any('/locations', [App\Http\Controllers\HomeController::class, 'locations'])->name('locations');
    Route::get('/property/{id}', [App\Http\Controllers\HomeController::class, 'property'])->name('property');
    Route::any('/payment', [App\Http\Controllers\BookingController::class, 'payment'])->name('payment');
    Route::get('/payment-success', [App\Http\Controllers\BookingController::class, 'paymentSuccess'])->name('payment-success');
    Route::post('/save-reservation', [App\Http\Controllers\BookingController::class, 'saveReservation'])->name('save-reservation');
}else{
    Route::group(['domain' => env('APP_MAINURL','bookingfwi.com')], function() {
        Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
        Route::get('/cancellation-policy', [App\Http\Controllers\HomeController::class, 'cancellationPolicy'])->name('cancellation-policy');
        Route::get('/page/{slug}', [App\Http\Controllers\HomeController::class, 'viewPage'])->name('page-view');
        Route::get('/contact', [App\Http\Controllers\HomeController::class, 'contact'])->name('contact');
        Route::any('/locations', [App\Http\Controllers\HomeController::class, 'locations'])->name('locations');
        Route::get('/property/{id}', [App\Http\Controllers\HomeController::class, 'property'])->name('property');
        Route::any('/payment', [App\Http\Controllers\BookingController::class, 'payment'])->name('payment');
        Route::get('/payment-success', [App\Http\Controllers\BookingController::class, 'paymentSuccess'])->name('payment-success');
        Route::post('/save-reservation', [App\Http\Controllers\BookingController::class, 'saveReservation'])->name('save-reservation');
    });
}

Route::group(['middleware' => [\App\Http\Middleware\CheckOwner::class]],function(){
    //Route::get('/admin/report', [App\Http\Controllers\ReportsController::class, 'index'])->name('report');
});

Route::group(['domain' => '{subdomain}.'.env('APP_MAINURL','bookingfwi.com')], function() {
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('subdomain');
    Route::get('/cancellation-policy', [App\Http\Controllers\HomeController::class, 'cancellationPolicy'])->name('agency-cancellation-policy');
    Route::get('/agency-page/{slug}', [App\Http\Controllers\HomeController::class, 'viewPageAgency'])->name('agency-page-view');
    Route::get('/contact', [App\Http\Controllers\HomeController::class, 'contact'])->name('agency-contact');
    Route::any('/locations', [App\Http\Controllers\HomeController::class, 'locations'])->name('agency-locations');
    Route::get('/property/{id}', [App\Http\Controllers\HomeController::class, 'property'])->name('agency-property');
    Route::any('/payment', [App\Http\Controllers\BookingController::class, 'payment'])->name('agency-payment');
    Route::get('/payment-success', [App\Http\Controllers\BookingController::class, 'paymentSuccess'])->name('agency-payment-success');
    Route::post('/save-reservation', [App\Http\Controllers\BookingController::class, 'saveReservation'])->name('agency-save-reservation');
});

Route::post('/change-currency', [App\Http\Controllers\HomeController::class, 'changeCurrency'])->name('change-currency');
Route::get('/change', [App\Http\Controllers\HomeController::class, 'change'])->name('changeLang');
Route::post('/send-contact', [App\Http\Controllers\HomeController::class, 'sendContact'])->name('send-contact');

Route::get('/calendar/{id}', [App\Http\Controllers\CalFileController::class, 'export'])->name('export-calendar');
Route::post('/calendar/{id}', [App\Http\Controllers\CalFileController::class, 'import'])->name('import-calendar');

Route::post('/admin/calendar-info-full', [App\Http\Controllers\Admin\CalendarController::class, 'calendarInfoFull'])->name('admin/calendar-info-full');
Route::post('/admin/calendar-info', [App\Http\Controllers\Admin\CalendarController::class, 'calendarInfo'])->name('admin/calendar-info');
Route::post('admin/calendar/open-modal', [App\Http\Controllers\Admin\CalendarController::class, 'openModal'])->name('calendar/open-modal');
Route::post('admin/calendar/remove-block', [App\Http\Controllers\Admin\CalendarController::class, 'removeBlock'])->name('calendar/remove-block');
Route::post('/admin/calc-total', [App\Http\Controllers\Admin\CalendarController::class, 'calcTotal'])->name('admin/calc-total');
Route::post('/admin/get-checkout', [App\Http\Controllers\Admin\CalendarController::class, 'getCheckout'])->name('admin/get-checkout');

Route::get('/admin/report', [App\Http\Controllers\ReportsController::class, 'index'])->name('report');
Route::get('/admin/rentalrevenue', [App\Http\Controllers\ReportsController::class, 'rentalrevenue'])->name('rentalrevenue');
Route::get('/admin/firstcall', [App\Http\Controllers\ReportsController::class, 'firstcall'])->name('firstcall');
Route::get('/admin/ajaxcall', [App\Http\Controllers\ReportsController::class, 'ajaxcall'])->name('ajaxcall');
Route::get('/admin/nightbooked', [App\Http\Controllers\ReportsController::class, 'nightbooked'])->name('nightbooked');
Route::get('/admin/reservationperchannel', [App\Http\Controllers\ReportsController::class, 'reservationperchannel'])->name('reservationperchannel');


Route::group(['middleware' => ['admin']], function () {

    Route::get('/admin/integration', [App\Http\Controllers\Admin\IntegrationController::class, 'index'])->name('admin/integration');
    Route::get('/admin/integration/settings/{id?}', [App\Http\Controllers\Admin\IntegrationController::class, 'settings'])->name('admin/group-settings');
    Route::post('admin/integration/save', [App\Http\Controllers\Admin\IntegrationController::class, 'save'])->name('save-group');
    Route::post('admin/integration/delete', [App\Http\Controllers\Admin\IntegrationController::class, 'delete'])->name('delete-group');

    Route::get('/admin/notifications', [App\Http\Controllers\Admin\UsersController::class, 'notifications'])->name('admin/notifications');

    Route::get('/admin/users', [App\Http\Controllers\Admin\UsersController::class, 'index'])->name('users');
    Route::get('admin/users/edit/{id}', [App\Http\Controllers\Admin\UsersController::class, 'edit'])->name('edit-user');
    Route::post('admin/users/save', [App\Http\Controllers\Admin\UsersController::class, 'save'])->name('save-user');
    Route::post('admin/users/delete/', [App\Http\Controllers\Admin\UsersController::class, 'delete'])->name('delete-user');
});

Route::group(['middleware' => ['auth']], function () {
    Route::get('/admin/settings', [App\Http\Controllers\Admin\DashboardController::class, 'profileSettings'])->name('admin/settings');
    Route::get('/admin/password', [App\Http\Controllers\Admin\DashboardController::class, 'changePassword'])->name('admin/password');
    Route::post('/admin/save-profile', [App\Http\Controllers\Admin\DashboardController::class, 'saveProfile'])->name('save-profile');
    Route::post('/admin/save-password', [App\Http\Controllers\Admin\DashboardController::class, 'savePassword'])->name('save-password');

    Route::get('/admin', [App\Http\Controllers\Admin\PropertiesController::class, 'index'])->name('admin');
    Route::get('/admin/properties/settings/{id?}', [App\Http\Controllers\Admin\PropertiesController::class, 'settings'])->name('admin/property-settings');
    Route::post('admin/properties/save', [App\Http\Controllers\Admin\PropertiesController::class, 'save'])->name('save-property');
    Route::post('admin/properties/save-commission', [App\Http\Controllers\Admin\PropertiesController::class, 'saveCommission'])->name('save-commission');
    Route::post('admin/properties/save-agencyproperty', [App\Http\Controllers\Admin\PropertiesController::class, 'saveAgencyproperty'])->name('save-agencyproperty');
    Route::get('/admin/properties/list', [App\Http\Controllers\Admin\PropertiesController::class, 'list'])->name('admin/properties-list');
    Route::post('admin/properties/show', [App\Http\Controllers\Admin\PropertiesController::class, 'show'])->name('show-property');
    Route::get('/admin/reservations/{id?}', [App\Http\Controllers\Admin\PropertiesController::class, 'reservations'])->name('admin/property-reservations');
    Route::post('admin/properties/delete', [App\Http\Controllers\Admin\PropertiesController::class, 'delete'])->name('delete-property');
    Route::post('admin/properties/reservations/delete', [App\Http\Controllers\Admin\PropertiesController::class, 'deleteReservation'])->name('delete-reservation');

    Route::post('admin/properties/open-modal', [App\Http\Controllers\Admin\PropertiesController::class, 'openModal'])->name('open-modal');
    Route::post('admin/properties/save-item', [App\Http\Controllers\Admin\PropertiesController::class, 'saveItem'])->name('save-item');
    Route::post('admin/properties/change-order', [App\Http\Controllers\Admin\PropertiesController::class, 'changeOrder'])->name('change-order');
    Route::post('admin/properties/delete-item', [App\Http\Controllers\Admin\PropertiesController::class, 'deleteItem'])->name('delete-item');
    Route::post('admin/properties/delete-agency-prop', [App\Http\Controllers\Admin\PropertiesController::class, 'deleteAgencyProp'])->name('delete-agency-prop');
    Route::post('admin/properties/suggest-agency', [App\Http\Controllers\Admin\PropertiesController::class, 'suggestAgency'])->name('suggest-agency');

    Route::get('/admin/calendar/{id?}', [App\Http\Controllers\Admin\CalendarController::class, 'index'])->name('admin/calendar');
    Route::post('/admin/save-reservation', [App\Http\Controllers\Admin\CalendarController::class, 'saveReservation'])->name('admin/save-reservation');

    Route::get('/admin/customize', [App\Http\Controllers\Admin\CustomizeController::class, 'index'])->name('admin/customize');
    Route::post('/admin/save-customizes', [App\Http\Controllers\Admin\CustomizeController::class, 'saveBatch'])->name('save-customizes');
    Route::post('/admin/save-slides', [App\Http\Controllers\Admin\CustomizeController::class, 'saveSlides'])->name('save-slides');
    Route::post('/admin/delete-slide', [App\Http\Controllers\Admin\CustomizeController::class, 'deleteSlides'])->name('delete-slide');
    Route::post('admin/customize/change-order', [App\Http\Controllers\Admin\CustomizeController::class, 'changeOrder'])->name('change-ord');
    Route::post('/admin/save-cancellation-policy', [App\Http\Controllers\Admin\CustomizeController::class, 'saveCancellationPolicy'])->name('save-cancellation-policy');
    Route::post('/admin/save-customize', [App\Http\Controllers\Admin\CustomizeController::class, 'save'])->name('save-customize');
    Route::post('/admin/save-subdomain', [App\Http\Controllers\Admin\CustomizeController::class, 'saveSubdomain'])->name('save-subdomain');
    Route::post('/admin/delete-customize', [App\Http\Controllers\Admin\CustomizeController::class, 'delete'])->name('delete-customize');
    Route::get('/admin/pages', [App\Http\Controllers\Admin\PageController::class, 'index'])->name('admin/pages');
    Route::get('/admin/pages/create', [App\Http\Controllers\Admin\PageController::class, 'create'])->name('admin/page-create');
    Route::get('/admin/pages/settings/{id?}', [App\Http\Controllers\Admin\PageController::class, 'edit'])->name('admin/page-settings');
    Route::post('admin/pages/save', [App\Http\Controllers\Admin\PageController::class, 'save'])->name('save-page');
    Route::post('admin/pages/delete', [App\Http\Controllers\Admin\PageController::class, 'delete'])->name('delete-page');
});
