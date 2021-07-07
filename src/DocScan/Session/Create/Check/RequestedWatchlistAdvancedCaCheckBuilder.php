<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create\Check;

use Yoti\DocScan\Session\Create\Check\Contracts\RequestedWatchlistAdvancedCaConfig;

class RequestedWatchlistAdvancedCaCheckBuilder
{
    /**
     * @var RequestedWatchlistAdvancedCaConfig
     */
    private $config;

    /**
     * @param RequestedWatchlistAdvancedCaConfig $config
     * @return $this
     */
    public function withConfig(RequestedWatchlistAdvancedCaConfig $config): RequestedWatchlistAdvancedCaCheckBuilder
    {
        $this->config = $config;

        return $this;
    }

    /**
     * @return RequestedWatchlistAdvancedCaCheck
     */
    public function build(): RequestedWatchlistAdvancedCaCheck
    {
        return new RequestedWatchlistAdvancedCaCheck($this->config);
    }
}
