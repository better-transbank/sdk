<?php

declare(strict_types=1);

/*
 * This file is part of the BetterTransbank\SDK project.
 * (c) Matías Navarro-Carter <mnavarrocarter@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BetterTransbank\SDK\Webpay;

use BetterTransbank\SDK\Soap\Credentials;

/**
 * Class WebpayCredentials.
 */
final class WebpayCredentials extends Credentials
{
    protected const STAGING_WSDL = 'https://webpay3gint.transbank.cl/WSWebpayTransaction/cxf/WSWebpayService?wsdl';
    protected const PRODUCTION_WSDL = 'https://webpay3g.transbank.cl/WSWebpayTransaction/cxf/WSWebpayService?wsdl';

    protected const NORMAL_STAGING_KEY = __DIR__.'/../../cert/webpay-plus-integration/597020000540.key';
    protected const NORMAL_STAGING_CERT = __DIR__.'/../../cert/webpay-plus-integration/597020000540.crt';

    protected const MALL_STAGING_KEY = __DIR__.'/../../cert/webpay-plus-mall-integration/597044444401.key';
    protected const MALL_STAGING_CERT = __DIR__.'/../../cert/webpay-plus-mall-integration/597044444401.crt';

    protected const PATPASS_STAGING_KEY = __DIR__.'/../../cert/webpay-patpass-integration/597044444432.key';
    protected const PATPASS_STAGING_CERT = __DIR__.'/../../cert/webpay-patpass-integration/597044444432.crt';

    /**
     * @return static
     */
    public static function normalStaging(): self
    {
        return self::fromFilesPath(
            self::NORMAL_STAGING_KEY,
            self::NORMAL_STAGING_CERT,
            self::STAGING_TRANSBANK_CERT,
            self::STAGING_WSDL
        );
    }

    /**
     * @return static
     */
    public static function mallStaging(): self
    {
        return self::fromFilesPath(
            self::MALL_STAGING_KEY,
            self::MALL_STAGING_CERT,
            self::STAGING_TRANSBANK_CERT,
            self::STAGING_WSDL
        );
    }

    /**
     * @return static
     */
    public static function patPassStaging(): self
    {
        return self::fromFilesPath(
            self::PATPASS_STAGING_KEY,
            self::PATPASS_STAGING_CERT,
            self::STAGING_TRANSBANK_CERT,
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
            self::PRODUCTION_TRANSBANK_CERT,
            self::PRODUCTION_WSDL
        );
    }
}
