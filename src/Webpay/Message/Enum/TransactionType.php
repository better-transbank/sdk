<?php

declare(strict_types=1);

/*
 * This file is part of the BetterTransbank\SDK project.
 * (c) Matías Navarro-Carter <mnavarrocarter@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BetterTransbank\SDK\Webpay\Message\Enum;

/**
 * Class TransactionType.
 */
final class TransactionType
{
    public const NORMAL = 'TR_NORMAL_WS';
    public const PAT_PASS = 'TR_NORMAL_WS_WPM';
    public const MALL = 'TR_MALL_WS';
}