# AML Check Example

## Setup

* Create your application in the [Yoti Hub](https://hub.yoti.com) (this requires having a Yoti account)
* Put `your-application-pem-file.pem` file inside the [keys](keys) folder.
* Copy `.env.dist` to `.env` and fill in the environment variables `YOTI_SCENARIO_ID`, `YOTI_SDK_ID`
  * Set `YOTI_KEY_FILE_PATH` to `./keys/your-application-pem-file.pem`

## Running the example

> Run the following commands in this directory

For AML check within the USA
```console
$ php aml-check-usa.php
```

For AML check outside the USA
```console
$ php aml-check.php
```
