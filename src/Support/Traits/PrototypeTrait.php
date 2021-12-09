<?php declare(strict_types=1);
/**
 * This file is part of Agarwood Cloud.
 *
 * @link     https://www.agarwood-cloud.com
 * @document https://www.agarwood-cloud.com/docs
 * @contact  676786620@qq.com
 * @author   agarwood
 */

namespace Agarwood\Core\Support\Traits;

use Closure;
use InvalidArgumentException;
use ReflectionClass;
use ReflectionException;
use RuntimeException;
use Swoft\Aop\Proxy;
use Swoft\Log\Helper\CLog;
use Swoft\Stdlib\Helper\Arr;
use Swoft\Stdlib\Helper\ObjectHelper;

/**
 * Class properties are automatically set according to the passed in array.
 */
trait PrototypeTrait
{
    public function __construct(array|object $attributes = [])
    {
        if (is_object($attributes) && method_exists($attributes, 'toArray')) {
            $attributes = $attributes->toArray();
        }
        $this->setAttr($attributes);
    }

    /**
     * Set class properties.
     *
     * @param array $prop
     * @param null  $closure
     *
     * @return \Agarwood\Core\Support\Impl\AbstractBaseDTO|\Agarwood\Core\Support\Traits\PrototypeTrait
     */
    public function setAttr(array $prop, $closure = null): self
    {
        try {
            $className = Proxy::getClassName(get_class($this));
            $ref       = new ReflectionClass($className);
            if ($ref->isInstantiable()) {
                foreach ($prop as $property => $setValue) {
                    foreach ($this->setterPrefix() as $prefix) {
                        $propSetter = $prefix . ucfirst($property);
                        if ($ref->hasProperty($property) && $ref->hasMethod($propSetter)) {
                            $reflectionProperty = $ref->getProperty($property);
                            $type               = ObjectHelper::getPropertyBaseType($reflectionProperty);
                            $setValue           = ObjectHelper::parseParamType($type, $setValue);
                            $this->$propSetter($setValue);
                            break;
                        }
                    }
                }
            } else {
                throw new RuntimeException(sprintf('Class %s cannot instantiable', $className));
            }

            if ($closure instanceof Closure) {
                $closure($this, $prop);
            } elseif (is_array($closure) && is_object($closure[0]) && is_callable([$closure[0], $closure[1]])) {
                call_user_func([$closure[0], $closure[1]], $this, $prop);
            }
        } catch (ReflectionException | RuntimeException $exception) {
            CLog::error('Class property setting error:' . $exception->getMessage());
            throw new InvalidArgumentException($exception->getMessage());
        }
        if (property_exists($this, '_oldAttributes')) {
            $this->_oldAttributes = $prop;
        }
        return $this;
    }

    /**
     * @return string[]
     */
    private function setterPrefix(): array
    {
        return ['set', 'is'];
    }

    /**
     * Determine whether there is an updated value
     *
     * @return array
     */
    public function isNew(): array
    {
        $newAttributes = Arr::toArray($this);
        return array_diff($newAttributes, $this->_oldAttributes);
    }
}
