<?php

declare(strict_types=1);

/*
 * This file is part of the BetterTransbank\SDK project.
 * (c) Matías Navarro-Carter <mnavarrocarter@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BetterTransbank\SDK\Services\WebpayPlus;

/**
 * Interface WebpayPlusService.
 *
 * This interface defines a contract for interacting with Webpay's payment
 * api. You can register transactions, confirm them and check whether they have
 * been paid or not.
 */
interface WebpayPlusService
{
    /**
     * Registers a transaction in Webpay Plus.
     *
     * Different kinds of transactions can be created using the Transaction class factory methods.
     * Webpay Plus supports three kinds of transactions.
     *
     * - Normal: is a traditional single payment transaction. Most transactions are like this.
     * - Subscription: is a recurrent transaction over a period of time. Webpay calls them PAT transactions.
     * - Multiple: is a transaction that can contain many sub-transactions from different stores.
     *
     * The result of this operation contains a token and a url for payment. You can
     * use the PaymentForm helper class to render that form easily in your PHP app.
     *
     * @see \BetterTransbank\SDK\Html\PaymentForm
     *
     * @param Transaction $transaction The transaction info
     *
     * @return RegisterTransactionResult The transaction result
     *
     * @throws \BetterTransbank\SDK\Soap\ClientException on transaction failure
     */
    public function register(Transaction $transaction): RegisterTransactionResult;

    /**
     * Confirms a transaction.
     *
     * You MUST call this method after a successful transaction registration.
     *
     * If you fail to do so, Webpay will cancel the transaction relevant to this
     * token is 30 seconds.
     *
     * @param string $transactionToken
     */
    public function confirm(string $transactionToken): void;

    /**
     * Obtains info of a transaction.
     *
     * This endpoint allows you to fetch some important information about a
     * transaction that you can keep in your records.
     *
     * @param string $transactionToken
     *
     * @return SingleTransactionInfo|MultipleTransactionInfo
     */
    public function info(string $transactionToken): TransactionInfo;
}
