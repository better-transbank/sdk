<?php
declare(strict_types=1);


namespace BetterTransbank\SDK\Webpay\Message;

use DateTimeImmutable;

/**
 * Class SubscriptionInfo
 * @package BetterTransbank\SDK\Webpay\Message
 */
final class SubscriptionInfo
{
    /**
     * @var string
     */
    private $serviceId;
    /**
     * @var string
     */
    private $cardHolderId;
    /**
     * @var string
     */
    private $cardHolderName;
    /**
     * @var string
     */
    private $cardHolderLastName1;
    /**
     * @var string
     */
    private $cardHolderLastName2;
    /**
     * @var string
     */
    private $cardHolderMail;
    /**
     * @var string
     */
    private $cellPhoneNumber;
    /**
     * @var DateTimeImmutable
     */
    private $expirationDate;
    /**
     * @var string
     */
    private $commerceMail;
    /**
     * @var bool
     */
    private $uf;

    /**
     * SubscriptionInfo constructor.
     * @param string $serviceId
     * @param string $cardHolderId
     * @param string $cardHolderName
     * @param string $cardHolderLastName1
     * @param string $cardHolderLastName2
     * @param string $cardHolderMail
     * @param string $cellPhoneNumber
     * @param DateTimeImmutable $expirationDate
     * @param string $commerceMail
     * @param bool $uf
     */
    public function __construct(
        string $serviceId,
        string $cardHolderId,
        string $cardHolderName,
        string $cardHolderLastName1,
        string $cardHolderLastName2,
        string $cardHolderMail,
        string $cellPhoneNumber,
        DateTimeImmutable $expirationDate,
        string $commerceMail,
        bool $uf = false
    ) {
        $this->serviceId = $serviceId;
        $this->cardHolderId = $cardHolderId;
        $this->cardHolderName = $cardHolderName;
        $this->cardHolderLastName1 = $cardHolderLastName1;
        $this->cardHolderLastName2 = $cardHolderLastName2;
        $this->cardHolderMail = $cardHolderMail;
        $this->cellPhoneNumber = $cellPhoneNumber;
        $this->expirationDate = $expirationDate;
        $this->commerceMail = $commerceMail;
        $this->uf = $uf;
    }

    /**
     * @return string
     */
    public function getServiceId(): string
    {
        return $this->serviceId;
    }

    /**
     * @return string
     */
    public function getCardHolderId(): string
    {
        return $this->cardHolderId;
    }

    /**
     * @return string
     */
    public function getCardHolderName(): string
    {
        return $this->cardHolderName;
    }

    /**
     * @return string
     */
    public function getCardHolderLastName1(): string
    {
        return $this->cardHolderLastName1;
    }

    /**
     * @return string
     */
    public function getCardHolderLastName2(): string
    {
        return $this->cardHolderLastName2;
    }

    /**
     * @return string
     */
    public function getCardHolderMail(): string
    {
        return $this->cardHolderMail;
    }

    /**
     * @return string
     */
    public function getCellPhoneNumber(): string
    {
        return $this->cellPhoneNumber;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getExpirationDate(): DateTimeImmutable
    {
        return $this->expirationDate;
    }

    /**
     * @return string
     */
    public function getCommerceMail(): string
    {
        return $this->commerceMail;
    }

    /**
     * @return bool
     */
    public function isUf(): bool
    {
        return $this->uf;
    }
}