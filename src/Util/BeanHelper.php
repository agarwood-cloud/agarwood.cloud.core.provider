<?php declare(strict_types=1);
/**
 * This file is part of Agarwood Cloud.
 *
 * @link     https://www.agarwood-cloud.com
 * @document https://www.agarwood-cloud.com/docs
 * @contact  676786620@qq.com
 * @author   agarwood
 */

namespace Agarwood\Core\Util;

use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionProperty;

class BeanHelper
{
    /**
     * @param       $src     object Source object.
     * @param       $dst     object Destination object.
     * @param array $exclude Excluded fields, which are not will be copied.
     *
     * @throws ReflectionException
     */
    public static function copyProperties(object $src, object $dst, array $exclude = []): void
    {
        $srcReflector = new ReflectionClass($src);
        $dstReflector = new ReflectionClass($dst);
        foreach ($srcReflector->getProperties() as $srcProperty) {
            $name  = $srcProperty->getName();
            $value = null;
            if ($srcProperty->isPublic() && !$srcProperty->isStatic()) {
                $value = $srcProperty->getValue($src);
            } elseif (!$srcProperty->isStatic() && ($srcProperty->isPrivate() || $srcProperty->isProtected())) {
                /** @var ReflectionMethod $getter */
                $methodName = 'get' . ucfirst($name);
                if ($srcReflector->hasMethod($methodName)) {
                    $getter = $srcReflector->getMethod($methodName);
                    $value  = $getter->invoke($src);
                }
            }
            if (!in_array($name, $exclude, false)) {
                if ($dstReflector->hasProperty($name)) {
                    $dstProperty = $dstReflector->getProperty($name);
                    if ($dstProperty->isPublic() && !$dstProperty->isStatic()) {
                        $dstProperty->setValue($dst, $value);
                    } elseif (!$dstProperty->isStatic() && ($dstProperty->isPrivate() || $dstProperty->isProtected())) {
                        /** @var ReflectionMethod $setter */
                        $setter = $dstReflector->getMethod('set' . ucfirst($name));
                        $setter->invoke($dst, $value);
                    }
                }
            }
        }
    }

    /**
     * @param       $obj     object Source object.
     * @param array $exclude Excluded fields, which are not will be null.
     *
     * @throws ReflectionException
     */
    public static function nullProperties(object $obj, array $exclude = []): void
    {
        $objReflector = new ReflectionClass($obj);
        foreach ($objReflector->getProperties() as $objProperty) {
            $name  = $objProperty->getName();
            $value = null;
            if (!in_array($name, $exclude, false)) {
                if ($objProperty->isPublic() && !$objProperty->isStatic()) {
                    $objProperty->setValue($obj, $value);
                } elseif (!$objProperty->isStatic() && ($objProperty->isPrivate() || $objProperty->isProtected())) {
                    /** @var ReflectionMethod $setter */
                    $setter = $objReflector->getMethod('set' . ucfirst($name));
                    $setter->invoke($obj, $value);
                }
            }
        }
    }

    /**
     * @param       $obj     object Source object.
     * @param mixed $value
     * @param array $exclude Excluded fields, which are not will be set.
     *
     * @throws ReflectionException
     */
    public static function setProperties(object $obj, mixed $value, array $exclude = []): void
    {
        $objReflector = new ReflectionClass($obj);
        /** @var ReflectionProperty $objProperty */
        foreach ($objReflector->getProperties() as $objProperty) {
            $name = $objProperty->getName();
            if (!in_array($name, $exclude, false)) {
                if ($objProperty->isPublic() && !$objProperty->isStatic()) {
                    $objProperty->setValue($obj, $value);
                } elseif (!$objProperty->isStatic() && ($objProperty->isPrivate() || $objProperty->isProtected())) {
                    /** @var ReflectionMethod $setter */
                    $setter = $objReflector->getMethod('set' . ucfirst($name));
                    $setter->invoke($obj, $value);
                }
            }
        }
    }

    /**
     * @param object $obj Source object.
     * @param mixed $value
     * @param array $map
     * @throws ReflectionException
     */
    public static function setPropertiesMap(object $obj, mixed $value, array $map): void
    {
        $objReflector = new ReflectionClass($obj);
        /** @var ReflectionProperty $objProperty */
        foreach ($map as $name) {
            $objProperty = $objReflector->getProperty($name);
            if ($objProperty->isPublic() && !$objProperty->isStatic()) {
                $objProperty->setValue($obj, $value);
            } elseif (!$objProperty->isStatic() && ($objProperty->isPrivate() || $objProperty->isProtected())) {
                /** @var ReflectionMethod $setter */
                $setter = $objReflector->getMethod('set' . ucfirst($name));
                $setter->invoke($obj, $value);
            }
        }
    }

    /**
     * @param       $src object Source object.
     * @param       $dst object Destination object.
     * @param array $map Mapped fields from source to destination
     *
     * @throws ReflectionException
     */
    public static function copyPropertiesMap(object $src, object $dst, array $map = []): void
    {
        $srcReflector = new ReflectionClass($src);
        $dstReflector = new ReflectionClass($dst);
        /** @var string $srcName */
        foreach ($map as $srcName => $dstName) {
            $value       = null;
            $srcProperty = $srcReflector->getProperty($srcName);
            if ($srcProperty->isPublic() && !$srcProperty->isStatic()) {
                /** @var mixed $value */
                $value = $srcProperty->getValue($src);
            } elseif (!$srcProperty->isStatic() && ($srcProperty->isPrivate() || $srcProperty->isProtected())) {
                /** @var ReflectionMethod $getter */
                $getter = $srcReflector->getMethod('get' . ucfirst($srcName));
                $value  = $getter->invoke($src);
            }
            $dstProperty = $dstReflector->getProperty($dstName);
            if ($dstProperty->isPublic() && !$dstProperty->isStatic()) {
                $dstProperty->setValue($dst, $value);
            } elseif (!$srcProperty->isStatic() && ($srcProperty->isPrivate() || $srcProperty->isProtected())) {
                /** @var ReflectionMethod $setter */
                $setter = $dstReflector->getMethod('set' . ucfirst($dstName));
                $setter->invoke($dst, $value);
            }
        }
    }
}
