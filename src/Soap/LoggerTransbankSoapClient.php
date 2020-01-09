<?php

declare(strict_types=1);

/*
 * This file is part of the BetterTransbank\SDK project.
 * (c) Matías Navarro-Carter <mnavarrocarter@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BetterTransbank\SDK\Soap;

use BetterTransbank\SDK\Soap\WSSE\InvalidResponseSignature;
use Psr\Log\LoggerInterface;

/**
 * Class LoggerTransbankSoapClient.
 */
class LoggerTransbankSoapClient extends TransbankSoapClient
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * LoggerTransbankSoapClient constructor.
     *
     * @param Credentials     $credentials
     * @param LoggerInterface $logger
     */
    public function __construct(Credentials $credentials, LoggerInterface $logger)
    {
        parent::__construct($credentials);
        $this->logger = $logger;
    }

    /**
     * @param string $function_name
     * @param array  $arguments
     *
     * @return mixed
     *
     * @throws ClientException
     */
    public function __call($function_name, $arguments)
    {
        try {
            return parent::__call($function_name, $arguments);
        } catch (ClientException $exception) {
            $this->logger->error('Transbank request error', [
                'errorMessage' => $exception->getMessage(),
            ]);
            throw $exception;
        } catch (InvalidResponseSignature $exception) {
            $this->logger->critical('Response signature is invalid. Possible man-in-the-middle attack.', [
                'reason' => $exception->getMessage(),
            ]);
            throw $exception;
        }
    }

    /**
     * @param string $request
     * @param string $location
     * @param string $action
     * @param int    $version
     * @param int    $one_way
     *
     * @return string
     * @noinspection PhpDocMissingThrowsInspection
     */
    public function __doRequest($request, $location, $action, $version, $one_way = 0): string
    {
        $this->logger->debug('Sending request to Transbank', [
            'location' => $location,
        ]);
        /** @noinspection PhpUnhandledExceptionInspection */
        $response = parent::__doRequest($request, $location, $action, $version, $one_way);
        $this->logger->debug('XML response received', [
            'responseXml' => $response,
        ]);

        return $response;
    }

    /**
     * @param string $xml
     *
     * @return string
     * @noinspection PhpDocMissingThrowsInspection
     */
    protected function signDocument(string $xml): string
    {
        /* @noinspection PhpUnhandledExceptionInspection */
        $signedXml = parent::signDocument($xml);
        $this->logger->debug('XML body has been signed', [
            'originalXml' => $xml,
            'signedXml' => $signedXml,
        ]);

        return $signedXml;
    }

    /**
     * @param string $xml
     */
    protected function verifyDocument(string $xml): void
    {
        parent::verifyDocument($xml);
        $this->logger->debug('XML response has been validated');
    }
}
