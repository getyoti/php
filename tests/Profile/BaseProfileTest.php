<?php

declare(strict_types=1);

namespace Yoti\Test\Profile;

use Yoti\Profile\Attribute;
use Yoti\Profile\BaseProfile;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\Profile\BaseProfile
 */
class BaseProfileTest extends TestCase
{
    private const SOME_ATTRIBUTE = 'some_attribute';
    private const SOME_OTHER_ATTRIBUTE = 'some_other_attribute';
    private const SOME_INVALID_ATTRIBUTE = 'some_invalid_attribute';
    private const SOME_MISSING_ATTRIBUTE = 'some_missing_attribute';

    /**
     * @var \Yoti\Profile\BaseProfile
     */
    private $baseProfile;

    /**
     * @var \Yoti\Profile\Attribute
     */
    private $someAttribute;

    /**
     * @var \Yoti\Profile\Attribute
     */
    private $someOtherAttribute;

    /**
     * @var \Yoti\Profile\Attribute
     */
    private $someOtherAttributeWithSameName;

    /**
     * Setup mocks.
     */
    public function setup(): void
    {
        $this->someAttribute = $this->createMock(Attribute::class);
        $this->someAttribute
            ->method('getName')
            ->willReturn(self::SOME_ATTRIBUTE);

        $this->someOtherAttributeWithSameName = $this->createMock(Attribute::class);
        $this->someOtherAttributeWithSameName
            ->method('getName')
            ->willReturn(self::SOME_ATTRIBUTE);

        $this->someOtherAttribute = $this->createMock(Attribute::class);
        $this->someOtherAttribute
            ->method('getName')
            ->willReturn(self::SOME_OTHER_ATTRIBUTE);

        $this->baseProfile = new BaseProfile([
            $this->someAttribute,
            $this->someOtherAttributeWithSameName,
            $this->someOtherAttribute,
        ]);
    }

    /**
     * @covers ::__construct
     * @covers ::getProfileAttribute
     * @covers ::setAttributesMap
     */
    public function testGetProfileAttribute()
    {
        $this->assertSame($this->someAttribute, $this->baseProfile->getProfileAttribute(self::SOME_ATTRIBUTE));
        $this->assertNull($this->baseProfile->getProfileAttribute(self::SOME_INVALID_ATTRIBUTE));
        $this->assertNull($this->baseProfile->getProfileAttribute(self::SOME_MISSING_ATTRIBUTE));
    }

    /**
     * @covers ::__construct
     * @covers ::getAttributesList
     */
    public function testGetAttributesList()
    {
        $attributesList = $this->baseProfile->getAttributesList(self::SOME_ATTRIBUTE);
        $this->assertCount(3, $attributesList);
        $this->assertContainsOnlyInstancesOf(Attribute::class, $attributesList);
    }

    /**
     * @covers ::__construct
     * @covers ::getAttributesByName
     */
    public function testGetAttributesByName()
    {
        $attributes = $this->baseProfile->getAttributesByName(self::SOME_ATTRIBUTE);
        $this->assertCount(2, $attributes);
        $this->assertSame($this->someAttribute, $attributes[0]);
        $this->assertSame($this->someOtherAttributeWithSameName, $attributes[1]);

        $invalidAttributes = $this->baseProfile->getAttributesByName(self::SOME_INVALID_ATTRIBUTE);
        $this->assertIsArray($invalidAttributes);
        $this->assertEmpty($invalidAttributes);

        $missingAttributes = $this->baseProfile->getAttributesByName(self::SOME_MISSING_ATTRIBUTE);
        $this->assertIsArray($missingAttributes);
        $this->assertEmpty($missingAttributes);
    }
}
