<?php

declare(strict_types=1);

/*
 * This file is part of the BetterTransbank\SDK project.
 * (c) Matías Navarro-Carter <mnavarrocarter@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BetterTransbank\SDK\Services\WebpayPlus;

use DateTimeInterface;

/**
 * Class CardInfo.
 */
class CardInfo
{
    /**
     * @var string
     */
    private $cardNumber;
    /**
     * @var DateTimeInterface|null
     */
    private $expirationDate;

    /**
     * CardDetails constructor.
     *
     * @param string $cardNumber
     * @param string $expirationDate
     * @noinspection PhpDocMissingThrowsInspection
     */
    public function __construct(string $cardNumber, string $expirationDate = null)
    {
        $this->cardNumber = $cardNumber;
        /* @noinspection PhpUnhandledExceptionInspection */
        $this->expirationDate = is_string($expirationDate) ?
            new \DateTimeImmutable($expirationDate, new \DateTimeZone('America/Santiago'))
            : null;
    }

    /**
     * @return string
     */
    public function getCardNumber(): string
    {
        return $this->cardNumber;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getExpirationDate(): ?DateTimeInterface
    {
        return $this->expirationDate;
    }
}
