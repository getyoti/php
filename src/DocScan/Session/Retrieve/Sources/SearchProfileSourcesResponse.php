<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Retrieve\Sources;

use Yoti\DocScan\Session\Retrieve\Contracts\CaSourcesResponse;

class SearchProfileSourcesResponse extends CaSourcesResponse
{
    /**
     * @var string
     */
    private $searchProfile;

    /**
     * @return string
     */
    public function getSearchProfile(): string
    {
        return $this->searchProfile;
    }
}
