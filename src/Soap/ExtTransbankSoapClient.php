<?php

declare(strict_types=1);

/*
 * This file is part of the BetterTransbank\SDK project.
 * (c) Matías Navarro-Carter <mnavarrocarter@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BetterTransbank\SDK\Soap;

use BetterTransbank\SDK\Soap\Credentials\Certificate;
use BetterTransbank\SDK\Soap\Credentials\PrivateKey;
use BetterTransbank\SDK\Soap\WSSE\InvalidResponseSignature;
use Exception;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerTrait;
use SoapClient;
use SoapFault;

/**
 * Class ExtTransbankSoapClient.
 *
 * This SOAP Client implements the WSSE standard to both sign and verify
 * request payload to and from Transbank.
 */
final class ExtTransbankSoapClient extends SoapClient implements TransbankSoapClient, LoggerAwareInterface
{
    use LoggerAwareTrait;
    use LoggerTrait;

    /**
     * @var PrivateKey
     */
    private $privateKey;
    /**
     * @var Certificate
     */
    private $publicCert;
    /**
     * @var Certificate
     */
    private $transbankCert;

    /**
     * ExtTransbankSoapClient constructor.
     *
     * @param string      $wsdl
     * @param PrivateKey  $privateKey
     * @param Certificate $publicCert
     * @param Certificate $transbankCert
     *
     * @throws SoapFault
     */
    public function __construct(string $wsdl, PrivateKey $privateKey, Certificate $publicCert, Certificate $transbankCert)
    {
        parent::__construct($wsdl, [
            'exceptions' => true,
            'trace' => true,
        ]);
        $this->privateKey = $privateKey;
        $this->publicCert = $publicCert;
        $this->transbankCert = $transbankCert;
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
            throw $this->prepareClientException($exception);
        }
    }

    /**
     * @param string $method
     * @param array  $payload
     *
     * @return array
     *
     * @throws ClientException
     */
    public function request(string $method, array $payload): array
    {
        try {
            return $this->sdtClassToArray($this->{$method}($payload));
        } catch (ClientException $exception) {
            $this->error('Transbank request error', [
                'errorMessage' => $exception->getMessage(),
            ]);
            throw $exception;
        } catch (InvalidResponseSignature $exception) {
            $this->critical('Response signature is invalid. Possible man-in-the-middle attack.', [
                'reason' => $exception->getMessage(),
            ]);
            throw $exception;
        }
    }

    /**
     * @param \stdClass $object
     *
     * @return array
     */
    private function sdtClassToArray(\stdClass $object): array
    {
        return json_decode(json_encode($object), true);
    }

    /**
     * @return string
     */
    public function getCommerceCode(): string
    {
        return $this->publicCert->getSubjectCN();
    }

    /**
     * @param SoapFault $soapFault
     *
     * @return ClientException
     */
    protected function prepareClientException(SoapFault $soapFault): ClientException
    {
        $message = trim(str_replace(['<!--', '-->'], '', $soapFault->getMessage()));
        if (preg_match('/(\(\d{1,3}\))/', $message, $matches)) {
            $message = str_replace($matches[0], '', $message);

            return new ClientException($message, (int) trim($matches[0], '()'));
        }

        return new ClientException($message, 500);
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
        $this->debug('Signing xml', [
            'xml' => $request,
        ]);
        $signedXml = $this->signDocument($request);
        $this->debug('Sending singed xml to transbank', [
            'url' => $location,
            'xml' => $signedXml,
        ]);
        $response = parent::__doRequest($signedXml, $location, $action, $version, $one_way);
        $this->debug('Verifying xml response', [
            'xml' => $response,
        ]);
        $this->verifyDocument($response);
        $this->debug('Request performed successfully');

        return $response;
    }

    /**
     * @param string $xml
     *
     * @return string
     *
     * @throws Exception
     */
    protected function signDocument(string $xml): string
    {
        $id = $this->uuid();
        $document = new WSSE\RequestDocument($xml);
        $document->markNodeWithWsuId($id);
        $document->addSignedInfoReference($id);
        $document->signDocument($this->privateKey);
        $document->addKeyInfo($this->publicCert);

        return $document->saveXML();
    }

    /**
     * @param string $xml
     */
    protected function verifyDocument(string $xml): void
    {
        $document = new WSSE\ResponseDocument($xml);
        $document->verifySignature($this->transbankCert);
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

    /**
     * @param $level
     * @param $message
     * @param array $context
     */
    public function log($level, $message, array $context = [])
    {
        if ($this->logger !== null) {
            $this->logger->log($level, $message, $context);
        }
    }
}
