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

class BusinessException extends AbstractException
{
    public function __construct(
        string $subMsg = 'Business is busy, please try again later!',
        int $code = 20002,
        string $subCode = 'isv.business.busy',
        string $message = 'BUSINESS_BUSY',
        Throwable $previous = null
    ) {
        parent::__construct($subMsg, $subCode, $message, $code, $previous);
    }
}
