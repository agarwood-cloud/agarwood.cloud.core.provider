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

use Agarwood\Core\Exception\SystemErrorException;

/**
 * Object that represents the replacement of array value while performing [[ArrayHelper::merge()]].
 *
 * Usage example:
 *
 * ```php
 * $array1 = [
 *     'ids' => [
 *         1,
 *     ],
 *     'validDomains' => [
 *         'example.com',
 *         'www.example.com',
 *     ],
 * ];
 *
 * $array2 = [
 *     'ids' => [
 *         2,
 *     ],
 *     'validDomains' => new Common\Util\ReplaceArrayValue([
 *         'abc.com',
 *         'www.abc.com',
 *     ]),
 * ];
 *
 * $result = Common\Util\ArrayHelper::merge($array1, $array2);
 * ```
 *
 * The result will be
 *
 * ```php
 * [
 *     'ids' => [
 *         1,
 *         2,
 *     ],
 *     'validDomains' => [
 *         'abc.com',
 *         'www.abc.com',
 *     ],
 * ]
 * ```
 *
 */
class ReplaceArrayValue
{
    /**
     * @var mixed value used as replacement.
     */
    public mixed $value;

    /**
     * Constructor.
     * @param mixed $value value used as replacement.
     */
    public function __construct(mixed $value)
    {
        $this->value = $value;
    }

    /**
     * Restores class state after using `var_export()`.
     *
     * @param array $state
     *
     * @return ReplaceArrayValue
     * @throws SystemErrorException when $state property does not contain `value` parameter
     * @see   var_export()
     */
    public static function __set_state(array $state): ReplaceArrayValue
    {
        if (!isset($state['value'])) {
            throw new SystemErrorException('Failed to instantiate class "ReplaceArrayValue". Required parameter "value" is missing');
        }

        return new self($state['value']);
    }
}
