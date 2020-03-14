<?php

declare(strict_types=1);

namespace Yoti\ShareUrl\Policy;

use Yoti\Profile\UserProfile;
use Yoti\Util\Json;

/**
 * Builder for DynamicPolicy.
 */
class DynamicPolicyBuilder
{
    /**
     * Selfie auth type.
     */
    private const SELFIE_AUTH_TYPE = 1;

    /**
     * PIN auth type.
     */
    private const PIN_AUTH_TYPE = 2;

    /**
     * @var \Yoti\ShareUrl\Policy\WantedAttribute[]
     */
    private $wantedAttributes = [];

    /**
     * @var int[]
     */
    private $wantedAuthTypes = [];

    /**
     * @var bool
     */
    private $wantedRememberMe = false;

    /**
     * @param \Yoti\ShareUrl\Policy\WantedAttribute $wantedAttribute
     *
     * @return $this
     */
    public function withWantedAttribute(WantedAttribute $wantedAttribute): self
    {
        $key = $wantedAttribute->getName();

        if ($wantedAttribute->getDerivation() !== null) {
            $key = $wantedAttribute->getDerivation();
        }

        if ($wantedAttribute->getConstraints() !== null) {
            $key .= '-' . md5(Json::encode($wantedAttribute->getConstraints()));
        }

        $this->wantedAttributes[$key] = $wantedAttribute;
        return $this;
    }

    /**
     * @param string $name
     * @param \Yoti\ShareUrl\Policy\Constraints $constraints
     * @param bool $acceptSelfAsserted
     *
     * @return $this
     */
    public function withWantedAttributeByName(
        string $name,
        Constraints $constraints = null,
        bool $acceptSelfAsserted = null
    ): self {
        $wantedAttributeBuilder = (new WantedAttributeBuilder())
            ->withName($name);

        if ($constraints !== null) {
            $wantedAttributeBuilder->withConstraints($constraints);
        }

        if ($acceptSelfAsserted !== null) {
            $wantedAttributeBuilder->withAcceptSelfAsserted($acceptSelfAsserted);
        }

        return $this->withWantedAttribute($wantedAttributeBuilder->build());
    }

    /**
     * @param \Yoti\ShareUrl\Policy\Constraints $constraints
     * @param bool $acceptSelfAsserted
     *
     * @return $this
     */
    public function withFamilyName(Constraints $constraints = null, bool $acceptSelfAsserted = null): self
    {
        return $this->withWantedAttributeByName(
            UserProfile::ATTR_FAMILY_NAME,
            $constraints,
            $acceptSelfAsserted
        );
    }

    /**
     * @param \Yoti\ShareUrl\Policy\Constraints $constraints
     * @param bool $acceptSelfAsserted
     *
     * @return self
     */
    public function withGivenNames(Constraints $constraints = null, bool $acceptSelfAsserted = null): self
    {
        return $this->withWantedAttributeByName(
            UserProfile::ATTR_GIVEN_NAMES,
            $constraints,
            $acceptSelfAsserted
        );
    }

    /**
     * @param \Yoti\ShareUrl\Policy\Constraints $constraints
     * @param bool $acceptSelfAsserted
     *
     * @return self
     */
    public function withFullName(Constraints $constraints = null, bool $acceptSelfAsserted = null): self
    {
        return $this->withWantedAttributeByName(
            UserProfile::ATTR_FULL_NAME,
            $constraints,
            $acceptSelfAsserted
        );
    }

    /**
     * @param \Yoti\ShareUrl\Policy\Constraints $constraints
     * @param bool $acceptSelfAsserted
     *
     * @return $this
     */
    public function withDateOfBirth(Constraints $constraints = null, bool $acceptSelfAsserted = null): self
    {
        return $this->withWantedAttributeByName(
            UserProfile::ATTR_DATE_OF_BIRTH,
            $constraints,
            $acceptSelfAsserted
        );
    }

    /**
     * @param int $age
     * @param \Yoti\ShareUrl\Policy\Constraints $constraints
     * @param bool $acceptSelfAsserted
     *
     * @return $this
     */
    public function withAgeOver(int $age, Constraints $constraints = null, bool $acceptSelfAsserted = null): self
    {
        return $this->withAgeDerivedAttribute(
            UserProfile::AGE_OVER . (string) $age,
            $constraints,
            $acceptSelfAsserted
        );
    }

    /**
     * @param int $age
     * @param \Yoti\ShareUrl\Policy\Constraints $constraints
     * @param bool $acceptSelfAsserted
     *
     * @return $this
     */
    public function withAgeUnder(int $age, Constraints $constraints = null, bool $acceptSelfAsserted = null): self
    {
        return $this->withAgeDerivedAttribute(
            UserProfile::AGE_UNDER . (string) $age,
            $constraints,
            $acceptSelfAsserted
        );
    }

    /**
     * @param string $derivation
     * @param \Yoti\ShareUrl\Policy\Constraints $constraints
     * @param bool $acceptSelfAsserted
     *
     * @return $this
     */
    public function withAgeDerivedAttribute(
        string $derivation,
        Constraints $constraints = null,
        bool $acceptSelfAsserted = null
    ): self {
        $wantedAttributeBuilder = (new WantedAttributeBuilder())
            ->withName(UserProfile::ATTR_DATE_OF_BIRTH)
            ->withDerivation($derivation)
            ->withAcceptSelfAsserted($acceptSelfAsserted);

        if ($constraints !== null) {
            $wantedAttributeBuilder->withConstraints($constraints);
        }

        return $this->withWantedAttribute($wantedAttributeBuilder->build());
    }

