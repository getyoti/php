<?php

namespace YotiTest\Http;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Yoti\Http\Request;
use YotiTest\TestCase;

/**
 * @coversDefaultClass \Yoti\Http\Request
 */
class RequestTest extends TestCase
{
    /**
     * @covers ::execute
     * @covers ::__construct
     */
    public function testExecute()
    {
        $someRequestMessage = $this->createMock(RequestInterface::class);
        $someResponseMessage = $this->createMock(ResponseInterface::class);

        $someClient = $this->createMock(ClientInterface::class);
        $someClient->expects($this->exactly(1))
            ->method('sendRequest')
            ->with($someRequestMessage)
            ->willReturn($someResponseMessage);

        $request = new Request($someRequestMessage, $someClient);

        $this->assertSame($someResponseMessage, $request->execute());
    }
}
