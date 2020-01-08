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
        return time() > $this->certData['validTo_time_t'];
    }

    /**
     * @return array
     */
    private function parseCertData(): array
    {
        return openssl_x509_parse($this->certificate);
    }
}
