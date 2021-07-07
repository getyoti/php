<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create\Check\Contracts;

use Yoti\DocScan\Session\Create\Check\Contracts\Advanced\RequestedCaMatchingStrategy;
use Yoti\DocScan\Session\Create\Check\Contracts\Advanced\RequestedCaSources;
use Yoti\DocScan\Session\Create\Check\RequestedCheckConfigInterface;

abstract class RequestedWatchlistAdvancedCaConfig implements RequestedCheckConfigInterface
{
    /**
     * @var bool
     */
    private $removeDeceased;

    /**
     * @var bool
     */
    private $shareUrl;

    /**
     * @var RequestedCaSources
     */
    private $sources;

    /**
     * @var RequestedCaMatchingStrategy
     */
    private $matchingStrategy;

    public function __construct(
        bool $removeDeceased,
        bool $shareUrl,
        RequestedCaSources $sources,
        RequestedCaMatchingStrategy $matchingStrategy
    ) {
        $this->removeDeceased = $removeDeceased;
        $this->shareUrl = $shareUrl;
        $this->sources = $sources;
        $this->matchingStrategy = $matchingStrategy;
    }

    /**
     * @return bool
     */
    public function getRemoveDeceased(): bool
    {
        return $this->removeDeceased;
    }

    /**
     * @return bool
     */
    public function getShareUrl(): bool
    {
        return $this->shareUrl;
    }

    /**
     * @return RequestedCaSources
     */
    public function getSources(): RequestedCaSources
    {
        return $this->sources;
    }

    /**
     * @return RequestedCaMatchingStrategy
     */
    public function getMatchingStrategy(): RequestedCaMatchingStrategy
    {
        return $this->matchingStrategy;
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'remove_deceased' => $this->getRemoveDeceased(),
            'share_url' => $this->getShareUrl(),
            'sources' => $this->getSources(),
            'matching_strategy' => $this->getMatchingStrategy(),
        ];
    }
}
