<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create\Check\Advanced;

use Yoti\DocScan\Session\Create\Check\Contracts\Advanced\RequestedCaSources;

class RequestedSearchProfileSources extends RequestedCaSources
{
    /**
     * @var string
     */
    private $searchProfile;

    public function __construct(string $searchProfile)
    {
        $this->searchProfile = $searchProfile;
    }

    /**
     * @return string
     */
    public function getSearchProfile(): string
    {
        return $this->searchProfile;
    }
}
