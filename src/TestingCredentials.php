<?php

declare(strict_types=1);

/*
 * This file is part of the BetterTransbank\SDK project.
 * (c) Matías Navarro-Carter <mnavarrocarter@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BetterTransbank\SDK;

use BetterTransbank\Certificates\Webpay\Plus\Deferred;
use BetterTransbank\Certificates\Webpay\Plus\Mall;
use BetterTransbank\Certificates\Webpay\Plus\MallDeferred;
use BetterTransbank\Certificates\Webpay\Plus\Normal;
use BetterTransbank\Certificates\Webpay\Plus\PatPass;

/**
 * Class TestingCredentials.
 */
final class TestingCredentials extends Credentials
{
    /**
     * @return static
     */
    public static function forWebpayPlusNormal(): self
    {
        return new self(Normal::PRIVATE, Normal::PUBLIC, Normal::COMMERCE_CODE);
    }

    public static function forWebpayCommerce(): self
    {
        return new self(Deferred::PRIVATE, Deferred::PUBLIC, Deferred::COMMERCE_CODE);
    }

    /**
     * @return static
     */
    public static function forWebpayPlusSubscription(): self
    {
        return new self(PatPass::PRIVATE, PatPass::PUBLIC, PatPass::COMMERCE_CODE);
    }

    /**
     * @return static
     */
    public static function forWebpayPlusMultiple(): self
    {
        return new self(Mall::PRIVATE, Mall::PUBLIC, Mall::COMMERCE_CODE);
    }

    /**
     * @return static
     */
    public static function forWebpayPlusMultipleDeferred(): self
    {
        return new self(MallDeferred::PRIVATE, MallDeferred::PUBLIC, MallDeferred::COMMERCE_CODE);
    }
}
