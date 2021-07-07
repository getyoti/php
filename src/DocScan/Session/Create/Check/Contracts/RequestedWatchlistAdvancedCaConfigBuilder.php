<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create\Check\Contracts;

use Yoti\DocScan\Session\Create\Check\Contracts\Advanced\RequestedCaMatchingStrategy;
use Yoti\DocScan\Session\Create\Check\Contracts\Advanced\RequestedCaSources;

abstract class RequestedWatchlistAdvancedCaConfigBuilder
{

    /**
     * @var bool
     */
    protected $removeDeceased;

    /**
     * @var bool
     */
    protected $shareUrl;

    /**
     * @var RequestedCaSources
     */
    protected $sources;

    /**
     * @var RequestedCaMatchingStrategy
     */
    protected $matchingStrategy;

    /**
     * @return mixed
     */
    abstract protected function build();

    /**
     * @param bool $removeDeceased
     * @return $this
     */
    public function withRemoveDeceased(bool $removeDeceased): RequestedWatchlistAdvancedCaConfigBuilder
    {
        $this->removeDeceased = $removeDeceased;

        return $this;
    }

    /**
     * @param bool $shareUrl
     * @return $this
     */
    public function withShareUrl(bool $shareUrl): RequestedWatchlistAdvancedCaConfigBuilder
    {
        $this->shareUrl = $shareUrl;

        return $this;
    }

    /**
     * @param RequestedCaSources $sources
     * @return $this
     */
    public function withSources(RequestedCaSources $sources): RequestedWatchlistAdvancedCaConfigBuilder
    {
        $this->sources = $sources;

        return $this;
    }

    /**
     * @param RequestedCaMatchingStrategy $matchingStrategy
     * @return $this
     */
    public function withMatchingStrategy(
        RequestedCaMatchingStrategy $matchingStrategy
    ): RequestedWatchlistAdvancedCaConfigBuilder {
        $this->matchingStrategy = $matchingStrategy;

        return $this;
    }
}
