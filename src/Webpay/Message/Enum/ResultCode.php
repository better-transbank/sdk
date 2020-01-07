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
 * Class ResultCode.
 */
final class ResultCode
{
    public const APPROVED = 0;
    public const REJECTED = -1;
    public const MUST_RETRY = -2;
    public const ERROR = -3;
    public const REJECTED_V2 = -4;
    public const REJECTED_FOR_RATE_ERROR = -5;
    public const EXCEEDS_MONTHLY_QUOTA = -6;
    public const EXCEEDS_DAILY_LIMIT = -7;
    public const NOT_AUTHORIZED = -8;

    /**
     * Mapa de constantes a mensajes legibles.
     *
     * @var array
     */
    private static $codeToHumanReadableMap = [
        self::APPROVED => 'Transacción aprobada',
        self::REJECTED => 'Transacción rechazada',
        self::MUST_RETRY => 'Debe reintentar',
        self::ERROR => 'Error de transacción',
        self::REJECTED_V2 => 'Transacción rechazada',
        self::REJECTED_FOR_RATE_ERROR => 'Rechazado por error de tasa',
        self::EXCEEDS_MONTHLY_QUOTA => 'Rechazado por exceso de cupo mensual',
        self::EXCEEDS_DAILY_LIMIT => 'Rechazado por exceso de límite diario',
        self::NOT_AUTHORIZED => 'Rubro no autorizado',
    ];

    /**
     * @param int $code
     *
     * @return string
     *
     * @throws RuntimeException if the code is invalid
     */
    public static function getHumanReadableMessageFor(int $code): string
    {
        if (array_key_exists($code, self::$codeToHumanReadableMap)) {
            return self::$codeToHumanReadableMap[$code];
        }
        throw new RuntimeException('Invalid code provided');
    }

    /**
     * @param int $code
     *
     * @return bool
     */
    public static function isError(int $code): bool
    {
        return self::APPROVED !== $code;
    }
}
