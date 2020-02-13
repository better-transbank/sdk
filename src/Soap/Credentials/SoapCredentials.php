<?php

declare(strict_types=1);

/*
 * This file is part of the BetterTransbank\SDK project.
 * (c) Matías Navarro-Carter <mnavarrocarter@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BetterTransbank\SDK\Soap\Credentials;

use BetterTransbank\Certificates\Transbank;

/**
 * Class SoapCredentials.
 */
class SoapCredentials
{
    public const ENV_STAGING = 'staging';
    public const ENV_PRODUCTION = 'production';

    private static $envUrlMap = [
        self::ENV_STAGING => 'https://webpay3gint.transbank.cl',
        self::ENV_PRODUCTION => 'https://webpay3g.transbank.cl',
    ];

    private static $envTransbankCertMap = [
        self::ENV_STAGING => Transbank::STAGING,
        self::ENV_PRODUCTION => Transbank::PRODUCTION,
    ];

    /**
     * @var PrivateKey
     */
    private $privateKey;
    /**
     * @var Certificate
     */
    private $publicCert;
    /**
     * @var Certificate
     */
    private $transbankCert;
    /**
     * @var string
     */
    private $env;

    /**
     * @param string $privateKeyPath
     * @param string $publicCertPath
     *
     * @return static
     */
    public static function staging(string $privateKeyPath, string $publicCertPath): self
    {
        return new static(
            new PrivateKey($privateKeyPath),
            new Certificate($publicCertPath),
            self::ENV_STAGING
        );
    }

    public static function production(string $privateKeyPath, string $publicCertPath): self
    {
        return new static(
            new PrivateKey($privateKeyPath),
            new Certificate($publicCertPath),
            self::ENV_PRODUCTION
        );
    }

    /**
     * SoapCredentials constructor.
     *
     * @param PrivateKey  $privateKey
     * @param Certificate $publicCert
     * @param string      $env
     */
    public function __construct(PrivateKey $privateKey, Certificate $publicCert, string $env)
    {
        $this->privateKey = $privateKey;
        $this->publicCert = $publicCert;
        $this->env = $env;
        $this->init();
    }

    /**
     * @return PrivateKey
     */
    public function privateKey(): PrivateKey
    {
        return $this->privateKey;
    }

    /**
     * @return Certificate
     */
    public function publicCert(): Certificate
    {
        return $this->publicCert;
    }

    /**
     * @return Certificate
     */
    public function transbankCert(): Certificate
    {
        return $this->transbankCert;
    }

    public function getBaseUrl(): string
    {
        return self::$envUrlMap[$this->env];
    }

    private function init(): void
    {
        if (!in_array($this->env, [self::ENV_PRODUCTION, self::ENV_STAGING], true)) {
            throw new \InvalidArgumentException(sprintf('Invalid env "%s" provided. It can only be either "%s or "%s".', $this->env, self::ENV_STAGING, self::ENV_PRODUCTION));
        }
        $this->transbankCert = new Certificate(self::$envTransbankCertMap[$this->env]);
    }
}
