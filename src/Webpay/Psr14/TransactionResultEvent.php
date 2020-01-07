<?php

declare(strict_types=1);

/*
 * This file is part of the BetterTransbank\SDK project.
 * (c) Matías Navarro-Carter <mnavarrocarter@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BetterTransbank\SDK\Webpay\Psr14;

use BetterTransbank\SDK\Webpay\Message\TransactionResult;

/**
 * Class TransactionResultEvent.
 */
final class TransactionResultEvent
{
    /**
     * @var TransactionResult
     */
    private $result;
    /**
     * @var string
     */
    private $transactionToken;

    /**
     * TransactionResultEvent constructor.
     *
     * @param TransactionResult $result
     * @param string            $transactionToken
     */
    public function __construct(TransactionResult $result, string $transactionToken)
    {
        $this->result = $result;
        $this->transactionToken = $transactionToken;
    }

    /**
     * @return TransactionResult
     */
    public function getResult(): TransactionResult
    {
        return $this->result;
    }

    /**
     * @return string
     */
    public function getTransactionToken(): string
    {
        return $this->transactionToken;
    }
}
