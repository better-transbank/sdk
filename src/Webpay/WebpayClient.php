<?php

declare(strict_types=1);

/*
 * This file is part of the BetterTransbank\SDK project.
 * (c) Matías Navarro-Carter <mnavarrocarter@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BetterTransbank\SDK\Webpay;

use BetterTransbank\SDK\Soap\ClientException;
use BetterTransbank\SDK\Webpay\Message\StartTransactionResponse;
use BetterTransbank\SDK\Webpay\Message\Transaction;
use BetterTransbank\SDK\Webpay\Message\TransactionResult;

/**
 * Interface WebpayClient.
 */
interface WebpayClient
{
    /**
     * Starts a transaction in Webpay.
     *
     * Returns a response with a token and a url. A POST request to that url,
     * using the token in the body of a x-www-form-urlencoded request returns
     * the webpay payment form.
     *
     * You can use the PaymentForm class to build that form.
     *
     * @see \BetterTransbank\SDK\Html\PaymentForm
     *
     * @param Transaction $transaction
     *
     * @return StartTransactionResponse
     *
     * @throws ClientException if an error occurs
     */
    public function startTransaction(Transaction $transaction): StartTransactionResponse;

    /**
     * Gets the result of a transaction.
     *
     * You must call this method to verify if a transaction has been completed
     * successfully or not.
     *
     * You can show your customer a voucher using the VoucherView helper.
     *
     * @see \BetterTransbank\SDK\Html\VoucherView
     *
     * @param string $transactionToken
     *
     * @return TransactionResult
     *
     * @throws ClientException if an error occurs
     */
    public function getTransactionResult(string $transactionToken): TransactionResult;

    /**
     * Confirms a transaction.
     *
     * You MUST call this method in the first 30 seconds of a successful payment
     * to confirm to Webpay that this transaction is valid.
     *
     * If you do not call this method, then Webpay reverses the charge to your
     * customer card.
     *
     * @param string $transactionToken
     *
     * @throws ClientException if an error occurs
     */
    public function confirmTransaction(string $transactionToken): void;
}
