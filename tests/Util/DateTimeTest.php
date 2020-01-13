<?php

namespace YotiTest\Util;

use Yoti\Util\DateTime;
use YotiTest\TestCase;

/**
 * @coversDefaultClass \Yoti\Util\DateTime
 */
class DateTimeTest extends TestCase
{
    /**
     * @covers ::stringToDateTime
     *
     * @dataProvider validDateStringProvider
     */
    public function testStringToDateTime($timeStampString, $expectedOutput)
    {
        $dateTime = DateTime::stringToDateTime($timeStampString);

        $this->assertEquals(
            $expectedOutput,
            $dateTime->format(DateTime::RFC3339)
        );
    }

    /**
     * Provides valid dates and their expected RFC3339 representation with microseconds.
     */
    public function validDateStringProvider()
    {
        return [
            [ '2006-01-02', '2006-01-02T00:00:00.000000+00:00' ],
            [ '2006-01-02T22:04:05Z', '2006-01-02T22:04:05.000000+00:00' ],
            [ '2006-01-02T22:04:05.1Z', '2006-01-02T22:04:05.100000+00:00' ],
            [ '2006-01-02T22:04:05.12Z', '2006-01-02T22:04:05.120000+00:00' ],
            [ '2006-01-02T22:04:05.123Z', '2006-01-02T22:04:05.123000+00:00' ],
            [ '2006-01-02T22:04:05.1234Z', '2006-01-02T22:04:05.123400+00:00' ],
            [ '2006-01-02T22:04:05.12345Z', '2006-01-02T22:04:05.123450+00:00' ],
            [ '2006-01-02T22:04:05.123456Z', '2006-01-02T22:04:05.123456+00:00' ],
            [ '2002-10-02T10:00:00-05:00', '2002-10-02T10:00:00.000000-05:00' ],
            [ '2002-10-02T10:00:00+11:00', '2002-10-02T10:00:00.000000+11:00' ],
            ['1920-03-13T19:50:53.000001Z', '1920-03-13T19:50:53.000001+00:00'],
            ['1920-03-13T19:50:53.999999Z', '1920-03-13T19:50:53.999999+00:00'],
            ['1920-03-13T19:50:53.000100Z', '1920-03-13T19:50:53.000100+00:00'],
            ['1920-03-13T19:50:53.999999+04:00', '1920-03-13T19:50:53.999999+04:00'],
        ];
    }

    /**
     * @covers ::stringToDateTime
     *
     * @expectedException \Yoti\Exception\DateTimeException
     * @expectedExceptionMessage Could not parse string to DateTime
     */
    public function testInvalidTimestamp()
    {
        DateTime::stringToDateTime('some-invalid-date');
    }

    /**
     * @covers ::stringToDateTime
     *
     * @expectedException \InvalidArgumentException
     *
     * @dataProvider emptyTimestampProvider
     */
    public function testEmptyTimestamp($emptyDateString, $exceptionMessage)
    {
        $this->expectExceptionMessage($exceptionMessage);
        DateTime::stringToDateTime($emptyDateString);
    }

    /**
     * Provides empty timestamps
     */
    public function emptyTimestampProvider()
    {
        return [
            [ null, 'value must be a string' ],
            [ '', 'value cannot be empty' ],
        ];
    }
}
