<?php

namespace Yoti\Test\DocScan\Session\Create\Check\Advanced;

use PHPUnit\Framework\Assert;
use Yoti\DocScan\Session\Create\Check\Advanced\RequestedExactMatchingStrategyBuilder;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Create\Check\Advanced\RequestedExactMatchingStrategyBuilder
 */
class RequestedExactMatchingStrategyTest extends TestCase
{
    /**
     * @test
     * @covers ::build
     * @covers \Yoti\DocScan\Session\Create\Check\Advanced\RequestedExactMatchingStrategy::isExactMatch
     */
    public function builderShouldBuildRequestedExactMatchingStrategyTest(): void
    {
        $result = (new RequestedExactMatchingStrategyBuilder())->build();

        Assert::assertNotNull($result);
        Assert::assertEquals(true, $result->isExactMatch());
    }
}
