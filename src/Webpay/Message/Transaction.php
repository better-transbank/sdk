<?php

declare(strict_types=1);

/*
 * This file is part of the BetterTransbank\SDK project.
 * (c) Matías Navarro-Carter <mnavarrocarter@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BetterTransbank\SDK\Webpay\Message;

use BetterTransbank\SDK\Webpay\Message\Enum\TransactionType;

/**
 * Class Transaction.
 */
final class Transaction
{
    /**
     * @var string
     */
    private $transactionType;
    /**
     * @var string
     */
    private $returnUrl;
    /**
     * @var string
     */
    private $finalUrl;
    /**
     * @var string|null
     */
    private $commerceCode;
    /**
     * @var string|null
     */
    private $sessionId;
    /**
     * @var string|null
     */
    private $identifier;
    /**
     * Patpass subscription info
     * @var SubscriptionInfo|null
     */
    private $subscriptionInfo;
    /**
     * @var array
     */
    private $details;

    /**
     * @param string      $finalUrl
     * @param string      $returnUrl
     * @param string|null $commerceCode
     *
     * @return static
     */
    public static function create(string $returnUrl, string $finalUrl, string $commerceCode = null): self
    {
        return new self($returnUrl, $finalUrl, $commerceCode);
    }

    /**
     * Transaction constructor.
     *
     * @param string $finalUrl
     * @param string $returnUrl
     * @param string $commerceCode
     */
    public function __construct(
        string $returnUrl,
        string $finalUrl,
        string $commerceCode = null
    ) {
        $this->transactionType = TransactionType::NORMAL;
        $this->returnUrl = $returnUrl;
        $this->finalUrl = $finalUrl;
        $this->commerceCode = $commerceCode;
        $this->details = [];
    }

    /**
     * @return string
     */
    public function getTransactionType(): string
    {
        return $this->transactionType;
    }

    /**
     * @return string
     */
    public function getReturnUrl(): string
    {
        return $this->returnUrl;
    }

    /**
     * @return string
     */
    public function getFinalUrl(): string
    {
        return $this->finalUrl;
    }

    /**
     * @return string|null
     */
    public function getCommerceCode(): ?string
    {
        return $this->commerceCode;
    }

    /**
     * @return string|null
     */
    public function getIdentifier(): ?string
    {
        return $this->identifier;
    }

    /**
     * @return $this
     */
    public function makeTypeNormal(): self
    {
        $cloned = clone $this;
        $cloned->commerceCode = null;
        $cloned->identifier = null;
        $cloned->transactionType = TransactionType::NORMAL;

        return $cloned;
    }

    /**
     * @param string $commerceCode
     * @param string $identifier
     *
     * @return $this
     */
    public function makeTypeMall(string $commerceCode, string $identifier): self
    {
        $cloned = clone $this;
        $cloned->commerceCode = $commerceCode;
        $cloned->identifier = $identifier;
        $cloned->transactionType = TransactionType::MALL;

        return $cloned;
    }

    /**
     * @param SubscriptionInfo $subscriptionInfo
     * @return $this
     */
    public function makeTypePatPass(SubscriptionInfo $subscriptionInfo): self
    {
        $cloned = clone $this;
        $cloned->transactionType = TransactionType::PAT_PASS;
        $cloned->subscriptionInfo = $subscriptionInfo;
        return $cloned;
    }

    /**
     * @param string $identifier
     * @param int $amount
     * @param string $commerceCode
     *
     * @param int $instalments
     * @param float $instalmentsAmount
     * @return $this
     */
    public function withAddedDetails(string $identifier, int $amount, string $commerceCode, int $instalments = 0, float $instalmentsAmount = 0): self
    {
        $cloned = clone $this;
        $cloned->details[] = [
            'buyOrder' => $identifier,
            'amount' => $amount,
            'commerceCode' => $commerceCode,
            'sharesNumber' => $instalments,
            'sharesAmount' => $instalmentsAmount
        ];

        return $cloned;
    }

    public function withSessionId(string $sessionId): self
    {
        $cloned = clone $this;
        $cloned->sessionId = $sessionId;
        return $cloned;
    }

    /**
     * @return string|null
     */
    public function getSessionId(): ?string
    {
        return $this->sessionId;
    }

    /**
     * @return array
     */
    public function getDetails(): array
    {
        return $this->details;
    }

    /**
     * @return SubscriptionInfo|null
     */
    public function getSubscriptionInfo(): ?SubscriptionInfo
    {
        return $this->subscriptionInfo;
    }
}
