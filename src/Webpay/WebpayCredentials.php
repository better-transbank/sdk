<?php

declare(strict_types=1);

/*
 * This file is part of the BetterTransbank\SDK project.
 * (c) Matías Navarro-Carter <mnavarrocarter@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BetterTransbank\SDK\Webpay;

use BetterTransbank\Certificates\Transbank;
use BetterTransbank\Certificates\Webpay\Plus;
use BetterTransbank\SDK\Soap\Credentials;

/**
 * Class WebpayCredentials.
 */
final class WebpayCredentials extends Credentials
{
    protected const STAGING_WSDL = 'https://webpay3gint.transbank.cl/WSWebpayTransaction/cxf/WSWebpayService?wsdl';
    protected const PRODUCTION_WSDL = 'https://webpay3g.transbank.cl/WSWebpayTransaction/cxf/WSWebpayService?wsdl';

    /**
     * @return static
     */
    public static function normalStaging(): self
    {
        return self::fromFilesPath(
            Plus\Normal::PRIVATE,
            Plus\Normal::PUBLIC,
            Transbank::STAGING,
            self::STAGING_WSDL
        );
    }

    /**
     * @return static
     */
    public static function mallStaging(): self
    {
        return self::fromFilesPath(
            Plus\Mall::PRIVATE,
            Plus\Mall::PUBLIC,
            Transbank::STAGING,
            self::STAGING_WSDL
        );
    }

    /**
     * @return static
     */
    public static function patPassStaging(): self
    {
        return self::fromFilesPath(
            Plus\PatPass::PRIVATE,
            Plus\PatPass::PUBLIC,
            Transbank::STAGING,
            self::STAGING_WSDL
        );
    }

    /**
     * @param string $privateKey
     * @param string $publicCert
     *
     * @return static
     */
    public static function production(string $privateKey, string $publicCert): self
    {
        return self::fromFilesPath(
            $privateKey,
            $publicCert,
            Transbank::PRODUCTION,
            self::PRODUCTION_WSDL
        );
    }
}
