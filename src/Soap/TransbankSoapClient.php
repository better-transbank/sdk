<?php

declare(strict_types=1);

/*
 * This file is part of the BetterTransbank\SDK project.
 * (c) Matías Navarro-Carter <mnavarrocarter@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BetterTransbank\SDK\Soap;

use Exception;
use SoapClient;
use SoapFault;

/**
 * Class TransbankSoapClient.
 */
class TransbankSoapClient extends SoapClient
{
    /**
     * @var Credentials
     */
    private $credentials;

    /**
     * TransbankSoapClient constructor.
     *
     * @param Credentials $credentials
     * @noinspection PhpDocMissingThrowsInspection
     */
    public function __construct(Credentials $credentials)
    {
        /* @noinspection PhpUnhandledExceptionInspection */
        parent::__construct($credentials->wsdl(), [
            'exceptions' => true,
            'trace' => true,
        ]);
        $this->credentials = $credentials;
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
        } catch (SoapFault $exception) {
            $message = trim(str_replace(['<!--', '-->'], '', $exception->faultstring));
            if (preg_match('/(\(\d{3}\))/', $message, $matches)) {
                $message = str_replace($matches[1], '', $message);
                throw new ClientException($message, (int) trim($matches[1], '()'));
            }
            throw new ClientException($message, 500);
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
     *
     * @throws Exception
     */
    public function __doRequest($request, $location, $action, $version, $one_way = 0): string
    {
        $signedXml = $this->signDocument($request);
        $response = parent::__doRequest($signedXml, $location, $action, $version, $one_way);
        $this->verifyDocument($response);
        return $response;
    }

    /**
     * @param string $xml
     * @return string
     * @throws Exception
     */
    protected function signDocument(string $xml): string
    {
        $id = $this->uuid();
        $document = new WSSE\RequestDocument($xml);
        $document->markNodeWithWsuId($id);
        $document->addSignedInfoReference($id);
        $document->signDocument($this->credentials->privateKey());
        $document->addKeyInfo($this->credentials->publicCert());
        return $document->saveXML();
    }

    /**
     * @param string $xml
     */
    protected function verifyDocument(string $xml): void
    {
        $document = new WSSE\ResponseDocument($xml);
        $document->verifySignature($this->credentials->transbankCert());
    }

    /**
     * @param string $prefix
     *
     * @return string
     *
     * @throws Exception
     */
    private function uuid(string $prefix = 'pfx'): string
    {
        $data = random_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
        return $prefix.vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
}
