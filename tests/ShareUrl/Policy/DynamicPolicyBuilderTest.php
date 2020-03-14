<?php

declare(strict_types=1);

namespace Yoti\Test\ShareUrl\Policy;

use Yoti\Profile\UserProfile;
use Yoti\ShareUrl\Policy\ConstraintsBuilder;
use Yoti\ShareUrl\Policy\DynamicPolicyBuilder;
use Yoti\ShareUrl\Policy\SourceConstraintBuilder;
use Yoti\ShareUrl\Policy\WantedAttributeBuilder;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\ShareUrl\Policy\DynamicPolicyBuilder
 */
class DynamicPolicyBuilderTest extends TestCase
{
    private const SELFIE_AUTH_TYPE = 1;
    private const PIN_AUTH_TYPE = 2;

    /**
     * @covers ::build
     * @covers ::withFamilyName
     * @covers ::withGivenNames
     * @covers ::withFullName
     * @covers ::withDateOfBirth
     * @covers ::withGender
     * @covers ::withPostalAddress
     * @covers ::withStructuredPostalAddress
     * @covers ::withNationality
     * @covers ::withPhoneNumber
     * @covers ::withSelfie
     * @covers ::withEmail
     * @covers ::withDocumentDetails
     * @covers ::withDocumentImages
     * @covers \Yoti\ShareUrl\Policy\DynamicPolicy::__construct
     * @covers \Yoti\ShareUrl\Policy\DynamicPolicy::__toString
     * @covers \Yoti\ShareUrl\Policy\DynamicPolicy::jsonSerialize
     */
    public function testBuildWithAttributes()
    {
        $dynamicPolicy = (new DynamicPolicyBuilder())
            ->withFamilyName()
            ->withGivenNames()
            ->withFullName()
            ->withDateOfBirth()
            ->withGender()
            ->withPostalAddress()
            ->withStructuredPostalAddress()
            ->withNationality()
            ->withPhoneNumber()
            ->withSelfie()
            ->withEmail()
            ->withDocumentDetails()
            ->withDocumentImages()
            ->build();

        $expectedWantedAttributeData = [
            'wanted' => [
                ['name' => 'family_name', 'optional' => false],
                ['name' => 'given_names', 'optional' => false],
                ['name' => 'full_name', 'optional' => false],
                ['name' => 'date_of_birth', 'optional' => false],
                ['name' => 'gender', 'optional' => false],
                ['name' => 'postal_address', 'optional' => false],
                ['name' => 'structured_postal_address', 'optional' => false],
                ['name' => 'nationality', 'optional' => false],
                ['name' => 'phone_number', 'optional' => false],
                ['name' => 'selfie', 'optional' => false],
                ['name' => 'email_address', 'optional' => false],
                ['name' => 'document_details', 'optional' => false],
                ['name' => 'document_images', 'optional' => false],
            ],
            'wanted_auth_types' => [],
            'wanted_remember_me' => false,
            'wanted_remember_me_optional' => false,
        ];

        $this->assertEquals(json_encode($expectedWantedAttributeData), json_encode($dynamicPolicy));
        $this->assertEquals(json_encode($expectedWantedAttributeData), $dynamicPolicy);
    }

    /**
     * @covers ::withWantedAttributeByName
     */
    public function testWithWantedAttributeByNameWithConstraints()
    {
        $someAttributeName = 'some_attribute_name';

        $constraints = (new ConstraintsBuilder())
            ->withSourceConstraint(
                (new SourceConstraintBuilder())
                    ->withDrivingLicence()
                    ->build()
            )
            ->build();

        $dynamicPolicy = (new DynamicPolicyBuilder())
            ->withWantedAttributeByName($someAttributeName, $constraints, true)
            ->build();

        $expectedWantedAttributeData = [
            'wanted' => [
                [
                    'name' => $someAttributeName,
                    'optional' => false,
                    "constraints" => [
                        [
                            "type" => "SOURCE",
                            "preferred_sources" => [
                                "anchors" => [
                                    [
                                        "name" => "DRIVING_LICENCE",
                                        "sub_type" => "",
                                    ]
                                ],
                                "soft_preference" => false,
                            ],
                        ],
                    ],
                    "accept_self_asserted" => true,
                ],
            ],
            'wanted_auth_types' => [],
            'wanted_remember_me' => false,
            'wanted_remember_me_optional' => false,
        ];

        $this->assertJsonStringEqualsJsonString(
            json_encode($expectedWantedAttributeData),
            json_encode($dynamicPolicy)
        );
    }

