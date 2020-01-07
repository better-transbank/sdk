<?php

declare(strict_types=1);

/*
 * This file is part of the BetterTransbank\SDK project.
 * (c) Matías Navarro-Carter <mnavarrocarter@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BetterTransbank\SDK\Soap;

/**
 * Class Credentials.
 */
class Credentials
{
    protected const PRODUCTION_TRANSBANK_CERT = __DIR__.'/../../cert/transbank-production.pem';
    protected const STAGING_TRANSBANK_CERT = __DIR__.'/../../cert/transbank.pem';

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
    private $wsdl;

    /**
     * Credentials constructor.
     *
     * @param string $privateKey
     * @param string $publicCert
     * @param string $transbankCert
     * @param string $wsdl
     *
     * @throws CertificateFileException
     */
    public function __construct(string $privateKey, string $publicCert, string $transbankCert, string $wsdl)
    {
        $this->privateKey = new PrivateKey($privateKey);
        $this->publicCert = new Certificate($publicCert);
        $this->transbankCert = new Certificate($transbankCert);
        $this->wsdl = $wsdl;
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

    /**
     * @return string
     */
    public function wsdl(): string
    {
        return $this->wsdl;
    }
}