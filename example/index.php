<?php

// Load dependency packages and env data
require_once __DIR__ . '/bootstrap.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Your Site</title>

        <link rel="stylesheet" type="text/css" href="css/style.css">

        <script src="https://sdk.yoti.com/clients/browser.2.0.1.js"></script>
    </head>
    <body>
        <h1>Your Site Information</h1>
        <span data-yoti-application-id="<?php echo getenv('YOTI_APP_ID') ?>"
            data-yoti-type="inline"
            data-yoti-scenario-id="<?php echo getenv('YOTI_SCENARIO_ID') ?>">
         Use Yoti
        </span>

        <!-- Initiate Yoti button -->
        <script>
            _ybg.init()
        </script>
    </body>
</html>