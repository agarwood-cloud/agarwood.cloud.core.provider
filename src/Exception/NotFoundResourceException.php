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

use Throwable;

class NotFoundResourceException extends AbstractException
{
    public function __construct(
        string $subMsg = 'The resource has been deleted or does not exist, please check again!',
        int $code = 40004,
        string $subCode = 'isv.invalid.resource',
        string $message = 'INVALID_RESOURCE',
        Throwable $previous = null
    ) {
        parent::__construct($subMsg, $subCode, $message, $code, $previous);
    }
}
