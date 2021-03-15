<?php
use App\Services\Reports\ReportOrdersFromNewAndOldCustomers;

// EXTERNAL LINKS
Route::get('/external', function (\Illuminate\Http\Request $request) {
    return redirect()->away($request->get('url'));
});


/**
 *  Корзина
 */
Route::group(['namespace' => 'Pub'], function () {

    Route::group(['namespace' => 'Payment', 'prefix' => 'payment'], function () {
        Route::get('/', 'Payment@index');
        Route::get('/failed', 'PaymentFailed@index');
        Route::get('/success', 'PaymentSuccess@index');
        Route::get('/{hash}', 'Payment@index');
        Route::post('/{order_id}', 'Payment@redirectToSberbank');
    });

    Route::get('/track-orders/{order_id?}', 'OrderTrackingController@index');
    Route::post('/track-orders', 'OrderTrackingController@search');
    Route::get('/card-payment', 'StaticPages@pageCardPayment');
    Route::get('/requisites', 'StaticPages@pageRequisites');
    Route::get('/confirm', 'StaticPages@pageConfirm');
    Route::get('/oferta', 'StaticPages@pageOferta');
    Route::get('/beznal', 'StaticPages@pageBeznal');
    Route::get('/dostavka', 'StaticPages@pageDostavka');
    Route::get('/kontakty', 'StaticPages@pageKontakty');
    Route::get('/bonusnaya-programma', 'StaticPages@pageBonusnayaProgramma');
    Route::get('/vozvrat', 'StaticPages@pageVozvrat');
    Route::get('/politika-konfidencialnosti', 'StaticPages@pagePolitikaKonfidencialnosti');
    Route::get('/oplata', 'StaticPages@pageOplata');
    Route::get('/kak-sdelat-zakaz', 'StaticPages@pageOrderInstruction');
    Route::get('/soglasie-na-obrabotku-pd', 'StaticPages@pageConfirm');
    
    Route::get('/otzyvy', 'Responses@getAll');
    
    Route::group(['prefix' => 'responses'], function () {
        Route::post('/add', 'Responses@add');
    });
    
    
    Route::get('/obratnaya-svyaz', 'Feedback@index');
    Route::post('/feedback-handler', 'Feedback@handler');
    
    Route::get('/category/{slug}', 'Cats@singleSlug');
    Route::get('/product/{symbol_code}', 'Products@singleSymbol');

    Route::group(['namespace' => 'Checkout', 'prefix' => 'checkout'], function () {
        Route::get('/', 'Checkout@index');
        Route::post('/update', 'Checkout@updateOrder');
        Route::post('/done', 'Checkout@doneOrder');
        Route::post('/fast', 'FastCheckout@index');
        Route::post('/preorder', 'PreOrderController@index');
    });

    Route::group(['prefix' => 'webhooks'], function () {
        Route::get('/order-added/{order_id}', function($order_id) {
            $order = App\Models\Order::with('productLinks.product')->where('id', $order_id)->first();
            event(new App\Events\OnAddOrderEvent($order));
        });
    });

    Route::group(['prefix' => 'services'], function () {
        Route::get('/delivery/{order_id}/{delivery_id}/{order_products_sum}/{city_id}', 'DeliveryController@index');
        Route::get('/search_city/{query}', function ($query) {
            return response()->json(\App\Services\Cdek\City::search($query), 200);
        });
        Route::get('/delivery_price/{city_id}', function ($city_id) {
            return \App\Services\Cdek\CalcDeliveryPrice::getPriceForCity($city_id);
        });
        Route::get('/city/points/{city_id}', function ($city_id) {
            return response()->json(\App\Services\Cdek\CityPickupPoints::getByCityId($city_id), 200);
        });
        Route::get('/city/points/price/{city_id}', function ($city_id) {
            return \App\Services\Cdek\CalcPvzPrice::getPriceForCity($city_id);
        });
    });

    /**
     *  Кабинет
     */
    Route::group(['prefix' => 'cabinet'], function () {
        Route::get('/reg_form', ['as' => 'reg_form', 'uses' => 'Cabinet@reg_form']);
        Route::post('/reg', ['as' => 'reg', 'uses' => 'Cabinet@reg']);
        Route::get('/auth_form', ['as' => 'auth_form', 'uses' => 'Cabinet@auth_form']);
        Route::post('/auth', ['as' => 'auth', 'uses' => 'Cabinet@auth']);
        Route::get('/logout', ['as' => 'logout', 'uses' => 'Cabinet@logout']);
        Route::post('/bonus_use', ['as' => 'bonus_use', 'uses' => 'Cabinet@bonus_use']);
        Route::get('/restore_password', 'RestorePassword@index');
        Route::post('/restore_password/reset_password', 'RestorePassword@resetPassword');
        Route::group(['prefix' => 'profile', 'middleware' => 'is_authorized'], function () {
            Route::get('/', 'Cabinet@profile');
            Route::post('/update', 'Cabinet@updateProfile');
            Route::post('/change_password', 'Cabinet@changePassword');
            Route::get('/history', 'Cabinet\CabinetOrdersHistory@index');
        });
    });

    Route::group(['prefix' => 'search'], function () {
        Route::post('/', 'Search@index');
        Route::get('/{query}', 'Search@page');
    });

    Route::get('/sitemap.xml', 'Sitemap@index');

});


