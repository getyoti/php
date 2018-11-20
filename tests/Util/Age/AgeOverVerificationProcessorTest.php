<?php
namespace YotiTest\Util\Age;

use YotiTest\TestCase;
use Yoti\Entity\Attribute;
use Yoti\Entity\AgeVerification;
use Yoti\Util\Age\AgeOverVerificationProcessor;

class AgeOverVerificationProcessorTest extends TestCase
{
    public function testProcessWithAgeOver()
    {
        $ageAttribute = new Attribute('age_over:18', 'true', []);
        $ageOverVerificationProcessor = new AgeOverVerificationProcessor($ageAttribute);
        $ageVerificationObj = $ageOverVerificationProcessor->process();
        $this->assertInstanceOf(AgeVerification::class, $ageVerificationObj);
        $this->assertTrue($ageVerificationObj->getResult());
        $this->assertEquals('age_over', $ageVerificationObj->getCheckType());
        $this->assertEquals(18, $ageVerificationObj->getAge());
    }

    public function testForAgeOver20ShouldReturnTrue()
    {
        $ageAttribute = new Attribute('age_over:20', 'true', []);
        $ageOverVerificationProcessor = new AgeOverVerificationProcessor($ageAttribute);
        $ageVerificationObj = $ageOverVerificationProcessor->process();
        $this->assertInstanceOf(AgeVerification::class, $ageVerificationObj);
        $this->assertTrue($ageVerificationObj->getResult());
        $this->assertEquals('age_over', $ageVerificationObj->getCheckType());
        $this->assertEquals(20, $ageVerificationObj->getAge());
    }

    public function testWhenThereIsNotAgeOverShouldReturnNull()
    {
        $ageAttribute = new Attribute('age_under:20', 'false', []);
        $ageOverVerificationProcessor = new AgeOverVerificationProcessor($ageAttribute);
        $this->assertNull($ageOverVerificationProcessor->process());
    }
}