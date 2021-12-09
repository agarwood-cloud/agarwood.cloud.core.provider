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

/**
 * String Util.
 */
final class StringUtil
{
    /**
     * Underline to hump naming
     *
     * @param string $string
     * @param bool $firstUp
     * @return string
     */
    public static function toHump(string $string, bool $firstUp = true): string
    {
        $humpString = implode(
            '',
            array_map('ucfirst', explode('_', $string))
        );

        return $firstUp ? $humpString : lcfirst($humpString);
    }

    /**
     * Hump naming to underline
     *
     * @param string $string need
     * @return string
     */
    public static function toLine(string $string): string
    {
        $replaceString = preg_replace_callback('/([A-Z]+)/', static function ($matches) {
            return '_' . strtolower($matches[0]);
        }, $string);
        return trim(preg_replace('/_{2,}/', '_', $replaceString), '_');
    }
}
