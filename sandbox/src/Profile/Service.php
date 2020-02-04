<?php

declare(strict_types=1);

namespace Yoti\Sandbox\Profile;

use Yoti\Constants;
use Yoti\Http\RequestBuilder;
use Yoti\Sandbox\Profile\Request\TokenRequest;
use Yoti\Sandbox\Profile\Response\TokenResponse;
use Yoti\Util\Config;
use Yoti\Util\PemFile;

class Service
{
    /**
     * Default sandbox API URL.
     */
    private const SANDBOX_URL = Constants::API_BASE_URL . '/sandbox/v1';

    /**
     * Token request endpoint.
     */
    private const TOKEN_REQUEST_ENDPOINT_FORMAT = "/apps/%s/tokens";

    /**
     * @var string
     */
    private $sdkId;

    /**
     * @var \Yoti\Util\PemFile
     */
    private $pemFile;

    /**
     * @var \Yoti\Util\Config
     */
    private $config;

    /**
     * SandboxClient constructor.
     *
     * @param string $sdkId
     *   The SDK identifier generated by Yoti Hub when you create your app.
     * @param \Yoti\Util\PemFile $pemFile
     *   PEM file
     * @param \Yoti\Util\Config $config
     *   SDK configuration options - {@see \Yoti\Util\Config} for available options.
     */
    public function __construct(
        string $sdkId,
        PemFile $pemFile,
        Config $config
    ) {
        $this->sdkId = $sdkId;
        $this->pemFile = $pemFile;
        $this->config = $config;
    }

    /**
     * @param \Yoti\Sandbox\Profile\Request\TokenRequest $tokenRequest
     *
     * @return string
     */
    public function setupSharingProfile(TokenRequest $tokenRequest): string
    {
        // Request endpoint
        $endpoint = sprintf(self::TOKEN_REQUEST_ENDPOINT_FORMAT, $this->sdkId);
        $response = (new RequestBuilder($this->config))
            ->withBaseUrl($this->config->getApiUrl() ?? self::SANDBOX_URL)
            ->withEndpoint($endpoint)
            ->withPost()
            ->withPemFile($this->pemFile)
            ->withPayload($tokenRequest->getPayload())
            ->withQueryParam('appId', $this->sdkId)
            ->build()
            ->execute();

        return (new TokenResponse($response))->getToken();
    }
}