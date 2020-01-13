<?php

declare(strict_types=1);

/*
 * This file is part of the BetterTransbank\SDK project.
 * (c) Matías Navarro-Carter <mnavarrocarter@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BetterTransbank\SDK\Soap;

use RuntimeException;

/**
 * Class Certificate.
 */
class Certificate
{
    use ReadableFileTrait;

    /**
     * @var string
     */
    private $certificate;
    /**
     * @var array
     */
    private $certData;

    /**
     * Certificate constructor.
     *
     * @param string $certificateFile
     */
    public function __construct(string $certificateFile)
    {
        $this->certificate = $this->ensureReadableAndOpen($certificateFile);
        $this->certData = $this->parseCertData();
    }

    public function getIssuerName(): string
    {
        return trim(str_replace('/', ',', $this->certData['name']), ',');
    }

    /**
     * @param string $data
     * @param string $signature
     * @param int    $algo
     */
    public function verifySignature(string $data, string $signature, int $algo = OPENSSL_ALGO_SHA1): void
    {
        $key = openssl_x509_read($this->certificate);
        $result = openssl_verify($data, $signature, $key, $algo);
        openssl_x509_free($key);
        if ($result === 0) {
            throw new RuntimeException('Invalid signature');
        }
        if ($result === -1) {
            throw new RuntimeException('Error validating signature');
        }
    }

    public function getSerialNumber(): string
    {
        return $this->certData['serialNumber'];
    }

    public function getSubjectCN(): string
    {
        return $this->certData['subject']['CN'];
    }

    public function getBinaryBase64(): string
    {
        return str_replace([
            '-----BEGIN CERTIFICATE-----',
            '-----END CERTIFICATE-----',
            "\n",
            "\r",
            ' ',
        ], '', $this->certificate);
    }

    public function isExpired(): bool
    {
        return $this->certData['validTo_time_t'] < time();
    }

    /**
     * @return array
     */
    private function parseCertData(): array
    {
        return openssl_x509_parse($this->certificate);
    }
}
