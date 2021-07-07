<?php

namespace Yoti\Test\DocScan\Session\Create\Check;

use PHPUnit\Framework\Assert;
use Yoti\DocScan\Session\Create\Check\Advanced\RequestedExactMatchingStrategy;
use Yoti\DocScan\Session\Create\Check\Advanced\RequestedSearchProfileSources;
use Yoti\DocScan\Session\Create\Check\RequestedCustomAccountWatchlistAdvancedCaConfigBuilder;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Create\Check\RequestedCustomAccountWatchlistAdvancedCaConfigBuilder
 */
class RequestedCustomAccountWatchlistAdvancedCaConfigTest extends TestCase
{
    private const SOME_REMOVE_DECEASED = true;
    private const SOME_SHARE_URL = false;
    private const SOME_API_KEY = "someApiKey";
    private const SOME_MONITORING = true;
    private const SOME_TAGS = ['some' => 'some2'];
    private const SOME_CLIENT_REF = "someClientRef";
    /**
     * @var RequestedExactMatchingStrategy
     */
    private $exactMatchingStrategy;
    /**
     * @var RequestedSearchProfileSources
     */
    private $profileSource;

    public function setup(): void
    {
        $this->exactMatchingStrategy = new RequestedExactMatchingStrategy();
        $this->profileSource = new RequestedSearchProfileSources('some_string');
        parent::setup();
    }

    /**
     * @test
     * @covers ::build
     * @covers ::withApiKey
     * @covers ::withMonitoring
     * @covers ::withClientRef
     * @covers ::withTags
     * @covers \Yoti\DocScan\Session\Create\Check\Contracts\RequestedWatchlistAdvancedCaConfigBuilder::withMatchingStrategy
     * @covers \Yoti\DocScan\Session\Create\Check\Contracts\RequestedWatchlistAdvancedCaConfigBuilder::withSources
     * @covers \Yoti\DocScan\Session\Create\Check\Contracts\RequestedWatchlistAdvancedCaConfigBuilder::withShareUrl
     * @covers \Yoti\DocScan\Session\Create\Check\Contracts\RequestedWatchlistAdvancedCaConfigBuilder::withRemoveDeceased
     * @covers \Yoti\DocScan\Session\Create\Check\Contracts\RequestedWatchlistAdvancedCaConfig::getMatchingStrategy
     * @covers \Yoti\DocScan\Session\Create\Check\Contracts\RequestedWatchlistAdvancedCaConfig::getSources
     * @covers \Yoti\DocScan\Session\Create\Check\Contracts\RequestedWatchlistAdvancedCaConfig::getShareUrl
     * @covers \Yoti\DocScan\Session\Create\Check\Contracts\RequestedWatchlistAdvancedCaConfig::getRemoveDeceased
     * @covers \Yoti\DocScan\Session\Create\Check\Contracts\RequestedWatchlistAdvancedCaConfig::__construct
     * @covers \Yoti\DocScan\Session\Create\Check\Contracts\RequestedWatchlistAdvancedCaConfig::jsonSerialize
     * @covers \Yoti\DocScan\Session\Create\Check\RequestedCustomAccountWatchlistAdvancedCaConfig::getTags
     * @covers \Yoti\DocScan\Session\Create\Check\RequestedCustomAccountWatchlistAdvancedCaConfig::getMonitoring
     * @covers \Yoti\DocScan\Session\Create\Check\RequestedCustomAccountWatchlistAdvancedCaConfig::getClientRef
     * @covers \Yoti\DocScan\Session\Create\Check\RequestedCustomAccountWatchlistAdvancedCaConfig::getApiKey
     * @covers \Yoti\DocScan\Session\Create\Check\RequestedCustomAccountWatchlistAdvancedCaConfig::__construct
     */
    public function builderShouldBuildWithCorrectProperties()
    {
        $result = (new RequestedCustomAccountWatchlistAdvancedCaConfigBuilder())
            ->withRemoveDeceased(self::SOME_REMOVE_DECEASED)
            ->withShareUrl(self::SOME_SHARE_URL)
            ->withSources($this->profileSource)
            ->withMatchingStrategy($this->exactMatchingStrategy)
            ->withApiKey(self::SOME_API_KEY)
            ->withClientRef(self::SOME_CLIENT_REF)
            ->withMonitoring(self::SOME_MONITORING)
            ->withTags(self::SOME_TAGS)
            ->build();

        Assert::assertNotNull($result);
        Assert::assertEquals(self::SOME_REMOVE_DECEASED, $result->getRemoveDeceased());
        Assert::assertEquals(self::SOME_SHARE_URL, $result->getShareUrl());
        Assert::assertEquals($this->profileSource, $result->getSources());
        Assert::assertEquals($this->exactMatchingStrategy, $result->getMatchingStrategy());
        Assert::assertEquals(self::SOME_API_KEY, $result->getApiKey());
        Assert::assertEquals(self::SOME_CLIENT_REF, $result->getClientRef());
        Assert::assertEquals(self::SOME_MONITORING, $result->getMonitoring());
        Assert::assertEquals(self::SOME_TAGS, $result->getTags());
    }
}
