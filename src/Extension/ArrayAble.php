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

interface ArrayAble extends \Swoft\Stdlib\Contract\Arrayable
{
    /**
     * @param array $notFields
     * @param bool  $toList
     *
     * @return array
     */
    public function toArray(array $notFields = [], bool $toList = false): array;

    /**
     * @param array $notFields
     *
     * @return array
     */
    public function toArrayLine(array $notFields = []): array;
}
