<?php

declare(strict_types=1);

/*
 * This file is part of the BetterTransbank\SDK project.
 * (c) Matías Navarro-Carter <mnavarrocarter@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BetterTransbank\SDK;

use BetterTransbank\SDK\Services\WebpayCommerce\SoapWebpayCommerceService;
use BetterTransbank\SDK\Services\WebpayCommerce\WebpayCommerceService;
use BetterTransbank\SDK\Services\WebpayOneClick\WebpayOneClickService;
use BetterTransbank\SDK\Services\WebpayPlus\SoapWebpayPlusService;
use BetterTransbank\SDK\Services\WebpayPlus\WebpayPlusService;
use BetterTransbank\SDK\Soap\Factory\ConfigWebpaySoapClientFactory;
use BetterTransbank\SDK\Soap\Factory\WebpaySoapClientFactory;

/**
 * Class Transbank.
 *
 * This is the main class that contains the transbank services.
 */
class Transbank
{
    /**
     * @var WebpaySoapClientFactory
     */
    private $factory;
    /**
     * A simple in-memory cache of services created.
     *
     * @var array
     */
    protected $cache = [];

    /**
     * @param Config $config
     *
     * @return Transbank
     */
    public static function create(Config $config): self
    {
        return new self(new ConfigWebpaySoapClientFactory($config));
    }

    /**
     * Transbank constructor.
     *
     * @param WebpaySoapClientFactory $factory
     */
    public function __construct(WebpaySoapClientFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * Gets the Webpay OneClick service.
     *
     * @return WebpayOneClickService
     */
    public function webpayOneClick(): WebpayOneClickService
    {
        throw new \RuntimeException('Webpay One Click is not implemented');
    }

    /**
     * Gets the Webpay Plus service.
     *
     * @return WebpayPlusService
     */
    public function webpayPlus(): WebpayPlusService
    {
        if (!isset($this->cache['wp-plus'])) {
            $this->cache['wp-plus'] = new SoapWebpayPlusService($this->factory->forWebpayPlus());
        }

        return $this->cache['wp-plus'];
    }

    /**
     * Gets the Webpay Commerce service.
     *
     * @return WebpayCommerceService
     */
    public function webpayCommerce(): WebpayCommerceService
    {
        if (!isset($this->cache['wp-commerce'])) {
            $this->cache['wp-commerce'] = new SoapWebpayCommerceService($this->factory->forWebpayCommerce());
        }

        return $this->cache['wp-commerce'];
    }
}
