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
 * Class PaymentInfo.
 */
class PaymentInfo
{
    /**
     * @var string
     */
    private $orderId;
    /**
     * @var string
     */
    private $authCode;
    /**
     * @var int
     */
    private $amount;
    /**
     * @var int
     */
    private $statusCode;
    /**
     * @var int
     */
    private $sharesNumber;
    /**
     * @var string
     */
    private $commerceCode;
    /**
     * @var string
     */
    private $type;

    /**
     * PaymentInfo constructor.
     *
     * @param string $orderId
     * @param string $authCode
     * @param int    $amount
     * @param int    $statusCode
     * @param int    $sharesNumber
     * @param string $commerceCode
     * @param string $type
     */
    public function __construct(string $orderId, string $authCode, int $amount, int $statusCode, int $sharesNumber, string $commerceCode, string $type)
    {
        $this->orderId = $orderId;
        $this->authCode = $authCode;
        $this->amount = $amount;
        $this->statusCode = $statusCode;
        $this->sharesNumber = $sharesNumber;
        $this->commerceCode = $commerceCode;
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getOrderId(): string
    {
        return $this->orderId;
    }

    /**
     * @return string
     */
    public function getAuthCode(): string
    {
        return $this->authCode;
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @return int
     */
    public function getSharesNumber(): int
    {
        return $this->sharesNumber;
    }

    /**
     * @return string
     */
    public function getCommerceCode(): string
    {
        return $this->commerceCode;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return bool
     */
    public function wasSuccessful(): bool
    {
        return $this->statusCode === 0;
    }
}
