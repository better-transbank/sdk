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
 * Class UrlReadyWebpayTransactionFactory.
 */
final class UrlReadyWebpayTransactionFactory implements WebpayTransactionFactory
{
    /**
     * @var string
     */
    private $returnUrl;
    /**
     * @var string
     */
    private $finalUrl;

    /**
     * UrlReadyWebpayTransactionFactory constructor.
     *
     * @param string $returnUrl
     * @param string $finalUrl
     */
    public function __construct(string $returnUrl, string $finalUrl)
    {
        $this->returnUrl = $returnUrl;
        $this->finalUrl = $finalUrl;
    }

    /**
     * @return Transaction
     */
    public function create(): Transaction
    {
        return Transaction::create(
            $this->finalUrl,
            $this->returnUrl
        );
    }
}
