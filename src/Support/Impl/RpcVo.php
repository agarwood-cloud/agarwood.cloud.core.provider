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

use Agarwood\Core\Support\IRpcVo;

class RpcVo implements IRpcVo
{
    /** @var array Data */
    private array $data = [];

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param array $data
     *
     * @return RpcVo
     */
    public function setData(array $data): self
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return array
     */
    public function sendBuilder(): array
    {
        return $this->buildData();
    }

    /**
     * @return array
     */
    protected function buildData(): array
    {
        return $this->data;
    }
}
