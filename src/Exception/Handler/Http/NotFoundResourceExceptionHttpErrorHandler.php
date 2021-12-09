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

use Agarwood\Core\Exception\NotFoundResourceException;
use Swoft\Error\Annotation\Mapping\ExceptionHandler;

/**
 * @ExceptionHandler(NotFoundResourceException::class)
 */
class NotFoundResourceExceptionHttpErrorHandler extends AbstractHttpErrorHandler
{
    public const ERROR_MSG = 'INVALID_RESOURCE';

    public const SUB_ERROR_MSG = 'The specified resource could not be found!';

    public const ERROR_CODE = 40004;

    public const SUB_ERROR_CODE = 'isp.not.found.resource';

    /**
     * @inheritDoc
     */
    public function withStatus(): int
    {
        return 404;
    }
}
