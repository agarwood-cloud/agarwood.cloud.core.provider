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
use Swoft\Http\Message\Response;
use Swoft\Http\Server\Exception\Handler\AbstractHttpErrorHandler;
use Swoft\Log\Error;
use Throwable;
use function get_class;
use function sprintf;
use const APP_DEBUG;

/**
 * @ExceptionHandler(Throwable::class)
 */
class HttpExceptionHandler extends AbstractHttpErrorHandler
{
    /**
     * @param Throwable $e
     * @param Response $response
     *
     * @return Response
     */
    public function handle(Throwable $e, Response $response): Response
    {
        // Log
        Error::log('File Name:' . $e->getFile() . ' Error Line:' . $e->getLine() . ' Error Message:' . $e->getMessage());

        $data = [
            'code' => $e->getCode(),
            'msg'  => $e->getMessage()
        ];

        if (APP_DEBUG) {
            $data = array_merge($data, [
                'error' => sprintf('(%s) %s', get_class($e), $e->getMessage()),
                'file'  => sprintf('At %s line %d', $e->getFile(), $e->getLine()),
                'trace' => $e->getTraceAsString()
            ]);
        }

        return $response->withStatus(500)->withData($data);
    }
}
