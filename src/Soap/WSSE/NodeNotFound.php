<?php
declare(strict_types=1);

namespace BetterTransbank\SDK\Soap\WSSE;

use RuntimeException;

/**
 * Class NodeNotFound
 * @package BetterTransbank\SDK\Soap
 */
class NodeNotFound extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Node not found');
    }
}