<?php

declare(strict_types=1);

namespace Brick\JsonMapper\NameMapper;

use ReflectionParameter;

interface INameMapper
{
    public function mapName(ReflectionParameter $constructorArgument): string;
}
