<?php

declare(strict_types=1);

/*
 * This file is part of the BetterTransbank\SDK project.
 * (c) Matías Navarro-Carter <mnavarrocarter@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BetterTransbank\SDK\Html;

use BetterTransbank\SDK\Webpay\Message\StartTransactionResponse;

/**
 * Class PaymentForm.
 */
class PaymentForm extends Template
{
    /**
     * @var StartTransactionResponse
     */
    private $response;

    /**
     * @param StartTransactionResponse $response
     *
     * @return static
     */
    public static function prepare(StartTransactionResponse $response): self
    {
        return new self($response);
    }

    /**
     * PaymentForm constructor.
     *
     * @param StartTransactionResponse $response
     */
    public function __construct(StartTransactionResponse $response)
    {
        $this->response = $response;
        parent::__construct();
    }

    public function toString(): string
    {
        return $this->render('redirect-form', [
            'rand' => bin2hex(random_bytes(8)),
            'token' => $this->response->getToken(),
            'url' => $this->response->getUrl(),
        ]);
    }
}
