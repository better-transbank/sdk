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
 * Class CardDetails.
 */
class CardDetails
{
    /**
     * Últimos cuatro dígitos de la tarjeta.
     *
     * Transbank provee el número completo solo para comercios autorizados.
     *
     * @var string
     */
    private $cardNumber;
    /**
     * Fecha de expiración de la tarjeta.
     *
     * Transbank solo provee este dato para comercios autorizados.
     *
     * @var string|null
     */
    private $expirationDate;

    /**
     * CardDetails constructor.
     *
     * @param string $cardNumber
     * @param string $expirationDate
     */
    public function __construct(string $cardNumber, string $expirationDate = null)
    {
        $this->cardNumber = $cardNumber;
        $this->expirationDate = $expirationDate;
    }

    /**
     * @return string
     */
    public function getCardNumber(): string
    {
        return $this->cardNumber;
    }

    /**
     * @return string|null
     */
    public function getExpirationDate(): ?string
    {
        return $this->expirationDate;
    }
}
