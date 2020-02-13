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
 * Class Customer.
 */
class Customer
{
    /**
     * @var string
     */
    private $id;
    /**
     * @var string
     */
    private $givenName;
    /**
     * @var string
     */
    private $lastNameOne;
    /**
     * @var string
     */
    private $lastNameTwo;
    /**
     * @var string
     */
    private $email;
    /**
     * @var string
     */
    private $phone;

    /**
     * @param string $id
     * @param string $givenName
     * @param string $lastNameOne
     * @param string $lastNameTwo
     * @param string $email
     * @param string $phone
     *
     * @return Customer
     */
    public static function register(string $id, string $givenName, string $lastNameOne, string $lastNameTwo, string $email, string $phone): Customer
    {
        return new self($id, $givenName, $lastNameOne, $lastNameTwo, $email, $phone);
    }

    /**
     * Customer constructor.
     *
     * @param string $id
     * @param string $givenName
     * @param string $lastNameOne
     * @param string $lastNameTwo
     * @param string $email
     * @param string $phone
     */
    public function __construct(string $id, string $givenName, string $lastNameOne, string $lastNameTwo, string $email, string $phone)
    {
        $this->id = $id;
        $this->givenName = $givenName;
        $this->lastNameOne = $lastNameOne;
        $this->lastNameTwo = $lastNameTwo;
        $this->email = $email;
        $this->phone = $phone;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getGivenName(): string
    {
        return $this->givenName;
    }

    /**
     * @return string
     */
    public function getLastNameOne(): string
    {
        return $this->lastNameOne;
    }

    /**
     * @return string
     */
    public function getLastNameTwo(): string
    {
        return $this->lastNameTwo;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }
}
