<?php

declare(strict_types=1);

/*
 * This file is part of the BetterTransbank\SDK project.
 * (c) Matías Navarro-Carter <mnavarrocarter@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BetterTransbank\SDK\Services\WebpayCommerce;

/**
 * Class NullifyOrder.
 */
class NullifyOrder
{
    /**
     * @var string
     */
    private $authCode;
    /**
     * @var string
     */
    private $orderId;
    /**
     * @var int
     */
    private $originalAmount;
    /**
     * @var int
     */
    private $amountToNullify;

    /**
     * NullifyOrder constructor.
     *
     * @param string   $authCode
     * @param string   $orderId
     * @param int      $originalAmount
     * @param int|null $amountToNullify
     */
    public function __construct(string $authCode, string $orderId, int $originalAmount, int $amountToNullify = null)
    {
        $this->authCode = $authCode;
        $this->orderId = $orderId;
        $this->originalAmount = $originalAmount;
        $this->amountToNullify = $amountToNullify ?? $originalAmount;
    }

    /**
     * @return string
     */
    public function getAuthCode(): string
    {
        return $this->authCode;
    }

    /**
     * @return string
     */
    public function getOrderId(): string
    {
        return $this->orderId;
    }

    /**
     * @return int
     */
    public function getOriginalAmount(): int
    {
        return $this->originalAmount;
    }

    /**
     * @return int
     */
    public function getAmountToNullify(): int
    {
        return $this->amountToNullify;
    }
}
