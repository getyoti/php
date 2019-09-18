<?php
/**
 * Define constants required by test suite.
 */
define('SDK_ID', '990a3996-5762-4e8a-aa64-cb406fdb0e68');
define('RECEIPT_JSON', __DIR__ . '/sample-data/receipt.json');
define('INVALID_YOTI_CONNECT_TOKEN', 'sdfsdfsdasdajsopifajsd=');
define('PEM_FILE', __DIR__ . '/sample-data/yw-access-security.pem');
define('INVALID_PEM_FILE', __DIR__ . '/sample-data/invalid.pem');
define('DUMMY_SELFIE_FILE', __DIR__ . '/sample-data/dummy-avatar.png');
define('AML_PRIVATE_KEY', __DIR__ . '/sample-data/aml-check-private-key.pem');
define('AML_PUBLIC_KEY', __DIR__ . '/sample-data/aml-check-public-key.pem');
define('AML_CHECK_RESULT_JSON', __DIR__ . '/sample-data/aml-check-result.json');
define('SHARE_URL_RESULT_JSON', __DIR__ . '/sample-data/share-url-result.json');
define('YOTI_CONNECT_TOKEN', file_get_contents(__DIR__ . '/sample-data/connect-token.txt'));
define('MULTI_VALUE_ATTRIBUTE', file_get_contents(__DIR__ . '/sample-data/attributes/multi-value.txt'));
define('PEM_AUTH_KEY', file_get_contents(__DIR__ . '/sample-data/pem-auth-key.txt'));
