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

class ForbiddenException extends AbstractException
{
    public function __construct(
        string $subMsg = 'The request was rejected. Please try again later!',
        int $code = 40003,
        string $subCode = 'isv.request.forbidden',
        string $message = 'REQUEST_FORBIDDEN',
        Throwable $previous = null
    ) {
        parent::__construct($subMsg, $subCode, $message, $code, $previous);
    }
}
