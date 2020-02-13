<?php

declare(strict_types=1);

/*
 * This file is part of the BetterTransbank\SDK project.
 * (c) Matías Navarro-Carter <mnavarrocarter@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BetterTransbank\SDK\Soap\Factory;

use BetterTransbank\SDK\Config;
use BetterTransbank\SDK\Soap\ExtTransbankSoapClient;
use BetterTransbank\SDK\Soap\TransbankSoapClient;

/**
 * Class ConfigWebpaySoapClientFactory.
 */
final class ConfigWebpaySoapClientFactory implements WebpaySoapClientFactory
{
    public const SERVICE_WEBPAY = 'webpay';
    public const SERVICE_ONE_CLICK = 'one_click';
    public const SERVICE_COMMERCE = 'commerce';

    private static $serviceWsdlMap = [
        self::SERVICE_ONE_CLICK => '/webpayserver/wswebpay/OneClickPaymentService?wsdl',
        self::SERVICE_COMMERCE => '/WSWebpayTransaction/cxf/WSCommerceIntegrationService?wsdl',
        self::SERVICE_WEBPAY => '/WSWebpayTransaction/cxf/WSWebpayService?wsdl',
    ];

    /**
     * @var Config
     */
    private $config;

    /**
     * ConfigWebpaySoapClientFactory constructor.
     *
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function forWebpayCommerce(): TransbankSoapClient
    {
        return $this->create(self::SERVICE_COMMERCE);
    }

    public function forWebpayOneClick(): TransbankSoapClient
    {
        return $this->create(self::SERVICE_ONE_CLICK);
    }

    public function forWebpayPlus(): TransbankSoapClient
    {
        return $this->create(self::SERVICE_WEBPAY);
    }

    /**
     * @param string $serviceName
     *
     * @return ExtTransbankSoapClient
     * @noinspection PhpDocMissingThrowsInspection
     */
    protected function create(string $serviceName): TransbankSoapClient
    {
        $wsdl = $this->buildWsdl($serviceName);
        /** @noinspection PhpUnhandledExceptionInspection */
        $client = new ExtTransbankSoapClient(
            $wsdl,
            $this->config->getPrivateKey(),
            $this->config->getPublicKey(),
            $this->config->getTransbankCert()
        );

        if (($logger = $this->config->getLogger()) !== null) {
            $client->setLogger($logger);
        }

        return $client;
    }

    /**
     * @param string $serviceName
     *
     * @return string
     */
    private function buildWsdl(string $serviceName): string
    {
        if (!in_array($serviceName, [self::SERVICE_WEBPAY, self::SERVICE_COMMERCE, self::SERVICE_ONE_CLICK], true)) {
            throw new \InvalidArgumentException(sprintf('Invalid service name "%s" provided', $serviceName));
        }

        return $this->config->wsdlUrl(self::$serviceWsdlMap[$serviceName]);
    }
}
