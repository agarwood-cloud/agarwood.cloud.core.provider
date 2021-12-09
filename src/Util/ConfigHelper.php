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

class ConfigHelper
{
    /**
     * 'config' Cannot be directly used for judgment
     *
     * @param string $key
     * @param null   $default
     *
     * @return mixed
     */
    public static function get(string $key, $default = null): mixed
    {
        $value = config($key);
        if ($value === sprintf('${.config.%s}', $key)) {
            return $default;
        }
        return $value;
    }
}
