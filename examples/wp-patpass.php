<?php
declare(strict_types=1);

use BetterTransbank\SDK\Config;
use BetterTransbank\SDK\Html\PaymentForm;
use BetterTransbank\SDK\Html\RedirectView;
use BetterTransbank\SDK\Services\WebpayPlus\Customer;
use BetterTransbank\SDK\Services\WebpayPlus\Subscription;
use BetterTransbank\SDK\Services\WebpayPlus\Transaction;
use BetterTransbank\SDK\TestingCredentials;
use BetterTransbank\SDK\Transbank;

require_once __DIR__ . '/../vendor/autoload.php';

// This example shows how you can use the sdk in a CGI single-script context.
// You can run this example with PHP built-in web server:
// php -S 0.0.0.0:8000 -t examples/ examples/wp-patpass.php

$config = Config::fromCredentials(TestingCredentials::forWebpayPlusSubscription());
$transbank = Transbank::create($config);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {


    $customer = Customer::register(
        '11.111.111-2',
        'Juanito',
        'Pérez',
        'González',
        'jpergon@example.com',
        '123456789'
    );

    $subscription = Subscription::define(
        '12345',
        10000,
        '124214125',
        new DateTime('+1 year')
    );

    $transaction = Transaction::subscription(
        $subscription,
        $customer,
        'commerce@example.com',
        'http://localhost:8000/return',
        'http://localhost:8000/final'
    );

    $result = $transbank->webpayPlus()->register($transaction);
    PaymentForm::prepare($result)->send();
    exit;
}

if ($_SERVER['REQUEST_URI'] === '/return') {
    $token = $_REQUEST['token_ws'];
    $transactionInfo = $transbank->webpayPlus()->info($token);
    if ($result->isSuccessful()) {
        $transbank->webpayPlus()->confirm($token);
    }
    RedirectView::prepare($result, $token)->send();
    exit;
}

if ($_SERVER['REQUEST_URI'] === '/final') {
    echo 'Transaction OK';
    exit;
}