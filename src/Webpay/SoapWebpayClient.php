<?php

declare(strict_types=1);

/*
 * This file is part of the BetterTransbank\SDK project.
 * (c) Matías Navarro-Carter <mnavarrocarter@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BetterTransbank\SDK\Webpay;

use BetterTransbank\SDK\Soap\Credentials;
use BetterTransbank\SDK\Soap\TransbankSoapClient;
use BetterTransbank\SDK\Webpay\Message\CardDetails;
use BetterTransbank\SDK\Webpay\Message\Detail;
use BetterTransbank\SDK\Webpay\Message\StartTransactionResponse;
use BetterTransbank\SDK\Webpay\Message\SubscriptionInfo;
use BetterTransbank\SDK\Webpay\Message\Transaction;
use BetterTransbank\SDK\Webpay\Message\TransactionResult;
use DateTimeImmutable;

/**
 * Class SoapWebpayClient.
 */
final class SoapWebpayClient implements WebpayClient
{
    /**
     * @var TransbankSoapClient
     */
    private $client;

    /**
     * @param Credentials $credentials
     *
     * @return WebpayClient
     */
    public static function fromCredentials(Credentials $credentials): WebpayClient
    {
        return new self(new TransbankSoapClient($credentials));
    }

    /**
     * SoapWebpayClient constructor.
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
    public function startTransaction(Transaction $transaction): StartTransactionResponse
    {
        $payload = [
            'wsInitTransactionInput' => [
                'wSTransactionType' => $transaction->getTransactionType(),
                'returnURL' => $transaction->getReturnUrl(),
                'finalURL' => $transaction->getFinalUrl(),
                'commerceId' => $transaction->getCommerceCode(),
                'buyOrder' => $transaction->getIdentifier(),
                'sessionId' => $transaction->getSessionId(),
                'transactionDetails' => $transaction->getDetails(),
            ],
        ];

        $subInfo = $transaction->getSubscriptionInfo();
        if ($subInfo instanceof SubscriptionInfo) {
            $payload['wsInitTransactionInput']['wPMDetail'] = [
                'serviceId' => $subInfo->getServiceId(),
                'cardHolderId' => $subInfo->getCardHolderId(),
                'cardHolderName' => $subInfo->getCardHolderName(),
                'cardHolderLastName1' => $subInfo->getCardHolderLastName1(),
                'cardHolderLastName2' => $subInfo->getCardHolderLastName2(),
                'cardHolderMail' => $subInfo->getCardHolderMail(),
                'cellPhoneNumber' => $subInfo->getCellPhoneNumber(),
                'expirationDate' => $subInfo->getExpirationDate()->format('Y-m-d'),
                'commerceMail' => $subInfo->getCommerceMail(),
                'ufFlag' => $subInfo->isUf(),
            ];
        }

        /** @noinspection PhpUndefinedMethodInspection */
        $response = $this->client->initTransaction($payload);

        return new StartTransactionResponse(
            $response->return->token,
            $response->return->url
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getTransactionResult(string $transactionToken): TransactionResult
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $response = $this->client->getTransactionResult(['tokenInput' => $transactionToken]);
        $envelope = $response->return;

        $transDate = new DateTimeImmutable($envelope->transactionDate);
        $accountingDate = $transDate->setDate(
            (int) $transDate->format('Y'),
            (int) substr($envelope->accountingDate, 0, 2),
            (int) substr($envelope->accountingDate, 2, 2)
        )->setTime(0, 0, 0);

        $cardDetails = new CardDetails(
            $envelope->cardDetail->cardNumber,
            $envelope->cardDetail->expirationDate ?? null
        );

        $transactionResult = new TransactionResult(
            $envelope->buyOrder,
            $transDate,
            $accountingDate,
            $envelope->urlRedirection,
            $envelope->VCI,
            $cardDetails
        );

        $details = $envelope->detailOutput;

        if (is_object($details)) {
            $details = [$details];
        }

        foreach ($details as $detail) {
            $transactionResult = $transactionResult->withAddedDetail(new Detail(
                $detail->buyOrder,
                (int) $detail->amount,
                $detail->sharesNumber,
                $detail->commerceCode,
                $detail->authorizationCode,
                $detail->paymentTypeCode,
                $detail->responseCode
            ));
        }

        return  $transactionResult;
    }

    /**
     * {@inheritdoc}
     */
    public function confirmTransaction(string $transactionToken): void
    {
        /* @noinspection PhpUndefinedMethodInspection */
        $this->client->acknowledgeTransaction(['tokenInput' => $transactionToken]);
    }
}
