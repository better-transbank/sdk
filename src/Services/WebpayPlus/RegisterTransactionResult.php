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
 * Class RegisterTransactionResult.
 */
class RegisterTransactionResult
{
    /**
     * @var string
     */
    protected $token;
    /**
     * @var string
     */
    protected $url;

    /**
     * RegisterTransactionResult constructor.
     *
     * @param string $token
     * @param string $url
     */
    public function __construct(string $token, string $url)
    {
        $this->token = $token;
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }
}
