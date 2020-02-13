<?php

declare(strict_types=1);

/*
 * This file is part of the BetterTransbank\SDK project.
 * (c) Matías Navarro-Carter <mnavarrocarter@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BetterTransbank\SDK\Services\WebpayPlus;

/**
 * Class MultipleTransactionInfo.
 */
final class MultipleTransactionInfo extends TransactionInfo implements \IteratorAggregate
{
    /**
     * @var PaymentInfo[]
     */
    protected $payments;

    /**
     * {@inheritdoc}
     *
     * @param PaymentInfo ...$payments
     */
    public function __construct(string $orderId, CardInfo $card, \DateTimeInterface $accountedAt, \DateTimeInterface $processedAt, string $vci, string $token, string $redirectionUrl, PaymentInfo ...$payments)
    {
        parent::__construct($orderId, $card, $accountedAt, $processedAt, $vci, $token, $redirectionUrl);
        $this->payments = $payments;
    }

    /**
     * @return PaymentInfo[]
     */
    public function getPayments(): array
    {
        return $this->payments;
    }

    public function getPaymentsCount(): int
    {
        return count($this->payments);
    }

    /**
     * @return bool
     */
    public function areAllSuccessful(): bool
    {
        foreach ($this->payments as $payment) {
            if (!$payment->wasSuccessful()) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return \Iterator
     */
    public function getIterator(): \Iterator
    {
        return new \ArrayIterator($this->payments);
    }

    /**
     * @return int
     */
    public function getTotalAmount(): int
    {
        return (int) array_reduce($this->payments, static function (int $sum, PaymentInfo $payment): int {
            return $sum + $payment->getAmount();
        }, 0);
    }
}
