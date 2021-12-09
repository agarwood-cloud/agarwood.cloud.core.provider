<?php declare(strict_types=1);
/**
 * This file is part of Agarwood Cloud.
 *
 * @link     https://www.agarwood-cloud.com
 * @document https://www.agarwood-cloud.com/docs
 * @contact  676786620@qq.com
 * @author   agarwood
 */

namespace Agarwood\Core\Middleware;

use GuzzleHttp\DefaultHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Swoft\Http\Server\Contract\MiddlewareInterface;
use Yurun\Util\Swoole\Guzzle\SwooleHandler;

/**
 * @\Swoft\Bean\Annotation\Mapping\Bean()
 */
class GuzzleHeaderMiddleware implements MiddlewareInterface
{
    //Guzzle Coroutine
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        DefaultHandler::setDefaultHandler(SwooleHandler::class);
        return $handler->handle($request);
    }
}
