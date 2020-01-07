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
 * Class PrivateKey.
 */
class PrivateKey
{
    use ReadableFileTrait;

    /**
     * @var string
     */
    private $privateKeyFile;

    /**
     * PrivateKey constructor.
     *
     * @param string $privateKeyFile
     */
    public function __construct(string $privateKeyFile)
    {
        $this->privateKeyFile = $this->ensureReadableFile($privateKeyFile);
    }

    /**
     * @param string $data
     * @param int    $algorithm
     *
     * @return string
     */
    public function sign(string $data, int $algorithm = OPENSSL_ALGO_SHA1): string
    {
        $key = openssl_pkey_get_private(file_get_contents($this->privateKeyFile));
        if (!openssl_sign($data, $signature, $key, $algorithm)) {
            throw new RuntimeException('Signature failed');
        }
        openssl_free_key($key);

        return $signature;
    }
}
