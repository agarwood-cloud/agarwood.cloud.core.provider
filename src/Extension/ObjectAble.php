<?php declare(strict_types=1);
/**
 * This file is part of Agarwood Cloud.
 *
 * @link     https://www.agarwood-cloud.com
 * @document https://www.agarwood-cloud.com/docs
 * @contact  676786620@qq.com
 * @author   agarwood
 */

namespace Agarwood\Core\Extension;

interface ObjectAble
{
    /**
     * Set class attributes.
     *
     * @param array $prop
     * @param null $closure
     */
    public function setAttr(array $prop, $closure = null);
}
