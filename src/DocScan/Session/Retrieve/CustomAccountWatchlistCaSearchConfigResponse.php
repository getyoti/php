<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Retrieve;

use Yoti\DocScan\Session\Retrieve\Contracts\WatchlistAdvancedCaSearchConfigResponse;

class CustomAccountWatchlistCaSearchConfigResponse extends WatchlistAdvancedCaSearchConfigResponse
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
