<?php

declare(strict_types=1);

/*
 * This file is part of the BetterTransbank\SDK project.
 * (c) Matías Navarro-Carter <mnavarrocarter@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BetterTransbank\SDK\Webpay\Exception;

use BetterTransbank\SDK\Webpay\Message\Enum\ResultCode;
use Exception;

/**
 * Class TransactionError.
 */
class TransactionError extends Exception
{
    /**
     * TransactionError constructor.
     *
     * @param int $code
     */
    public function __construct(int $code)
    {
        parent::__construct(ResultCode::getHumanReadableMessageFor($code), $code);
    }
}