    /**
     * @covers ::withWantedAttribute
     * @covers ::withFamilyName
     */
    public function testWithDuplicateAttribute()
    {
        $dynamicPolicy = (new DynamicPolicyBuilder())
            ->withFamilyName()
            ->withFamilyName()
            ->build();

        $expectedWantedAttributeData = [
            'wanted' => [
                ['name' => 'family_name', 'optional' => false],
            ],
            'wanted_auth_types' => [],
            'wanted_remember_me' => false,
            'wanted_remember_me_optional' => false,
        ];

        $this->assertEquals(json_encode($expectedWantedAttributeData), json_encode($dynamicPolicy));
    }

    /**
     * @covers ::withWantedAttribute
     * @covers ::withFamilyName
     */
    public function testWithDuplicateAttributeDifferentConstraints()
    {
        $passportConstraints = (new ConstraintsBuilder())
            ->withSourceConstraint(
                (new SourceConstraintBuilder())
                    ->withPassport()
                    ->build()
            )
            ->build();

        $drivingLicenseConstraints = (new ConstraintsBuilder())
            ->withSourceConstraint(
                (new SourceConstraintBuilder())
                    ->withDrivingLicence()
                    ->build()
            )
            ->build();

        $dynamicPolicy = (new DynamicPolicyBuilder())
            ->withFamilyName()
            ->withFamilyName($passportConstraints)
            ->withFamilyName($drivingLicenseConstraints)
            ->build();

        $jsonData = $dynamicPolicy->jsonSerialize();

        $this->assertCount(3, $jsonData['wanted']);
        foreach ($jsonData['wanted'] as $wantedAttribute) {
            $this->assertEquals('family_name', $wantedAttribute->getName());
        }
    }

    /**
     * @covers ::build
     * @covers ::withWantedAttributeByName
     */
    public function testWithWantedAttributeByName()
    {
        $dynamicPolicy = (new DynamicPolicyBuilder())
            ->withWantedAttributeByName('family_name')
            ->withWantedAttributeByName('given_names')
            ->build();

        $expectedWantedAttributeData = [
            'wanted' => [
                ['name' => 'family_name', 'optional' => false],
                ['name' => 'given_names', 'optional' => false],
            ],
            'wanted_auth_types' => [],
            'wanted_remember_me' => false,
            'wanted_remember_me_optional' => false,
        ];

        $this->assertEquals(json_encode($expectedWantedAttributeData), json_encode($dynamicPolicy));
    }

    /**
     * @covers ::build
     * @covers ::withWantedAttribute
     */
    public function testWithAttributeObjects()
    {
        $wantedFamilyName = (new WantedAttributeBuilder())
            ->withName('family_name')
            ->build();

        $wantedGivenNames = (new WantedAttributeBuilder())
            ->withName('given_names')
            ->build();

        $dynamicPolicy = (new DynamicPolicyBuilder())
            ->withWantedAttribute($wantedFamilyName)
            ->withWantedAttribute($wantedGivenNames)
            ->build();

        $expectedWantedAttributeData = [
            'wanted' => [
                ['name' => 'family_name', 'optional' => false],
                ['name' => 'given_names', 'optional' => false],
            ],
            'wanted_auth_types' => [],
            'wanted_remember_me' => false,
            'wanted_remember_me_optional' => false,
        ];

        $this->assertEquals(json_encode($expectedWantedAttributeData), json_encode($dynamicPolicy));
    }

    /**
     * @covers ::withDateOfBirth
     * @covers ::withAgeOver
     * @covers ::withAgeUnder
     * @covers ::withAgeDerivedAttribute
     */
    public function testWithAgeDerivedAttributes()
    {
        $dynamicPolicy = (new DynamicPolicyBuilder())
            ->withDateOfBirth()
            ->withAgeOver(18)
            ->withAgeUnder(30)
            ->withAgeUnder(40)
            ->build();

        $expectedWantedAttributeData = [
            'wanted' => [
                ['name' => 'date_of_birth', 'optional' => false],
                ['name' => 'date_of_birth', 'optional' => false, 'derivation' => 'age_over:18'],
                ['name' => 'date_of_birth', 'optional' => false, 'derivation' => 'age_under:30'],
                ['name' => 'date_of_birth', 'optional' => false, 'derivation' => 'age_under:40'],
            ],
            'wanted_auth_types' => [],
            'wanted_remember_me' => false,
            'wanted_remember_me_optional' => false,
        ];

        $this->assertEquals(json_encode($expectedWantedAttributeData), json_encode($dynamicPolicy));
    }

