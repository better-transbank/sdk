<?php

declare(strict_types=1);

/*
 * This file is part of the BetterTransbank\SDK project.
 * (c) Matías Navarro-Carter <mnavarrocarter@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BetterTransbank\SDK\Webpay\Message\Enum;

use RuntimeException;

/**
 * Class AuthResult.
 *
 * Códigos de resultado de autenticación 3DSecure del tarjetahabiente.
 */
final class AuthResult
{
    public const SUCCESS = 'TSY';
    public const FAILED = 'TSN';
    public const TIMED_OUT = 'TO';
    public const ABORTED = 'ABO';
    public const INTERNAL_ERROR = 'U3';
    public const NOT_IMPLEMENTED = 'NP';
    public const FAILED_FOREIGN = 'ACS2';
    public const ATTEMPT = 'A';
    public const INVALID = 'INV';
    public const OPERATIONAL_ERROR = 'EOP';

    /**
     * @var array<string,string>
     */
    private static $codeToHumanReadableMap = [
        self::SUCCESS => 'Autenticación exitosa',
        self::FAILED => 'Autenticación fallida',
        self::TIMED_OUT => 'Tiempo máximo excedido para autenticación',
        self::ABORTED => 'Autenticación abortada por tarjetahabiente',
        self::INTERNAL_ERROR => 'Error interno de autenticación',
        self::NOT_IMPLEMENTED => 'Tarjeta extranjera no forma parte de 3DSecure',
        self::FAILED_FOREIGN => 'Autenticación de tarjeta extranjera fallida',
        self::ATTEMPT => 'Intento de autenticación',
        self::INVALID => 'Autenticación inválida',
        self::OPERATIONAL_ERROR => 'Error operacional',
    ];

    /**
     * @param string $code
     *
     * @return string
     *
     * @throws RuntimeException if the code is invalid
     */
    public static function getHumanReadableMessageFor(string $code): string
    {
        if (array_key_exists($code, self::$codeToHumanReadableMap)) {
            return self::$codeToHumanReadableMap[$code];
        }
        throw new RuntimeException('Invalid code provided');
    }
}
