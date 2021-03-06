<?php

declare(strict_types=1);

/*
 * This file is part of the BetterTransbank\SDK project.
 * (c) Matías Navarro-Carter <mnavarrocarter@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BetterTransbank\SDK\Soap\Factory;

use BetterTransbank\SDK\Soap\TransbankSoapClient;

/**
 * Interface CommerceSoapClientFactory.
 */
interface CommerceSoapClientFactory
{
    /**
     * Creates a client for Webpay Commerce service.
     *
     * @return TransbankSoapClient
     */
    public function forWebpayCommerce(): TransbankSoapClient;
}
