<?php

namespace YotiTest\Aml;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Yoti\Aml\Country;
use Yoti\Aml\Address;
use Yoti\Aml\Profile;
use Yoti\Aml\Result;
use Yoti\Aml\Service;
use Yoti\Util\Config;
use Yoti\Util\PemFile;
use YotiTest\TestCase;

use function GuzzleHttp\Psr7\stream_for;

/**
 * @coversDefaultClass \Yoti\Aml\Service
 */
class ServiceTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::performCheck
     * @covers ::validateAmlResult
     * @covers \Yoti\Aml\Address::__construct
     * @covers \Yoti\Aml\Profile::__construct
     * @covers \Yoti\Aml\Country::__construct
     */
    public function testPerformCheck()
    {
        $expectedPathPattern = sprintf(
            '~^%s/aml-check\?appId=%s&nonce=.*?&timestamp=.*?~',
            CONNECT_BASE_URL,
            SDK_ID
        );

        $amlAddress = new Address(new Country('GBR'));
        $amlProfile = new Profile('Edward Richard George', 'Heath', $amlAddress);

        $response = $this->createMock(ResponseInterface::class);
        $response->method('getBody')->willReturn(stream_for(file_get_contents(AML_CHECK_RESULT_JSON)));
        $response->method('getStatusCode')->willReturn(200);

        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->exactly(1))
            ->method('sendRequest')
            ->with(
                $this->callback(function ($requestMessage) use ($amlProfile, $expectedPathPattern) {
                    $this->assertEquals('POST', $requestMessage->getMethod());
                    $this->assertEquals((string) $amlProfile, (string) $requestMessage->getBody());
                    $this->assertRegExp($expectedPathPattern, (string) $requestMessage->getUri());
                    $this->assertEquals('application/json', $requestMessage->getHeader('Content-Type')[0]);
                    return true;
                })
            )
            ->willReturn($response);

        $amlService = new Service(
            SDK_ID,
            PemFile::fromFilePath(PEM_FILE),
            new Config([
                Config::HTTP_CLIENT => $httpClient,
            ])
        );

        $result = $amlService->performCheck($amlProfile);

        $this->assertInstanceOf(Result::class, $result);
    }

    /**
     * @covers ::performCheck
     * @covers ::validateAmlResult
     * @covers ::getErrorMessage
     *
     * @dataProvider httpErrorStatusCodeProvider
     *
     * @expectedException \Yoti\Exception\AmlException
     */
    public function testPerformAmlCheckFailure($statusCode)
    {
        $this->expectExceptionMessage("Server responded with {$statusCode}");
        $amlService = $this->createServiceWithErrorResponse($statusCode);
        $amlService->performCheck($this->createMock(Profile::class));
    }

    /**
     * @covers ::performCheck
     * @covers ::validateAmlResult
     * @covers ::getErrorMessage
     *
     * @dataProvider httpErrorStatusCodeProvider
     *
     * @expectedException \Yoti\Exception\AmlException
     * @expectedExceptionMessage Error - some property: some message
     */
    public function testPerformAmlCheckFailureWithErrorMessage($statusCode)
    {
        $amlService = $this->createServiceWithErrorResponse(
            $statusCode,
            json_encode([
                'errors' => [
                    [
                        'message' => 'some message',
                        'property' => 'some property',
                    ]
                ]
            ])
        );

        $amlService->performCheck($this->createMock(Profile::class));
    }

    /**
     * @param int $statusCode
     *
     * @return \Yoti\Aml\Service
     */
    private function createServiceWithErrorResponse($statusCode, $body = '{}')
    {
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getBody')->willReturn(stream_for($body));
        $response->method('getStatusCode')->willReturn($statusCode);

        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient
            ->method('sendRequest')
            ->willReturn($response);

        return new Service(
            SDK_ID,
            PemFile::fromFilePath(PEM_FILE),
            new Config([
                Config::HTTP_CLIENT => $httpClient,
            ])
        );
    }
}
