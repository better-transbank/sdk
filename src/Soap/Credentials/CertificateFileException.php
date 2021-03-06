<?php

declare(strict_types=1);

/*
 * This file is part of the BetterTransbank\SDK project.
 * (c) Matías Navarro-Carter <mnavarrocarter@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BetterTransbank\SDK\Soap\Credentials;

use RuntimeException;

/**
 * Class CertificateFileException.
 */
class CertificateFileException extends RuntimeException
{
    /**
     * CertificateFileException constructor.
     *
     * @param string $filename
     */
    public function __construct(string $filename)
    {
        parent::__construct(sprintf(
            'Certificate file "%s" does not exist or is not readable',
            $filename
        ));
    }
}
