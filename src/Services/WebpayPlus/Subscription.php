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
 * Class Subscription.
 */
class Subscription
{
    /**
     * @var string
     */
    private $orderId;
    /**
     * @var int
     */
    private $amount;
    /**
     * @var string
     */
    private $serviceId;
    /**
     * @var \DateTimeInterface
     */
    private $expiration;
    /**
     * @var bool
     */
    private $inUf;

    /**
     * @param string             $orderId
     * @param int                $amount
     * @param string             $serviceId
     * @param \DateTimeInterface $expiration
     * @param bool               $inUf
     *
     * @return Subscription
     */
    public static function define(string $orderId, int $amount, string $serviceId, \DateTimeInterface $expiration, bool $inUf = false): self
    {
        return new self($orderId, $amount, $serviceId, $expiration, $inUf);
    }

    /**
     * Subscription constructor.
     *
     * @param string             $orderId
     * @param int                $amount
     * @param string             $serviceId
     * @param \DateTimeInterface $expiration
     * @param bool               $inUf
     */
    public function __construct(string $orderId, int $amount, string $serviceId, \DateTimeInterface $expiration, bool $inUf)
    {
        $this->orderId = $orderId;
        $this->amount = $amount;
        $this->serviceId = $serviceId;
        $this->expiration = $expiration;
        $this->inUf = $inUf;
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

    /**
     * @return string
     */
    public function getServiceId(): string
    {
        return $this->serviceId;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getExpiration(): \DateTimeInterface
    {
        return $this->expiration;
    }

    /**
     * @return bool
     */
    public function isInUf(): bool
    {
        return $this->inUf;
    }
}
