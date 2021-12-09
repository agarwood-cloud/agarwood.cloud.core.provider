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

use ArrayAccess;
use Closure;
use Exception;
use InvalidArgumentException;
use Traversable;

class ArrayHelper
{
    /**
     * Get repeated values in the data.
     *
     * @param array $arr
     *
     * @return array
     */
    public static function getRepeat(array $arr): array
    {
        // Get an array with duplicate data removed
        $unique_arr = array_unique($arr);
        return array_diff_assoc($arr, $unique_arr);
    }

    /**
     * Convert array subscript to underscore
     *
     * @param array $array
     *
     * @return array
     */
    public static function convertToLine(array $array): array
    {
        $convert = [];
        foreach ($array as $key => $value) {
            if (is_array($key)) {
                $convert[] = self::convertToLine($key);
            } else {
                $convert[StringUtil::toLine($key)] = $value;
            }
        }

        return $convert;
    }

    /**
     * Convert array subscript to camel case
     *
     * @param array $array
     *
     * @return array
     */
    public static function convertToHump(array $array): array
    {
        $convert = [];
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $convert[$key] = self::convertToHump($value);
            } else {
                $convert[StringUtil::toHump($key, false)] = $value;
            }
        }

        return $convert;
    }

    /**
     * @param array  $array
     * @param string $prefix
     *
     * @return array|false
     */
    public static function convertKeyPrefix(array $array, string $prefix): bool|array
    {
        return array_combine(array_map(static function ($key) use ($prefix) {
            return $prefix . $key;
        }, array_keys($array)), array_values($array));
    }

    /**
     * Indexes and/or groups the array according to a specified key.
     * The input should be either multidimensional array or an array of objects.
     *
     * The $key can be either a key name of the sub-array, a property name of object, or an anonymous
     * function that must return the value that will be used as a key.
     *
     * $groups is an array of keys, that will be used to group the input array into one or more sub-arrays based
     * on keys specified.
     *
     * If the `$key` is specified as `null` or a value of an element corresponding to the key is `null` in addition
     * to `$groups` not specified then the element is discarded.
     *
     * For example:
     *
     * ```php
     * $array = [
     *     ['id' => '123', 'data' => 'abc', 'device' => 'laptop'],
     *     ['id' => '345', 'data' => 'def', 'device' => 'tablet'],
     *     ['id' => '345', 'data' => 'hgi', 'device' => 'smartphone'],
     * ];
     * $result = ArrayHelper::index($array, 'id');
     * ```
     *
     * The result will be an associative array, where the key is the value of `id` attribute
     *
     * ```php
     * [
     *     '123' => ['id' => '123', 'data' => 'abc', 'device' => 'laptop'],
     *     '345' => ['id' => '345', 'data' => 'hgi', 'device' => 'smartphone']
     *     // The second element of an original array is overwritten by the last element because of the same id
     * ]
     * ```
     *
     * An anonymous function can be used in the grouping array as well.
     *
     * ```php
     * $result = ArrayHelper::index($array, function ($element) {
     *     return $element['id'];
     * });
     * ```
     *
     * Passing `id` as a third argument will group `$array` by `id`:
     *
     * ```php
     * $result = ArrayHelper::index($array, null, 'id');
     * ```
     *
     * The result will be a multidimensional array grouped by `id` on the first level, by `device` on the second level
     * and indexed by `data` on the third level:
     *
     * ```php
     * [
     *     '123' => [
     *         ['id' => '123', 'data' => 'abc', 'device' => 'laptop']
     *     ],
     *     '345' => [ // all elements with this index are present in the result array
     *         ['id' => '345', 'data' => 'def', 'device' => 'tablet'],
     *         ['id' => '345', 'data' => 'hgi', 'device' => 'smartphone'],
     *     ]
     * ]
     * ```
     *
     * The anonymous function can be used in the array of grouping keys as well:
     *
     * ```php
     * $result = ArrayHelper::index($array, 'data', [function ($element) {
     *     return $element['id'];
     * }, 'device']);
     * ```
     *
     * The result will be a multidimensional array grouped by `id` on the first level, by the `device` on the second one
     * and indexed by the `data` on the third level:
     *
     * ```php
     * [
     *     '123' => [
     *         'laptop' => [
     *             'abc' => ['id' => '123', 'data' => 'abc', 'device' => 'laptop']
     *         ]
     *     ],
     *     '345' => [
     *         'tablet' => [
     *             'def' => ['id' => '345', 'data' => 'def', 'device' => 'tablet']
     *         ],
     *         'smartphone' => [
     *             'hgi' => ['id' => '345', 'data' => 'hgi', 'device' => 'smartphone']
     *         ]
     *     ]
     * ]
     * ```
     *
     * @param array                          $array   the array that needs to be indexed or grouped
     * @param Closure|string|null            $key     the column name or anonymous function which result will be used to index the array
     * @param string|Closure[]|string[]|null $groups  the array of keys, that will be used to group the input array
     *                                                by one or more keys. If the $key attribute or its value for the particular element is null and $groups is not
     *                                                defined, the array element will be discarded. Otherwise, if $groups is specified, array element will be added
     *                                                to the result array without any key. This parameter is available since version 2.0.8.
     *
     * @return array the indexed and/or grouped array
     * @throws Exception
     */
    public static function index(array $array, Closure|string|null $key, array|string|null $groups = []): array
    {
        $result = [];
        $groups = (array)$groups;

        foreach ($array as $element) {
            $lastArray = &$result;

            foreach ($groups as $group) {
                $value = static::getValue($element, $group);
                if (!array_key_exists($value, $lastArray)) {
                    $lastArray[$value] = [];
                }
                $lastArray = &$lastArray[$value];
            }

            if ($key === null) {
                if (!empty($groups)) {
                    $lastArray[] = $element;
                }
            } else {
                $value = static::getValue($element, $key);
                if ($value !== null) {
                    if (is_float($value)) {
                        $value = StringHelper::floatToString($value);
                    }
                    $lastArray[$value] = $element;
                }
            }
            unset($lastArray);
        }

        return $result;
    }

    /**
     * Retrieves the value of an array element or object property with the given key or property name.
     * If the key does not exist in the array, the default value will be returned instead.
     * Not used when getting value from an object.
     *
     * The key may be specified in a dot format to retrieve the value of a sub-array or the property
     * of an embedded object. In particular, if the key is `x.y.z`, then the returned value would
     * be `$array['x']['y']['z']` or `$array->x->y->z` (if `$array` is an object). If `$array['x']`
     * or `$array->x` is neither an array nor an object, the default value will be returned.
     * Note that if the array already has an element `x.y.z`, then its value will be returned
     * instead of going through the sub-arrays. So it is better to be done specifying an array of key names
     * like `['x', 'y', 'z']`.
     *
     * Below are some usage examples,
     *
     * ```php
     * // working with array
     * $username = Common\Util\ArrayHelper::getValue($_POST, 'username');
     * // working with object
     * $username = Common\Util\ArrayHelper::getValue($user, 'username');
     * // working with anonymous function
     * $fullName = Common\Util\ArrayHelper::getValue($user, function ($user, $defaultValue) {
     *     return $user->firstName . ' ' . $user->lastName;
     * });
     * // using dot format to retrieve the property of embedded object
     * $street = Common\Util\ArrayHelper::getValue($users, 'address.street');
     * // using an array of keys to retrieve the value
     * $value = Common\Util\ArrayHelper::getValue($versions, ['1.0', 'date']);
     * ```
     *
     * @param object|array         $array   array or object to extract value from
     * @param array|Closure|string $key     key name of the array element, an array of keys or property name of the object,
     *                                      or an anonymous function returning the value. The anonymous function signature should be:
     *                                      `function($array, $defaultValue)`.
     *                                      The possibility to pass an array of keys is available since version 2.0.4.
     * @param mixed|null           $default the default value to be returned if the specified array key does not exist. Not used when
     *                                      getting value from an object.
     *
     * @return mixed the value of the element if found, default value otherwise
     * @throws Exception
     */
    public static function getValue(object|array $array, array|Closure|string $key, mixed $default = null): mixed
    {
        if ($key instanceof Closure) {
            return $key($array, $default);
        }

        if (is_array($key)) {
            $lastKey = array_pop($key);
            foreach ($key as $keyPart) {
                $array = static::getValue($array, $keyPart);
            }
            $key = $lastKey;
        }

        if (static::keyExists($key, $array)) {
            return $array[$key];
        }

        if (($pos = strrpos($key, '.')) !== false) {
            $array = static::getValue($array, substr($key, 0, $pos), $default);
            $key   = substr($key, $pos + 1);
        }

        if (static::keyExists($key, $array)) {
            return $array[$key];
        }
        if (is_object($array)) {
            // this is expected to fail if the property does not exist, or __get() is not implemented
            // it is not reliably possible to check whether a property is accessible beforehand
            try {
                return $array->$key;
            } catch (Exception $e) {
                if ($array instanceof ArrayAccess) {
                    return $default;
                }
                throw $e;
            }
        }

        return $default;
    }

    /**
     * Checks if the given array contains the specified key.
     * This method enhances the `array_key_exists()` function by supporting case-insensitive
     * key comparison.
     *
     * @param string            $key           the key to check
     * @param ArrayAccess|array $array         the array with keys to check
     * @param bool              $caseSensitive whether the key comparison should be case-sensitive
     *
     * @return bool whether the array contains the specified key
     */
    public static function keyExists(string $key, ArrayAccess|array $array, bool $caseSensitive = true): bool
    {
        if ($caseSensitive) {
            // Function `isset` checks key faster but skips `null`, `array_key_exists` handles this case
            // https://secure.php.net/manual/en/function.array-key-exists.php#107786
            if (is_array($array) && (isset($array[$key]) || array_key_exists($key, $array))) {
                return true;
            }
            // Cannot use `array_has_key` on Objects for PHP 7.4+, therefore we need to check using [[ArrayAccess::offsetExists()]]
            return $array instanceof ArrayAccess && $array->offsetExists($key);
        }

        if ($array instanceof ArrayAccess) {
            throw new InvalidArgumentException('Second parameter($array) cannot be ArrayAccess in case insensitive mode');
        }

        foreach (array_keys($array) as $k) {
            if (strcasecmp($key, $k) === 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * Merges two or more arrays into one recursively.
     * If each array has an element with the same string key value, the latter
     * will overwrite the former (different from array_merge_recursive).
     * Recursive merging will be conducted if both arrays have an element of array
     * type and are having the same key.
     * For integer-keyed elements, the elements from the latter array will
     * be appended to the former array.
     * You can use [[UnsetArrayValue]] object to unset value from previous array or
     * [[ReplaceArrayValue]] to force replace former value instead of recursive merging.
     *
     * @param array $a array to be merged to
     * @param array $b array to be merged from. You can specify additional
     *                 arrays via third argument, fourth argument etc.
     *
     * @return array the merged array (the original arrays are not changed.)
     */
    public static function merge(array $a, array $b): array
    {
        $args = func_get_args();
        $res  = array_shift($args);
        while (!empty($args)) {
            foreach (array_shift($args) as $k => $v) {
                if ($v instanceof UnsetArrayValue) {
                    unset($res[$k]);
                } elseif ($v instanceof ReplaceArrayValue) {
                    $res[$k] = $v->value;
                } elseif (is_int($k)) {
                    if (array_key_exists($k, $res)) {
                        $res[] = $v;
                    } else {
                        $res[$k] = $v;
                    }
                } elseif (is_array($v) && isset($res[$k]) && is_array($res[$k])) {
                    $res[$k] = static::merge($res[$k], $v);
                } else {
                    $res[$k] = $v;
                }
            }
        }

        return $res;
    }

    /**
     * Writes a value into an associative array at the key path specified.
     * If there is no such key path yet, it will be created recursively.
     * If the key exists, it will be overwritten.
     *
     * ```php
     *  $array = [
     *      'key' => [
     *          'in' => [
     *              'val1',
     *              'key' => 'val'
     *          ]
     *      ]
     *  ];
     * ```
     *
     * The result of `ArrayHelper::setValue($array, 'key.in.0', ['arr' => 'val']);` will be the following:
     *
     * ```php
     *  [
     *      'key' => [
     *          'in' => [
     *              ['arr' => 'val'],
     *              'key' => 'val'
     *          ]
     *      ]
     *  ]
     *
     * ```
     *
     * The result of
     * `ArrayHelper::setValue($array, 'key.in', ['arr' => 'val']);` or
     * `ArrayHelper::setValue($array, ['key', 'in'], ['arr' => 'val']);`
     * will be the following:
     *
     * ```php
     *  [
     *      'key' => [
     *          'in' => [
     *              'arr' => 'val'
     *          ]
     *      ]
     *  ]
     * ```
     *
     * @param array             $array the array to write the value to
     * @param array|string|null $path  the path of where do you want to write a value to `$array`
     *                                 the path can be described by a string when each key should be separated by a dot
     *                                 you can also describe the path as an array of keys
     *                                 if the path is null then `$array` will be assigned the `$value`
     * @param mixed             $value the value to be written
     *
     * @since 2.0.13
     */
    public static function setValue(array &$array, array|string|null $path, mixed $value): void
    {
        if ($path === null) {
            $array = $value;
            return;
        }

        $keys = is_array($path) ? $path : explode('.', $path);

        while (count($keys) > 1) {
            $key = array_shift($keys);
            if (!isset($array[$key])) {
                $array[$key] = [];
            }
            if (!is_array($array[$key])) {
                $array[$key] = [$array[$key]];
            }
            $array = &$array[$key];
        }

        $array[array_shift($keys)] = $value;
    }

    /**
     * Removes an item from an array and returns the value. If the key does not exist in the array, the default value
     * will be returned instead.
     *
     * Usage examples,
     *
     * ```php
     * // $array = ['type' => 'A', 'options' => [1, 2]];
     * // working with array
     * $type = Common\Util\ArrayHelper::remove($array, 'type');
     * // $array content
     * // $array = ['options' => [1, 2]];
     * ```
     *
     * @param array      $array   the array to extract value from
     * @param string     $key     key name of the array element
     * @param mixed|null $default the default value to be returned if the specified key does not exist
     *
     * @return mixed the value of the element if found, default value otherwise
     */
    public static function remove(array &$array, string $key, mixed $default = null): mixed
    {
        if ((isset($array[$key]) || array_key_exists($key, $array))) {
            $value = $array[$key];
            unset($array[$key]);

            return $value;
        }

        return $default;
    }

    /**
     * Removes items with matching values from the array and returns the removed items.
     *
     * Example,
     *
     * ```php
     * $array = ['Bob' => 'Dylan', 'Michael' => 'Jackson', 'Mick' => 'Jagger', 'Janet' => 'Jackson'];
     * $removed = Common\Util\ArrayHelper::removeValue($array, 'Jackson');
     * // result:
     * // $array = ['Bob' => 'Dylan', 'Mick' => 'Jagger'];
     * // $removed = ['Michael' => 'Jackson', 'Janet' => 'Jackson'];
     * ```
     *
     * @param array  $array the array where to look the value from
     * @param string $value the value to remove from the array
     *
     * @return array the items that were removed from the array
     * @since 2.0.11
     */
    public static function removeValue(array &$array, string $value): array
    {
        $result = [];
        foreach ($array as $key => $val) {
            if ($val === $value) {
                $result[$key] = $val;
                unset($array[$key]);
            }
        }

        return $result;
    }

    /**
     * Returns the values of a specified column in an array.
     * The input array should be multidimensional or an array of objects.
     *
     * For example,
     *
     * ```php
     * $array = [
     *     ['id' => '123', 'data' => 'abc'],
     *     ['id' => '345', 'data' => 'def'],
     * ];
     * $result = ArrayHelper::getColumn($array, 'id');
     * // the result is: ['123', '345']
     *
     * // using anonymous function
     * $result = ArrayHelper::getColumn($array, function ($element) {
     *     return $element['id'];
     * });
     * ```
     *
     * @param array              $array
     * @param int|Closure|string $name
     * @param bool               $keepKeys  whether to maintain the array keys. If false, the resulting array
     *                                      will be re-indexed with integers.
     *
     * @return array the list of column values
     * @throws Exception
     */
    public static function getColumn(array $array, int|Closure|string $name, bool $keepKeys = true): array
    {
        $result = [];
        if ($keepKeys) {
            foreach ($array as $k => $element) {
                $result[$k] = static::getValue($element, $name);
            }
        } else {
            foreach ($array as $element) {
                $result[] = static::getValue($element, $name);
            }
        }

        return $result;
    }

    /**
     * Builds a map (key-value pairs) from a multidimensional array or an array of objects.
     * The `$from` and `$to` parameters specify the key names or property names to set up the map.
     * Optionally, one can further group the map according to a grouping field `$group`.
     *
     * For example,
     *
     * ```php
     * $array = [
     *     ['id' => '123', 'name' => 'aaa', 'class' => 'x'],
     *     ['id' => '124', 'name' => 'bbb', 'class' => 'x'],
     *     ['id' => '345', 'name' => 'ccc', 'class' => 'y'],
     * ];
     *
     * $result = ArrayHelper::map($array, 'id', 'name');
     * // the result is:
     * // [
     * //     '123' => 'aaa',
     * //     '124' => 'bbb',
     * //     '345' => 'ccc',
     * // ]
     *
     * $result = ArrayHelper::map($array, 'id', 'name', 'class');
     * // the result is:
     * // [
     * //     'x' => [
     * //         '123' => 'aaa',
     * //         '124' => 'bbb',
     * //     ],
     * //     'y' => [
     * //         '345' => 'ccc',
     * //     ],
     * // ]
     * ```
     *
     * @param array          $array
     * @param Closure|string $from
     * @param Closure|string $to
     * @param null           $group
     *
     * @return array
     * @throws Exception
     */
    public static function map(array $array, Closure|string $from, Closure|string $to, $group = null): array
    {
        $result = [];
        foreach ($array as $element) {
            $key   = static::getValue($element, $from);
            $value = static::getValue($element, $to);
            if ($group !== null) {
                $result[static::getValue($element, $group)][$key] = $value;
            } else {
                $result[$key] = $value;
            }
        }

        return $result;
    }

    /**
     * Sorts an array of objects or arrays (with the same structure) by one or several keys.
     *
     * @param array                $array      the array to be sorted. The array will be modified after calling this method.
     * @param array|Closure|string $key        the key(s) to be sorted by. This refers to a key name of the sub-array
     *                                         elements, a property name of the objects, or an anonymous function returning the values for comparison
     *                                         purpose. The anonymous function signature should be: `function($item)`.
     *                                         To sort by multiple keys, provide an array of keys here.
     * @param int|array            $direction  the sorting direction. It can be either `SORT_ASC` or `SORT_DESC`.
     *                                         When sorting by multiple keys with different sorting directions, use an array of sorting directions.
     * @param int|array            $sortFlag   the PHP sort flag. Valid values include
     *                                         `SORT_REGULAR`, `SORT_NUMERIC`, `SORT_STRING`, `SORT_LOCALE_STRING`, `SORT_NATURAL` and `SORT_FLAG_CASE`.
     *                                         Please refer to [PHP manual](https://secure.php.net/manual/en/function.sort.php)
     *                                         for more details. When sorting by multiple keys with different sort flags, use an array of sort flags.
     *
     * @throws InvalidArgumentException|Exception if the $direction or $sortFlag parameters do not have
     * correct number of elements as that of $key.
     */
    public static function multipleSort(array &$array, array|Closure|string $key, int|array $direction = SORT_ASC, int|array $sortFlag = SORT_REGULAR): void
    {
        $keys = is_array($key) ? $key : [$key];
        if (empty($keys) || empty($array)) {
            return;
        }
        $n = count($keys);
        if (is_scalar($direction)) {
            $direction = array_fill(0, $n, $direction);
        } elseif (count($direction) !== $n) {
            throw new InvalidArgumentException('The length of $direction parameter must be the same as that of $keys.');
        }
        if (is_scalar($sortFlag)) {
            $sortFlag = array_fill(0, $n, $sortFlag);
        } elseif (count($sortFlag) !== $n) {
            throw new InvalidArgumentException('The length of $sortFlag parameter must be the same as that of $keys.');
        }
        $args = [];
        foreach ($keys as $i => $k) {
            $flag   = $sortFlag[$i];
            $args[] = static::getColumn($array, $k);
            $args[] = $direction[$i];
            $args[] = $flag;
        }

        // This fix is used for cases when main sorting specified by columns has equal values
        // Without it it will lead to Fatal Error: Nesting level too deep - recursive dependency?
        $args[] = range(1, count($array));
        $args[] = SORT_ASC;
        $args[] = SORT_NUMERIC;

        $args[] = &$array;
        array_multisort(...$args);
    }

    /**
     * Returns a value indicating whether the given array is an associative array.
     *
     * An array is associative if all its keys are strings. If `$allStrings` is false,
     * then an array will be treated as associative if at least one of its keys is a string.
     *
     * Note that an empty array will NOT be considered associative.
     *
     * @param array $array      the array being checked
     * @param bool  $allStrings whether the array keys must be all strings in order for
     *                          the array to be treated as associative.
     *
     * @return bool whether the array is associative
     */
    public static function isAssociative(array $array, bool $allStrings = true): bool
    {
        if (!is_array($array) || empty($array)) {
            return false;
        }

        if ($allStrings) {
            foreach ($array as $key => $value) {
                if (!is_string($key)) {
                    return false;
                }
            }

            return true;
        }

        foreach ($array as $key => $value) {
            if (is_string($key)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Returns a value indicating whether the given array is an indexed array.
     *
     * An array is indexed if all its keys are integers. If `$consecutive` is true,
     * then the array keys must be a consecutive sequence starting from 0.
     *
     * Note that an empty array will be considered indexed.
     *
     * @param array $array       the array being checked
     * @param bool  $consecutive whether the array keys must be a consecutive sequence
     *                           in order for the array to be treated as indexed.
     *
     * @return bool whether the array is indexed
     */
    public static function isIndexed(array $array, bool $consecutive = false): bool
    {
        if (!is_array($array)) {
            return false;
        }

        if (empty($array)) {
            return true;
        }

        if ($consecutive) {
            return array_keys($array) === range(0, count($array) - 1);
        }

        foreach ($array as $key => $value) {
            if (!is_int($key)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check whether an array or [[Traversable]] contains an element.
     *
     * This method does the same as the PHP function [in_array()](https://secure.php.net/manual/en/function.in-array.php)
     * but additionally works for objects that implement the [[Traversable]] interface.
     *
     * @param mixed             $needle   The value to look for.
     * @param Traversable|array $haystack The set of values to search.
     * @param bool              $strict   Whether to enable strict (`===`) comparison.
     *
     * @return bool `true` if `$needle` was found in `$haystack`, `false` otherwise.
     * @throws InvalidArgumentException if `$haystack` is neither traversable nor an array.
     * @see   https://secure.php.net/manual/en/function.in-array.php
     * @since 2.0.7
     */
    public static function isIn(mixed $needle, Traversable|array $haystack, bool $strict = false): bool
    {
        if ($haystack instanceof Traversable) {
            foreach ($haystack as $value) {
                if ($needle == $value && (!$strict || $needle === $value)) {
                    return true;
                }
            }
        } elseif (is_array($haystack)) {
            return in_array($needle, $haystack, $strict);
        } else {
            throw new InvalidArgumentException('Argument $haystack must be an array or implement Traversable');
        }

        return false;
    }

    /**
     * Checks whether a variable is an array or [[Traversable]].
     *
     * This method does the same as the PHP function [is_array()](https://secure.php.net/manual/en/function.is-array.php)
     * but additionally works on objects that implement the [[Traversable]] interface.
     *
     * @param mixed $var The variable being evaluated.
     *
     * @return bool whether $var can be traversed via foreach
     * @see https://secure.php.net/manual/en/function.is-array.php
     */
    protected static function isTraversable(mixed $var): bool
    {
        return is_array($var) || $var instanceof Traversable;
    }

    /**
     * Checks whether an array or [[Traversable]] is a subset of another array or [[Traversable]].
     *
     * This method will return `true`, if all elements of `$needles` are contained in
     * `$haystack`. If at least one element is missing, `false` will be returned.
     *
     * @param Traversable|array $needles  The values that must **all** be in `$haystack`.
     * @param Traversable|array $haystack The set of value to search.
     * @param bool              $strict   Whether to enable strict (`===`) comparison.
     *
     * @return bool `true` if `$needles` is a subset of `$haystack`, `false` otherwise.
     * @throws InvalidArgumentException if `$haystack` or `$needles` is neither traversable nor an array.
     */
    public static function isSubset(Traversable|array $needles, Traversable|array $haystack, bool $strict = false): bool
    {
        if (is_array($needles) || $needles instanceof Traversable) {
            foreach ($needles as $needle) {
                if (!static::isIn($needle, $haystack, $strict)) {
                    return false;
                }
            }

            return true;
        }

        throw new InvalidArgumentException('Argument $needles must be an array or implement Traversable');
    }

    /**
     * Filters array according to rules specified.
     *
     * For example:
     *
     * ```php
     * $array = [
     *     'A' => [1, 2],
     *     'B' => [
     *         'C' => 1,
     *         'D' => 2,
     *     ],
     *     'E' => 1,
     * ];
     *
     * $result = Common\Util\ArrayHelper::filter($array, ['A']);
     * // $result will be:
     * // [
     * //     'A' => [1, 2],
     * // ]
     *
     * $result = Common\Util\ArrayHelper::filter($array, ['A', 'B.C']);
     * // $result will be:
     * // [
     * //     'A' => [1, 2],
     * //     'B' => ['C' => 1],
     * // ]
     *
     * $result = Common\Util\ArrayHelper::filter($array, ['B', '!B.C']);
     * // $result will be:
     * // [
     * //     'B' => ['D' => 2],
     * // ]
     * ```
     *
     * @param array $array   Source array
     * @param array $filters Rules that define array keys which should be left or removed from results.
     *                       Each rule is:
     *                       - `var` - `$array['var']` will be left in result.
     *                       - `var.key` = only `$array['var']['key'] will be left in result.
     *                       - `!var.key` = `$array['var']['key'] will be removed from result.
     *
     * @return array Filtered array
     */
    public static function filter(array $array, array $filters): array
    {
        $result         = [];
        $excludeFilters = [];

        foreach ($filters as $filter) {
            if ($filter[0] === '!') {
                $excludeFilters[] = substr($filter, 1);
                continue;
            }

            $nodeValue = $array; //set $array as root node
            $keys      = explode('.', $filter);
            foreach ($keys as $key) {
                if (!array_key_exists($key, $nodeValue)) {
                    continue 2; //Jump to next filter
                }
                $nodeValue = $nodeValue[$key];
            }

            //We've found a value now let's insert it
            $resultNode = &$result;
            foreach ($keys as $key) {
                if (!array_key_exists($key, $resultNode)) {
                    $resultNode[$key] = [];
                }
                $resultNode = &$resultNode[$key];
            }
            $resultNode = $nodeValue;
        }

        foreach ($excludeFilters as $filter) {
            $excludeNode   = &$result;
            $keys          = explode('.', $filter);
            $numNestedKeys = count($keys) - 1;
            foreach ($keys as $i => $key) {
                if (!array_key_exists($key, $excludeNode)) {
                    continue 2; //Jump to next filter
                }

                if ($i < $numNestedKeys) {
                    $excludeNode = &$excludeNode[$key];
                } else {
                    unset($excludeNode[$key]);
                    break;
                }
            }
        }

        return $result;
    }

    /**
     * 将数字 字符串转化为整型
     *
     * $array = [
     *     'page' = '1',
     *     'perPage' = '10',
     *     'pageCount' = '100',
     *  ]
     *
     * $result = ArrayHelper::numericToInt($array);
     * // $result will be:
     * [
     *     'page' = 1,
     *     'perPage' = 10,
     *     'pageCount' = 100,
     *  ]
     *
     *  $result = ArrayHelper::numericToInt($array,['page','perPage']);
     * // $result will be:
     * [
     *     'page' = 1,
     *     'perPage' = 10,
     *     'pageCount' = 100,
     *  ]
     *
     *
     * @param array $numeric     需要转化的数组
     * @param array $targetValue 需要转化的value值 数组
     *
     * @return array
     */
    public static function numericToInt(array $numeric, array $targetValue = []): array
    {
        foreach ($numeric as $key => $item) {

            // 只转指定的key值
            if ($targetValue && in_array($key, $targetValue, true)) {
                is_numeric($item) && $numeric[$key] = (int)$item;
            }

            if (empty($targetValue)) {
                is_numeric($item) && $numeric[$key] = (int)$item;
            }
        }

        return $numeric;
    }
}
