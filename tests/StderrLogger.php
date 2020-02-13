<?php
declare(strict_types=1);


namespace BetterTransbank\SDK\Tests;

use Psr\Log\AbstractLogger;

/**
 * Class StdoutLogger
 * @package BetterTransbank\SDK\Tests
 */
final class StderrLogger extends AbstractLogger
{
    /**
     * @param mixed $level
     * @param string $message
     * @param array $context
     */
    public function log($level, $message, array $context = array()): void
    {
        $data = array_merge([
            'level' => $level,
            'msg' => $message,
            'host' => gethostname(),
            'pid' => getmypid(),
            'time' => time(),
        ], $context);
        fwrite(STDERR, json_encode($data) . PHP_EOL);
    }
}