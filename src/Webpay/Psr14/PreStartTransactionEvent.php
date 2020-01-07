<?php

declare(strict_types=1);

/*
 * This file is part of the BetterTransbank\SDK project.
 * (c) Matías Navarro-Carter <mnavarrocarter@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BetterTransbank\SDK\Webpay\Psr14;

use BetterTransbank\SDK\Webpay\Message\Transaction;

/**
 * Class PreStartTransactionEvent.
 */
final class PreStartTransactionEvent
{
    /**
     * @var Transaction
     */
    private $transaction;

    /**
     * PreStartTransactionEvent constructor.
     *
     * @param Transaction $transaction
     */
    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * @return Transaction
     */
    public function getTransaction(): Transaction
    {
        return $this->transaction;
    }
}
