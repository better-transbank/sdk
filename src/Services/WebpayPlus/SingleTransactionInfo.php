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
 * Class SingleTransactionInfo.
 */
class SingleTransactionInfo extends TransactionInfo
{
    /**
     * @var PaymentInfo
     */
    protected $payment;

    /**
     * {@inheritdoc}
     *
     * @param PaymentInfo ...$payments
     */
    public function __construct(string $orderId, CardInfo $card, \DateTimeInterface $accountedAt, \DateTimeInterface $processedAt, string $vci, string $token, string $redirectionUrl, PaymentInfo $payment)
    {
        parent::__construct($orderId, $card, $accountedAt, $processedAt, $vci, $token, $redirectionUrl);
        $this->payment = $payment;
    }

    /**
     * @return PaymentInfo
     */
    public function getPayment(): PaymentInfo
    {
        return $this->payment;
    }
}
