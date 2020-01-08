<?php

declare(strict_types=1);

/*
 * This file is part of the BetterTransbank\SDK project.
 * (c) Matías Navarro-Carter <mnavarrocarter@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BetterTransbank\SDK\Webpay;

use BetterTransbank\SDK\Webpay\Message\Transaction;

/**
 * Interface WebpayTransactionFactory.
 */
interface WebpayTransactionFactory
{
    /**
     * @return Transaction
     */
    public function create(): Transaction;
}
