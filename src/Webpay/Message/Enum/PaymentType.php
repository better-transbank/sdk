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
 * Class PaymentType.
 */
final class PaymentType
{
    public const DEBIT = 'VD';
    public const NORMAL = 'VN';
    public const INSTALMENT = 'VC';
    public const THREE_INSTALMENTS_NO_RATE = 'SI';
    public const TWO_INSTALMENTS_NO_RATE = 'S2';
    public const INSTALMENTS_NO_RATE = 'NC';
    public const PREPAID = 'VP';
}
