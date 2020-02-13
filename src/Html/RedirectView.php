<?php

declare(strict_types=1);

/*
 * This file is part of the BetterTransbank\SDK project.
 * (c) Matías Navarro-Carter <mnavarrocarter@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BetterTransbank\SDK\Html;

use BetterTransbank\SDK\Services\WebpayPlus\TransactionInfo;

/**
 * Class VoucherView.
 */
class RedirectView extends Template
{
    /**
     * @var TransactionInfo
     */
    private $info;

    /**
     * @param TransactionInfo $info
     *
     * @return Template
     */
    public static function prepare(TransactionInfo $info): Template
    {
        return new self($info);
    }

    /**
     * VoucherView constructor.
     *
     * @param TransactionInfo $info
     */
    public function __construct(TransactionInfo $info)
    {
        $this->info = $info;
        parent::__construct();
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        try {
            return $this->render('redirect-form', [
                'rand' => bin2hex(random_bytes(8)),
                'url' => $this->info->getRedirectionUrl(),
                'token' => $this->info->getToken(),
            ]);
        } catch (\Exception $e) {
            throw new \RuntimeException('Not enough entropy');
        }
    }
}
