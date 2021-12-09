<?php declare(strict_types=1);
/**
 * This file is part of Agarwood Cloud.
 *
 * @link     https://www.agarwood-cloud.com
 * @document https://www.agarwood-cloud.com/docs
 * @contact  676786620@qq.com
 * @author   agarwood
 */

namespace Agarwood\Core\Exception;

use Agarwood\Core\Exception\Handler\Http\BusinessExceptionHandler;
use Agarwood\Core\Exception\Handler\Http\ForbiddenExceptionHttpErrorHandler;
use Agarwood\Core\Exception\Handler\Http\InvalidParameterExceptionHttpErrorHandler;
use Agarwood\Core\Exception\Handler\Http\NotFoundResourceExceptionHttpErrorHandler;
use Agarwood\Core\Exception\Handler\Http\NotFoundRouteExceptionHandler;
use Agarwood\Core\Exception\Handler\Http\SystemErrorExceptionHttpErrorHandler;
use Agarwood\Core\Exception\Handler\Http\ValidatorExceptionHandler;
use Agarwood\Core\Util\Wrapper;
use RuntimeException;
use Throwable;

abstract class AbstractException extends RuntimeException
{
    /**
     * Business processing result description
     *
     * @var string
     */
    protected string $msg = Wrapper::SUCCESS_MSG;

    /**
     * Business processing result code
     *
     * @var string
     */
    protected string $subCode = Wrapper::SUB_SUCCESS_CODE;

    /**
     * Business processing result message tips
     *
     * @var string
     */
    protected string $subMsg = Wrapper::SUB_SUCCESS_MSG;

    /**
     * AbstractException constructor.
     *
     * @param string $message
     * @param int $code
     * @param string $subCode
     * @param string $subMsg
     * @param Throwable|null $previous
     */
    public function __construct(
        string $subMsg = Wrapper::SUB_SUCCESS_MSG,
        string $subCode = Wrapper::SUB_SUCCESS_CODE,
        string $message = Wrapper::SUCCESS_MSG,
        int $code = Wrapper::SUCCESS_CODE,
        Throwable $previous = null
    ) {
        if (in_array($message, [
            BusinessExceptionHandler::ERROR_MSG,
            ForbiddenExceptionHttpErrorHandler::ERROR_MSG,
            InvalidParameterExceptionHttpErrorHandler::ERROR_MSG,
            NotFoundResourceExceptionHttpErrorHandler::ERROR_MSG,
            NotFoundRouteExceptionHandler::ERROR_MSG,
            SystemErrorExceptionHttpErrorHandler::ERROR_MSG,
            ValidatorExceptionHandler::ERROR_MSG
        ], true)) {
            $message = $subMsg;
        }
        $this->setSubCode($subCode);
        $this->setSubMsg($subMsg);
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return string
     */
    public function getMsg(): string
    {
        return $this->msg;
    }

    /**
     * @param string $msg
     */
    public function setMsg(string $msg): void
    {
        $this->msg = $msg;
    }

    /**
     * @return string
     */
    public function getSubCode(): string
    {
        return $this->subCode;
    }

    /**
     * @param string $subCode
     */
    public function setSubCode(string $subCode): void
    {
        $this->subCode = $subCode;
    }

    /**
     * @return string
     */
    public function getSubMsg(): string
    {
        return $this->subMsg;
    }

    /**
     * @param string $subMsg
     */
    public function setSubMsg(string $subMsg): void
    {
        $this->subMsg = $subMsg;
    }
}
