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
 * Class Transaction.
 *
 * This class represents a Webpay Transaction
 *
 * Transactions can be of three types:
 * - Normal
 * - Multiple
 * - Subscription
 *
 * Refer to the static factory methods to get more information.
 */
class Transaction
{
    private const TYPE_NORMAL = 'TR_NORMAL_WS';
    private const TYPE_SUBSCRIPTION = 'TR_NORMAL_WS_WPM';
    private const TYPE_MULTIPLE = 'TR_MALL_WS';

    /**
     * @var array
     */
    protected $data;

    /**
     * Creates a normal transaction.
     *
     * A normal transaction is the traditional single payment.
     *
     * @param string $orderId
     * @param int    $amount
     * @param string $returnUrl
     * @param string $finalUrl
     *
     * @return Transaction
     */
    public static function normal(string $orderId, int $amount, string $returnUrl, string $finalUrl): Transaction
    {
        return new self([
            'wSTransactionType' => self::TYPE_NORMAL,
            'returnURL' => $returnUrl,
            'finalURL' => $finalUrl,
            'transactionDetails' => [
                'sharesAmount' => 0,
                'sharesNumber' => 0,
                'amount' => $amount,
                'commerceCode' => '',
                'buyOrder' => $orderId,
            ],
        ]);
    }

    /**
     * Creates a transaction that supports multiple added sub-transactions.
     *
     * A multiple transaction (or Mall Transaction) is a transaction that
     * allows wrapping multiple sub-transaction into a main one. Use it when
     * you have many sub-stores and you want to be clear which charge belongs
     * to each.
     *
     * @param string $mainOrderId
     * @param string $returnUrl
     * @param string $finalUrl
     *
     * @return Transaction
     */
    public static function multiple(string $mainOrderId, string $returnUrl, string $finalUrl): Transaction
    {
        return new self([
            'wSTransactionType' => self::TYPE_MULTIPLE,
            'buyOrder' => $mainOrderId,
            'commerceId' => '',
            'returnURL' => $returnUrl,
            'finalURL' => $finalUrl,
            'transactionDetails' => [],
        ]);
    }

    /**
     * Creates a subscription transaction.
     *
     * A subscription transaction is a recurrent transaction. It charges the
     * customer's card every month and notifies the urls of the payments.
     *
     * @param Subscription $subscription
     * @param Customer     $customer
     * @param string       $commerceEmail
     * @param string       $returnUrl
     * @param string       $finalUrl
     *
     * @return Transaction
     */
    public static function subscription(Subscription $subscription, Customer $customer, string $commerceEmail, string $returnUrl, string $finalUrl): Transaction
    {
        return new self([
            'wSTransactionType' => self::TYPE_SUBSCRIPTION,
            'returnURL' => $returnUrl,
            'finalURL' => $finalUrl,
            'transactionDetails' => [
                'sharesAmount' => 4000,
                'sharesNumber' => 0,
                'amount' => $subscription->getAmount(),
                'commerceCode' => '',
                'buyOrder' => $subscription->getOrderId(),
            ],
            'wPMDetail' => [
                'serviceId' => $subscription->getServiceId(),
                'cardHolderId' => $customer->getId(),
                'cardHolderName' => $customer->getGivenName(),
                'cardHolderLastName1' => $customer->getLastNameOne(),
                'cardHolderLastName2' => $customer->getLastNameTwo(),
                'cardHolderMail' => $customer->getEmail(),
                'cellPhoneNumber' => $customer->getPhone(),
                'expirationDate' => $subscription->getExpiration()->format('Y-m-d'),
                'commerceMail' => $commerceEmail,
                'ufFlag' => $subscription->isInUf(),
            ],
        ]);
    }

    /**
     * Transaction constructor.
     *
     * @param array $data
     */
    protected function __construct(array $data)
    {
        $this->data = [
            'wsInitTransactionInput' => $data,
        ];
    }

    /**
     * @param string $subOrderId
     * @param string $commerceCode
     * @param int    $amount
     *
     * @return $this
     */
    public function withAddedTransaction(string $subOrderId, string $commerceCode, int $amount): self
    {
        if (!$this->typeIs(self::TYPE_MULTIPLE)) {
            throw new \InvalidArgumentException('You can only add extra transaction to a multiple transaction');
        }
        $clone = clone $this;
        $clone->data['wsInitTransactionInput']['transactionDetails'][] = [
            'sharesAmount' => 0,
            'sharesNumber' => 0,
            'amount' => $amount,
            'commerceCode' => $commerceCode,
            'buyOrder' => $subOrderId,
        ];

        return $clone;
    }

    /**
     * @param string $commerceCode
     *
     * @return $this
     */
    public function withCommerceCode(string $commerceCode): self
    {
        $clone = clone $this;
        if ($clone->typeIs(self::TYPE_MULTIPLE)) {
            $clone->data['wsInitTransactionInput']['commerceId'] = $commerceCode;

            return $clone;
        }
        $clone->data['wsInitTransactionInput']['transactionDetails']['commerceCode'] = $commerceCode;

        return $clone;
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    protected function typeIs(string $type): bool
    {
        return $this->data['wsInitTransactionInput']['wSTransactionType'] === $type;
    }
}
