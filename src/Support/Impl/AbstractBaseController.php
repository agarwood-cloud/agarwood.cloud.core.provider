<?php declare(strict_types=1);
/**
 * This file is part of Agarwood Cloud.
 *
 * @link     https://www.agarwood-cloud.com
 * @document https://www.agarwood-cloud.com/docs
 * @contact  676786620@qq.com
 * @author   agarwood
 */

namespace Agarwood\Core\Support\Impl;

use Agarwood\Core\Extension\ArrayAble;
use Agarwood\Core\Support\Traits\ArrayTrait;

class AbstractBaseController implements ArrayAble
{
    use ArrayTrait;

    /**
     * @return DataWrapper
     */
    protected function wrapper(): DataWrapper
    {
        return new DataWrapper;
    }
}
