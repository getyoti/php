<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create\Check\Advanced;

class RequestedSearchProfileSourcesBuilder
{
    /**
     * @var string
     */
    private $searchProfile;

    /**
     * @param string $searchProfile
     * @return $this
     */
    public function withSearchProfile(string $searchProfile): RequestedSearchProfileSourcesBuilder
    {
        $this->searchProfile = $searchProfile;

        return $this;
    }

    /**
     * @return RequestedSearchProfileSources
     */
    public function build(): RequestedSearchProfileSources
    {
        return new RequestedSearchProfileSources($this->searchProfile);
    }
}
