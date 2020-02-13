<?php

declare(strict_types=1);

/*
 * This file is part of the BetterTransbank\SDK project.
 * (c) Matías Navarro-Carter <mnavarrocarter@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BetterTransbank\SDK\Soap;

/**
 * Interface TransbankSoapClient.
 */
interface TransbankSoapClient
{
    /**
     * Executes a SOAP Request.
     *
     * @param string $method
     * @param array  $payload
     *
     * @return array
     *
     * @throws ClientException
     */
    public function request(string $method, array $payload): array;

    /**
     * @return string
     */
    public function getCommerceCode(): string;
}
