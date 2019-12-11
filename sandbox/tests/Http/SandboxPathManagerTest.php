<?php

namespace SandboxTest\Http;

use YotiSandbox\Http\SandboxPathManager;
use YotiTest\TestCase;

/**
 * @coversDefaultClass \YotiSandbox\Http\SandboxPathManager
 */
class SandboxPathManagerTest extends TestCase
{
    const SOME_TOKEN_PATH = 'some-token-path';
    const SOME_PROFILE_PATH = 'some-profile-path';

    /**
     * @var \YotiSandbox\Http\SandboxPathManager
     */
    private $sandboxPathManager;

    /**
     * Setup SandboxPathManager
     */
    public function setup()
    {
        $this->sandboxPathManager = new SandboxPathManager(
            self::SOME_TOKEN_PATH,
            self::SOME_PROFILE_PATH
        );
    }

    /**
     * @covers ::getTokenApiPath
     * @covers ::__construct
     */
    public function testGetTokenApiPath()
    {
        $this->assertEquals(
            self::SOME_TOKEN_PATH,
            $this->sandboxPathManager->getTokenApiPath()
        );
    }

    /**
     * @covers ::getProfileApiPath
     * @covers ::__construct
     */
    public function testGetProfileApiPath()
    {
        $this->assertEquals(
            self::SOME_PROFILE_PATH,
            $this->sandboxPathManager->getProfileApiPath()
        );
    }
}
