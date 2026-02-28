<?php

use Illuminate\Support\Facades\Route;
use Modules\PaymentMethodSetting\Http\Controllers\PaymentMethodSettingController;

Route::prefix('paymentmethodsetting')->middleware('auth')->group(function () {
    Route::get('payment-method-setting', 'PaymentMethodSettingController@index')->name('paymentmethodsetting.payment_method_setting')->middleware('RoutePermissionCheck:paymentmethodsetting.payment_method_setting');
    Route::get('payment-method-setting-test', 'PaymentMethodSettingController@test')->name('paymentmethodsetting.test')->middleware('RoutePermissionCheck:paymentmethodsetting.payment_method_setting');
    Route::post('payment-method-setting-test', 'PaymentMethodSettingController@testSubmit');
    Route::post('payment-method-setting', 'PaymentMethodSettingController@update')->name('paymentmethodsetting.update_payment_gateway')->middleware('RoutePermissionCheck:paymentmethodsetting.payment_method_setting_update');
    Route::post('changePaymentGatewayStatus', 'PaymentMethodSettingController@changePaymentGatewayStatus')->name('paymentmethodsetting.changePaymentGatewayStatus');


    Route::get('paypalTestSuccess', 'PaymentMethodSettingController@paypalTestSuccess')->name('paypalTestSuccess');
    Route::get('paypalTestFailed', 'PaymentMethodSettingController@paypalTestFailed')->name('paypalTestFailed');

    Route::get('stripe/success', [PaymentMethodSettingController::class, 'stripeSuccess'])->name('stripe.test.success');
    Route::get('stripe/cancel', [PaymentMethodSettingController::class, 'stripeCancel'])->name('stripe.test.cancel');
});


Route::get('recurring', 'PaymentMethodSettingController@recurring');
