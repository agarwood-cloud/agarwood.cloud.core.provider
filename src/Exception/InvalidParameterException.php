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

class InvalidParameterException extends AbstractException
{
    public function __construct(
        string $subMsg = 'Parameter validation failed, please check again!',
        int $code = 40002,
        string $subCode = 'isv.invalid.params',
        string $message = 'INVALID_PARAMS',
        Throwable $previous = null
    ) {
        parent::__construct($subMsg, $subCode, $message, $code, $previous);
    }
}
