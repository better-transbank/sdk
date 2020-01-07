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
use BetterTransbank\SDK\Webpay\Message\TransactionResult;
use BetterTransbank\SDK\Webpay\WebpayClient;
use Psr\EventDispatcher\EventDispatcherInterface;

/**
 * Class Psr14WebpayClient.
 */
final class Psr14WebpayClient implements WebpayClient
{
    /**
     * @var WebpayClient
     */
    private $client;
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * Psr14WebpayClient constructor.
     *
     * @param WebpayClient             $client
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(WebpayClient $client, EventDispatcherInterface $dispatcher)
    {
        $this->client = $client;
        $this->dispatcher = $dispatcher;
    }

    /**
     * {@inheritdoc}
     */
    public function startTransaction(Transaction $transaction): StartTransactionResponse
    {
        $this->dispatcher->dispatch(new PreStartTransactionEvent($transaction));
        $response = $this->client->startTransaction($transaction);
        $this->dispatcher->dispatch(new StartTransactionResponseEvent($transaction, $response));

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function getTransactionResult(string $transactionToken): TransactionResult
    {
        $this->dispatcher->dispatch(new PreTransactionResultEvent($transactionToken));
        $result = $this->client->getTransactionResult($transactionToken);
        $this->dispatcher->dispatch(new TransactionResultEvent($result, $transactionToken));

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function confirmTransaction(string $transactionToken): void
    {
        $this->dispatcher->dispatch(new PreConfirmTransactionEvent($transactionToken));
        $this->client->confirmTransaction($transactionToken);
        $this->dispatcher->dispatch(new TransactionConfirmedEvent($transactionToken));
    }
}