    /**
     * @covers ::withAgeDerivedAttribute
     */
    public function testWithAgeDerivedAttributesWithConstraints()
    {
        $constraints = (new ConstraintsBuilder())
            ->withSourceConstraint(
                (new SourceConstraintBuilder())
                    ->withDrivingLicence()
                    ->build()
            )
            ->build();

        $dynamicPolicy = (new DynamicPolicyBuilder())
            ->withAgeDerivedAttribute(UserProfile::AGE_OVER . '18', $constraints)
            ->build();

        $expectedWantedAttributeData = [
            'wanted' => [
                [
                    'name' => 'date_of_birth',
                    'optional' => false,
                    'derivation' => 'age_over:18',
                    "constraints" => [
                        [
                            "type" => "SOURCE",
                            "preferred_sources" => [
                                "anchors" => [
                                    [
                                        "name" => "DRIVING_LICENCE",
                                        "sub_type" => "",
                                    ]
                                ],
                                "soft_preference" => false,
                            ],
                        ],
                    ],
                ],
            ],
            'wanted_auth_types' => [],
            'wanted_remember_me' => false,
            'wanted_remember_me_optional' => false,
        ];

        $this->assertJsonStringEqualsJsonString(
            json_encode($expectedWantedAttributeData),
            json_encode($dynamicPolicy)
        );
    }

    /**
     * @covers ::withAgeOver
     * @covers ::withAgeDerivedAttribute
     * @covers ::withWantedAttribute
     */
    public function testWithAgeOverIntegersOnly()
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('must be of the type int');

