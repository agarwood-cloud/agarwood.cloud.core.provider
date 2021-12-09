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

use Agarwood\Core\Exception\SystemErrorException;
use Swoft\Error\Annotation\Mapping\ExceptionHandler;

/**
 * @ExceptionHandler(SystemErrorException::class)
 */
class SystemErrorExceptionHttpErrorHandler extends AbstractHttpErrorHandler
{
    public const ERROR_MSG = 'SYSTEM_BUSY';

    public const SUB_ERROR_MSG = 'The system is busy now, please try again later!';

    public const ERROR_CODE = 50000;

    public const SUB_ERROR_CODE = 'isp.system.busy';

    /**
     * @inheritDoc
     */
    public function withStatus(): int
    {
        return 500;
    }
}
