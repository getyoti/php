<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create\Check;

use Yoti\DocScan\Session\Create\Check\Contracts\RequestedWatchlistAdvancedCaConfigBuilder;

class RequestedYotiAccountWatchlistAdvancedCaConfigBuilder extends RequestedWatchlistAdvancedCaConfigBuilder
{
    /**
     * @return RequestedYotiAccountWatchlistAdvancedCaConfig
     */
    public function build(): RequestedYotiAccountWatchlistAdvancedCaConfig
    {
        return new RequestedYotiAccountWatchlistAdvancedCaConfig(
            $this->removeDeceased,
            $this->shareUrl,
            $this->sources,
            $this->matchingStrategy
        );
    }
}
