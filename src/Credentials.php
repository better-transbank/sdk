<?php

declare(strict_types=1);

/*
 * This file is part of the BetterTransbank\SDK project.
 * (c) Matías Navarro-Carter <mnavarrocarter@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BetterTransbank\SDK;

/**
 * Class Credentials.
 */
class Credentials
{
    /**
     * @var string
     */
    private $privateKeyPath;
    /**
     * @var string
     */
    private $publicKeyPath;
    /**
     * @var string
     */
    private $commerceCode;

    /**
     * Credentials constructor.
     *
     * @param string $privateKeyPath
     * @param string $publicKeyPath
     * @param string $commerceCode
     */
    public function __construct(string $privateKeyPath, string $publicKeyPath, string $commerceCode)
    {
        $this->privateKeyPath = $privateKeyPath;
        $this->publicKeyPath = $publicKeyPath;
        $this->commerceCode = $commerceCode;
    }

    /**
     * @return string
     */
    public function getPrivateKeyPath(): string
    {
        return $this->privateKeyPath;
    }

    /**
     * @return string
     */
    public function getPublicKeyPath(): string
    {
        return $this->publicKeyPath;
    }

    /**
     * @return string
     */
    public function getCommerceCode(): string
    {
        return $this->commerceCode;
    }
}
