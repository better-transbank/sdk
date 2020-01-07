<?php

declare(strict_types=1);

/*
 * This file is part of the BetterTransbank\SDK project.
 * (c) Matías Navarro-Carter <mnavarrocarter@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BetterTransbank\SDK\Webpay\Psr14;

use BetterTransbank\SDK\Webpay\Message\StartTransactionResponse;
use BetterTransbank\SDK\Webpay\Message\Transaction;

/**
 * Class StartTransactionResponseEvent.
 */
final class StartTransactionResponseEvent
{
    /**
     * @var StartTransactionResponse
     */
    private $response;
    /**
     * @var Transaction
     */
    private $transaction;

    /**
     * StartTransactionResponseEvent constructor.
     *
     * @param Transaction              $transaction
     * @param StartTransactionResponse $response
     */
    public function __construct(Transaction $transaction, StartTransactionResponse $response)
    {
        $this->response = $response;
        $this->transaction = $transaction;
    }

    /**
     * @return StartTransactionResponse
     */
    public function getResponse(): StartTransactionResponse
    {
        return $this->response;
    }

    /**
     * @return Transaction
     */
    public function getTransaction(): Transaction
    {
        return $this->transaction;
    }
}
