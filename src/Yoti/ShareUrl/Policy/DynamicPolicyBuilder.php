<?php

namespace Yoti\ShareUrl\Policy;

use Yoti\Entity\Profile;
use Yoti\Util\Validation;

/**
 * Builder for DynamicPolicy.
 */
class DynamicPolicyBuilder
{
    const SELFIE_AUTH_TYPE = 1;
    const PIN_AUTH_TYPE = 2;

    /**
     * @var \Yoti\ShareUrl\Policy\WantedAttribute[]
     */
    private $wantedAttributes = [];

    /**
     * @var int[]
     */
    private $wantedAuthTypes = [];

    /**
     * @param \Yoti\ShareUrl\Policy\WantedAttribute wantedAttribute
     */
    public function withWantedAttribute(WantedAttribute $wantedAttribute)
    {
        $key = $wantedAttribute->getName();
        if ($wantedAttribute->getDerivation()) {
            $key = $wantedAttribute->getDerivation();
        }

        $this->wantedAttributes[$key] = $wantedAttribute;
        return $this;
    }

    /**
     * @param string $name
     * @param \Yoti\ShareUrl\Policy\Constraints $constraints
     * @param boolean $acceptSelfAsserted
     */
    public function withWantedAttributeByName($name, $constraints = null, $acceptSelfAsserted = null)
    {
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
     * @param boolean $acceptSelfAsserted
     */
    public function withFamilyName($constraints = null, $acceptSelfAsserted = null)
    {
        return $this->withWantedAttributeByName(
            Profile::ATTR_FAMILY_NAME,
            $constraints,
            $acceptSelfAsserted
        );
    }

    /**
     * @param \Yoti\ShareUrl\Policy\Constraints $constraints
     * @param boolean $acceptSelfAsserted
     */
    public function withGivenNames(Constraints $constraints = null, $acceptSelfAsserted = null)
    {
        return $this->withWantedAttributeByName(
            Profile::ATTR_GIVEN_NAMES,
            $constraints,
            $acceptSelfAsserted
        );
    }

    /**
     * @param \Yoti\ShareUrl\Policy\Constraints $constraints
     * @param boolean $acceptSelfAsserted
     */
    public function withFullName(Constraints $constraints = null, $acceptSelfAsserted = null)
    {
        return $this->withWantedAttributeByName(
            Profile::ATTR_FULL_NAME,
            $constraints,
            $acceptSelfAsserted
        );
    }

    /**
     * @param \Yoti\ShareUrl\Policy\Constraints $constraints
     * @param boolean $acceptSelfAsserted
     */
    public function withDateOfBirth(Constraints $constraints = null, $acceptSelfAsserted = null)
    {
        return $this->withWantedAttributeByName(
            Profile::ATTR_DATE_OF_BIRTH,
            $constraints,
            $acceptSelfAsserted
        );
    }

    /**
     * @param int $age
     * @param \Yoti\ShareUrl\Policy\Constraints $constraints
     * @param boolean $acceptSelfAsserted
     */
    public function withAgeOver($age, Constraints $constraints = null, $acceptSelfAsserted = null)
    {
        Validation::isInteger($age, 'age');
        return $this->withAgeDerivedAttribute(
            sprintf(Profile::AGE_OVER_FORMAT, $age),
            $constraints,
            $acceptSelfAsserted
        );
    }

    /**
     * @param int $age
     * @param \Yoti\ShareUrl\Policy\Constraints $constraints
     * @param boolean $acceptSelfAsserted
     */
    public function withAgeUnder($age, Constraints $constraints = null, $acceptSelfAsserted = null)
    {
        Validation::isInteger($age, 'age');
        return $this->withAgeDerivedAttribute(
            sprintf(Profile::AGE_UNDER_FORMAT, $age),
            $constraints,
            $acceptSelfAsserted
        );
    }

    /**
     * @param string $derivation
     * @param \Yoti\ShareUrl\Policy\Constraints $constraints
     * @param boolean $acceptSelfAsserted
     */
    public function withAgeDerivedAttribute($derivation, Constraints $constraints = null, $acceptSelfAsserted = null)
    {
        $wantedAttributeBuilder = (new WantedAttributeBuilder())
            ->withName(Profile::ATTR_DATE_OF_BIRTH)
            ->withDerivation($derivation)
            ->withAcceptSelfAsserted($acceptSelfAsserted);

        if ($constraints !== null) {
            $wantedAttributeBuilder->withConstraints($constraints);
        }

        return $this->withWantedAttribute($wantedAttributeBuilder->build());
    }

    /**
     * @param \Yoti\ShareUrl\Policy\Constraints $constraints
     * @param boolean $acceptSelfAsserted
     */
    public function withGender(Constraints $constraints = null, $acceptSelfAsserted = null)
    {
        return $this->withWantedAttributeByName(
            Profile::ATTR_GENDER,
            $constraints,
            $acceptSelfAsserted
        );
    }

    /**
     * @param \Yoti\ShareUrl\Policy\Constraints $constraints
     * @param boolean $acceptSelfAsserted
     */
    public function withPostalAddress(Constraints $constraints = null, $acceptSelfAsserted = null)
    {
        return $this->withWantedAttributeByName(
            Profile::ATTR_POSTAL_ADDRESS,
            $constraints,
            $acceptSelfAsserted
        );
    }

    /**
     * @param \Yoti\ShareUrl\Policy\Constraints $constraints
     * @param boolean $acceptSelfAsserted
     */
    public function withStructuredPostalAddress(Constraints $constraints = null, $acceptSelfAsserted = null)
    {
        return $this->withWantedAttributeByName(
            Profile::ATTR_STRUCTURED_POSTAL_ADDRESS,
            $constraints,
            $acceptSelfAsserted
        );
    }

    /**
     * @param \Yoti\ShareUrl\Policy\Constraints $constraints
     * @param boolean $acceptSelfAsserted
     */
    public function withNationality(Constraints $constraints = null, $acceptSelfAsserted = null)
    {
        return $this->withWantedAttributeByName(
            Profile::ATTR_NATIONALITY,
            $constraints,
            $acceptSelfAsserted
        );
    }

    /**
     * @param \Yoti\ShareUrl\Policy\Constraints $constraints
     * @param boolean $acceptSelfAsserted
     */
    public function withPhoneNumber(Constraints $constraints = null, $acceptSelfAsserted = null)
    {
        return $this->withWantedAttributeByName(
            Profile::ATTR_PHONE_NUMBER,
            $constraints,
            $acceptSelfAsserted
        );
    }

    /**
     * @param \Yoti\ShareUrl\Policy\Constraints $constraints
     * @param boolean $acceptSelfAsserted
     */
    public function withSelfie(Constraints $constraints = null, $acceptSelfAsserted = null)
    {
        return $this->withWantedAttributeByName(
            Profile::ATTR_SELFIE,
            $constraints,
            $acceptSelfAsserted
        );
    }

    /**
     * @param \Yoti\ShareUrl\Policy\Constraints $constraints
     * @param boolean $acceptSelfAsserted
     */
    public function withDocumentDetails(Constraints $constraints = null, $acceptSelfAsserted = null)
    {
        return $this->withWantedAttributeByName(
            Profile::ATTR_DOCUMENT_DETAILS,
            $constraints,
            $acceptSelfAsserted
        );
    }

    /**
     * @param \Yoti\ShareUrl\Policy\Constraints $constraints
     * @param boolean $acceptSelfAsserted
     */
    public function withDocumentImages(Constraints $constraints = null, $acceptSelfAsserted = null)
    {
        return $this->withWantedAttributeByName(
            Profile::ATTR_DOCUMENT_IMAGES,
            $constraints,
            $acceptSelfAsserted
        );
    }

    /**
     * @param \Yoti\ShareUrl\Policy\Constraints $constraints
     * @param boolean $acceptSelfAsserted
     */
    public function withEmail(Constraints $constraints = null, $acceptSelfAsserted = null)
    {
        return $this->withWantedAttributeByName(
            Profile::ATTR_EMAIL_ADDRESS,
            $constraints,
            $acceptSelfAsserted
        );
    }

    /**
     * @param boolean $enabled
     */
    public function withSelfieAuthentication($enabled = true)
    {
        return $this->withWantedAuthType(self::SELFIE_AUTH_TYPE, $enabled);
    }

    /**
     * @param boolean $enabled
     */
    public function withPinAuthentication($enabled = true)
    {
        return $this->withWantedAuthType(self::PIN_AUTH_TYPE, $enabled);
    }

    /**
     * @param int $wantedAuthType
     * @param boolean $enabled
     */
    public function withWantedAuthType($wantedAuthType, $enabled = true)
    {
        if ($enabled) {
            $this->wantedAuthTypes[$wantedAuthType] = $wantedAuthType;
        } else {
            unset($this->wantedAuthTypes[$wantedAuthType]);
        }

        return this;
    }

    /**
     * @param boolean $wantedRememberMe
     */
    public function withWantedRememberMe($wantedRememberMe)
    {
        $this->wantedRememberMe = $wantedRememberMe;
        return $this;
    }

    /**
     * @returns {DynamicPolicy}
     */
    public function build()
    {
        return new DynamicPolicy(
            array_values($this->wantedAttributes),
            array_values($this->wantedAuthTypes),
            $this->wantedRememberMe,
            false
        );
    }
}
