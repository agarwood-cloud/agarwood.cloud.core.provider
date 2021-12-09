<?php declare(strict_types=1);
/**
 * This file is part of Agarwood Cloud.
 *
 * @link     https://www.agarwood-cloud.com
 * @document https://www.agarwood-cloud.com/docs
 * @contact  676786620@qq.com
 * @author   agarwood
 */

namespace Agarwood\Core\Constant;

class GlobalConstant
{
    /** @var float|int Maximum file upload：5M */
    public const FILE_MAX_SIZE = 5 * 1024 * 1024;

    public const X_FORWARDED_FOR = 'X-Forwarded-For';

    public const X_REAL_IP = 'X-Real-IP';

    public const PROXY_CLIENT_IP = 'Proxy-Client-IP';

    public const WL_PROXY_CLIENT_IP = 'WL-Proxy-Client-IP';

    public const HTTP_CLIENT_IP = 'HTTP_CLIENT_IP';

    public const HTTP_X_FORWARDED_FOR = 'HTTP_X_FORWARDED_FOR';

    public const LOCALHOST_IP = '127.0.0.1';

    public const LOCALHOST_IP_16 = '0:0:0:0:0:0:0:1';

    public const X_IP_LENGTH = 15;

    public const DEV_PROFILE = 'dev';

    public const TEST_PROFILE = 'test';

    public const PRO_PROFILE = 'pro';

    public const RES_SUCCESS_MSG = 'SUCCESS';

    public const DEFAULT_SUCCESS_CODE = 0;

    public const CONTENT_JSON = 'application/json';

    public const HTTP_CHARSET = 'UTF-8';

    public const RES_ERROR_MSG = 'ERROR!';

    public const DEFAULT_ERROR_CODE = -1;
}