Route::group(['namespace' => 'Admin'], function () {
    Route::post('/add_post_id', ['as' => 'add_post_id', 'uses' => 'Sender@add_post_id']);

    Route::group(['prefix' => 'admin'], function () {
        Route::group(['prefix' => 'orders'], function () {
            Route::post('/all', 'Orders@index');
            Route::post('/update', 'Orders@update');
            Route::post('/add', 'Orders@add');
            Route::post('/set_point', 'Orders@setPoint');
            Route::post('/sms/order_approved', 'Orders@sendSmsApproved');
            Route::post('/sms/order_changed', 'Orders@sendSmsOrderChanged');
            Route::post('/sms/cdek_tracking_code', 'Orders@sendSmsCdekTrackingCode');
            Route::post('/sms/payment_done', 'Orders@sendSmsPaymentDone');
            Route::post('/sms/order_transferred_to_delivery', 'Orders@sendSmsOrderTransferredToDelivery');
            Route::post('/sms/order_ready_to_pickup', 'Orders@sendSmsOrderReadyToPickup');
            Route::post('/{id}', 'Orders@getSingle');
            Route::post('/{id}/clone', 'Orders@clone');
            Route::get('/{id}/pdf_receipt', 'Orders\PdfSaleReceipt@index');
            Route::get('/{id}/pdf_invoice', 'Orders\PdfInvoice@index');
            Route::post('/{id}/send_invoice', 'Orders\SendInvoice@index');
            Route::post('/{id}/send_payment_link', 'Orders\SendPaymentLink@index');
            Route::post('/sberbank/status', 'Orders\GetSberbankOrderStatus@index');
            Route::group(['prefix' => 'products'], function () {
                Route::get('/remove/{id}', 'OrderProducts@remove');
                Route::post('/update', 'OrderProducts@update');
                Route::post('/add', 'OrderProducts@add');
            });
        });
        Route::group(['prefix' => 'responses'], function () {
            Route::get('/all', 'Responses@getAll');
            Route::post('/update', 'Responses@update');
            Route::post('/{id}', 'Responses@getSingle');
        });
        Route::group(['prefix' => 'order-cancellation-reasons'], function () {
            Route::post('/', 'OrderCancelReasons@index');
        });
        Route::group(['prefix' => 'deliveries'], function () {
            Route::post('/', 'Deliveries@getAll');
        });
        Route::group(['prefix' => 'cdek'], function () {
            Route::get('/pdf/{order_id}/', 'Orders\PdfCdek@index');
            Route::post('/create-order/{order_id}', function ($order_id) {
                return App\Services\CdekOrdersManagement\CdekOrder::init()
                    ->setGlukozaOrderId($order_id)
                    ->create();
            });
        });
        Route::group(['prefix' => 'collections'], function () {
            Route::post('/', 'Collections@getAll');
            Route::post('/search', 'Collections@searchProducts');
            Route::post('/add', 'Collections@addProductToCollection');
            Route::post('/remove', 'Collections@removeProductFromCollection');
        });
        Route::group(['prefix' => 'cats'], function () {
            Route::post('/', 'Cats@all');
        });
        Route::group(['prefix' => 'products'], function () {
            Route::post('/all', 'Products@getAll');
            Route::post('/all_l/{skip}/{take}', 'Products@getAll_limit');
            Route::post('/update', 'Products@update');
            Route::post('/add', 'Products@add');
            Route::post('/update_cat_links', 'Products@updateCatLinks');
            Route::post('/search', 'Products@searchByName');
            Route::post('/imageupload', 'Products@uploadPicDescription');
            Route::post('/{id}', 'Products@getSingle');
            Route::get('/{id}/clone', 'Products@clone');
            Route::post('/{id}/upload', 'Products@uploadPic');
            
            Route::group(['prefix' => 'related'], function () {
                Route::post('/add', 'ProductRelateds@add');
                Route::post('/remove', 'ProductRelateds@remove');
            });
        });
        Route::group(['prefix' => 'reports'], function () {
            Route::post('/old-and-new-customers', function() {
                return ReportOrdersFromNewAndOldCustomers::render();
            });
        });
    });

});


Route::get('/', 'RoutesWithIndex@index');
Route::post('/', function () {
    return response()->view('errors.404')->setStatusCode(404);
});

Route::controllers([
    'auth' => 'Auth\AuthController',
    'password' => 'Auth\PasswordController',
]);
