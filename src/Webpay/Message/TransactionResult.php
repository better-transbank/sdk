<?php

declare(strict_types=1);

/*
 * This file is part of the BetterTransbank\SDK project.
 * (c) Matías Navarro-Carter <mnavarrocarter@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BetterTransbank\SDK\Webpay\Message;

use BetterTransbank\SDK\Webpay\Message\Enum\ResultCode;
use DateTimeImmutable;

/**
 * Class TransactionResult.
 *
 * This class models the result of a transaction.
 */
final class TransactionResult
{
    /**
     * Commerce identifier.
     *
     * Usually is a order number or similar.
     *
     * @var string
     */
    private $identifier;
    /**
     * Accounting date.
     *
     * What matters here is only the day, month and year.
     *
     * @var DateTimeImmutable
     */
    private $accountingDate;
    /**
     * Transaction date.
     *
     * Date in which the transaction was paid
     *
     * @var DateTimeImmutable
     */
    private $transactionDate;
    /**
     * Result of 3DSecure Auth.
     *
     * @var string
     */
    private $authResult;
    /**
     * @var string
     */
    private $redirectionUrl;
    /**
     * Card details.
     *
     * @var CardDetails
     */
    private $cardDetails;
    /**
     * Transaction detail.
     *
     * @var Detail
     */
    private $detail;

    /**
     * TransactionResult constructor.
     *
     * @param string            $identifier
     * @param DateTimeImmutable $transactionDate
     * @param DateTimeImmutable $accountingDate
     * @param string            $redirectionUrl
     * @param string            $authResult
     * @param CardDetails       $cardDetails
     * @param Detail            $detail
     */
    public function __construct(
        string $identifier,
        DateTimeImmutable $transactionDate,
        DateTimeImmutable $accountingDate,
        string $redirectionUrl,
        string $authResult,
        CardDetails $cardDetails,
        Detail $detail
    ) {
        $this->identifier = $identifier;
        $this->transactionDate = $transactionDate;
        $this->accountingDate = $accountingDate;
        $this->redirectionUrl = $redirectionUrl;
        $this->authResult = $authResult;
        $this->cardDetails = $cardDetails;
        $this->detail = $detail;
    }

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getAccountingDate(): DateTimeImmutable
    {
        return $this->accountingDate;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getTransactionDate(): DateTimeImmutable
    {
        return $this->transactionDate;
    }

    /**
     * @return string
     */
    public function getAuthResult(): string
    {
        return $this->authResult;
    }

    /**
     * @return CardDetails
     */
    public function getCardDetails(): CardDetails
    {
        return $this->cardDetails;
    }

    /**
     * @return Detail
     */
    public function getDetail(): Detail
    {
        return $this->detail;
    }

    /**
     * @return string
     */
    public function getRedirectionUrl(): string
    {
        return $this->redirectionUrl;
    }

    public function isSuccessful(): bool
    {
        return !ResultCode::isError($this->detail->getResponseCode());
    }
}
