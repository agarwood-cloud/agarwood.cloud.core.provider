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

class StringHelper
{
    /**
     * Safely casts a float to string independent of the current locale.
     *
     * The decimal separator will always be `.`.
     *
     * @param float|int $number a floating point number or integer.
     *
     * @return string the string representation of the number.
     * @since 2.0.13
     */
    public static function floatToString(float|int $number): string
    {
        // . and , are the only decimal separators known in ICU data,
        // so its safe to call str_replace here
        return str_replace(',', '.', (string) $number);
    }
}
