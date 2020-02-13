<?php

declare(strict_types=1);

/*
 * This file is part of the BetterTransbank\SDK project.
 * (c) Matías Navarro-Carter <mnavarrocarter@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BetterTransbank\SDK\Services\WebpayCommerce;

use BetterTransbank\SDK\Soap\TransbankSoapClient;

/**
 * Class SoapWebpayCommerceService.
 */
final class SoapWebpayCommerceService implements WebpayCommerceService
{
    /**
     * @var TransbankSoapClient
     */
    private $client;

    /**
     * SoapWebpayCommerceService constructor.
     *
     * @param TransbankSoapClient $client
     */
    public function __construct(TransbankSoapClient $client)
    {
        $this->client = $client;
    }

    /**
     * {@inheritdoc}
     */
    public function nullify(NullifyOrder $order): NullifyResult
    {
        $commerceCode = $this->client->getCommerceCode();
        $payload = [
            'nullificationInput' => [
                'commerceId' => $commerceCode,
                'buyOrder' => $order->getOrderId(),
                'authorizationCode' => $order->getAuthCode(),
                'authorizedAmount' => $order->getOriginalAmount(),
                'nullifyAmount' => $order->getAmountToNullify(),
            ],
        ];

        $data = $this->client->request('nullify', $payload)['return'];

        return new NullifyResult(
            $data['authorizationCode'],
            new \DateTimeImmutable($data['authorizationDate']),
            (int) $data['balance'],
            (int) $data['nullifiedAmount'],
            $data['token']
        );
    }

    /**
     * {@inheritdoc}
     */
    public function capture(CaptureOrder $order): CaptureResult
    {
        $commerceCode = $this->client->getCommerceCode();
        $payload = [
            'captureInput' => [
                'commerceId' => $commerceCode,
                'buyOrder' => $order->getOrderId(),
                'authorizationCode' => $order->getAuthCode(),
                'captureAmount' => $order->getAmount(),
            ],
        ];

        $data = $this->client->request('capture', $payload)['return'];

        return new CaptureResult(
            $data['authorizationCode'],
            new \DateTimeImmutable($data['authorizationDate']),
            (int) $data['capturedAmount'],
            $data['token']
        );
    }
}
