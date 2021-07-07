<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Retrieve\Sources;

use Yoti\DocScan\Session\Retrieve\Contracts\CaSourcesResponse;

class TypeListSourcesResponse extends CaSourcesResponse
{
    /**
     * @var string[]
     */
    private $types;

    /**
     * @return string[]
     */
    public function getTypes(): array
    {
        return $this->types;
    }
}
