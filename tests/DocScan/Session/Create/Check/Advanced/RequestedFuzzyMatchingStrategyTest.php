<?php

namespace Yoti\Test\DocScan\Session\Create\Check\Advanced;

use PHPUnit\Framework\Assert;
use Yoti\DocScan\Session\Create\Check\Advanced\RequestedFuzzyMatchingStrategyBuilder;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Create\Check\Advanced\RequestedFuzzyMatchingStrategyBuilder
 */
class RequestedFuzzyMatchingStrategyTest extends TestCase
{
    /**
     * @var float
     */
    private const SOME_FUZZINESS = 0.56;

    /**
     * @test
     * @covers ::build
     * @covers ::withFuzziness
     * @covers \Yoti\DocScan\Session\Create\Check\Advanced\RequestedFuzzyMatchingStrategy::getFuzziness
     * @covers \Yoti\DocScan\Session\Create\Check\Advanced\RequestedFuzzyMatchingStrategy::__construct
     */
    public function builderShouldBuildWithCorrectFuzziness(): void
    {
        $result = (new RequestedFuzzyMatchingStrategyBuilder())
            ->withFuzziness(self::SOME_FUZZINESS)
            ->build();

        Assert::assertNotNull($result);
        Assert::assertEquals(self::SOME_FUZZINESS, $result->getFuzziness());
    }
}
