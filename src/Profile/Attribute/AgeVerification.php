<?php

declare(strict_types=1);

namespace Yoti\Profile\Attribute;

class AgeVerification
{
    /**
     * @var int
     */
    private $age;

    /**
     * @var bool
     */
    private $result;

    /**
     * @var string
     */
    private $checkType;

    /**
     * @var Attribute
     */
    private $derivedAttribute;

    public function __construct(Attribute $derivedAttribute, string $checkType, int $age, bool $result)
    {
        $this->age = $age;
        $this->result = $result;
        $this->checkType = $checkType;
        $this->derivedAttribute = $derivedAttribute;
    }

    /**
     * The type of age check performed, as specified on Yoti Hub.
     * Currently this might be 'age_over' or 'age_under'.
     *
     * @return string $checkType
     */
    public function getCheckType(): string
    {
        return $this->checkType;
    }

    /**
     * The age that was that checked, as specified on Yoti Hub.
     *
     * @return int $age
     */
    public function getAge(): int
    {
        return $this->age;
    }

    /**
     * Whether or not the profile passed the age check.
     *
     * @return bool $result
     */
    public function getResult(): bool
    {
        return $this->result;
    }

    /**
     * The wrapped profile attribute. Use this if you need access to the underlying List of {@link Anchor}s
     *
     * @return \Yoti\Profile\Attribute\Attribute
     */
    public function getAttribute(): Attribute
    {
        return $this->derivedAttribute;
    }
}
