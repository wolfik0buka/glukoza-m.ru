<?php declare(strict_types=1);
namespace App\Services\Sberbank;

use App\Http\Controllers\Controller;
use Voronkovich\SberbankAcquiring\Client;
use Voronkovich\SberbankAcquiring\Currency;
use Voronkovich\SberbankAcquiring\HttpClient\HttpClientInterface;
use Voronkovich\SberbankAcquiring\HttpClient\GuzzleAdapter;
use Voronkovich\SberbankAcquiring\OrderStatus;
use GuzzleHttp\Client as Guzzle;

/**
 * @author Iurii Sokolov <s@is.spb.ru>
 * @see Основная среда: https://securepayments.sberbank.ru/mportal3
 * @see Тестовая среда: https://3dsec.sberbank.ru/mportal3
 */
class Sberbank extends Controller
{
    static $failUrl;
    static $successUrl;

    static $testUser = '';
    static $testPassword = '';

    static $productionUser = 'glukoza-med1-api';
    static $productionPassword = 'qA+jJ8IN=2r!tra';


    protected static function instance()
    {
        self::$failUrl = env('WEB_URL', 'https://glukoza-med.ru').'/payment/failed';
        self::$successUrl = env('WEB_URL', 'https://glukoza-med.ru').'/payment/success';

        return new Client([

            'userName' => (env('SBERBANK_MODE') === 'test')
                ? self::$testUser
                : self::$productionUser,

            'password' => (env('SBERBANK_MODE') === 'test')
                ? self::$testPassword
                : self::$productionPassword,

            'language' => 'ru',

            'currency' => Currency::RUB,

            'apiUri' => (env('SBERBANK_MODE') === 'test')
                ? Client::API_URI_TEST
                : Client::API_URI,

            'httpMethod' => HttpClientInterface::METHOD_GET,

            'httpClient' => new GuzzleAdapter(new Guzzle())

        ]);
    }


    public static function getApiURL()
    {
        if (env('SBERBANK_MODE') === 'test') {
            return  'https://3dsec.sberbank.ru';
        }
        if (env('SBERBANK_MODE') === 'production') {
            return  'https://securepayments.sberbank.ru';
        }

        return  'https://3dsec.sberbank.ru';
    }


    public static function create ($orderId, int $orderAmount) : array
    {
        return self::instance()
            ->registerOrderPreAuth($orderId, $orderAmount, self::$successUrl, [
                'currency' => Currency::RUB,
                'failUrl' => self::$failUrl,
//                'orderBundle' => [
//                    'customerDetails' => [
//                        'email',
//                        'phone'
//                    ],
//                    'cartItems' => [
//                        'items' => [
//                            'positionId',
//                            'name',
//                            'quantity',
//                            'itemAmount',
//                            'itemCode',
//
//                        ]
//                    ]
//                ]
            ]);

        //header('Location: ' . $paymentFormUrl);
    }


    public static function reverse(string $orderId) : array
    {
        return self::instance()->reverseOrder($orderId);
    }


    public static function refund(string $orderId, int $amountToRefund) : array
    {
        return self::instance()
            ->refundOrder($orderId, $amountToRefund);
    }


    public static function status(string $orderId)
    {
        return self::instance()->getOrderStatus($orderId);
    }


    public static function isDeclined(string $orderId) : bool
    {
        $result = self::status($orderId);
        return OrderStatus::isDeclined($result['orderStatus']);
    }


    public static function isCreated(string $orderId) : bool
    {
        $result = self::status($orderId);
        return OrderStatus::isCreated($result['orderStatus']);
    }


    public static function isApproved(string $orderId): bool
    {
        $result = self::status($orderId);
        return OrderStatus::isApproved($result['orderStatus']);
    }


    public static function isDeposited(string $orderId): bool
    {
        $result = self::status($orderId);
        return OrderStatus::isDeposited($result['orderStatus']);
    }


    public static function isReversed(string $orderId): bool
    {
        $result = self::status($orderId);
        return OrderStatus::isReversed($result['orderStatus']);
    }


    public static function isRefunded(string $orderId): bool
    {
        $result = self::status($orderId);
        return OrderStatus::isRefunded($result['orderStatus']);
    }


    public static function isAuthorizationInitialized(string $orderId): bool
    {
        $result = self::status($orderId);
        return OrderStatus::isAuthorizationInitialized($result['orderStatus']);
    }


}