        (new DynamicPolicyBuilder())
            ->withDateOfBirth()
            ->withAgeOver('18')
            ->build();
    }

    /**
     * @covers ::withAgeUnder
     * @covers ::withAgeDerivedAttribute
     * @covers ::withWantedAttribute
     */
    public function testWithAgeUnderIntegersOnly()
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('must be of the type int');

        (new DynamicPolicyBuilder())
            ->withDateOfBirth()
            ->withAgeUnder('18')
            ->build();
    }

    /**
     * @covers ::withAgeUnder
     * @covers ::withAgeDerivedAttribute
     * @covers ::withWantedAttribute
     */
    public function testWithDuplicateAgeDerivedAttributes()
    {
        $dynamicPolicy = (new DynamicPolicyBuilder())
            ->withAgeUnder(30)
            ->withAgeUnder(30)
            ->build();

        $expectedWantedAttributeData = [
            'wanted' => [
                ['name' => 'date_of_birth', 'optional' => false, 'derivation' => 'age_under:30'],
            ],
            'wanted_auth_types' => [],
            'wanted_remember_me' => false,
            'wanted_remember_me_optional' => false,
        ];

        $this->assertEquals(json_encode($expectedWantedAttributeData), json_encode($dynamicPolicy));
    }

    /**
     * @covers ::withSelfieAuthentication
     * @covers ::withPinAuthentication
     * @covers ::withWantedAuthType
     */
    public function testWithAuthTypes()
    {
        $dynamicPolicy = (new DynamicPolicyBuilder())
            ->withSelfieAuthentication()
            ->withPinAuthentication()
            ->withWantedAuthType(99)
            ->build();

        $expectedWantedAttributeData = [
            'wanted' => [],
            'wanted_auth_types' => [self::SELFIE_AUTH_TYPE, self::PIN_AUTH_TYPE, 99],
            'wanted_remember_me' => false,
            'wanted_remember_me_optional' => false,
        ];

        $this->assertEquals(json_encode($expectedWantedAttributeData), json_encode($dynamicPolicy));
    }

    /**
     * @covers ::withSelfieAuthentication
     * @covers ::withPinAuthentication
     * @covers ::withWantedAuthType
     */
    public function testWithAuthTypesTrue()
    {
        $dynamicPolicy = (new DynamicPolicyBuilder())
            ->withSelfieAuthentication(true)
            ->withPinAuthentication(true)
            ->withWantedAuthType(99, true)
            ->build();

        $expectedWantedAttributeData = [
            'wanted' => [],
            'wanted_auth_types' => [self::SELFIE_AUTH_TYPE, self::PIN_AUTH_TYPE, 99],
            'wanted_remember_me' => false,
            'wanted_remember_me_optional' => false,
        ];

        $this->assertEquals(json_encode($expectedWantedAttributeData), json_encode($dynamicPolicy));
    }

    /**
     * @covers ::withSelfieAuthentication
     * @covers ::withPinAuthentication
     * @covers ::withWantedAuthType
     */
    public function testWithAuthTypesFalse()
    {
        $dynamicPolicy = (new DynamicPolicyBuilder())
            ->withSelfieAuthentication(false)
            ->withPinAuthentication(false)
            ->withWantedAuthType(99, false)
            ->build();

        $expectedWantedAttributeData = [
            'wanted' => [],
            'wanted_auth_types' => [],
            'wanted_remember_me' => false,
            'wanted_remember_me_optional' => false,
        ];

        $this->assertEquals(json_encode($expectedWantedAttributeData), json_encode($dynamicPolicy));
    }

    /**
     * @covers ::withSelfieAuthentication
     * @covers ::withPinAuthentication
     */
    public function testWithAuthEnabledThenDisabled()
    {
        $dynamicPolicy = (new DynamicPolicyBuilder())
            ->withSelfieAuthentication(true)
            ->withSelfieAuthentication(false)
            ->withPinAuthentication(true)
            ->withPinAuthentication(false)
            ->build();

        $expectedWantedAttributeData = [
            'wanted' => [],
            'wanted_auth_types' => [],
            'wanted_remember_me' => false,
            'wanted_remember_me_optional' => false,
        ];

        $this->assertEquals(json_encode($expectedWantedAttributeData), json_encode($dynamicPolicy));
    }

    /**
     * @covers ::withSelfieAuthentication
     */
    public function testWithSameAuthTypeAddedOnlyOnce()
    {
        $dynamicPolicy = (new DynamicPolicyBuilder())
            ->withSelfieAuthentication(true)
            ->withSelfieAuthentication()
            ->withSelfieAuthentication(true)
            ->build();

        $expectedWantedAttributeData = [
            'wanted' => [],
            'wanted_auth_types' => [self::SELFIE_AUTH_TYPE],
            'wanted_remember_me' => false,
            'wanted_remember_me_optional' => false,
        ];

        $this->assertEquals(json_encode($expectedWantedAttributeData), json_encode($dynamicPolicy));
    }

    /**
     * @covers ::withSelfieAuthentication
     */
    public function testWithOnlyTwoAuthTypes()
    {
        $dynamicPolicy = (new DynamicPolicyBuilder())
            ->withSelfieAuthentication(true)
            ->withPinAuthentication(true)
            ->build();

        $expectedWantedAttributeData = [
            'wanted' => [],
            'wanted_auth_types' => [self::SELFIE_AUTH_TYPE, self::PIN_AUTH_TYPE],
            'wanted_remember_me' => false,
            'wanted_remember_me_optional' => false,
        ];

        $this->assertEquals(json_encode($expectedWantedAttributeData), json_encode($dynamicPolicy));
    }

    /**
     * @covers ::withSelfieAuthentication
     */
    public function testWithNoSelfieAuthAfterRemoval()
    {
        $dynamicPolicy = (new DynamicPolicyBuilder())
            ->withSelfieAuthentication(true)
            ->withSelfieAuthentication(false)
            ->build();

        $expectedWantedAttributeData = [
            'wanted' => [],
            'wanted_auth_types' => [],
            'wanted_remember_me' => false,
            'wanted_remember_me_optional' => false,
        ];

        $this->assertEquals(json_encode($expectedWantedAttributeData), json_encode($dynamicPolicy));
    }

    /**
     * @covers ::withSelfieAuthentication
     */
    public function testWithNoPinAuthAfterRemoval()
    {
        $dynamicPolicy = (new DynamicPolicyBuilder())
            ->withPinAuthentication(true)
            ->withPinAuthentication(false)
            ->build();

        $expectedWantedAttributeData = [
            'wanted' => [],
            'wanted_auth_types' => [],
            'wanted_remember_me' => false,
            'wanted_remember_me_optional' => false,
        ];

        $this->assertEquals(json_encode($expectedWantedAttributeData), json_encode($dynamicPolicy));
    }

    /**
     * @covers ::withWantedAuthType
     */
    public function testWithNonIntegerAuthType()
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('must be of the type int');

        (new DynamicPolicyBuilder())
            ->withWantedAuthType('99')
            ->build();
    }

    /**
     * @covers ::withWantedRememberMe
     */
    public function testWithRememberMe()
    {
        $dynamicPolicy = (new DynamicPolicyBuilder())
            ->withWantedRememberMe(true)
            ->build();

        $expectedWantedAttributeData = [
            'wanted' => [],
            'wanted_auth_types' => [],
            'wanted_remember_me' => true,
            'wanted_remember_me_optional' => false,
        ];

        $this->assertEquals(json_encode($expectedWantedAttributeData), json_encode($dynamicPolicy));
    }

    /**
     * @covers ::withWantedRememberMe
     */
    public function testWithoutRememberMe()
    {
        $dynamicPolicy = (new DynamicPolicyBuilder())
            ->withWantedRememberMe(false)
            ->build();

        $expectedWantedAttributeData = [
            'wanted' => [],
            'wanted_auth_types' => [],
            'wanted_remember_me' => false,
            'wanted_remember_me_optional' => false,
        ];

        $this->assertEquals(json_encode($expectedWantedAttributeData), json_encode($dynamicPolicy));
    }
}
