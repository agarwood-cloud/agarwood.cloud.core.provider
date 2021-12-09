<?php declare(strict_types=1);
/**
 * This file is part of Agarwood Cloud.
 *
 * @link     https://www.agarwood-cloud.com
 * @document https://www.agarwood-cloud.com/docs
 * @contact  676786620@qq.com
 * @author   agarwood
 */

namespace Agarwood\Core\Exception\Handler\Http;

use Agarwood\Core\Exception\AbstractException;
use Agarwood\Core\Util\Wrapper;
use Swoft\Http\Message\Response;
use Swoft\Log\Error;
use Throwable;

abstract class AbstractHttpErrorHandler extends \Swoft\Http\Server\Exception\Handler\AbstractHttpErrorHandler
{
    public const ERROR_MSG = 'SYSTEM_ERROR';

    public const SUB_ERROR_MSG = 'The system is busy, please try again later!';

    public const ERROR_CODE = 20000;

    public const SUB_ERROR_CODE = 'isp.unknown.error';

    /**
     * @inheritDoc
     */
    public function handle(Throwable $e, Response $response): Response
    {
        $wrapper = Wrapper::new()
            ->setMsg($e->getMessage() ?? static::ERROR_MSG)
            ->setCode($e->getCode() ?? static::ERROR_CODE)
            ->setSubMsg(static::SUB_ERROR_MSG)
            ->setSubCode(static::SUB_ERROR_CODE);

        if ($e instanceof AbstractException) {
            $wrapper->setSubMsg($e->getSubMsg() ?? static::SUB_ERROR_MSG)
                ->setSubCode($e->getSubCode() ?? static::SUB_ERROR_CODE);
            $subMsg = $e->getSubMsg() ?? static::SUB_ERROR_MSG;
        }
        Error::log('File Name:' . $e->getFile() . ' Error Line:' . $e->getLine() . ' Error Message:' . ($subMsg ?? static::SUB_ERROR_CODE) . '--' . $e->getMessage());

        // 404: 服务器找不到请求的页面
        return $response->withStatus($this->withStatus())
            ->withAddedHeader('content-type', 'application/json')
            ->withData($wrapper->toArray());
    }

    /** @return int */
    abstract public function withStatus(): int;
}
