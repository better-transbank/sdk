<?php
declare(strict_types=1);

use BetterTransbank\SDK\Config;
use BetterTransbank\SDK\Html\PaymentForm;
use BetterTransbank\SDK\Html\RedirectView;
use BetterTransbank\SDK\Services\WebpayCommerce\CaptureOrder;
use BetterTransbank\SDK\Services\WebpayCommerce\NullifyOrder;
use BetterTransbank\SDK\Services\WebpayPlus\SingleTransactionInfo;
use BetterTransbank\SDK\Services\WebpayPlus\Transaction;
use BetterTransbank\SDK\TestingCredentials;
use BetterTransbank\SDK\Transbank;

require_once __DIR__ . '/../vendor/autoload.php';

// This example shows how you can use the sdk in a CGI single-script context.
// You can run this example with PHP built-in web server:
// php -S 0.0.0.0:8000 -t examples/ examples/wp-deferred.php

$orderId = 'Ll0IveVyddZXEFLDXGRgWQ==';

$config = Config::fromCredentials(TestingCredentials::forWebpayCommerce());
$transbank = Transbank::create($config);

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_SERVER['REQUEST_URI'] === '/') {

    $transaction = Transaction::normal(
        $orderId,
        10000,
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

    if (!$transactionInfo instanceof SingleTransactionInfo) {
        throw new RuntimeException('Invalid transaction class');
    }

    if ($transactionInfo->getPayment()->wasSuccessful() && $transactionInfo->getPayment()->getAmount() === 10000) {
        $transbank->webpayPlus()->confirm($token);
    }

    RedirectView::prepare($transactionInfo)->send();
    exit;
}

if ($_SERVER['REQUEST_URI'] === '/final') {

    sleep(10);

    $result = $transbank->webpayCommerce()->capture(new CaptureOrder(
        '1213',
        $orderId,
        10000
    ));

    echo 'Transaction has been captured. Now nullifying...';
    http_response_code(302);
    header('Location: http://localhost:8000/nullify');
    exit;
}

if ($_SERVER['REQUEST_URI'] === '/nullify') {

    sleep(10);

    $result = $transbank->webpayCommerce()->nullify(new NullifyOrder(
        '1213',
        $orderId,
        10000
    ));

    echo 'Transaction has been nullified';
    exit;
}