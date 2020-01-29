<?php

declare(strict_types=1);

namespace Yoti\Sandbox\Test\Profile\Request;

use Yoti\Sandbox\Profile\Request\Attribute\SandboxAgeVerification;
use Yoti\Sandbox\Profile\Request\Attribute\SandboxAnchor;
use Yoti\Sandbox\Profile\Request\Attribute\SandboxAttribute;
use Yoti\Sandbox\Profile\Request\Attribute\SandboxDocumentDetails;
use Yoti\Sandbox\Profile\Request\TokenRequest;
use Yoti\Sandbox\Profile\Request\TokenRequestBuilder;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\Sandbox\Profile\Request\TokenRequestBuilder
 */
class TokenRequestBuilderTest extends TestCase
{
    private const SOME_REMEMBER_ME_ID = 'some_remember_me_id';
    private const SOME_NAME = 'some name';
    private const SOME_STRING_VALUE = 'some string';
    private const SOME_TYPE = 'some type';
    private const SOME_SUB_TYPE = 'some sub type';
    private const SOME_TIMESTAMP = 1575998454;

    /**
     * @var \Yoti\Sandbox\Profile\RequestBuilders
     */
    private $requestBuilder;

    public function setup(): void
    {
        $this->requestBuilder = new TokenRequestBuilder();
    }

    /**
     * @covers ::build
     */
    public function testBuild()
    {
        $tokenRequest = $this->requestBuilder->build();

        $this->assertInstanceOf(TokenRequest::class, $tokenRequest);
    }

    /**
     * @covers ::setRememberMeId
     */
    public function testGetRememberMeId()
    {
        $this->requestBuilder->setRememberMeId(self::SOME_REMEMBER_ME_ID);
        $tokenRequest = $this->requestBuilder->build();

        $this->assertEquals(self::SOME_REMEMBER_ME_ID, $tokenRequest->getRememberMeId());
    }

    /**
     * @covers ::setFullName
     * @covers ::setFamilyName
     * @covers ::setGivenNames
     * @covers ::setGender
     * @covers ::setNationality
     * @covers ::setPhoneNumber
     * @covers ::setBase64Selfie
     * @covers ::setEmailAddress
     * @covers ::setPostalAddress
     * @covers ::setStructuredPostalAddress
     * @covers ::setDocumentDetailsWithString
     * @covers ::createAttribute
     * @covers ::addAttribute
     * @covers ::formatAnchors
     *
     * @dataProvider stringAttributeSettersDataProvider
     */
    public function testStringAttributeSetters($setterMethod, $name)
    {
        $this->requestBuilder->{$setterMethod}(self::SOME_STRING_VALUE);
        $tokenRequest = $this->requestBuilder->build();
        $sandboxAttribute = $tokenRequest->getSandboxAttributes()[0];

        $this->assertEquals($sandboxAttribute['name'], $name);
        $this->assertEquals($sandboxAttribute['value'], self::SOME_STRING_VALUE);
        $this->assertEquals($sandboxAttribute['anchors'], []);
    }

    /**
     * Provides test data for attribute setters.
     *
     * @return array
     */
    public function stringAttributeSettersDataProvider()
    {
        return [
            ['setFullName', 'full_name'],
            ['setFamilyName', 'family_name'],
            ['setGivenNames', 'given_names'],
            ['setGender', 'gender'],
            ['setNationality', 'nationality'],
            ['setPhoneNumber', 'phone_number'],
            ['setBase64Selfie', 'selfie'],
            ['setEmailAddress', 'email_address'],
            ['setPostalAddress', 'postal_address'],
            ['setStructuredPostalAddress', 'structured_postal_address'],
            ['setDocumentDetailsWithString', 'document_details'],
        ];
    }

    /**
     * @covers ::setDateOfBirth
     */
    public function testSetDateOfBirth()
    {
        $someDOB = new \DateTime();
        $this->requestBuilder->setDateOfBirth($someDOB);
        $tokenRequest = $this->requestBuilder->build();
        $sandboxAttribute = $tokenRequest->getSandboxAttributes()[0];

        $this->assertEquals($sandboxAttribute['name'], 'date_of_birth');
        $this->assertEquals($sandboxAttribute['value'], $someDOB->format('Y-m-d'));
    }

    /**
     * @covers ::setSelfie
     */
    public function testSetSelfie()
    {
        $this->requestBuilder->setSelfie(self::SOME_STRING_VALUE);
        $tokenRequest = $this->requestBuilder->build();
        $sandboxAttribute = $tokenRequest->getSandboxAttributes()[0];

        $this->assertEquals($sandboxAttribute['name'], 'selfie');
        $this->assertEquals($sandboxAttribute['value'], base64_encode(self::SOME_STRING_VALUE));
    }

    /**
     * @covers ::setDocumentDetails
     */
    public function testSetDocumentDetails()
    {
        $someDocumentDetails  = $this->createMock(SandboxDocumentDetails::class);
        $someDocumentDetails->method('getValue')->willReturn(self::SOME_STRING_VALUE);

        $this->requestBuilder->setDocumentDetails($someDocumentDetails);
        $tokenRequest = $this->requestBuilder->build();
        $sandboxAttribute = $tokenRequest->getSandboxAttributes()[0];

        $this->assertEquals($sandboxAttribute['name'], 'document_details');
        $this->assertEquals($sandboxAttribute['value'], self::SOME_STRING_VALUE);
    }

    /**
     * @covers ::setAgeVerification
     */
    public function testSetAgeVerification()
    {
        $someAgeVerification  = $this->createMock(SandboxAgeVerification::class);
        $someAgeVerification->method('getName')->willReturn(self::SOME_NAME);
        $someAgeVerification->method('getValue')->willReturn(self::SOME_STRING_VALUE);
        $someAgeVerification->method('getAnchors')->willReturn([]);

        $this->requestBuilder->setAgeVerification($someAgeVerification);
        $tokenRequest = $this->requestBuilder->build();
        $sandboxAttribute = $tokenRequest->getSandboxAttributes()[0];

        $this->assertEquals($sandboxAttribute['name'], self::SOME_NAME);
        $this->assertEquals($sandboxAttribute['value'], self::SOME_STRING_VALUE);
    }

    /**
     * @covers ::formatAnchors
     */
    public function testFormatAnchors()
    {
        $someAnchor = $this->createMock(SandboxAnchor::class);
        $someAnchor->method('getType')->willReturn(self::SOME_TYPE);
        $someAnchor->method('getSubType')->willReturn(self::SOME_SUB_TYPE);
        $someAnchor->method('getValue')->willReturn(self::SOME_STRING_VALUE);
        $someAnchor->method('getTimestamp')->willReturn(self::SOME_TIMESTAMP);

        $someAttribute = $this->createMock(SandboxAttribute::class);
        $someAttribute->method('getAnchors')->willReturn([$someAnchor]);

        $this->requestBuilder->addAttribute($someAttribute);
        $tokenRequest = $this->requestBuilder->build();
        $sandboxAttribute = $tokenRequest->getSandboxAttributes()[0];

        $this->assertEquals($sandboxAttribute['anchors'], [
            [
                'type' => strtoupper(self::SOME_TYPE),
                'value' => self::SOME_STRING_VALUE,
                'sub_type' => self::SOME_SUB_TYPE,
                'timestamp' => self::SOME_TIMESTAMP * 1000000,
            ],
        ]);
    }
}
