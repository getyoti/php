<?php
namespace Yoti;

use Yoti\Entity\Selfie;
use Yoti\Entity\Profile;
use Yoti\Entity\Receipt;
use Yoti\Entity\Attribute;
use Attrpubapi_v1\Attribute as ProtobufAttribute;
use Attrpubapi_v1\AttributeList;
use Yoti\Util\Age\AgeUnderOverProcessor;
use Yoti\Util\Profile\AnchorProcessor;
use Yoti\Util\Profile\AttributeConverter;
use Yoti\Entity\ApplicationProfile;

/**
 * Class ActivityDetails
 *
 * @package Yoti
 * @author Yoti SDK <websdk@yoti.com>
 */
class ActivityDetails
{
    const ATTR_FAMILY_NAME = 'family_name';
    const ATTR_GIVEN_NAMES = 'given_names';
    const ATTR_FULL_NAME = 'full_name';
    const ATTR_DATE_OF_BIRTH = 'date_of_birth';
    const ATTR_AGE_VERIFIED = 'age_verified';
    const ATTR_GENDER = 'gender';
    const ATTR_NATIONALITY = 'nationality';
    const ATTR_PHONE_NUMBER = 'phone_number';
    const ATTR_SELFIE = 'selfie';
    const ATTR_EMAIL_ADDRESS = 'email_address';
    const ATTR_POSTAL_ADDRESS = 'postal_address';
    const ATTR_STRUCTURED_POSTAL_ADDRESS = 'structured_postal_address';

    /**
     * @var string receipt identifier
     */
    private $rememberMeId;

    /**
     * @var array
     */
    private $oldProfileData = [];

    /**
     * @var \Yoti\Entity\Profile
     */
    private $userProfile;

    /**
     * @var ApplicationProfile
     */
    private $applicationProfile;

    /**
     * @var Receipt
     */
    private $receipt;

    /**
     * @var string
     */
    private $pem;

    /**
     * @var \Yoti\Util\Age\Condition
     */
    private $ageCondition;

    /**
     * @var AnchorProcessor
     */
    private $anchorProcessor;

    /**
     * ActivityDetails constructor.
     *
     * @param array $attributes
     * @param $rememberMeId
     */
    public function __construct(Receipt $receipt, $pem)
    {
        $this->receipt = $receipt;
        $this->pem = $pem;
        $this->anchorProcessor = new AnchorProcessor();
        // Set default value of ageCondition
        $this->ageCondition = new \Yoti\Util\Age\Condition([]);

        $this->setRememberMeId();
        $this->setProfile();
        $this->setApplicationProfile();
    }

    private function setRememberMeId()
    {
        $this->rememberMeId = $this->receipt->getRememberMeId();
    }

    private function setProfile()
    {
        $protobufAttrList = $this->receipt->parseAttribute(
            Receipt::ATTR_OTHER_PARTY_PROFILE_CONTENT,
            $this->pem
        );
        $this->userProfile = new Profile($this->processUserProfileAttributes($protobufAttrList));
    }

    private function setApplicationProfile()
    {
        $protobufAttributesList = $this->receipt->parseAttribute(
            Receipt::ATTR_PROFILE_CONTENT,
            $this->pem
        );
        $this->applicationProfile = new ApplicationProfile(
            AttributeConverter::convertToYotiAttributesMap($protobufAttributesList)
        );
    }

    private function processUserProfileAttributes(AttributeList $attributeList)
    {
        // For ActivityDetails attributes
        $attrsMap = [];
        // For Profile attributes
        $profileAttributes = [];
        $ageVerificationAnchors = [];

        foreach ($attributeList->getAttributes() as $item) /** @var ProtobufAttribute $item */
        {
            $attrName = $item->getName();

            if ($attrName === 'selfie') {
                $attrsMap[$attrName] = new Selfie(
                    $item->getValue(),
                    $item->getName()
                );
            }
            else {
                $attrsMap[$attrName] = $item->getValue();
            }

            // Build attribute object for user profile
            $attributeAnchors = $this->anchorProcessor->process($item->getAnchors());
            $yotiAttribute = AttributeConverter::convertToYotiAttribute($item, $attributeAnchors, $attrName);
            $profileAttributes[$attrName] = $yotiAttribute;
            // Add 'is_age_verified' and 'verified_age' attributes
            if (NULL !==  $yotiAttribute && preg_match(AgeUnderOverProcessor::AGE_PATTERN, $attrName)) {
                $ageVerificationAnchors = $attributeAnchors;
            }
        }

        // Add 'age_condition' and 'verified_age' attributes values
        $this->addAgeVerificationAttributes($profileAttributes, $ageVerificationAnchors, $attrsMap);

        // Set user profile attributes for the old profile
        $this->oldProfileData = $attrsMap;

        return $profileAttributes;
    }

    private function addAgeVerificationAttributes(array &$profileAttributes, $ageVerificationAnchors, array $attrsMap)
    {
        // Add 'age_condition' and 'verified_age' attributes values
        if (!empty($ageVerificationAnchors)) {
            $ageProcessor = new \Yoti\Util\Age\Processor($attrsMap);
            $this->ageCondition = $ageProcessor->getCondition();

            $profileAttributes[Attribute::AGE_CONDITION] = new Attribute(
                Attribute::AGE_CONDITION,
                $this->ageCondition->isVerified(),
                $ageVerificationAnchors['sources'],
                $ageVerificationAnchors['verifiers']
            );

            $profileAttributes[Attribute::VERIFIED_AGE] = new Attribute(
                Attribute::VERIFIED_AGE,
                $this->ageCondition->getVerifiedAge(),
                $ageVerificationAnchors['sources'],
                $ageVerificationAnchors['verifiers']
            );
        }
    }

