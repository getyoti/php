<?php

namespace Yoti\Test\DocScan\Session\Create\Check\Advanced;

use PHPUnit\Framework\Assert;
use Yoti\DocScan\Session\Create\Check\Advanced\RequestedSearchProfileSourcesBuilder;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Create\Check\Advanced\RequestedSearchProfileSourcesBuilder
 */
class RequestedSearchProfileSourcesTest extends TestCase
{
    /**
     * @var string
     */
    private const SOME_SEARCH_PROFILE = "someSearchProfile";

    /**
     * @test
     * @covers ::build
     * @covers ::withSearchProfile
     * @covers \Yoti\DocScan\Session\Create\Check\Advanced\RequestedSearchProfileSources::getSearchProfile
     * @covers \Yoti\DocScan\Session\Create\Check\Advanced\RequestedSearchProfileSources::__construct
     */
    public function builderShouldBuildWithCorrectSearchProfile(): void
    {
        $result = (new RequestedSearchProfileSourcesBuilder())
            ->withSearchProfile(self::SOME_SEARCH_PROFILE)
            ->build();

        Assert::assertNotNull($result);
        Assert::assertEquals(self::SOME_SEARCH_PROFILE, $result->getSearchProfile());
    }
}
