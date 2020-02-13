<?php

declare(strict_types=1);

/*
 * This file is part of the BetterTransbank\SDK project.
 * (c) Matías Navarro-Carter <mnavarrocarter@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BetterTransbank\SDK\Services\WebpayCommerce;

/**
 * Interface WebpayCommerceService.
 *
 * The WebpayCommerceService offers some services for commerces, like nullifying
 * transactions or capture them.
 */
interface WebpayCommerceService
{
    /**
     * @param NullifyOrder $order
     *
     * @return NullifyResult
     */
    public function nullify(NullifyOrder $order): NullifyResult;

    /**
     * @param CaptureOrder $order
     *
     * @return CaptureResult
     */
    public function capture(CaptureOrder $order): CaptureResult;
}
