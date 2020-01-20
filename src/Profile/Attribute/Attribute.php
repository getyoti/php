<?php

declare(strict_types=1);

namespace Yoti\Profile\Attribute;

class Attribute
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var mixed
     */
    private $value;

    /**
     * @var array
     */
    private $sources;

    /**
     * @var array
     */
    private $verifiers;

    /**
     * @var array
     */
    private $anchors;

    /**
     * Attribute constructor.
     *
     * @param string $name
     * @param mixed $value
     *
     * @param array $anchorsMap
     */
    public function __construct(string $name, $value, array $anchorsMap)
    {
        $this->name = $name;
        $this->value = $value;

        $this->setSources($anchorsMap);
        $this->setVerifiers($anchorsMap);
        $this->setAnchors($anchorsMap);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return array
     */
    public function getSources(): array
    {
        return $this->sources;
    }

    /**
     * @return array
     */
    public function getVerifiers(): array
    {
        return $this->verifiers;
    }

    /**
     * Return an array of anchors e.g
     * [
     *  new Anchor(),
     *  new Anchor(),
     *  ...
     * ]
     *
     * @return array
     */
    public function getAnchors(): array
    {
        return $this->anchors;
    }

    private function setSources(array $anchorsMap): void
    {
        $this->sources = $this->getAnchorType(
            $anchorsMap,
            Anchor::TYPE_SOURCE_OID
        );
    }

    private function setVerifiers(array $anchorsMap): void
    {
        $this->verifiers = $this->getAnchorType(
            $anchorsMap,
            Anchor::TYPE_VERIFIER_OID
        );
    }

    private function setAnchors(array $anchorsMap): void
    {
        // Remove Oids from the anchorsMap
        $anchors = [];
        array_walk($anchorsMap, function ($val) use (&$anchors) {
            $anchors = array_merge($anchors, array_values($val));
        });
        $this->anchors = $anchors;
    }

    /**
     * @param string $anchorType
     *
     * @return array
     */
    private function getAnchorType($anchorsMap, $anchorType): array
    {
        return isset($anchorsMap[$anchorType]) ? $anchorsMap[$anchorType] : [];
    }
}
