<?php

declare(strict_types=1);

namespace Yoti\ShareUrl\Policy;

use Yoti\Util\Validation;

/**
 * Defines a contraint for wanted attributes.
 */
class SourceConstraint implements \JsonSerializable
{
    /**
     * Constraint Type.
     */
    const TYPE = 'SOURCE';

    /**
     * @var \Yoti\ShareUrl\Policy\WantedAnchor[]
     */
    private $anchors = [];

    /**
     * @var boolean
     */
    private $softPreference;

    /**
     * @param \Yoti\ShareUrl\Policy\WantedAnchor[] $anchors
     * @param boolean $softPreference
     */
    public function __construct(array $anchors, bool $softPreference = false)
    {
        Validation::isArrayOfType($anchors, [WantedAnchor::class], 'anchors');
        $this->anchors = $anchors;

        Validation::isBoolean($softPreference, 'softPreference');
        $this->softPreference = $softPreference;
    }

    /**
     * @inheritDoc
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'type' => self::TYPE,
            'preferred_sources' => [
                'anchors' => $this->anchors,
                'soft_preference' => $this->softPreference,
            ],
        ];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return json_encode($this);
    }
}
