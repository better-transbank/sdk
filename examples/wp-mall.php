<?php
declare(strict_types=1);

use BetterTransbank\SDK\Html\PaymentForm;
use BetterTransbank\SDK\Html\VoucherView;
use BetterTransbank\SDK\Webpay\Message\Transaction;
use BetterTransbank\SDK\Webpay\SoapWebpayClient;
use BetterTransbank\SDK\Webpay\WebpayCredentials;

require_once __DIR__ . '/../vendor/autoload.php';

// This example shows how you can use the sdk in a CGI single-script context.
// You can run this example with PHP built-in web server:
// php -S 0.0.0.0:8000 -t examples/ examples/wp-mall.php

$cred = WebpayCredentials::mallStaging();
$webpay = SoapWebpayClient::fromCredentials($cred);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Los codigos de comercio de WebpayMall los puedes ver aquí:
    // https://www.transbankdevelopers.cl/documentacion/como_empezar#ambientes
    $transaction = Transaction::create('http://localhost:8000/return', 'http://localhost:8000/final')
        ->makeTypeMall('597044444401', 'MainOrder1234')
        ->withAddedDetails('SubOrder1', 10000, '597044444402')
        ->withAddedDetails('SubOrder2', 14000, '597044444403');
    $response = $webpay->startTransaction($transaction);
    PaymentForm::prepare($response)->send();
    exit;
}

if ($_SERVER['REQUEST_URI'] === '/return') {
    $token = $_REQUEST['token_ws'];
    $result = $webpay->getTransactionResult($token);
    if ($result->isSuccessful()) {
        $webpay->confirmTransaction($token);
    }
    VoucherView::prepare($result, $token)->send();
    exit;
}

if ($_SERVER['REQUEST_URI'] === '/final') {
    echo 'Transaction OK';
    exit;
}