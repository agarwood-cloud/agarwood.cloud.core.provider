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

use Agarwood\Core\Constant\GlobalConstant;
use Agarwood\Core\Support\IBaseVO;
use Swoft\Http\Message\Response;
use Swoft\Stdlib\Contract\Arrayable;

/**
 * @\Swoft\Bean\Annotation\Mapping\Bean()
 */
class DataWrapper implements IBaseVO
{
    /** @var string Response information */
    private string $message = '';

    /** @var int Status code */
    private int $code = 0;

    /** @var array Response data */
    private array $data = [];

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     *
     * @return DataWrapper
     */
    public function setMessage(string $message = GlobalConstant::RES_SUCCESS_MSG): self
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * @param int $code
     *
     * @return DataWrapper
     */
    public function setCode(int $code = GlobalConstant::DEFAULT_SUCCESS_CODE): self
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     *
     * @return DataWrapper
     */
    public function setData(mixed $data): self
    {
        if (is_array($data)) {
            $this->data = $data;
        } elseif ($data instanceof Arrayable) {
            $this->data = $data->toArray();
        } elseif (method_exists($data, 'toArray')) {
            $this->data = $data->toArray();
        } else {
            $this->data = [$data];
        }

        return $this;
    }

    /**
     * @param \Swoft\Http\Message\Response|null $response
     *
     * @return Response|null
     */
    public function response(?Response $response = null): ?Response
    {
        if (!$response) {
            $response = context()->getResponse();
        }
        return $response
            ->withData($this->buildData());
    }

    /**
     * @return array
     */
    public function buildData(): array
    {
        return [
            'code' => $this->code ?? GlobalConstant::DEFAULT_SUCCESS_CODE,
            'data' => $this->data ?? [],
            'msg'  => $this->message ?? GlobalConstant::RES_SUCCESS_MSG,
        ];
    }
}
