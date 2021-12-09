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

use Agarwood\Core\Exception\InvalidParameterException;
use Swoft\Error\Annotation\Mapping\ExceptionHandler;

/**
 * @ExceptionHandler(InvalidParameterException::class)
 */
class InvalidParameterExceptionHttpErrorHandler extends AbstractHttpErrorHandler
{
    public const ERROR_MSG = 'INVALID_PARAMS';

    public const SUB_ERROR_MSG = 'Error in request parameters!';

    public const ERROR_CODE = 40002;

    public const SUB_ERROR_CODE = 'isv.invalid.parameter';

    /**
     * @inheritDoc
     */
    public function withStatus(): int
    {
        return 402;
    }
}
