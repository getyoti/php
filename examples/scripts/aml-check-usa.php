<?php
# AML check for USA (requires postcode and SSN)

// Load dependent packages and env data
require_once __DIR__ . '/../bootstrap.php';

use Yoti\Entity\Country;
use Yoti\Entity\AmlAddress;
use Yoti\Entity\AmlProfile;
use Yoti\YotiClient;

$zipCode = '12345';
$ssn = '170206773';

try {
    $connectAPI = getenv('YOTI_CONNECT_API') ?: YotiClient::DEFAULT_CONNECT_API;
    $amlAddress = new AmlAddress(new Country('USA'), $zipCode);
    $amlProfile = new AmlProfile('Edward Richard George', 'Heath', $amlAddress, $ssn);
    $yotiClient = new YotiClient(getenv('YOTI_SDK_ID'), getenv('YOTI_KEY_FILE_PATH'), $connectAPI);
    $amlResult = $yotiClient->performAmlCheck($amlProfile);
} catch(\Exception $e) {
    die("Error - {$e->getMessage()}");
}

var_dump($amlResult->isOnPepList());
var_dump($amlResult->isOnFraudList());
var_dump($amlResult->isOnWatchList());

echo 'Full result'. PHP_EOL;
echo $amlResult;