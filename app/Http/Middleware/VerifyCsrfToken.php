<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{

    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        '/api/tasks/deal',
        'api/lead',
        '/api/invoice/download',
        '/api/recurring-invoice/cancel',
        '/api/quote/download',
        '/api/quote/bulk',
        '/api/order/download',
        '/api/order/bulk',
        '/api/payment/completePayment',
        '/api/refund/*',
        '/api/promocode/*',
        '/api/shipping/getRates',
        '/api/credit/download',
        '/api/tasks/form',
        '/api/tasks/*',
        '/api/product/*',
        '/api/categories/products/*'
    ];

}
