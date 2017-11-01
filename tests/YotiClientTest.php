<?php
use Yoti\YotiClient;


define('PEM_FILE', __DIR__ . '/../src/sample-data/yw-access-security.pem');
//define('APP_ID', 'e5eca4a1-f9fc-42dd-b986-b23c96848ace');
define('SDK_ID', '990a3996-5762-4e8a-aa64-cb406fdb0e68');
define('YOTI_CONNECT_TOKEN', file_get_contents(__DIR__ . '/../src/sample-data/connect-token.txt'));
define('INVALID_YOTI_CONNECT_TOKEN', 'sdfsdfsdasdajsopifajsd=');

class YotiClientTest extends PHPUnit\Framework\TestCase
{
    /**
     * @var YotiClient
     */
    private $_yoti;

    public function setUp()
    {
        $this->_yoti = new YotiClient(SDK_ID, file_get_contents(PEM_FILE));

        // Switch this off when using real endpoints
        $this->_yoti->setMockRequests(true);
    }

    /**
     * Test the use of pem file
     */
    public function testCanUsePemFile()
    {
        $yotiClientObj = new YotiClient(SDK_ID, 'file://' . PEM_FILE);
        $this->assertInstanceOf(\Yoti\YotiClient::class, $yotiClientObj);
    }

    /**
     * Test passing invalid pem file path
     */
    public function testInvalidPem()
    {
        $this->expectException('Exception');
        $yotiClientObj = new YotiClient(SDK_ID, 'file://blahblah.pem');
    }

    /**
     * Test getting activity details
     */
    public function testGetActivityDetails()
    {
        $ad = $this->_yoti->getActivityDetails(YOTI_CONNECT_TOKEN);
        $this->assertInstanceOf(\Yoti\ActivityDetails::class, $ad);
    }

    /**
     * Test invalid Token
     */
    public function testInvalidConnectToken()
    {
        $this->expectException('Exception');
        $this->_yoti->getActivityDetails(INVALID_YOTI_CONNECT_TOKEN);
    }

    /**
     * Test invalid http header value for X-Yoti-SDK
     */
    public function testInvalidSdkIdentifier()
    {
        $this->expectException('Exception');
        $yotiClientObj = new YotiClient(SDK_ID, file_get_contents(PEM_FILE), YotiClient::DEFAULT_CONNECT_API, 'WrongHeader');
    }

    /**
     * Test X-Yoti-SDK http header value for Wordpress
     */
    public function testCanUseWordPressAsSdkIdentifier()
    {
        $expectedValue  = 'WordPress';
        $yotiClientObj  = new YotiClient(SDK_ID, file_get_contents(PEM_FILE), YotiClient::DEFAULT_CONNECT_API, $expectedValue);
        $property       = $this->getPrivateProperty('Yoti\YotiClient', '_sdkIdentifier');
        $this->assertEquals($property->getValue($yotiClientObj), $expectedValue);
    }

    /**
     * Test X-Yoti-SDK http header value for Drupal
     */
    public function testCanUseDrupalAsSdkIdentifier()
    {
        $expectedValue  = 'Drupal';
        $yotiClientObj  = new YotiClient(SDK_ID, file_get_contents(PEM_FILE), YotiClient::DEFAULT_CONNECT_API, $expectedValue);
        $property       = $this->getPrivateProperty('Yoti\YotiClient', '_sdkIdentifier');
        $this->assertEquals($property->getValue($yotiClientObj), $expectedValue);
    }

    /**
     * Test X-Yoti-SDK http header value for Joomla
     */
    public function testCanUseJoomlaAsSdkIdentifier()
    {
        $expectedValue  = 'Joomla';
        $yotiClientObj  = new YotiClient(SDK_ID, file_get_contents(PEM_FILE), YotiClient::DEFAULT_CONNECT_API, $expectedValue);
        $property       = $this->getPrivateProperty('Yoti\YotiClient', '_sdkIdentifier');
        $this->assertEquals($property->getValue($yotiClientObj), $expectedValue);
    }

    /**
     * Test X-Yoti-SDK http header value for PHP
     */
    public function testCanUsePHPAsSdkIdentifier()
    {
        $expectedValue  = 'PHP';
        $yotiClientObj  = new YotiClient(SDK_ID, file_get_contents(PEM_FILE), YotiClient::DEFAULT_CONNECT_API, $expectedValue);
        $property       = $this->getPrivateProperty('Yoti\YotiClient', '_sdkIdentifier');
        $this->assertEquals($property->getValue($yotiClientObj), $expectedValue);
    }

    /**
     * Get private or protected property of a class.
     *
     * @param 	string $className
     * @param 	string $propertyName
     * @return	ReflectionProperty
     */
    public function getPrivateProperty($className, $propertyName)
    {
        $reflector = new ReflectionClass($className);
        $property = $reflector->getProperty($propertyName);
        $property->setAccessible(TRUE);

        return $property;
    }
}