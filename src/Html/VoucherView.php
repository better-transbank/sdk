<?php

declare(strict_types=1);

/*
 * This file is part of the BetterTransbank\SDK project.
 * (c) Matías Navarro-Carter <mnavarrocarter@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BetterTransbank\SDK\Html;

use BetterTransbank\SDK\Webpay\Message\TransactionResult;

/**
 * Class VoucherView.
 */
class VoucherView extends Template
{
    /**
     * @var TransactionResult
     */
    private $result;
    /**
     * @var string
     */
    private $token;

    /**
     * @param TransactionResult $result
     * @param string            $token
     *
     * @return static
     */
    public static function prepare(TransactionResult $result, string $token): self
    {
        return new self($result, $token);
    }

    /**
     * VoucherView constructor.
     *
     * @param TransactionResult $result
     * @param string            $token
     */
    public function __construct(TransactionResult $result, string $token)
    {
        $this->result = $result;
        $this->token = $token;
        parent::__construct();
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return $this->render('redirect-form', [
            'rand' => bin2hex(random_bytes(8)),
            'url' => $this->result->getRedirectionUrl(),
            'token' => $this->token,
        ]);
    }
}
