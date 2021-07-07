<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Retrieve\Contracts;

abstract class CaMatchingStrategyResponse
{
    /**
     * @var string
     */
    private $type;

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }
}
