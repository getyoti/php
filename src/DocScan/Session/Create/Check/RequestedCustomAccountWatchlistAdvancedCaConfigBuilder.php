<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create\Check;

use Yoti\DocScan\Session\Create\Check\Contracts\RequestedWatchlistAdvancedCaConfigBuilder;

class RequestedCustomAccountWatchlistAdvancedCaConfigBuilder extends RequestedWatchlistAdvancedCaConfigBuilder
{
    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var bool
     */
    private $monitoring;

    /**
     * @var string[]
     */
    private $tags;

    /**
     * @var string
     */
    private $clientRef;

    /**
     * @param string $apiKey
     * @return $this
     */
    public function withApiKey(string $apiKey): RequestedCustomAccountWatchlistAdvancedCaConfigBuilder
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    /**
     * @param bool $monitoring
     * @return $this
     */
    public function withMonitoring(bool $monitoring): RequestedCustomAccountWatchlistAdvancedCaConfigBuilder
    {
        $this->monitoring = $monitoring;

        return $this;
    }

    /**
     * @param string[] $tags
     * @return $this
     */
    public function withTags(array $tags): RequestedCustomAccountWatchlistAdvancedCaConfigBuilder
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * @param string $clientRef
     * @return $this
     */
    public function withClientRef(string $clientRef): RequestedCustomAccountWatchlistAdvancedCaConfigBuilder
    {
        $this->clientRef = $clientRef;

        return $this;
    }

    /**
     * @return RequestedCustomAccountWatchlistAdvancedCaConfig
     */
    public function build(): RequestedCustomAccountWatchlistAdvancedCaConfig
    {
        return new RequestedCustomAccountWatchlistAdvancedCaConfig(
            $this->removeDeceased,
            $this->shareUrl,
            $this->sources,
            $this->matchingStrategy,
            $this->apiKey,
            $this->monitoring,
            $this->tags,
            $this->clientRef
        );
    }
}
