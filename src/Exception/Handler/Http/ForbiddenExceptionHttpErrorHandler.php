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

use Agarwood\Core\Exception\ForbiddenException;
use Swoft\Error\Annotation\Mapping\ExceptionHandler;

/**
 * @ExceptionHandler(ForbiddenException::class)
 */
class ForbiddenExceptionHttpErrorHandler extends AbstractHttpErrorHandler
{
    public const ERROR_MSG = 'REQUEST_FORBIDDEN';

    public const SUB_ERROR_MSG = 'Request was refused';

    public const ERROR_CODE = 40003;

    public const SUB_ERROR_CODE = 'isv.request.forbidden';

    /**
     * @inheritDoc
     */
    public function withStatus(): int
    {
        return 403;
    }
}
