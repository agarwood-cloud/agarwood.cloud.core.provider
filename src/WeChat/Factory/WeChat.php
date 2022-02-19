<?php declare(strict_types=1);
/**
 * This file is part of Agarwood Cloud.
 *
 * @link     https://www.agarwood-cloud.com
 * @document https://www.agarwood-cloud.com/docs
 * @contact  676786620@qq.com
 * @author   agarwood
 */

namespace Agarwood\Core\WeChat\Factory;

/**
 * @Swoft\Bean\Annotation\Mapping\Bean()
 */
interface WeChat
{
    /**
     * Get instance of EasyWechat's payment
     *
     * @param array $config
     *
     * @return \EasyWeChat\Payment\Application
     */
    public function payment(array $config): \EasyWeChat\Payment\Application;

    /**
     * Get instance of EasyWechat's miniProgram
     *
     * @param array $config
     *
     * @return \EasyWeChat\MiniProgram\Application
     */
    public function miniProgram(array $config): \EasyWeChat\MiniProgram\Application;

    /**
     * Get instance of EasyWechat's openPlatform
     *
     * @param array $config
     *
     * @return \EasyWeChat\OpenPlatform\Application
     */
    public function openPlatform(array $config): \EasyWeChat\OpenPlatform\Application;

    /**
     * Get instance of EasyWechat's officialAccount
     *
     * @param array $config
     *
     * @return \EasyWeChat\OfficialAccount\Application
     */
    public function officialAccount(array $config): \EasyWeChat\OfficialAccount\Application;

    /**
     * Get instance of EasyWechat's officialAccount
     *
     * @param array $config
     *
     * @return \EasyWeChat\OfficialAccount\Application
     */
    public function officialAccountConsole(array $config): \EasyWeChat\OfficialAccount\Application;

    /**
     * Get instance of EasyWechat's basicService
     *
     * @param array $config
     *
     * @return \EasyWeChat\BasicService\Application
     */
    public function basicService(array $config): \EasyWeChat\BasicService\Application;

    /**
     * Get instance of EasyWechat's work
     *
     * @param array $config
     *
     * @return \EasyWeChat\Work\Application
     */
    public function work(array $config): \EasyWeChat\Work\Application;

    /**
     * Get instance of EasyWechat's openWork
     *
     * @param array $config
     *
     * @return \EasyWeChat\OpenWork\Application
     */
    public function openWork(array $config): \EasyWeChat\OpenWork\Application;

    /**
     * Get instance of EasyWechat's microMerchant
     *
     * @param array $config
     *
     * @return \EasyWeChat\MicroMerchant\Application
     */
    public function microMerchant(array $config): \EasyWeChat\MicroMerchant\Application;
}
