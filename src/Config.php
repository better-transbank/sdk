<?php

declare(strict_types=1);

/*
 * This file is part of the BetterTransbank\SDK project.
 * (c) Matías Navarro-Carter <mnavarrocarter@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BetterTransbank\SDK;

use BetterTransbank\SDK\Soap\Credentials\Certificate;
use BetterTransbank\SDK\Soap\Credentials\PrivateKey;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;

/**
 * Class Config.
 */
class Config
{
    protected const INTEGRATION_URL = 'https://webpay3gint.transbank.cl';
    protected const PRODUCTION_URL = 'https://webpay3g.transbank.cl';

    /**
     * @var bool
     */
    private $integration;
    /**
     * @var PrivateKey
     */
    private $privateKey;
    /**
     * @var Certificate
     */
    private $publicKey;
    /**
     * @var Certificate
     */
    private $transbankCert;
    /**
     * @var string
     */
    private $commerceCode;
    /**
     * @var LoggerInterface|null
     */
    private $logger;
    /**
     * @var EventDispatcherInterface|null
     */
    private $eventDispatcher;

    /**
     * @param Credentials $credentials
     * @param bool        $integration
     *
     * @return Config
     */
    public static function fromCredentials(Credentials $credentials, bool $integration = true): self
    {
        return new self(
            $integration,
            new PrivateKey($credentials->getPrivateKeyPath()),
            new Certificate($credentials->getPublicKeyPath()),
            $credentials->getCommerceCode()
        );
    }

    /**
     * Config constructor.
     *
     * @param bool        $integration
     * @param PrivateKey  $privateKey
     * @param Certificate $publicKey
     * @param string      $commerceCode
     */
    public function __construct(bool $integration, PrivateKey $privateKey, Certificate $publicKey, string $commerceCode)
    {
        $this->integration = $integration;
        $this->privateKey = $privateKey;
        $this->publicKey = $publicKey;
        $this->transbankCert = $this->fetchTransbankCert();
        $this->commerceCode = $commerceCode;
        $this->guard();
    }

    public function setLogger(LoggerInterface $logger): self
    {
        $this->logger = $logger;

        return $this;
    }

    public function getLogger(): ?LoggerInterface
    {
        return $this->logger;
    }

    public function setEventDispatcher(EventDispatcherInterface $dispatcher): self
    {
        $this->eventDispatcher = $dispatcher;

        return $this;
    }

    public function getEventDispatcher(): ?EventDispatcherInterface
    {
        return $this->eventDispatcher;
    }

    /**
     * @param string $endpoint
     *
     * @return string
     */
    public function wsdlUrl(string $endpoint): string
    {
        $baseUrl = self::INTEGRATION_URL;
        if ($this->integration === false) {
            $baseUrl = self::PRODUCTION_URL;
        }

        return sprintf('%s/%s', $baseUrl, ltrim($endpoint));
    }

    /**
     * @return PrivateKey
     */
    public function getPrivateKey(): PrivateKey
    {
        return $this->privateKey;
    }

    /**
     * @return Certificate
     */
    public function getPublicKey(): Certificate
    {
        return $this->publicKey;
    }

    public function getCommerceCode(): string
    {
        return $this->commerceCode;
    }

    /**
     * @return Certificate
     */
    public function getTransbankCert(): Certificate
    {
        return $this->transbankCert;
    }

    private function guard(): void
    {
        // TODO: We check the commerce code is not empty and that is equal to the public key subject CN
        if ($this->publicKey->getSubjectCN() === '') {
            throw new \InvalidArgumentException('Your public key Subject CN must contain your commerce code');
        }
    }

    /**
     * @return Certificate
     */
    private function fetchTransbankCert(): Certificate
    {
        if ($this->integration === true) {
            return new Certificate(\BetterTransbank\Certificates\Transbank::STAGING);
        }

        return new Certificate(\BetterTransbank\Certificates\Transbank::PRODUCTION);
    }
}
