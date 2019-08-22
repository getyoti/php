<?php

namespace Yoti\Http;

use Yoti\Util\Config;
use Yoti\Exception\RequestException;

abstract class AbstractRequestHandler
{
    // HTTP methods
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_PATCH = 'PATCH';
    const METHOD_DELETE = 'DELETE';

    // Request HttpHeader keys
    const YOTI_AUTH_HEADER_KEY = 'X-Yoti-Auth-Key';
    const YOTI_DIGEST_HEADER_KEY = 'X-Yoti-Auth-Digest';
    const YOTI_SDK_IDENTIFIER_KEY = 'X-Yoti-SDK';
    const YOTI_SDK_VERSION = 'X-Yoti-SDK-Version';

    /**
     * @var string
     */
    private $pem;

    /**
     * @var string
     */
    private $sdkId;

    /**
     * @var string
     */
    private $connectApiUrl;

    /**
     * @var string
     */
    private $sdkIdentifier;

    /**
     * @var string
     */
    private $authKey;

    /**
     * AbstractRequestHandler constructor.
     *
     * @param string $connectApiUrl
     * @param string $pem
     * @param string $sdkId
     * @param string $sdkIdentifier
     *
     * @throws RequestException
     */
    public function __construct($connectApiUrl, $pem, $sdkId = null, $sdkIdentifier = null)
    {
        $this->pem = $pem;
        $this->sdkId = $sdkId;
        $this->connectApiUrl = $connectApiUrl;
        $this->sdkIdentifier = $sdkIdentifier;

        $this->authKey = $this->extractAuthKeyFromPemContent();
    }

    /**
     * @param string $endpoint
     * @param string $httpMethod
     * @param Payload|NULL $payload
     * @param array queryParams
     *
     * @return array
     *
     * @throws RequestException
     */
    public function sendRequest(
        $endpoint,
        $httpMethod,
        Payload $payload = null,
        array $queryParams = []
    ) {
        self::validateHttpMethod($httpMethod);

        $signedDataArr = RequestSigner::signRequest($this, $endpoint, $httpMethod, $payload, $queryParams);
        $requestHeaders = $this->generateRequestHeaders($signedDataArr[RequestSigner::SIGNED_MESSAGE_KEY]);
        $requestUrl = $this->connectApiUrl . $signedDataArr[RequestSigner::END_POINT_PATH_KEY];

        return $this->executeRequest($requestHeaders, $requestUrl, $httpMethod, $payload);
    }

    /**
     * Performs GET request.
     *
     * @param string $endpoint
     * @param array queryParams
     *
     * @return array
     *
     * @throws RequestException
     */
    public function get($endpoint, array $queryParams = [])
    {
        return $this->sendRequest($endpoint, self::METHOD_GET, null, $queryParams);
    }

    /**
     * Performs POST request.
     *
     * @param string $endpoint
     * @param Payload|NULL $payload
     * @param array queryParams
     *
     * @return array
     *
     * @throws RequestException
     */
    public function post($endpoint, Payload $payload = null, array $queryParams = [])
    {
        return $this->sendRequest($endpoint, self::METHOD_POST, $payload, $queryParams);
    }

    /**
     * @deprecated will be removed in version 3 - SDK ID is now added as a query param.
     *
     * @return string|null
     */
    public function getSdkId()
    {
        return $this->sdkId;
    }

    /**
     * @return string
     */
    public function getPem()
    {
        return $this->pem;
    }

    /**
     * Return the request headers including the signed message.
     *
     * @param string $signedMessage
     *
     * @return array
     */
    private function generateRequestHeaders($signedMessage)
    {
        // Prepare request Http Headers
        $requestHeaders = [
            CurlRequestHandler::YOTI_AUTH_HEADER_KEY . ": {$this->authKey}",
            CurlRequestHandler::YOTI_DIGEST_HEADER_KEY . ": {$signedMessage}",
            'Content-Type: application/json',
            'Accept: application/json',
        ];

        // Include SDK identifier and version if provided.
        if (!is_null($this->sdkIdentifier)) {
            $requestHeaders[] = CurlRequestHandler::YOTI_SDK_IDENTIFIER_KEY . ": {$this->sdkIdentifier}";

            if ($version = Config::getInstance()->get('version')) {
                $requestHeaders[] =  self::YOTI_SDK_VERSION . ": {$this->sdkIdentifier}-{$version}";
            }
        }

        return $requestHeaders;
    }

    /**
     * @return string
     *
     * @throws RequestException
     */
    private function extractAuthKeyFromPemContent()
    {
        $details = openssl_pkey_get_details(openssl_pkey_get_private($this->pem));
        if (!array_key_exists('key', $details)) {
            return null;
        }

        // Remove BEGIN RSA PRIVATE KEY / END RSA PRIVATE KEY lines
        $KeyStr = trim($details['key']);
        // Support line break on *nix systems, OS, older OS, and Microsoft
        $keyArr = preg_split('/\r\n|\r|\n/', $KeyStr);
        if (strpos($KeyStr, 'BEGIN') !== false) {
            array_shift($keyArr);
            array_pop($keyArr);
        }
        $authKey = implode('', $keyArr);

        // Check auth key is not empty
        if (empty($authKey)) {
            throw new RequestException('Could not retrieve Auth key from PEM content.', 401);
        }

        return $authKey;
    }

    /**
     * Check if the provided HTTP method is valid.
     *
     * @param string $httpMethod
     *
     * @throws RequestException
     */
    private static function validateHttpMethod($httpMethod)
    {
        if (!self::methodIsAllowed($httpMethod)) {
            throw new RequestException("Unsupported HTTP Method {$httpMethod}", 400);
        }
    }

    /**
     * Check the HTTP method is allowed.
     *
     * @param string $httpMethod
     *
     * @return bool
     */
    private static function methodIsAllowed($httpMethod)
    {
        $allowedMethods = [
            self::METHOD_GET,
            self::METHOD_POST,
            self::METHOD_PUT,
            self::METHOD_PATCH,
            self::METHOD_DELETE,
        ];

        return in_array($httpMethod, $allowedMethods, true);
    }

    /**
     * Execute Request against the API.
     *
     * @param string $requestUrl
     * @param array $httpHeaders
     * @param string $httpMethod
     * @param Payload|NULL $payload
     *
     * @return array
     *
     * @throws \Yoti\Exception\RequestException
     */
    abstract protected function executeRequest(array $httpHeaders, $requestUrl, $httpMethod, $payload);
}
