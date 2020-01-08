<?php
declare(strict_types=1);

namespace BetterTransbank\SDK\Soap\WSSE;

use BetterTransbank\SDK\Soap\Certificate;
use RuntimeException;

/**
 * Class ResponseDocument
 *
 * This class encapsulates an XML with the purposes of implementing WSSE Security validation.
 *
 * @package BetterTransbank\SDK\Soap
 * @internal
 */
final class ResponseDocument extends BaseWSSEDocument
{
    /**
     * Verifies the signature of an XML response
     *
     * @param Certificate $certificate
     */
    public function verifySignature(Certificate $certificate): void
    {
        $this->ensureIsTransbankCertificate($certificate);
        $this->compareReferenceDigestsToNodes();
        $this->ensureSignature($certificate);
    }

    /**
     * @param Certificate $certificate
     */
    private function ensureIsTransbankCertificate(Certificate $certificate): void
    {
        $base = '/soap:Envelope/soap:Header/wsse:Security/ds:Signature/ds:KeyInfo/wsse:SecurityTokenReference/ds:X509Data/ds:X509IssuerSerial';
        $serialNumberEl = $this->queryElement($base . '/ds:X509SerialNumber');

        if ($serialNumberEl->nodeValue !== $certificate->getSerialNumber()) {
            throw new InvalidResponseSignature('Transbank certificate serial number does not match');
        }
    }

    private function compareReferenceDigestsToNodes(): void
    {
        $elements = $this->queryElements('/soapenv:Envelope/soapenv:Header/wsse:Security/ds:Signature/ds:SignedInfo/ds:Reference');
        foreach ($elements as $element) {
            $id = trim($element->getAttribute('URI'), '#');

            $referencedNode = $this->queryElement("//*[(@wsu:Id='{$id}')]");
            $canon = $referencedNode->C14N(true, false);
            $replicatedDigest = base64_encode(sha1($canon, true));

            if ($replicatedDigest !== $element->lastChild->nodeValue) {
                throw new InvalidResponseSignature(sprintf(
                    'Calculated digest "%s" for node of id %s does not match the value in document "%s"',
                    $replicatedDigest,
                    $id,
                    $element->lastChild->nodeValue
                ));
            }
        }
    }

    /**
     * @param Certificate $certificate
     */
    private function ensureSignature(Certificate $certificate): void
    {
        $signedInfoEl = $this->queryElement('/soap:Envelope/soap:Header/wsse:Security/ds:Signature/ds:SignedInfo');
        $signatureValueEl = $this->queryElement('/soap:Envelope/soap:Header/wsse:Security/ds:Signature/ds:SignatureValue');

        $canon = $signedInfoEl->C14N(true, false, null, ['soap']);
        $signature = base64_decode(trim($signatureValueEl->nodeValue));
        try {
            $certificate->verifySignature($canon, $signature);
        } catch (RuntimeException $exception) {
            throw new InvalidResponseSignature($exception->getMessage());
        }
    }
}