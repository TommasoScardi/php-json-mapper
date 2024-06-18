<?php

declare(strict_types=1);

namespace Brick\JsonMapper\Reflection;

use Brick\JsonMapper\JsonMapperException;
use ReflectionParameter;

class ImportResolver
{
    private ReflectionParameter $refParam;

    public function __construct(ReflectionParameter $refParam) {
        $this->refParam = $refParam;
    }

    public function resolve(string $namedType) : string
    {
        $refDeclClass = $this->refParam->getDeclaringClass();

        if($refDeclClass !== null)
        {
            $namespaceDeclClass = $refDeclClass->getNamespaceName();
            if(class_exists($namespaceDeclClass."\\".$namedType))
            {
                return $namespaceDeclClass."\\".$namedType;
            }
            else if(class_exists($namedType))
            {
                return $namedType;
            }
            else
            {
                throw new JsonMapperException(sprintf("Class (%s) to instantiate not found in parent class (%s) namespace (%s), please provide at least a relative namespace from the position where the class %s is been required",
                    $namedType, $refDeclClass->getName(), $namespaceDeclClass, $namedType));
            }
        }
        else
        {
            throw new JsonMapperException(sprintf("Unable to get the class where the function (%s) parameter (%s) where declared, failed to resolve class name for %s",
                $this->refParam->getDeclaringFunction()->getName(), $this->refParam->getName(), $namedType));
        }
    }
}