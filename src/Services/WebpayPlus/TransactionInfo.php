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
 * Abstract class TransactionInfo.
 *
 * Contains information about the transaction.
 *
 * @see https://www.transbankdevelopers.cl/referencia/webpay#confirmar-una-transaccion-webpay-plus-mall
 */
abstract class TransactionInfo
{
    /**
     * @var string
     */
    private $orderId;
    /**
     * @var CardInfo
     */
    private $card;
    /**
     * @var \DateTimeInterface
     */
    private $accountedAt;
    /**
     * @var \DateTimeInterface
     */
    private $processedAt;
    /**
     * @var string
     */
    private $vci;
    /**
     * @var string
     */
    private $token;
    /**
     * @var string
     */
    private $redirectionUrl;

    /**
     * TransactionInfo constructor.
     *
     * @param string             $orderId
     * @param CardInfo           $card
     * @param \DateTimeInterface $accountedAt
     * @param \DateTimeInterface $processedAt
     * @param string             $vci
     * @param string             $token
     * @param string             $redirectionUrl
     */
    public function __construct(
        string $orderId,
        CardInfo $card,
        \DateTimeInterface $accountedAt,
        \DateTimeInterface $processedAt,
        string $vci,
        string $token,
        string $redirectionUrl
    ) {
        $this->orderId = $orderId;
        $this->card = $card;
        $this->accountedAt = $accountedAt;
        $this->processedAt = $processedAt;
        $this->vci = $vci;
        $this->token = $token;
        $this->redirectionUrl = $redirectionUrl;
    }

    /**
     * @return string
     */
    public function getOrderId(): string
    {
        return $this->orderId;
    }

    /**
     * @return CardInfo
     */
    public function getCard(): CardInfo
    {
        return $this->card;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getAccountedAt(): \DateTimeInterface
    {
        return $this->accountedAt;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getProcessedAt(): \DateTimeInterface
    {
        return $this->processedAt;
    }

    /**
     * @return string
     */
    public function getVci(): string
    {
        return $this->vci;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @return string
     */
    public function getRedirectionUrl(): string
    {
        return $this->redirectionUrl;
    }
}
