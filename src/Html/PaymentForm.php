<?php

declare(strict_types=1);

/*
 * This file is part of the BetterTransbank\SDK project.
 * (c) Matías Navarro-Carter <mnavarrocarter@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BetterTransbank\SDK\Html;

use BetterTransbank\SDK\Services\WebpayPlus\RegisterTransactionResult;

/**
 * Class PaymentForm.
 */
class PaymentForm extends Template
{
    /**
     * @var RegisterTransactionResult
     */
    private $result;

    /**
     * @param RegisterTransactionResult $result
     *
     * @return Template
     */
    public static function prepare(RegisterTransactionResult $result): Template
    {
        return new self($result);
    }

    /**
     * PaymentForm constructor.
     *
     * @param RegisterTransactionResult $result
     */
    public function __construct(RegisterTransactionResult $result)
    {
        $this->result = $result;
        parent::__construct();
    }

    public function toString(): string
    {
        try {
            return $this->render('redirect-form', [
                'rand' => bin2hex(random_bytes(8)),
                'token' => $this->result->getToken(),
                'url' => $this->result->getUrl(),
            ]);
        } catch (\Exception $e) {
            throw new \RuntimeException('Not enough entropy');
        }
    }
}
