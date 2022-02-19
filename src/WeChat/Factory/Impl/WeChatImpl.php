<?php declare(strict_types=1);
/**
 * This file is part of Agarwood Cloud.
 *
 * @link     https://www.agarwood-cloud.com
 * @document https://www.agarwood-cloud.com/docs
 * @contact  676786620@qq.com
 * @author   agarwood
 */

namespace Agarwood\Core\WeChat\Factory\Impl;

use Agarwood\Core\WeChat\Factory\WeChat;
use EasyWeChat\Factory;
use Swoft\Context\Context;
use Swoft\Contract\ContextInterface;
use Swoft\Http\Server\HttpContext;
use Swoft\Rpc\Server\ServiceContext;
use Swoft\Server\Context\ShutdownContext;
use Swoft\Server\Context\StartContext;
use Swoft\Server\Context\WorkerStartContext;
use Swoft\Server\Context\WorkerStopContext;

/**
 * @\Swoft\Bean\Annotation\Mapping\Bean()
 */
class WeChatImpl implements WeChat
{
    /**
     * @inheritDoc
     */
    public function officialAccount(array $config): \EasyWeChat\OfficialAccount\Application
    {
        $app = Factory::officialAccount($config);
        $this->initialize($app);
        return $app;
    }

    /**
     * @inheritDoc
     */
    public function officialAccountConsole(array $config): \EasyWeChat\OfficialAccount\Application
    {
        return Factory::officialAccount($config);
    }

    /**
     * @inheritDoc
     */
    public function openPlatform(array $config): \EasyWeChat\OpenPlatform\Application
    {
        $app = Factory::openPlatform($config);
        $this->initialize($app);
        return $app;
    }

    /**
     * @inheritDoc
     */
    public function miniProgram(array $config): \EasyWeChat\MiniProgram\Application
    {
        $app = Factory::miniProgram($config);
        $this->initialize($app);
        return $app;
    }

    /**
     * @inheritDoc
     */
    public function payment(array $config): \EasyWeChat\Payment\Application
    {
        return Factory::payment($config);
    }

    /**
     * @inheritDoc
     */
    public function basicService(array $config): \EasyWeChat\BasicService\Application
    {
        $app = Factory::basicService($config);
        $this->initialize($app);
        return $app;
    }

    /**
     * @inheritDoc
     */
    public function work(array $config): \EasyWeChat\Work\Application
    {
        $app = Factory::work($config);
        $this->initialize($app);
        return $app;
    }

    /**
     * @inheritDoc
     */
    public function openWork(array $config): \EasyWeChat\OpenWork\Application
    {
        $app = Factory::openWork($config);
        $this->initialize($app);
        return $app;
    }

    /**
     * @inheritDoc
     */
    public function microMerchant(array $config): \EasyWeChat\MicroMerchant\Application
    {
        $app = Factory::microMerchant($config);
        $this->initialize($app);
        return $app;
    }

    /**
     * init
     *
     * @param  $app
     */
    private function initialize($app): void
    {
        $get     = $this->context()->getRequest()->get()  ?? [];
        $post    = $this->context()->getRequest()->post() ?? [];
        $attr    = [];
        $cookies = $this->context()->getRequest()->cookie() ?? [];
        // $files   = $this->context()->getRequest()->getUploadedFiles() ?? [];
        $files   = [];
        $server  = $this->context()->getRequest()->server() ?? [];
        $raw     = $this->context()->getRequest()->raw()    ?? [];

        /**
         * @var $app \EasyWeChat\OfficialAccount\Application
         * @var $app \EasyWeChat\MiniProgram\Application
         * @var $app \EasyWeChat\BasicService\Application
         * @var $app \EasyWeChat\OpenPlatform\Application
         * @var $app \EasyWeChat\MicroMerchant\Application
         * @var $app \EasyWeChat\OpenWork\Application
         * @var $app \EasyWeChat\Work\Application
         * @var $app \EasyWeChat\Payment\Application
         */
        $app->request->initialize($get, $post, $attr, $cookies, $files, $server, $raw);
    }

    /**
     * @return ContextInterface|HttpContext|ServiceContext|StartContext|WorkerStartContext|WorkerStopContext|ShutdownContext
     */
    private function context(): ContextInterface
    {
        return Context::get(true);
    }
}