    /**
     * @param \Yoti\ShareUrl\Policy\Constraints $constraints
     * @param bool $acceptSelfAsserted
     *
     * @return $this
     */
    public function withGender(Constraints $constraints = null, bool $acceptSelfAsserted = null): self
    {
        return $this->withWantedAttributeByName(
            UserProfile::ATTR_GENDER,
            $constraints,
            $acceptSelfAsserted
        );
    }

    /**
     * @param \Yoti\ShareUrl\Policy\Constraints $constraints
     * @param bool $acceptSelfAsserted
     *
     * @return $this
     */
    public function withPostalAddress(Constraints $constraints = null, bool $acceptSelfAsserted = null): self
    {
        return $this->withWantedAttributeByName(
            UserProfile::ATTR_POSTAL_ADDRESS,
            $constraints,
            $acceptSelfAsserted
        );
    }

    /**
     * @param \Yoti\ShareUrl\Policy\Constraints $constraints
     * @param bool $acceptSelfAsserted
     *
     * @return $this
     */
    public function withStructuredPostalAddress(Constraints $constraints = null, bool $acceptSelfAsserted = null): self
    {
        return $this->withWantedAttributeByName(
            UserProfile::ATTR_STRUCTURED_POSTAL_ADDRESS,
            $constraints,
            $acceptSelfAsserted
        );
    }

    /**
     * @param \Yoti\ShareUrl\Policy\Constraints $constraints
     * @param bool $acceptSelfAsserted
     *
     * @return $this
     */
    public function withNationality(Constraints $constraints = null, bool $acceptSelfAsserted = null): self
    {
        return $this->withWantedAttributeByName(
            UserProfile::ATTR_NATIONALITY,
            $constraints,
            $acceptSelfAsserted
        );
    }

    /**
     * @param \Yoti\ShareUrl\Policy\Constraints $constraints
     * @param bool $acceptSelfAsserted
     *
     * @return $this
     */
    public function withPhoneNumber(Constraints $constraints = null, bool $acceptSelfAsserted = null): self
    {
        return $this->withWantedAttributeByName(
            UserProfile::ATTR_PHONE_NUMBER,
            $constraints,
            $acceptSelfAsserted
        );
    }

    /**
     * @param \Yoti\ShareUrl\Policy\Constraints $constraints
     * @param bool $acceptSelfAsserted
     *
     * @return $this
     */
    public function withSelfie(Constraints $constraints = null, bool $acceptSelfAsserted = null): self
    {
        return $this->withWantedAttributeByName(
            UserProfile::ATTR_SELFIE,
            $constraints,
            $acceptSelfAsserted
        );
    }

    /**
     * @param \Yoti\ShareUrl\Policy\Constraints $constraints
     * @param bool $acceptSelfAsserted
     *
     * @return $this
     */
    public function withDocumentDetails(Constraints $constraints = null, bool $acceptSelfAsserted = null): self
    {
        return $this->withWantedAttributeByName(
            UserProfile::ATTR_DOCUMENT_DETAILS,
            $constraints,
            $acceptSelfAsserted
        );
    }

    /**
     * @param \Yoti\ShareUrl\Policy\Constraints $constraints
     * @param bool $acceptSelfAsserted
     *
     * @return $this
     */
    public function withDocumentImages(Constraints $constraints = null, bool $acceptSelfAsserted = null): self
    {
        return $this->withWantedAttributeByName(
            UserProfile::ATTR_DOCUMENT_IMAGES,
            $constraints,
            $acceptSelfAsserted
        );
    }

    /**
     * @param \Yoti\ShareUrl\Policy\Constraints $constraints
     * @param bool $acceptSelfAsserted
     *
     * @return $this
     */
    public function withEmail(Constraints $constraints = null, bool $acceptSelfAsserted = null): self
    {
        return $this->withWantedAttributeByName(
            UserProfile::ATTR_EMAIL_ADDRESS,
            $constraints,
            $acceptSelfAsserted
        );
    }

    /**
     * @param bool $enabled
     *
     * @return $this
     */
    public function withSelfieAuthentication(bool $enabled = true): self
    {
        return $this->withWantedAuthType(self::SELFIE_AUTH_TYPE, $enabled);
    }

    /**
     * @param bool $enabled
     *
     * @return $this
     */
    public function withPinAuthentication(bool $enabled = true): self
    {
        return $this->withWantedAuthType(self::PIN_AUTH_TYPE, $enabled);
    }

    /**
     * @param int $wantedAuthType
     * @param bool $enabled
     *
     * @return $this
     */
    public function withWantedAuthType(int $wantedAuthType, bool $enabled = true): self
    {
        if ($enabled) {
            $this->wantedAuthTypes[$wantedAuthType] = $wantedAuthType;
        } else {
            unset($this->wantedAuthTypes[$wantedAuthType]);
        }

        return $this;
    }

    /**
     * @param bool $wantedRememberMe
     *
     * @return $this
     */
    public function withWantedRememberMe(bool $wantedRememberMe): self
    {
        $this->wantedRememberMe = $wantedRememberMe;
        return $this;
    }

    /**
     * @return \Yoti\ShareUrl\Policy\DynamicPolicy
     */
    public function build(): DynamicPolicy
    {
        return new DynamicPolicy(
            array_values($this->wantedAttributes),
            array_values($this->wantedAuthTypes),
            $this->wantedRememberMe
        );
    }
}
