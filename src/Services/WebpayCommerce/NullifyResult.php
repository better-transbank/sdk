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
 * Class NullifyResult.
 */
class NullifyResult
{
    /**
     * @var string
     */
    private $authCode;
    /**
     * @var \DateTimeInterface
     */
    private $authorizedAt;
    /**
     * @var int
     */
    private $balance;
    /**
     * @var int
     */
    private $nullifiedAmount;
    /**
     * @var string
     */
    private $token;

    /**
     * NullifyResult constructor.
     *
     * @param string             $authCode
     * @param \DateTimeInterface $authorizedAt
     * @param int                $balance
     * @param int                $nullifiedAmount
     * @param string             $token
     */
    public function __construct(string $authCode, \DateTimeInterface $authorizedAt, int $balance, int $nullifiedAmount, string $token)
    {
        $this->authCode = $authCode;
        $this->authorizedAt = $authorizedAt;
        $this->balance = $balance;
        $this->nullifiedAmount = $nullifiedAmount;
        $this->token = $token;
    }

    /**
     * @return string
     */
    public function getAuthCode(): string
    {
        return $this->authCode;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getAuthorizedAt(): \DateTimeInterface
    {
        return $this->authorizedAt;
    }

    /**
     * @return int
     */
    public function getBalance(): int
    {
        return $this->balance;
    }

    /**
     * @return int
     */
    public function getNullifiedAmount(): int
    {
        return $this->nullifiedAmount;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }
}
