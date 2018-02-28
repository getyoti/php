<?php

namespace YotiTest;

use \Yoti\ActivityDetails;
use \Yoti\Entity\Selfie;

class ActivityDetailsTest extends TestCase
{
    /**
     * @var array
     */
    public $dummyProfile = [];

    /**
     * @var ActivityDetails
     */
    public $profile;

    public $userId = 45555;

    public function setUp()
    {
        $this->dummyProfile = [
            'family_name' => 'TestFamilyName',
            'given_names' => 'TestGivenName',
            'full_name'   => 'TestGivenName TestFamilyName',
            'date_of_birth' => '11-07-2017',
            'age_over:18'=> 'false',
            'gender' => 'Male',
            'nationality' => 'British',
            'phone_number' => '07856836932',
            'selfie' => new Selfie(file_get_contents(DUMMY_SELFIE_FILE), 'jpeg'),
            'email_address' => 'test_email@yoti.com',
            'postal_address' => '130 Fenchurch Street London, EC3M 5DJ'
        ];

        $this->profile = new ActivityDetails($this->dummyProfile, $this->userId);
    }

    /**
     * Test getting ActivityDetails Instance
     */
    public function testActivityDetailsInstance()
    {
        $this->assertInstanceOf(ActivityDetails::class, $this->profile);
    }

    /**
     * Test getting UserID
     */
    public function testGetUserId()
    {
        $this->assertEquals($this->userId, $this->profile->getUserId());
    }

    /**
     * Test getting Given Names
     */
    public function testGetGivenNames()
    {
        $this->assertEquals($this->dummyProfile['given_names'], $this->profile->getGivenNames());
    }

    /**
     * Test getting Family Name
     */
    public function testGetFamilyName()
    {
        $this->assertEquals($this->dummyProfile['family_name'], $this->profile->getFamilyName());
    }

    /**
     * Test getting Full Name
     */
    public function testGetFullName()
    {
        $this->assertEquals($this->dummyProfile['full_name'], $this->profile->getFullName());
    }

    /**
     * Test getting Date Of Birth
     */
    public function testGetDateOfBirth()
    {
        $this->assertEquals($this->dummyProfile['date_of_birth'], $this->profile->getDateOfBirth());
    }

    /**
     * Test age over 18.
     */
    public function testIsAgeVerified()
    {
        $this->assertEquals($this->dummyProfile['age_over:18'], $this->profile->getProfileAttribute('age_over:18'));
    }

    /**
     * Test Getting Gender
     */
    public function testGetGender()
    {
        $this->assertEquals($this->dummyProfile['gender'], $this->profile->getGender());
    }

    /**
     * Test getting Nationality
     */
    public function testGetNationality()
    {
        $this->assertEquals($this->dummyProfile['nationality'], $this->profile->getNationality());
    }

    /**
     * Test getting Phone Number
     */
    public function testGetPhoneNumber()
    {
        $this->assertEquals($this->dummyProfile['phone_number'], $this->profile->getPhoneNumber());
    }

    /**
     * Test getting Email Address
     */
    public function testGetEmailAddress()
    {
        $this->assertEquals($this->dummyProfile['email_address'], $this->profile->getEmailAddress());
    }

    /**
     * Test getting Selfie
     */
    public function testGetSelfie()
    {
        $this->assertEquals($this->dummyProfile['selfie'], $this->profile->getSelfie());
    }

    /**
     * Test getting selfie object
     */
    public function testGetSelfieEntity()
    {
        $this->assertInstanceOf(Selfie::class, $this->profile->getSelfieEntity());
    }
}