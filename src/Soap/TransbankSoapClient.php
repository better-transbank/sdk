<?php

declare(strict_types=1);

/*
 * This file is part of the BetterTransbank\SDK project.
 * (c) Matías Navarro-Carter <mnavarrocarter@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BetterTransbank\SDK\Soap;

use DOMDocument;
use DOMElement;
use DOMXPath;
use Exception;
use RuntimeException;
use SoapClient;
use SoapFault;

/**
 * Class TransbankSoapClient.
 */
class TransbankSoapClient extends SoapClient
{
    private const SOAP_NS = 'http://schemas.xmlsoap.org/soap/envelope/';
    private const WSU_NS = 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd';
    private const WSSE_NS = 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd';
    private const DS_NS = 'http://www.w3.org/2000/09/xmldsig#';

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
        /** @noinspection PhpUnhandledExceptionInspection */
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
     * @return string|void
     *
     * @throws Exception
     */
    public function __doRequest($request, $location, $action, $version, $one_way = 0)
    {
        $document = new DOMDocument();
        $document->loadXML($request);
        $this->signXmlDocument($document);
        $response = parent::__doRequest($document->saveXML(), $location, $action, $version, $one_way);

        return $response;
    }

    /**
     * @param DOMDocument $dom
     *
     * @return void
     *
     * @throws Exception
     */
    protected function signXmlDocument(DOMDocument $dom): void
    {
        $xp = new DOMXPath($dom);
        $xp->registerNamespace('SOAP-ENV', self::SOAP_NS);

        $refId = $this->uuid();

        // Mark body node with ID for signing
        $bodyNode = $xp->query('/SOAP-ENV:Envelope/SOAP-ENV:Body')->item(0);
        $bodyNode->setAttributeNS(self::WSU_NS, 'wsu:Id', $refId);

        // find or create SoapHeader node
        $headerNode = $xp->query('/SOAP-ENV:Envelope/SOAP-ENV:Header')->item(0);
        if (!$headerNode) {
            $headerNode = $dom->documentElement->insertBefore($dom->createElementNS(self::SOAP_NS, 'SOAP-ENV:Header'), $bodyNode);
        }

        // Prepare security element
        $secNode = $dom->createElementNS(self::WSSE_NS, 'wsse:Security');
        $secNode->setAttribute('SOAP-ENV:mustUnderstand', '1');
        $headerNode->appendChild($secNode);

        // Add signature node
        $signNode = $secNode->appendChild($dom->createElementNS(self::DS_NS, 'ds:Signature'));

        // Add signature info to security element
        $signedInfo = $signNode->appendChild($this->buildSignedInfo($dom, $refId));

        // Now, we actually sign it
        $signature = $this->credentials->privateKey()->sign($signedInfo->C14N(true, false));
        $signNode->appendChild($dom->createElementNS(self::DS_NS, 'ds:SignatureValue', base64_encode($signature)));

        // Add key info to security element
        $signNode->appendChild($this->buildKeyInfo($dom));
    }

    /**
     * @param DOMDocument $dom
     * @param string      $id
     *
     * @return DOMElement
     */
    private function buildSignedInfo(DOMDocument $dom, string $id): DOMElement
    {
        $xp = new DOMXPath($dom);
        $xp->registerNamespace('SOAP-ENV', self::SOAP_NS);
        $xp->registerNamespace('wsu', self::WSU_NS);
        $xp->registerNamespace('wsse', self::WSSE_NS);
        $xp->registerNamespace('ds', self::DS_NS);

        $signedInfo = $dom->createElementNS(self::DS_NS, 'ds:SignedInfo');

        // canonization algorithm
        $canonizationMethodNode = $signedInfo->appendChild($dom->createElementNS(self::DS_NS, 'ds:CanonicalizationMethod'));
        $canonizationMethodNode->setAttribute('Algorithm', 'http://www.w3.org/2001/10/xml-exc-c14n#');

        // signature algorithm
        $signatureMethodNode = $signedInfo->appendChild($dom->createElementNS(self::DS_NS, 'ds:SignatureMethod'));
        $signatureMethodNode->setAttribute('Algorithm', 'http://www.w3.org/2000/09/xmldsig#rsa-sha1');

        // Find the node id marked to be signed
        $nodes = $xp->query("//*[(@wsu:Id='{$id}')]");
        if (0 === $nodes->length) {
            throw new RuntimeException('No Id for signature');
        }

        // We canonize the node using C14N standard
        $canonized = $nodes->item(0)->C14N(true, false);

        // create node Reference
        $reference = $signedInfo->appendChild($dom->createElementNS(self::DS_NS, 'ds:Reference'));
        $reference->setAttribute('URI', "#{$id}");
        $transforms = $reference->appendChild($dom->createElementNS(self::DS_NS, 'ds:Transforms'));
        $transform = $transforms->appendChild($dom->createElementNS(self::DS_NS, 'ds:Transform'));
        // mark node as canonicalized
        $transform->setAttribute('Algorithm', 'http://www.w3.org/2001/10/xml-exc-c14n#');
        // and add a SHA1 digest
        $method = $reference->appendChild($dom->createElementNS(self::DS_NS, 'ds:DigestMethod'));
        $method->setAttribute('Algorithm', 'http://www.w3.org/2000/09/xmldsig#sha1');

        $digestValue = base64_encode(sha1($canonized, true));
        $reference->appendChild($dom->createElementNS(self::DS_NS, 'ds:DigestValue', $digestValue));

        return $signedInfo;
    }

    /**
     * @param DOMDocument $dom
     *
     * @return DOMElement
     */
    private function buildKeyInfo(DOMDocument $dom): DOMElement
    {
        $keyInfo = $dom->createElementNS(self::DS_NS, 'ds:KeyInfo');
        $secTokenRef = $keyInfo->appendChild($dom->createElement('wsse:SecurityTokenReference'));
        $x509Data = $secTokenRef->appendChild($dom->createElementNS(self::DS_NS, 'ds:X509Data'));
        $x509IssuerSerial = $x509Data->appendChild($dom->createElementNS(self::DS_NS, 'ds:X509IssuerSerial'));
        $x509IssuerSerial->appendChild($dom->createElementNS(self::DS_NS, 'ds:X509IssuerName', $this->credentials->publicCert()->getIssuerName()));
        $x509IssuerSerial->appendChild($dom->createElementNS(self::DS_NS, 'ds:X509SerialNumber', $this->credentials->publicCert()->getSerialNumber()));

        return $keyInfo;
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
