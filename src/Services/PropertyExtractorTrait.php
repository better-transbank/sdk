<?php

declare(strict_types=1);

/*
 * This file is part of the BetterTransbank\SDK project.
 * (c) Matías Navarro-Carter <mnavarrocarter@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BetterTransbank\SDK\Services;

/**
 * Trait PropertyExtractorTrait.
 */
trait PropertyExtractorTrait
{
    /**
     * @param object      $object
     * @param string      $property
     * @param string|null $scope
     *
     * @return mixed
     */
    protected function extract(object $object, string $property, string $scope = null)
    {
        $realScope = $scope ?? get_class($object);

        /** @var \Closure():mixed $closure */
        $closure = \Closure::fromCallable(function () use ($property) {
            return $this->{$property} ?? null;
        })->bindTo($object, $realScope);

        return $closure();
    }
}
