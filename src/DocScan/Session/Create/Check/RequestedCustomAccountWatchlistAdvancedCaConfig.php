<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create\Check;

use Yoti\DocScan\Session\Create\Check\Contracts\Advanced\RequestedCaMatchingStrategy;
use Yoti\DocScan\Session\Create\Check\Contracts\Advanced\RequestedCaSources;
use Yoti\DocScan\Session\Create\Check\Contracts\RequestedWatchlistAdvancedCaConfig;

class RequestedCustomAccountWatchlistAdvancedCaConfig extends RequestedWatchlistAdvancedCaConfig
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
     * RequestedCustomAccountWatchlistAdvancedCaConfig constructor.
     * @param bool $removeDeceased
     * @param bool $shareUrl
     * @param RequestedCaSources $sources
     * @param RequestedCaMatchingStrategy $matchingStrategy
     * @param string $apiKey
     * @param bool $monitoring
     * @param string[] $tags
     * @param string $clientRef
     */
    public function __construct(
        bool $removeDeceased,
        bool $shareUrl,
        RequestedCaSources $sources,
        RequestedCaMatchingStrategy $matchingStrategy,
        string $apiKey,
        bool $monitoring,
        array $tags,
        string $clientRef
    ) {
        $this->apiKey = $apiKey;
        $this->monitoring = $monitoring;
        $this->tags = $tags;
        $this->clientRef = $clientRef;

        parent::__construct($removeDeceased, $shareUrl, $sources, $matchingStrategy);
    }

    /**
     * @return string
     */
    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    /**
     * @return bool
     */
    public function getMonitoring(): bool
    {
        return $this->monitoring;
    }

    /**
     * @return string[]
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * @return string
     */
    public function getClientRef(): string
    {
        return $this->clientRef;
    }
}
