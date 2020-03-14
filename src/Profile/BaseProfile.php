<?php

declare(strict_types=1);

namespace Yoti\Profile;

use Yoti\Util\Validation;

class BaseProfile
{
    /**
     * @var \Yoti\Profile\Attribute[]
     */
    private $attributesList;

    /**
     * @var \Yoti\Profile\Attribute[][] keyed by attribute name.
     */
    private $attributesMap;

    /**
     * Profile constructor.
     *
     * @param \Yoti\Profile\Attribute[] $attributesList
     */
    public function __construct(array $attributesList)
    {
        Validation::isArrayOfType($attributesList, [Attribute::class], 'attributesList');
        $this->attributesList = $attributesList;
        $this->setAttributesMap();
    }

    /**
     * Set attributes map keyed by attribute name.
     */
    private function setAttributesMap(): void
    {
        $this->attributesMap = array_reduce(
            $this->getAttributesList(),
            function ($carry, Attribute $attr) {
                $carry[$attr->getName()][] = $attr;
                return $carry;
            },
            []
        );
    }

    /**
     * @param string $attributeName.
     *
     * @return \Yoti\Profile\Attribute[]
     */
    public function getAttributesByName(string $attributeName): array
    {
        return $this->attributesMap[$attributeName] ?? [];
    }

    /**
     * @param string $attributeName.
     *
     * @return \Yoti\Profile\Attribute|null
     */
    public function getProfileAttribute(string $attributeName): ?Attribute
    {
        $attributes = $this->getAttributesByName($attributeName);
        return count($attributes) > 0 ? $attributes[0] : null;
    }

    /**
     * Get all attributes.
     *
     * @return \Yoti\Profile\Attribute[]
     */
    public function getAttributesList(): array
    {
        return $this->attributesList;
    }
}