    /**
     * @return ApplicationProfile
     */
    public function getApplicationProfile()
    {
        return $this->applicationProfile;
    }

    /**
     * Set a user profile attribute.
     *
     * @param $param
     * @param $value
     */
    protected function setProfileAttribute($param, $value)
    {
        if (!empty($param)) {
            $this->oldProfileData[$param] = $value;
        }
    }

    /**
     * Get user profile attribute.
     *
     * @param null|string $param
     *
     * @return array|mixed
     */
    public function getProfileAttribute($param = null)
    {
        if ($param)
        {
            return $this->hasProfileAttribute($param) ? $this->oldProfileData[$param] : null;
        }

        return $this->oldProfileData;
    }

    /**
     * Get user profile object.
     *
     * @return Profile
     */
    public function getProfile()
    {
        return $this->userProfile;
    }

    /**
     * Check if attribute exists.
     *
     * @param string $param
     *
     * @return bool
     */
    public function hasProfileAttribute($param)
    {
        return array_key_exists($param, $this->oldProfileData);
    }

    /**
     * Get user id.
     *
     * @return string
     */
    public function getUserId()
    {
        return $this->rememberMeId;
    }

    /**
     * Get family name.
     *
     * @deprecated 1.2.0
     *  Use profile::getFamilyName()
     *
     * @return null|string
     */
    public function getFamilyName()
    {
        return $this->getProfileAttribute(self::ATTR_FAMILY_NAME);
    }

    /**
     * Get given names.
     *
     * @deprecated 1.2.0
     *  Use profile::getGivenNames()
     *
     * @return null|string
     */
    public function getGivenNames()
    {
        return $this->getProfileAttribute(self::ATTR_GIVEN_NAMES);
    }

    /**
     * Get full name.
     *
     * @deprecated 1.2.0
     *  Use profile::getFullName()
     *
     * @return null|string
     */
    public function getFullName()
    {
        return $this->getProfileAttribute(self::ATTR_FULL_NAME);
    }

    /**
     * Get date of birth.
     *
     * @deprecated 1.2.0
     *  Use profile::getDateOfBirth()
     *
     * @return null|string
     */
    public function getDateOfBirth()
    {
        return $this->getProfileAttribute(self::ATTR_DATE_OF_BIRTH);
    }

    /**
     * Get gender.
     *
     * @deprecated 1.2.0
     *  Use profile::getGender()
     *
     * @return null|string
     */
    public function getGender()
    {
        return $this->getProfileAttribute(self::ATTR_GENDER);
    }

    /**
     * Get user nationality.
     *
     * @deprecated 1.2.0
     *  Use profile::getNationality()
     *
     * @return null|string
     */
    public function getNationality()
    {
        return $this->getProfileAttribute(self::ATTR_NATIONALITY);
    }

    /**
     * Get user phone number.
     *
     * @deprecated 1.2.0
     *  Use profile::getPhoneNumber()
     *
     * @return null|string
     */
    public function getPhoneNumber()
    {
        return $this->getProfileAttribute(self::ATTR_PHONE_NUMBER);
    }

    /**
     * Get user selfie image data.
     *
     * @deprecated 1.2.0
     *  Use profile::getSelfie()
     *
     * @return null|string
     */
    public function getSelfie()
    {
        $selfie = $this->getProfileAttribute(self::ATTR_SELFIE);

        if($selfie instanceof Selfie)
        {
            $selfie = $selfie->getContent();
        }

        return $selfie;
    }

    /**
     * Get selfie image object.
     *
     * @deprecated 1.2.0
     *
     * @return null| \Yoti\Entity\Selfie $selfie
     */
    public function getSelfieEntity()
    {
        $selfieObj = $this->getProfileAttribute(self::ATTR_SELFIE);
        // Returns selfie entity or null
        return ($selfieObj instanceof Selfie) ? $selfieObj : NULL;
    }

    /**
     * Get user email address.
     *
     * @deprecated 1.2.0
     *  Use profile::getEmailAddress()
     *
     * @return null|string
     */
    public function getEmailAddress()
    {
        return $this->getProfileAttribute(self::ATTR_EMAIL_ADDRESS);
    }

    /**
     * Get user address.
     *
     * @deprecated 1.2.0
     *  Use profile::getPostalAddress()
     *
     * @return null|string
     */
    public function getPostalAddress()
    {
        $postalAddress = $this->getProfileAttribute(self::ATTR_POSTAL_ADDRESS);
        if (NULL === $postalAddress) {
            // Get it from structured_postal_address.formatted_address
            $structuredPostalAddress = $this->getStructuredPostalAddress();
            if (
                is_array($structuredPostalAddress)
                && isset($structuredPostalAddress['formatted_address'])
            ) {
                $postalAddress = $structuredPostalAddress['formatted_address'];
            }
        }
        return $postalAddress;
    }

    /**
     * Get user structured postal address as an array.
     *
     * @deprecated 1.2.0
     *  Use profile::getStructuredPostalAddress()
     *
     * @return null|array
     */
    public function getStructuredPostalAddress()
    {
        $structuredPostalAddress = $this->getProfileAttribute(self::ATTR_STRUCTURED_POSTAL_ADDRESS);
        return json_decode($structuredPostalAddress, true);
    }

    /**
     * Returns a boolean representing the attribute value
     * Or null if the attribute is not set in the dashboard
     *
     * @deprecated 1.2.0
     *  Use profile::getAgeCondition()
     *
     * @return bool|null
     */
    public function isAgeVerified()
    {
        return $this->ageCondition->isVerified();
    }

    /**
     * @deprecated 1.2.0
     *  Use profile::getVerifiedAge()
     *
     * @return null|string
     */
    public function getVerifiedAge()
    {
        return $this->ageCondition->getVerifiedAge();
    }
}