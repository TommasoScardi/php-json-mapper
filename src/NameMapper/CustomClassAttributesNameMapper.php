<?php

declare(strict_types=1);

namespace Brick\JsonMapper\NameMapper;

use Brick\JsonMapper\JsonMapperException;
use Brick\JsonMapper\NameMapper\INameMapper;
use ReflectionClass;
use ReflectionException;
use ReflectionParameter;

class CustomClassAttributesNameMapper implements INameMapper
{
    private const DOC_FIELD_NAME = "@jsonMapper";

    /**
     * Links the class constructor argument names that we're tring to parse data from json to the actual json field name  
     * 
     * This is possible thanks to a new notation in document comment section of the class property that have to exactly match with by name with the constructor arguments  
     * 
     * Like in this example, the @jsonMapper does the trick:
     * ```
     * class Test
     * {
     *  //@jsonMapper code
     *  public int $id
     * 
     *  public function __construct(int $id)
     *  {
     *      $this->id = $id;
     *  }
     * }
     * ```
     *
     * @param ReflectionParameter $constructorArgument
     * @return string
     */
    public function mapName(ReflectionParameter $constructorArgument): string
    {
        $refDeclParamClass = $constructorArgument->getDeclaringClass();
        if($refDeclParamClass !== null)
        {
            try
            {
                $refProp = $refDeclParamClass->getProperty($constructorArgument->getName());
                $propDocComment = $refProp->getDocComment();

                if(is_string($propDocComment))
                {
                    if(str_contains($propDocComment, self::DOC_FIELD_NAME))
                    {
                        $startPos = strpos($propDocComment, self::DOC_FIELD_NAME) + strlen(self::DOC_FIELD_NAME);
                        $len = strpos($propDocComment, PHP_EOL, $startPos) - $startPos;
                        return trim(substr($propDocComment, $startPos, $len));
                    }
                    else
                    {
                        //no doc comment for this property
                        return $constructorArgument->getName();
                    }
                }
                else
                {
                    //no doc comment for this property
                    return $constructorArgument->getName();
                }
            }
            catch(ReflectionException $exc)
            {
                throw new JsonMapperException(sprintf("NameMapper: parameter %s from %s class doesn't have a class property linked, the constructor argument names must match the class parameters names", $constructorArgument->getName(), $refDeclParamClass->getName()));
            }
        }
        else
        {
            throw new JsonMapperException("NameMapper: parameter ".$constructorArgument->getName()." doesn't come from a class");
        }
    }
}
