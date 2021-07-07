<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create\Check\Advanced;

use Yoti\DocScan\Session\Create\Check\Contracts\Advanced\RequestedCaSources;

class RequestedTypeListSources extends RequestedCaSources
{
    /**
     * @var string[]
     */
    private $types;

    /**
     * RequestedTypeListSources constructor.
     * @param string[] $types
     */
    public function __construct(array $types)
    {
        $this->types = $types;
    }

    /**
     * @return string[]
     */
    public function getTypes(): array
    {
        return $this->types;
    }
}
