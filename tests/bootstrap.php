<?php
/**
 * loads all required files including composer vendor autoload
 */
require_once __DIR__ . '/../vendor/autoload.php';

define('SDK_ID', '990a3996-5762-4e8a-aa64-cb406fdb0e68');
define('RECEIPT_JSON', __DIR__ . '/sample-data/receipt.json');
define('INVALID_YOTI_CONNECT_TOKEN', 'sdfsdfsdasdajsopifajsd=');
define('PEM_FILE', __DIR__ . '/sample-data/yw-access-security.pem');
define('DUMMY_SELFIE_FILE', __DIR__ . '/sample-data/dummy-avatar.png');
define('AML_PRIVATE_KEY', __DIR__ . '/sample-data/aml-check-private-key.pem');
define('AML_PUBLIC_KEY', __DIR__ . '/sample-data/aml-check-public-key.pem');
define('AML_CHECK_RESULT_JSON', __DIR__ . '/sample-data/aml-check-result.json');
define('SOURCE_ANCHOR_DATA', __DIR__ . '/sample-data/source-anchor-data.json');
define('VERIFIER_ANCHOR_DATA', __DIR__ . '/sample-data/verifier-anchor-data.json');
define('YOTI_CONNECT_TOKEN', file_get_contents(__DIR__ . '/sample-data/connect-token.txt'));