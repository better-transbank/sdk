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
 * Class CaptureOrder.
 */
class CaptureOrder
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
    private $amount;

    /**
     * CaptureOrder constructor.
     *
     * @param string $authCode
     * @param string $orderId
     * @param int    $amount
     */
    public function __construct(string $authCode, string $orderId, int $amount)
    {
        $this->authCode = $authCode;
        $this->orderId = $orderId;
        $this->amount = $amount;
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
    public function getAmount(): int
    {
        return $this->amount;
    }
}
