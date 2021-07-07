<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create\Check\Advanced;

use Yoti\DocScan\Session\Create\Check\Contracts\Advanced\RequestedCaMatchingStrategy;

class RequestedExactMatchingStrategy extends RequestedCaMatchingStrategy
{
    /**
     * @return bool
     */
    public function isExactMatch(): bool
    {
        return true;
    }
}
