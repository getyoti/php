<?php

use Yoti\Http\SignedRequest;
use Yoti\Entity\Country;
use Yoti\Entity\AmlAddress;
use Yoti\Entity\AmlProfile;
use Yoti\Http\Payload;

defined('SDK_ID') || define('SDK_ID', '990a3996-5762-4e8a-aa64-cb406fdb0e68');
defined('AML_PRIVATE_KEY') || define('AML_PRIVATE_KEY', __DIR__ . '/../src/sample-data/aml-check-pkey.pem');
defined('AML_PUBLIC_KEY') || define('AML_PUBLIC_KEY', __DIR__ . '/../src/sample-data/aml-check-pubkey.pem');

class SignedRequestTest extends PHPUnit\Framework\TestCase
{
    public $signedRequest;
    public $payload;
    public $messageToSign;

    public function setup()
    {
        $pem = $this->getDummyPrivateKey();
        $this->payload = $this->getDummyPayload();

        $this->signedRequest = new SignedRequest($this->payload, '/aml-check', $pem, SDK_ID, 'POST');

        $this->messagetoSign = 'POST&'.$this->signedRequest->getEndpointPath().'&'.$this->payload->getBase64Payload();
    }

    public function testSignedMessage()
    {
        $signedMessage = $this->signedRequest->getSignedMessage();

        $publicKey = openssl_pkey_get_public($this->getDummyPublicKey());

        $verify = openssl_verify($this->messagetoSign, base64_decode($signedMessage), $publicKey, OPENSSL_ALGO_SHA256);

        $this->assertEquals(1, $verify);
    }

    public function getDummyPayload()
    {
        $amlAddress = new AmlAddress(new Country('GBR'));
        $amlProfile = new AmlProfile('Edward Richard George', 'Heath', $amlAddress);
        return new Payload($amlProfile->getData());
    }

    public function getDummyPrivateKey()
    {
        return file_get_contents(AML_PRIVATE_KEY);
    }

    public function getDummyPublicKey()
    {
        return file_get_contents(AML_PUBLIC_KEY);
    }
}