<?php
declare(strict_types=1);


namespace BetterTransbank\SDK\Soap\WSSE;

use BetterTransbank\SDK\Soap\Certificate;
use BetterTransbank\SDK\Soap\PrivateKey;
use DOMElement;
use RuntimeException;

/**
 * Class RequestDocument
 *
 * This class encapsulates an XML with the purposes of implementing WSSE Security in it.
 *
 * It provides an api that makes trivial to execute the signature correctly.
 *
 * @package BetterTransbank\SDK\Soap
 *
 * @internal
 */
final class RequestDocument extends BaseWSSEDocument
{
    /**
     * @var DOMElement
     */
    private $signature;

    /**
     * RequestDocument constructor.
     * @param string $xml
     */
    public function __construct(string $xml)
    {
        parent::__construct($xml);
        // Ensure Header element
        try {
            $headerNode = $this->queryElement('/SOAP-ENV:Envelope/SOAP-ENV:Header');
        } catch (NodeNotFound $exception) {
            $headerNode = $this->documentElement->insertBefore(
                $this->createElementNS(self::SOAP_NS, 'SOAP-ENV:Header'), $this->getBodyNode()
            );
        }
        // Add security element
        $secNode = $this->createElementNS(self::WSSE_NS, 'wsse:Security');
        $secNode->setAttribute('SOAP-ENV:mustUnderstand', '1');
        $headerNode->appendChild($secNode);
        // Add signature element
        $this->signature = $this->createElementNS(self::DS_NS, 'ds:Signature');
        $secNode->appendChild($this->signature);
    }

    /**
     * Marks a node with a wsu:Id attribute
     *
     * @param string $id
     * @param string $xPathNode By default is the body node
     */
    public function markNodeWithWsuId(string $id, string $xPathNode = '/SOAP-ENV:Envelope/SOAP-ENV:Body'): void
    {
        $nodeToMark = $this->queryElement($xPathNode);
        $nodeToMark->setAttributeNS(self::WSU_NS, 'wsu:Id', $id);
    }

    /**
     * Attaches a reference to ds:SignedInfo for an specific id
     *
     * This method looks for the referenced node id and then attaches the reference,
     * with the node digest.
     *
     * @param string $id
     */
    public function addSignedInfoReference(string $id): void
    {
        // Ensure there is a signed info element
        try {
            $signedInfoEl = $this->queryElement('/SOAP-ENV:Envelope/SOAP-ENV:Header/wsse:Security/ds:Signature/ds:SignedInfo');
        } catch (NodeNotFound $exception) {
            $signedInfoEl = $this->createElementNS(self::DS_NS, 'ds:SignedInfo');
            $this->signature->appendChild($signedInfoEl);
            // Add the algorithms part
            $canonMethodEl = $this->createElementNS(self::DS_NS, 'ds:CanonicalizationMethod');
            $canonMethodEl->setAttribute('Algorithm', 'http://www.w3.org/2001/10/xml-exc-c14n#');
            $signedInfoEl->appendChild($canonMethodEl);
            $signMethodEl = $this->createElementNS(self::DS_NS, 'ds:SignatureMethod');
            $signMethodEl->setAttribute('Algorithm', 'http://www.w3.org/2000/09/xmldsig#rsa-sha1');
            $signedInfoEl->appendChild($signMethodEl);
        }
        // Compute the digest of the referenced node
        $refNode = $this->queryElement("//*[(@wsu:Id='{$id}')]");
        $canon = $refNode->C14N(true, false);
        $digest = base64_encode(sha1($canon, true));

        // Add the reference node
        $reference = $this->createElementNS(self::DS_NS, 'ds:Reference');
        $signedInfoEl->appendChild($reference);
        $reference->setAttribute('URI', "#{$id}");

        $transforms = $this->createElementNS(self::DS_NS, 'ds:Transforms');
        $reference->appendChild($transforms);

        $transform = $this->createElementNS(self::DS_NS, 'ds:Transform');
        $transforms->appendChild($transform);
        $transform->setAttribute('Algorithm', 'http://www.w3.org/2001/10/xml-exc-c14n#');

        $method = $this->createElementNS(self::DS_NS, 'ds:DigestMethod');
        $reference->appendChild($method);
        $method->setAttribute('Algorithm', 'http://www.w3.org/2000/09/xmldsig#sha1');

        $reference->appendChild($this->createElementNS(self::DS_NS, 'ds:DigestValue', $digest));
    }

    /**
     * Signs the document with a private key.
     *
     * @param PrivateKey $privateKey
     */
    public function signDocument(PrivateKey $privateKey): void
    {
        // Query and canonize the SignedInfo node
        try {
            $signedInfo = $this->queryElement('/SOAP-ENV:Envelope/SOAP-ENV:Header/wsse:Security/ds:Signature/ds:SignedInfo');
        } catch (NodeNotFound $exception) {
            throw new RuntimeException('Cannot sign document. There is not a ds:SignedInfo node. You must add references first.');
        }

        $canon = $signedInfo->C14N(true, false);

        // Sign it and attach the signature
        $signature = base64_encode($privateKey->sign($canon));
        $signatureEl = $this->createElementNS(self::DS_NS, 'ds:SignatureValue', $signature);
        $this->signature->appendChild($signatureEl);
    }

    /**
     * Adds the public certificate info to the document
     *
     * @param Certificate $certificate
     */
    public function addKeyInfo(Certificate $certificate): void
    {
        try {
            $this->queryElement('/SOAP-ENV:Envelope/SOAP-ENV:Header/wsse:Security/ds:Signature/ds:SignatureValue');
        } catch (NodeNotFound $exception) {
            throw new RuntimeException('Must sign the document first before adding the public cert info');
        }
        $keyInfo = $this->createElementNS(self::DS_NS, 'ds:KeyInfo');
        $this->signature->appendChild($keyInfo);

        $secTokenRef = $this->createElement('wsse:SecurityTokenReference');
        $keyInfo->appendChild($secTokenRef);

        $x509Data = $this->createElementNS(self::DS_NS, 'ds:X509Data');
        $secTokenRef->appendChild($x509Data);

        $x509IssuerSerial = $this->createElementNS(self::DS_NS, 'ds:X509IssuerSerial');
        $x509Data->appendChild($x509IssuerSerial);
        $x509IssuerSerial->appendChild($this->createElementNS(self::DS_NS, 'ds:X509IssuerName', $certificate->getIssuerName()));
        $x509IssuerSerial->appendChild($this->createElementNS(self::DS_NS, 'ds:X509SerialNumber', $certificate->getSerialNumber()));
    }
}