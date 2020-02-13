<?php
declare(strict_types=1);

use BetterTransbank\SDK\Config;
use BetterTransbank\SDK\Html\PaymentForm;
use BetterTransbank\SDK\Html\RedirectView;
use BetterTransbank\SDK\Services\WebpayPlus\MultipleTransactionInfo;
use BetterTransbank\SDK\Services\WebpayPlus\Transaction;
use BetterTransbank\SDK\TestingCredentials;
use BetterTransbank\SDK\Transbank;

require_once __DIR__ . '/../vendor/autoload.php';

// This example shows how you can use the sdk in a CGI single-script context.
// You can run this example with PHP built-in web server:
// php -S 0.0.0.0:8000 -t examples/ examples/wp-mall.php

$config = Config::fromCredentials(TestingCredentials::forWebpayPlusMultiple());
$transbank = Transbank::create($config);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $transaction = Transaction::multiple('12345','http://localhost:8000/return','http://localhost:8000/final')
        ->withAddedTransaction('trans-2', '597044444402', 3000)
        ->withAddedTransaction('trans-2', '597044444403', 23401);

    $result = $transbank->webpayPlus()->register($transaction);
    PaymentForm::prepare($result)->send();
    exit;
}

if ($_SERVER['REQUEST_URI'] === '/return') {
    $token = $_REQUEST['token_ws'];
    $transactionInfo = $transbank->webpayPlus()->info($token);

    if (!$transactionInfo instanceof MultipleTransactionInfo) {
        throw new RuntimeException('Invalid transaction class');
    }

    if ($transactionInfo->areAllSuccessful() && $transactionInfo->getTotalAmount() === 26401) {
        $transbank->webpayPlus()->confirm($transactionInfo->getToken());
    }

    RedirectView::prepare($transactionInfo)->send();
    exit;
}