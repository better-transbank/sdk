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
 * Trait ReadableFileTrait.
 *
 * @internal
 */
trait ReadableFileTrait
{
    /**
     * @param string $filename
     *
     * @return string
     *
     * @throws CertificateFileException
     */
    private function ensureReadableAndOpen(string $filename): string
    {
        $filename = realpath($filename);
        if (!is_file($filename) && !is_readable($filename)) {
            throw new CertificateFileException($filename);
        }

        return file_get_contents($filename);
    }
}
