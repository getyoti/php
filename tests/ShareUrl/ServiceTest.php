<?php

declare(strict_types=1);

namespace Yoti\Test\Service\ShareUrl;

use GuzzleHttp\Psr7;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Yoti\Constants;
use Yoti\Exception\base\YotiException;
use Yoti\Exception\PemFileException;
use Yoti\ShareUrl\DynamicScenario;
use Yoti\ShareUrl\DynamicScenarioBuilder;
use Yoti\ShareUrl\Policy\DynamicPolicyBuilder;
use Yoti\ShareUrl\Service;
use Yoti\Test\TestCase;
use Yoti\Test\TestData;
use Yoti\Util\Config;
use Yoti\Util\PemFile;

/**
 * @coversDefaultClass \Yoti\ShareUrl\Service
 */
class ServiceTest extends TestCase
{
    /**
     * @covers ::createShareUrl
     * @covers ::__construct
     */
    public function testCreateShareUrl()
    {
        $expectedUrl = sprintf(
            '%s/qrcodes/apps/%s?appId=%s',
            Constants::API_URL,
            TestData::SDK_ID,
            TestData::SDK_ID
        );
        $expectedUrlPattern = sprintf('~%s.*?nonce=.*?&timestamp=.*?~', preg_quote($expectedUrl));
        $expectedQrCode = 'https://dynamic-code.yoti.com/CAEaJDRjNTQ3M2IxLTNiNzktNDg3My1iMmM4LThiMTQxZDYwMjM5ODAC';
        $expectedRefId = '4c5473b1-3b79-4873-b2c8-8b141d602398';

        $dynamicScenario = (new DynamicScenarioBuilder())
            ->withCallbackEndpoint('/test-callback-url')
            ->withPolicy(
                (new DynamicPolicyBuilder())->build()
            )
            ->build();

        $response = $this->createMock(ResponseInterface::class);
        $response->method('getBody')->willReturn(Psr7\Utils::streamFor(json_encode([
            'qrcode' => $expectedQrCode,
            'ref_id' => $expectedRefId,
        ])));
        $response->method('getStatusCode')->willReturn(201);

        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient
            ->expects($this->once())
            ->method('sendRequest')
            ->with($this->callback(function ($request) use ($expectedUrlPattern, $dynamicScenario) {
                $this->assertMatchesRegularExpression($expectedUrlPattern, (string) $request->getUri());
                $this->assertEquals(json_encode($dynamicScenario), (string) $request->getBody());
                return true;
            }))
            ->willReturn($response);

        $service = new Service(
            TestData::SDK_ID,
            PemFile::fromFilePath(TestData::PEM_FILE),
            new Config([
                Config::HTTP_CLIENT => $httpClient,
            ])
        );

        $result = $service->createShareUrl($dynamicScenario);

        $this->assertEquals($expectedQrCode, $result->getShareUrl());
        $this->assertEquals($expectedRefId, $result->getRefId());
    }

    /**
     * @covers ::createShareUrl
     *
     * @dataProvider httpErrorStatusCodeProvider
     */
    public function testCreateShareUrlFailure($statusCode)
    {
        $this->expectException(YotiException::class);

        $yotiClient = $this->createServiceWithErrorResponse($statusCode);
        $yotiClient->createShareUrl($this->createMock(DynamicScenario::class));
    }

    /**
     * @param int $statusCode
     *
     * @return Service
     * @throws PemFileException
     */
    private function createServiceWithErrorResponse($statusCode)
    {
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getBody')->willReturn(Psr7\Utils::streamFor('{}'));
        $response->method('getStatusCode')->willReturn($statusCode);

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
