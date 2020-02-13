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
 * Class CaptureResult.
 */
class CaptureResult
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
    private $capturedAmount;
    /**
     * @var string
     */
    private $token;

    /**
     * CaptureResult constructor.
     *
     * @param string             $authCode
     * @param \DateTimeInterface $authorizedAt
     * @param int                $capturedAmount
     * @param string             $token
     */
    public function __construct(string $authCode, \DateTimeInterface $authorizedAt, int $capturedAmount, string $token)
    {
        $this->authCode = $authCode;
        $this->authorizedAt = $authorizedAt;
        $this->capturedAmount = $capturedAmount;
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
    public function getCapturedAmount(): int
    {
        return $this->capturedAmount;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }
}
