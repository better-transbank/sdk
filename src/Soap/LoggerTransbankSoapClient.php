<?php
declare(strict_types=1);

namespace BetterTransbank\SDK\Soap;

use DOMDocument;
use Psr\Log\LoggerInterface;

/**
 * Class LoggerTransbankSoapClient
 * @package BetterTransbank\SDK\Soap
 */
class LoggerTransbankSoapClient extends TransbankSoapClient
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * LoggerTransbankSoapClient constructor.
     * @param Credentials $credentials
     * @param LoggerInterface $logger
     */
    public function __construct(Credentials $credentials, LoggerInterface $logger)
    {
        parent::__construct($credentials);
        $this->logger = $logger;
    }

    /**
     * @param string $function_name
     * @param array $arguments
     * @return mixed
     * @throws ClientException
     */
    public function __call($function_name, $arguments)
    {
        try {
            return parent::__call($function_name, $arguments);
        } catch (ClientException $exception) {
            $this->logger->error('Transbank request error', [
                'errorMessage' => $exception->getMessage()
            ]);
            throw $exception;
        }
    }

    public function __doRequest($request, $location, $action, $version, $one_way = 0)
    {
        $this->logger->debug('Sending request to Transbank', [
            'location' => $location
        ]);
        $response = parent::__doRequest($request, $location, $action, $version, $one_way);
        $this->logger->debug('XML response received', [
            'responseXml' => $response
        ]);
        return $response;
    }

    /**
     * @param DOMDocument $dom
     * @return void
     * @noinspection PhpDocMissingThrowsInspection
     */
    protected function signXmlDocument(DOMDocument $dom): void
    {
        $originalXml = $dom->saveXML();
        /** @noinspection PhpUnhandledExceptionInspection */
        parent::signXmlDocument($dom);
        $signedXml = $dom->saveXML();
        $this->logger->debug('XML body has been signed', [
            'originalXml' => $originalXml,
            'signedXml' => $signedXml
        ]);
    }
}