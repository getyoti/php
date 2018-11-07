<?php

namespace YotiTest\Util;

use YotiTest\TestCase;
use Yoti\Util\Config;

class ConfigTest extends TestCase
{
    public function testConfigInstance()
    {
        $this->assertInstanceOf(Config::class, Config::getInstance());
    }

    public function testGetSDKVersion()
    {
        $sdkVerision = Config::getInstance()->get('version');
        $this->assertNotNull($sdkVerision);
    }
}