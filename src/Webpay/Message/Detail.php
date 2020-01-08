<?php

declare(strict_types=1);

/*
 * This file is part of the BetterTransbank\SDK project.
 * (c) Matías Navarro-Carter <mnavarrocarter@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BetterTransbank\SDK\Webpay\Message;

/**
 * Class Detail.
 */
final class Detail
{
    /**
     * @var string
     */
    private $identifier;
    /**
     * @var int
     */
    private $amount;
    /**
     * @var int
     */
    private $instalments;
    /**
     * @var string
     */
    private $commerceCode;
    /**
     * @var string
     */
    private $authCode;
    /**
     * @var string
     */
    private $paymentType;
    /**
     * @var int
     */
    private $responseCode;

    /**
     * Detail constructor.
     *
     * @param string $identifier
     * @param int    $amount
     * @param int    $instalments
     * @param string $commerceCode
     * @param string $authCode
     * @param string $paymentType
     * @param int    $responseCode
     */
    public function __construct(
        string $identifier,
        int $amount,
        int $instalments,
        string $commerceCode,
        string $authCode,
        string $paymentType,
        int $responseCode
    ) {
        $this->identifier = $identifier;
        $this->amount = $amount;
        $this->instalments = $instalments;
        $this->commerceCode = $commerceCode;
        $this->authCode = $authCode;
        $this->paymentType = $paymentType;
        $this->responseCode = $responseCode;
    }

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
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
    public function getInstalments(): int
    {
        return $this->instalments;
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
    public function getAuthCode(): string
    {
        return $this->authCode;
    }

    /**
     * @return string
     */
    public function getPaymentType(): string
    {
        return $this->paymentType;
    }

    /**
     * @return int
     */
    public function getResponseCode(): int
    {
        return $this->responseCode;
    }
}
