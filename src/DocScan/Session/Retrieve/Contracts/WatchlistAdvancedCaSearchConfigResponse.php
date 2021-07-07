<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Retrieve\Contracts;

abstract class WatchlistAdvancedCaSearchConfigResponse extends WatchlistSearchConfigResponse
{
    /**
     * @var string $type
     */
    private $type;

    /**
     * @var bool
     */
    private $removeDeceased;

    /**
     * @var bool
     */
    private $shareUrl;

    /**
     * @var CaSourcesResponse
     */
    private $sources;

    /**
     * @var CaMatchingStrategyResponse
     */
    private $matchingStrategy;

    /**
     * @return bool
     */
    public function isRemoveDeceased(): bool
    {
        return $this->removeDeceased;
    }

    /**
     * @return bool
     */
    public function isShareUrl(): bool
    {
        return $this->shareUrl;
    }
}
