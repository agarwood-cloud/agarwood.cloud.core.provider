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

use Agarwood\Core\Extension\ArrayAble;
use Agarwood\Core\Support\Traits\ArrayTrait;

/**
 * Unified Response Export Data Boxing
 */
class Wrapper implements ArrayAble
{
    use ArrayTrait;

    public const SUCCESS_MSG = 'Success';

    public const SUB_SUCCESS_MSG = 'Call Succeeded';

    public const SUCCESS_CODE = 0;

    public const SUB_SUCCESS_CODE = 'isp.success';

    public const ERROR_MSG = 'Error';

    public const SUB_ERROR_MSG = 'System Busy';

    public const ERROR_CODE = -1;

    public const SUB_ERROR_CODE = 'isp.unknown.error';

    /**
     * Response Data
     *
     * @var mixed $data
     */
    protected mixed $data = [];

    /**
     * Business processing result description
     *
     * @var string
     */
    protected string $msg = self::SUCCESS_MSG;

    /**
     * Business processing result description status code
     *
     * @var int
     */
    protected int $code = self::SUCCESS_CODE;

    /**
     * Business processing result success status code
     *
     * @var string
     */
    protected string $subCode = self::SUB_SUCCESS_CODE;

    /**
     * Business processing result success message tips
     *
     * @var string
     */
    protected string $subMsg = self::SUB_SUCCESS_MSG;

    /**
     * Interface HTTP response status code
     *
     * @var int
     */
    protected int $httpStatusCode = 200;

    /**
     * Interface signature
     *
     * @var string
     */
    protected string $sign = '';

    /**
     * Debug information
     *
     * @var array
     */
    protected array $debug = [];

    /**
     * @return Wrapper
     */
    public static function new(): Wrapper
    {
        return new self;
    }

    /**
     * Convert array
     *
     * @param array $notFields
     * @param bool  $toList
     *
     * @return array
     */
    public function toArray(array $notFields = [], bool $toList = false): array
    {
        return [
            'data'    => $this->getData(),
            'subMsg'  => $this->getSubMsg(),
            'subCode' => $this->getSubCode(),
            'msg'     => $this->getMsg(),
            'code'    => $this->getCode(),
            'sign'    => $this->getSign(),
        ];
    }

    /**
     * @return mixed
     */
    public function getData(): mixed
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     *
     * @return Wrapper
     */
    public function setData(mixed $data): Wrapper
    {
        if ($data instanceof Arrayable) {
            $data = $data->toArray();
        }

        $this->data = $data;
        return $this;
    }

    /**
     * @return string
     */
    private function getSubMsg(): string
    {
        return $this->subMsg;
    }

    /**
     * @return string
     */
    private function getSubCode(): string
    {
        return $this->subCode;
    }

    /**
     * @return string
     */
    private function getMsg(): string
    {
        return $this->msg;
    }

    /**
     * @return int
     */
    private function getCode(): int
    {
        return $this->code;
    }

    /**
     * @return string|null
     */
    private function getSign(): ?string
    {
        return $this->sign ?: $this->buildSign();
    }

    /**
     * Signature operation
     *
     * @return string
     */
    private function buildSign(): string
    {
        return md5(serialize($this));
    }

    /**
     * @param string $subMsg
     *
     * @return Wrapper
     */
    public function setSubMsg(string $subMsg): Wrapper
    {
        $this->subMsg = $subMsg;
        return $this;
    }

    /**
     * @param string $subCode
     *
     * @return Wrapper
     */
    public function setSubCode(string $subCode): Wrapper
    {
        $this->subCode = $subCode;
        return $this;
    }

    /**
     * @param string $msg
     *
     * @return Wrapper
     */
    public function setMsg(string $msg): Wrapper
    {
        $this->msg = $msg;
        return $this;
    }

    /**
     * @param int $code
     *
     * @return Wrapper
     */
    public function setCode(int $code): Wrapper
    {
        $this->code = $code;
        return $this;
    }

    /**
     * set Signature
     *
     * @param string $sign
     *
     * @return Wrapper
     */
    public function setSign(string $sign): Wrapper
    {
        $this->sign = $sign;
        return $this;
    }

    /**
     * @return int
     */
    public function getHttpStatusCode(): int
    {
        return $this->httpStatusCode;
    }

    /**
     * @param int $httpStatusCode
     *
     * @return Wrapper
     */
    public function setHttpStatusCode(int $httpStatusCode): self
    {
        $this->httpStatusCode = $httpStatusCode;
        return $this;
    }

    /**
     * @return array
     */
    public function getDebug(): array
    {
        return $this->debug;
    }

    /**
     * @param array|mixed $debug
     *
     * @return Wrapper
     */
    public function setDebug(array $debug): Wrapper
    {
        $this->debug = (array)$debug;
        return $this;
    }
}
