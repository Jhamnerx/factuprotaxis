<?php

$hostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);

if ($hostname) {
    Route::domain($hostname->fqdn)->group(function () {

        Route::middleware(['auth:api', 'locked.tenant'])->group(function () {

            // WhatsApp Cloud API Oficial
            Route::prefix('whatsapp-cloud')->group(function () {
                Route::post('send-message', 'Api\WhatsAppApiController@sendMessage');
            });

            // WhatsApp API No Oficial
            Route::prefix('whatsapp-unofficial')->group(function () {
                Route::post('send-message', 'Api\WhatsAppUnofficialApiController@sendMessage');
                Route::post('send-media', 'Api\WhatsAppUnofficialApiController@sendMedia');
            });

            // WhatsApp Service Unificado (Recomendado)
            Route::prefix('whatsapp')->group(function () {
                Route::post('send-message', 'Api\WhatsAppServiceController@sendMessage');
                Route::post('send-media', 'Api\WhatsAppServiceController@sendMedia');
                Route::post('send-message-with-api', 'Api\WhatsAppServiceController@sendMessageWithApi');
                Route::get('config-status', 'Api\WhatsAppServiceController@getConfigStatus');
            });
        });
    });
}
