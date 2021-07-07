<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create\Check\Advanced;

use Yoti\DocScan\Session\Create\Check\Contracts\Advanced\RequestedCaMatchingStrategy;

class RequestedFuzzyMatchingStrategy extends RequestedCaMatchingStrategy
{
    /**
     * @var float
     */
    private $fuzziness;

    public function __construct(float $fuzziness)
    {
        $this->fuzziness = $fuzziness;
    }

    /**
     * @return float
     */
    public function getFuzziness(): float
    {
        return $this->fuzziness;
    }
}
