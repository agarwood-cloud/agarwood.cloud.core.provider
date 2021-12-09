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

class ValidateException extends AbstractException
{
    public function __construct(
        string $subMsg = 'The parameter could not be verified, please check again!',
        int $code = 40000,
        string $subCode = 'isv.validate.exception',
        string $message = 'PARAMS_VALIDATE_EXCEPTION',
        Throwable $previous = null
    ) {
        parent::__construct($subMsg, $subCode, $message, $code, $previous);
    }
}
