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

use Agarwood\Core\Util\Wrapper;
use Swoft\Error\Annotation\Mapping\ExceptionHandler;
use Swoft\Http\Message\Response;
use Swoft\Http\Server\Exception\Handler\AbstractHttpErrorHandler;
use Swoft\Validator\Exception\ValidatorException;
use Throwable;

/**
 * @ExceptionHandler(ValidatorException::class)
 */
class ValidatorExceptionHandler extends AbstractHttpErrorHandler
{
    public const ERROR_MSG = 'PARAMS_VALIDATE_EXCEPTION';

    public const SUB_ERROR_MSG = 'Parameter verification failed, please check and try again!';

    public const ERROR_CODE = 40000;

    public const SUB_ERROR_CODE = 'isv.validate.exception';

    /**
     * @inheritDoc
     */
    public function handle(Throwable $e, Response $response): Response
    {
        $wrapper = Wrapper::new()
            ->setData([
                $e->getMessage()
            ])
            // ->setDebug($e)
            ->setMsg(static::ERROR_MSG)
            ->setSubMsg(static::SUB_ERROR_MSG)
            ->setCode(static::ERROR_CODE)
            ->setSubCode(static::SUB_ERROR_CODE);
        return $response->withStatus(400)
            ->withAddedHeader('content-type', 'application/json')
            ->withData($wrapper->toArray());
    }
}
