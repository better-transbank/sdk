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
    private $certificateFile;
    /**
     * @var array|null
     */
    private $certDataCache;

    /**
     * Certificate constructor.
     *
     * @param string $certificateFile
     */
    public function __construct(string $certificateFile)
    {
        $this->certificateFile = $this->ensureReadableFile($certificateFile);
        $this->cacheCertData();
    }

    public function getIssuerName(): string
    {
        return trim(str_replace('/', ',', $this->certDataCache['name']), ',');
    }

    public function getSerialNumber(): string
    {
        return $this->certDataCache['serialNumber'];
    }

    public function getSubjectCN(): string
    {
        return $this->certDataCache['subject']['CN'];
    }

    public function getBinaryBase64(): string
    {
        $string = file_get_contents($this->certificateFile);

        return str_replace([
            '-----BEGIN CERTIFICATE-----',
            '-----END CERTIFICATE-----',
            "\n",
            "\r",
            ' ',
        ], '', $string);
    }

    public function isExpired(): bool
    {
        return time() > $this->certDataCache['validTo_time_t'];
    }

    private function cacheCertData(): void
    {
        if (null === $this->certDataCache) {
            $this->certDataCache = openssl_x509_parse(file_get_contents($this->certificateFile));
        }
    }
}
