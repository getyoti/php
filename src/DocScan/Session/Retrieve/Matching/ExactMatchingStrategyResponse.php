<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Retrieve\Matching;

use Yoti\DocScan\Session\Retrieve\Contracts\CaMatchingStrategyResponse;

class ExactMatchingStrategyResponse extends CaMatchingStrategyResponse
{
    /**
     * @var bool
     */
    private $exactMatch;

    /**
     * @return bool
     */
    public function isExactMatch(): bool
    {
        return $this->exactMatch;
    }
}
