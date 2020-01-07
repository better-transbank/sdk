<?php

declare(strict_types=1);

/*
 * This file is part of the BetterTransbank\SDK project.
 * (c) Matías Navarro-Carter <mnavarrocarter@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BetterTransbank\SDK\Webpay\Psr14;

/**
 * Class PreConfirmTransactionEvent.
 */
final class PreConfirmTransactionEvent
{
    /**
     * @var string
     */
    private $transactionToken;

    /**
     * PreConfirmTransactionEvent constructor.
     *
     * @param string $transactionToken
     */
    public function __construct(string $transactionToken)
    {
        $this->transactionToken = $transactionToken;
    }

    /**
     * @return string
     */
    public function getTransactionToken(): string
    {
        return $this->transactionToken;
    }
}
