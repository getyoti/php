<?php

namespace Yoti\Test\DocScan\Session\Create\Check;

use PHPUnit\Framework\Assert;
use Yoti\DocScan\Session\Create\Check\Advanced\RequestedExactMatchingStrategy;
use Yoti\DocScan\Session\Create\Check\Advanced\RequestedSearchProfileSources;
use Yoti\DocScan\Session\Create\Check\RequestedYotiAccountWatchlistAdvancedCaConfigBuilder;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Create\Check\RequestedYotiAccountWatchlistAdvancedCaConfigBuilder
 */
class RequestedYotiAccountWatchlistAdvancedCaConfigTest extends TestCase
{
    private const SOME_REMOVE_DECEASED = true;
    private const SOME_SHARE_URL = false;

    /**
     * @test
     * @covers ::build
     * @covers \Yoti\DocScan\Session\Create\Check\Contracts\RequestedWatchlistAdvancedCaConfigBuilder::withMatchingStrategy
     * @covers \Yoti\DocScan\Session\Create\Check\Contracts\RequestedWatchlistAdvancedCaConfigBuilder::withSources
     * @covers \Yoti\DocScan\Session\Create\Check\Contracts\RequestedWatchlistAdvancedCaConfigBuilder::withShareUrl
     * @covers \Yoti\DocScan\Session\Create\Check\Contracts\RequestedWatchlistAdvancedCaConfigBuilder::withRemoveDeceased
     * @covers \Yoti\DocScan\Session\Create\Check\Contracts\RequestedWatchlistAdvancedCaConfigBuilder::build
     */
    public function builderShouldBuildWithCorrectProperties(): void
    {
        $exactMatchingStrategy = new RequestedExactMatchingStrategy();
        $profileSource = new RequestedSearchProfileSources('some_string');

        $result = (new RequestedYotiAccountWatchlistAdvancedCaConfigBuilder())
            ->withRemoveDeceased(self::SOME_REMOVE_DECEASED)
            ->withShareUrl(self::SOME_SHARE_URL)
            ->withSources($profileSource)
            ->withMatchingStrategy($exactMatchingStrategy)
            ->build();

        Assert::assertNotNull($result);
        Assert::assertEquals(self::SOME_REMOVE_DECEASED, $result->getRemoveDeceased());
        Assert::assertEquals(self::SOME_SHARE_URL, $result->getShareUrl());
        Assert::assertEquals($profileSource, $result->getSources());
        Assert::assertEquals($exactMatchingStrategy, $result->getMatchingStrategy());
    }
}
