<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Retrieve\Matching;

use Yoti\DocScan\Session\Retrieve\Contracts\CaMatchingStrategyResponse;

class FuzzyMatchingStrategyResponse extends CaMatchingStrategyResponse
{
    /**
     * @var float
     */
    private $fuzziness;

    /**
     * @return float
     */
    public function getFuzziness(): float
    {
        return $this->fuzziness;
    }
}
