<?php

declare(strict_types=1);

/*
 * This file is part of the BetterTransbank\SDK project.
 * (c) Matías Navarro-Carter <mnavarrocarter@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BetterTransbank\SDK\Services\WebpayPlus;

use BetterTransbank\SDK\Services\PropertyExtractorTrait;
use BetterTransbank\SDK\Soap\TransbankSoapClient;
use DateTimeImmutable;

/**
 * Class SoapWebpayPlusService.
 */
final class SoapWebpayPlusService implements WebpayPlusService
{
    use PropertyExtractorTrait;

    /**
     * @var TransbankSoapClient
     */
    private $client;

    /**
     * SoapWebpayPlusService constructor.
     *
     * @param TransbankSoapClient $client
     */
    public function __construct(TransbankSoapClient $client)
    {
        $this->client = $client;
    }

    /**
     * {@inheritdoc}
     */
    public function register(Transaction $transaction): RegisterTransactionResult
    {
        $transaction = $transaction->withCommerceCode($this->client->getCommerceCode());
        $data = $this->extract($transaction, 'data');
        $response = $this->client->request('initTransaction', $data);

        return new RegisterTransactionResult($response['return']['token'], $response['return']['url']);
    }

    /**
     * {@inheritdoc}
     */
    public function info(string $transactionToken): TransactionInfo
    {
        $data = $this->client->request('getTransactionResult', ['tokenInput' => $transactionToken]);
        $envelope = $data['return'];

        $transDate = new DateTimeImmutable($envelope['transactionDate']);
        $accountingDate = $transDate->setDate(
            (int) $transDate->format('Y'),
            (int) substr($envelope['accountingDate'], 0, 2),
            (int) substr($envelope['accountingDate'], 2, 2)
        )->setTime(0, 0, 0);

        $cardInfo = new CardInfo(
            $envelope['cardDetail']['cardNumber'],
            $envelope['cardDetail']['expirationDate'] ?? null
        );

        // Multiple Transaction
        if (array_keys($envelope['detailOutput'])[0] === 0) {
            return new MultipleTransactionInfo(
                $envelope['buyOrder'],
                $cardInfo,
                $accountingDate,
                $transDate,
                $envelope['VCI'],
                $transactionToken,
                $envelope['urlRedirection'],
                ...array_map(\Closure::fromCallable([$this, 'createPaymentInfo']), $envelope['detailOutput'])
            );
        }

        return new SingleTransactionInfo(
            $envelope['buyOrder'],
            $cardInfo,
            $accountingDate,
            $transDate,
            $envelope['VCI'],
            $transactionToken,
            $envelope['urlRedirection'],
            $this->createPaymentInfo($envelope['detailOutput'])
        );
    }

    /**
     * {@inheritdoc}
     */
    public function confirm(string $transactionToken): void
    {
        $this->client->request('acknowledgeTransaction', ['tokenInput' => $transactionToken]);
    }

    /**
     * @param array $paymentInfo
     *
     * @return PaymentInfo
     */
    protected function createPaymentInfo(array $paymentInfo): PaymentInfo
    {
        return new PaymentInfo(
            $paymentInfo['buyOrder'],
            $paymentInfo['authorizationCode'],
            (int) $paymentInfo['amount'],
            $paymentInfo['responseCode'],
            $paymentInfo['sharesNumber'],
            $paymentInfo['commerceCode'],
            $paymentInfo['paymentTypeCode']
        );
    }
}
