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

use Agarwood\Core\Util\StringUtil;
use ReflectionClass;
use ReflectionException;
use RuntimeException;
use Swoft\Aop\Proxy;
use Swoft\Log\Helper\CLog;

/**
 * Provide class attributes converted into array output
 */
trait ArrayTrait
{
    /**
     * Provide serialization and filter null values
     *
     * For example
     *
     * ```php
     *
     * $array = [
     *     'a' => 'hello',
     *     'b' => null,
     *     'c' => 'world',
     *  ];
     *
     * After calling the function method
     * $result = ArrayTrait::toArrayNotNull($array);
     *
     *  The result is:
     *  $result = [
     *      'a' => 'hello',
     *      'c' =>  'world',
     *  ];
     *
     * ```
     *
     * @param array $notFields
     * @param bool  $toList
     *
     * @return array
     * @noinspection StaticClosureCanBeUsedInspection
     */
    public function toArrayNotNull(array $notFields = [], bool $toList = false): array
    {
        return $this->toArrayWithFilter(function ($ab): array {
            foreach ($ab as $key => $value) {
                if ($value === null) {
                    unset($ab[$key]);
                }
            }
            return $ab;
        }, $notFields, $toList);
    }

    /**
     * Provide serialization and filter null & empty values
     *
     * For example
     *
     * ```php
     *
     * $array = [
     *     'a' => 'hello',
     *     'b' => null,
     *     'c' => 'world',
     *     'd' => '',
     *  ];
     *
     * After calling the function method
     * $result = ArrayTrait::toArrayNotEmpty($array);
     *
     *  The result is:
     *  $result = [
     *      'a' => 'hello',
     *      'c' =>  'world',
     *  ];
     *
     * ```
     *
     * @param array $notFields
     * @param bool  $toList
     *
     * @return array
     */
    public function toArrayNotEmpty(array $notFields = [], bool $toList = false):array
    {
        return $this->toArrayWithFilter(function ($ab): array {
            foreach ($ab as $key => $value) {
                if ($value === null) {
                    unset($ab[$key]);
                }
                if ($value === '') {
                    unset($ab[$key]);
                }
            }
            return $ab;
        }, $notFields, $toList);
    }

    /**
     * Use custom processing functions to process arrays
     *
     * @param callable $cb
     * @param array    $notFields
     * @param bool     $toList
     *
     * @return array
     */
    public function toArrayWithFilter(callable $cb, array $notFields = [], bool $toList = false): array
    {
        $arrayBuffer = $this->toArray($notFields, $toList);
        return (array)$cb($arrayBuffer);
    }

    /**
     * Serialization
     *
     * For example
     *
     * ```php
     *
     *  class MyClass extend  MyArrayTrait{
     *     private int $a = 1 ;
     *     public  string  $b = 'hello' ;
     *     protected string $c = 'world';
     *  }
     *
     *    $object = new MyClass(); It must be extends from this class
     *
     *  After calling toArray:
     *    $result = $object -> toArray();
     *
     *  The result is:
     *    $result = [
     *         'a' => 1,
     *         'b' => 'hello',
     *         'c' => 'world',
     *     ];
     *
     * ```
     *
     * @param array $notFields
     * @param bool  $toList
     *
     * @return array
     */
    public function toArray(array $notFields = [], bool $toList = false): array
    {
        return $this->toArrayPrepare(static function ($property, $keys) {
            return in_array($property, $keys, false);
        }, $notFields, $toList);
    }

    /**
     * For example,
     *
     * ```php
     *
     * class {
     *
     *    protected  $aAa = 'hello';
     *    public     $bBb = 'world';
     *    private    $cCc = 'cloud';
     *
     * }
     *
     * // The result is:
     * $result = [
     *      'a_aa' => 'hello',
     *      'b_bb' => 'world',
     *      'c_cc' => 'cloud',
     * ];
     *
     *
     * ```
     *
     * @param callable $continueCb
     * @param array    $keys
     * @param bool     $toList
     *
     * @return array
     */
    private function toArrayPrepare(callable $continueCb, array $keys = [], bool $toList = false): array
    {
        $arrayRes = [];
        try {
            $className = Proxy::getClassName(get_class($this));
            $ref       = new ReflectionClass($className);
            // TODO 这里在 PHP7.4强类型上有问题，如果不设置默认值，这里不会返回该属性
            $refProperties = $ref->getDefaultProperties();
            foreach (array_keys($refProperties) as &$property) {
                if ($continueCb($property, $keys)) {
                    continue;
                }
                foreach ($this->getMethodPrefix() as $prefix) {
                    $propertyGetter = $prefix . ucfirst($property);
                    if ($ref->hasMethod($propertyGetter)) {
                        $property            = $toList ? StringUtil::toLine($property) : $property;
                        $arrayRes[$property] = $this->$propertyGetter();
                        break;
                    }
                }
            }
        } catch (ReflectionException $exception) {
            CLog::error('An error occurred for toArray =%s', $exception->getMessage());
            throw new RuntimeException($exception->getMessage());
        }
        return $arrayRes;
    }

    /**
     * Serialization and filter null values
     *
     * @param array $notFields
     * @param bool  $toList
     *
     * @return array
     * @noinspection StaticClosureCanBeUsedInspection
     */
    public function toArrayByKeysNotNull(array $notFields = [], bool $toList = false): array
    {
        return $this->toArrayByKeysWithFilter(function ($ab): array {
            foreach ($ab as $key => $value) {
                if ($value === null) {
                    unset($ab[$key]);
                }
            }
            return $ab;
        }, $notFields, $toList);
    }

    /**
     * Use custom processing functions to process arrays
     *
     * @param callable $cb
     * @param array    $notFields
     * @param bool     $toList
     *
     * @return array
     */
    public function toArrayByKeysWithFilter(callable $cb, array $notFields = [], bool $toList = false): array
    {
        $arrayBuffer = $this->toArrayByKeys($notFields, $toList);
        return (array)$cb($arrayBuffer);
    }

    /**
     * @param array $keys
     * @param bool  $toList
     *
     * @return array
     */
    public function toArrayByKeys(array $keys = [], bool $toList = false): array
    {
        return $this->toArrayPrepare(static function ($property, $keys) {
            return !in_array($property, $keys, false);
        }, $keys, $toList);
    }

    /**
     * For example,
     *
     * ```php
     *
     * class {
     *
     *    protected  $aAa = 'hello';
     *    public     $bBb = 'world';
     *    private    $cCc = 'cloud';
     *
     * }
     *
     * // The result is:
     * $result = [
     *      'a_aa' => 'hello',
     *      'b_bb' => 'world',
     *      'c_cc' => 'cloud',
     * ];
     *
     *
     * ```
     *
     * @param array $notFields
     *
     * @return array
     */
    public function toArrayLine(array $notFields = []): array
    {
        return $this->toArray($notFields, true);
    }

    /**
     * Get property setter method prefix
     *
     * @return string[]
     */
    protected function getMethodPrefix(): array
    {
        return ['get', 'is'];
    }
}
