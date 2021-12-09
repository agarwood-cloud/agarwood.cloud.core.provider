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

use Agarwood\Core\Support\IBaseDTO;
use Agarwood\Core\Support\Traits\ArrayTrait;
use Agarwood\Core\Support\Traits\PrototypeTrait;

abstract class AbstractBaseDTO implements IBaseDTO
{
    use PrototypeTrait, ArrayTrait;
}
