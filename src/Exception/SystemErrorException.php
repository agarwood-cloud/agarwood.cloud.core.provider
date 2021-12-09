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

class SystemErrorException extends AbstractException
{
    public function __construct(
        string $subMsg = 'The system is busy, please try again later!',
        int $code = 50000,
        string $subCode = 'isv.system.busy',
        string $message = 'SYSTEM_BUSY',
        Throwable $previous = null
    ) {
        parent::__construct($subMsg, $subCode, $message, $code, $previous);
    }
}
