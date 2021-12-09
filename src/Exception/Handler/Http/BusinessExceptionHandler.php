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

use Agarwood\Core\Exception\BusinessException;
use Swoft\Error\Annotation\Mapping\ExceptionHandler;

/**
 * @ExceptionHandler(BusinessException::class)
 */
class BusinessExceptionHandler extends AbstractHttpErrorHandler
{
    public const ERROR_MSG = 'BUSINESS_BUSY';

    public const SUB_ERROR_MSG = 'Business is busy now, please try again later!';

    public const ERROR_CODE = 20002;

    public const SUB_ERROR_CODE = 'isv.business.busy';

    /**
     * @inheritDoc
     */
    public function withStatus(): int
    {
        return 202;
    }
}
