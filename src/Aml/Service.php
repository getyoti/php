<?php

declare(strict_types=1);

namespace Yoti\Aml;

use Psr\Http\Message\ResponseInterface;
use Yoti\Constants;
use Yoti\Exception\AmlException;
use Yoti\Exception\base\YotiException;
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
     * @var \Yoti\Util\PemFile
     */
    private $pemFile;

    /**
     * @var \Yoti\Util\Config
     */
    private $config;

    /**
     * @param string $sdkId
     * @param \Yoti\Util\PemFile $pemFile
     * @param \Yoti\Util\Config $config
     */
    public function __construct(string $sdkId, PemFile $pemFile, Config $config)
    {
        $this->sdkId = $sdkId;
        $this->pemFile = $pemFile;
        $this->config = $config;
    }

    /**
     * @param \Yoti\Aml\Profile $amlProfile
     *
     * @return \Yoti\Aml\Result
     *
     * @throws YotiException
     */
    public function performCheck(Profile $amlProfile): Result
    {
        $response = (new RequestBuilder($this->config))
            ->withBaseUrl($this->config->getApiUrl() ?? Constants::API_URL)
            ->withEndpoint('/aml-check')
            ->withQueryParam('appId', $this->sdkId)
            ->withPost()
            ->withPayload(Payload::fromJsonData($amlProfile))
            ->withPemFile($this->pemFile)
            ->build()
            ->execute();

        // Validate result
        $this->validateAmlResult($response);

        // Set and return result
        return new Result(Json::decode((string)$response->getBody()));
    }

    /**
     * Handle request result.
     *
     * @param ResponseInterface $response
     *
     * @throws AmlException
     */
    private function validateAmlResult(ResponseInterface $response): void
    {
        $httpCode = $response->getStatusCode();

        if ($httpCode >= 200 && $httpCode < 300) {
            // The request is successful - nothing to do
            return;
        }

        throw new AmlException($this->getErrorMessage($response), $response);
    }

    /**
     * Get error message from the response.
     *
     * @param ResponseInterface $response
     *
     * @return string
     */
    private function getErrorMessage(ResponseInterface $response): string
    {
        $httpCode = $response->getStatusCode();
        $statusCodeMessage = "Server responded with {$httpCode}";

        if (
            $response->hasHeader('Content-Type') &&
            $response->getHeader('Content-Type')[0] !== 'application/json'
        ) {
            return $statusCodeMessage;
        }

        $jsonData = Json::decode((string)$response->getBody());

        $errorCode = $jsonData['code'] ?? 'Error';

        // Throw the error message that's included in the response.
        if (isset($jsonData['errors'][0]['property']) && isset($jsonData['errors'][0]['message'])) {
            $errorMessage = $jsonData['errors'][0]['property'] . ': ' . $jsonData['errors'][0]['message'];
            return "{$errorCode} - {$errorMessage}";
        }

        // Throw a general error message.
        return "{$errorCode} - {$statusCodeMessage}";
    }
}
