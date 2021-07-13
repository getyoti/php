<?php

declare(strict_types=1);

namespace Yoti\ShareUrl;

use Yoti\Constants;
use Yoti\Exception\ShareUrlException;
use Yoti\Http\Payload;
use Yoti\Http\RequestBuilder;
use Yoti\Util\Config;
use Yoti\Util\Json;
use Yoti\Util\PemFile;

class Service
{
    /**
     * @var string
     */
    private $sdkId;

    /**
     * @var PemFile
     */
    private $pemFile;

    /**
     * @var Config
     */
    private $config;

    /**
     * @param string $sdkId
     * @param PemFile $pemFile
     * @param Config $config
     */
    public function __construct(string $sdkId, PemFile $pemFile, Config $config)
    {
        $this->sdkId = $sdkId;
        $this->pemFile = $pemFile;
        $this->config = $config;
    }

    /**
     * @param DynamicScenario $dynamicScenario
     *
     * @return Result
     *
     * @throws ShareUrlException
     */
    public function createShareUrl(DynamicScenario $dynamicScenario): Result
    {
        $response = (new RequestBuilder($this->config))
            ->withBaseUrl($this->config->getApiUrl() ?? Constants::API_URL)
            ->withEndpoint(sprintf('/qrcodes/apps/%s', $this->sdkId))
            ->withQueryParam('appId', $this->sdkId)
            ->withPost()
            ->withPayload(Payload::fromJsonData($dynamicScenario))
            ->withPemFile($this->pemFile)
            ->build()
            ->execute();

        $httpCode = $response->getStatusCode();
        if ($httpCode < 200 || $httpCode > 299) {
            throw new ShareUrlException("Server responded with {$httpCode}", $response);
        }

        return new Result(Json::decode((string) $response->getBody()));
    }
}
