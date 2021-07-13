<?php

declare(strict_types=1);

namespace Yoti\Test\Aml;

use GuzzleHttp\Psr7;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Yoti\Aml\Address;
use Yoti\Aml\Country;
use Yoti\Aml\Profile;
use Yoti\Aml\Result;
use Yoti\Aml\Service;
use Yoti\Exception\base\YotiException;
use Yoti\Exception\PemFileException;
use Yoti\Test\TestCase;
use Yoti\Test\TestData;
use Yoti\Util\Config;
use Yoti\Util\PemFile;

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
            TestData::CONNECT_BASE_URL,
            TestData::SDK_ID
        );

        $amlAddress = new Address(new Country('GBR'));
        $amlProfile = new Profile('Edward Richard George', 'Heath', $amlAddress);

        $response = $this->createMock(ResponseInterface::class);
        $body = file_get_contents(TestData::AML_CHECK_RESULT_JSON);
        $response->method('getBody')->willReturn(Psr7\Utils::streamFor($body));
        $response->method('getStatusCode')->willReturn(200);

        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->exactly(1))
            ->method('sendRequest')
            ->with(
                $this->callback(function ($requestMessage) use ($amlProfile, $expectedPathPattern) {
                    $this->assertEquals('POST', $requestMessage->getMethod());
                    $this->assertEquals((string) $amlProfile, (string) $requestMessage->getBody());
                    $this->assertMatchesRegularExpression($expectedPathPattern, (string) $requestMessage->getUri());
                    $this->assertEquals('application/json', $requestMessage->getHeader('Content-Type')[0]);
                    return true;
                })
            )
            ->willReturn($response);

        $amlService = new Service(
            TestData::SDK_ID,
            PemFile::fromFilePath(TestData::PEM_FILE),
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
     */
    public function testPerformAmlCheckFailure($statusCode)
    {
        $this->expectException(YotiException::class);

        $amlService = $this->createServiceWithErrorResponse($statusCode);
        $amlService->performCheck($this->createMock(Profile::class));
    }

    /**
     * @covers ::performCheck
     * @covers ::validateAmlResult
     * @covers ::getErrorMessage
     *
     * @dataProvider httpErrorStatusCodeProvider
     */
    public function testPerformAmlCheckFailureWithErrorMessage($statusCode)
    {
        $this->expectException(YotiException::class);

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
     * @covers ::performCheck
     * @covers ::validateAmlResult
     * @covers ::getErrorMessage
     *
     * @dataProvider httpErrorStatusCodeProvider
     */
    public function testPerformAmlCheckFailureWithCode($statusCode)
    {
        $this->expectException(YotiException::class);

        $amlService = $this->createServiceWithErrorResponse(
            $statusCode,
            json_encode([
                'code' => 'SOME_CODE',
            ])
        );

        $amlService->performCheck($this->createMock(Profile::class));
    }

    /**
     * @covers ::performCheck
     * @covers ::validateAmlResult
     * @covers ::getErrorMessage
     *
     * @dataProvider httpErrorStatusCodeProvider
     */
    public function testPerformAmlCheckFailureWithCodeAndErrors($statusCode)
    {
        $this->expectException(YotiException::class);

        $amlService = $this->createServiceWithErrorResponse(
            $statusCode,
            json_encode([
                'code' => 'SOME_CODE',
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
     * @covers ::performCheck
     * @covers ::validateAmlResult
     * @covers ::getErrorMessage
     *
     * @dataProvider httpErrorStatusCodeProvider
     */
    public function testPerformAmlCheckFailureWithoutJsonResponse($statusCode)
    {
        $this->expectException(YotiException::class);

        $amlService = $this->createServiceWithErrorResponse(
            $statusCode,
            'some response',
            'text/html'
        );

        $amlService->performCheck($this->createMock(Profile::class));
    }

    /**
     * @param int $statusCode
     * @param string $body
     * @param string|null $contentType
     * @return Service
     * @throws PemFileException
     */
    private function createServiceWithErrorResponse($statusCode, $body = '{}', ?string $contentType = null)
    {
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getBody')->willReturn(Psr7\Utils::streamFor($body));
        $response->method('getStatusCode')->willReturn($statusCode);

        if ($contentType !== null) {
            $response->method('hasHeader')->willReturn(true);
            $response->method('getHeader')->willReturn([$contentType]);
        }

        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient
            ->method('sendRequest')
            ->willReturn($response);

        return new Service(
            TestData::SDK_ID,
            PemFile::fromFilePath(TestData::PEM_FILE),
            new Config([
                Config::HTTP_CLIENT => $httpClient,
            ])
        );
    }
}
