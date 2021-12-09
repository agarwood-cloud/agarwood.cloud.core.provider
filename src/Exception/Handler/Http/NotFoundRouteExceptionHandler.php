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

use Swoft\Error\Annotation\Mapping\ExceptionHandler;
use Swoft\Http\Server\Exception\NotFoundRouteException;

/**
 * @ExceptionHandler(NotFoundRouteException::class)
 */
class NotFoundRouteExceptionHandler extends AbstractHttpErrorHandler
{
    public const ERROR_MSG = 'ROUTE_NOT_FOUND';

    public const SUB_ERROR_MSG = 'Route not found！';

    public const ERROR_CODE = 40002;

    public const SUB_ERROR_CODE = 'isv.route.not.found';

    public function withStatus(): int
    {
        return 404;
    }
}
