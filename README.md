# Yoti PHP SDK

Welcome to the Yoti PHP SDK. This repo contains the tools you need to quickly integrate your PHP back-end with Yoti, so that your users can share their identity details with your application in a secure and trusted way.

## Table of Contents

1) [An Architectural view](#an-architectural-view) -
High level overview of integration

2) [Requirements](#requirements) -
Check you have what you need

3) [Enabling the SDK](#enabling-the-sdk) -
How to install our SDK

4) [Client initialisation](#client-initialisation) -
How to initialise your configuration

5) [Profile Retrieval](#profile-retrieval) -
How to retrieve a Yoti profile using the token

6) [Handling users](#handling-users) -
How to manage users

7) [AML Integration](#aml-integration) -
How to integrate with Yoti's AML (Anti Money Laundering) service

8) [How to run the examples](#how-to-run-the-examples)

9) [Running the tests](#running-the-tests)

10) [API Coverage](#api-coverage) -
Attributes defined

11) [Support](#support) -
Please feel free to reach out

## An Architectural View

To integrate your application with Yoti, your back-end must expose a GET endpoint that Yoti will use to forward tokens.
The endpoint can be configured in Yoti Dashboard when you create/update your application.

The image below shows how your application back-end and Yoti integrate in the context of a Login flow.
Yoti SDK carries out steps 6 through 9 for you, including profile decryption and communication with backend services.

![alt text](login_flow.png "Login flow")

Yoti also allows you to enable user details verification from your mobile app by means of the Android (TBA) and iOS (TBA) SDKs. In that scenario, your Yoti-enabled mobile app is playing both the role of the browser and the Yoti app. By the way, your back-end doesn't need to handle these cases in a significantly different way. You might just decide to handle the `User-Agent` header in order to provide different responses for web and mobile clients.

## Requirements

* PHP 5.5.19
* CURL PHP extension (must support TLSv1.2)

## Enabling the SDK

To import the Yoti SDK inside your project, you can use your favourite dependency management system.
If you are using Composer, you need to do one of the following:

Add the Yoti SDK dependency:

```json
"require": {
    "yoti/yoti-php-sdk" : "1.2.*"
}
```

Or run this Composer command
`composer require yoti/yoti-php-sdk`

## Client Initialisation

The YotiClient is the SDK entry point. To initialise it you need include the following snippet inside your endpoint initialisation section:

```php
<?php
require_once './vendor/autoload.php';
$client = new \Yoti\YotiClient('SDK_ID', 'path/to/your-application-pem-file.pem');
```

Where:

* `YOUR_SDK_ID` is the identifier generated by Yoti Dashboard when you create your app.
* `PATH/TO/YOUR/APPLICATION/KEY_PAIR.pem` is the path to the pem file your browser generates for you, when you create your app on Yoti Dashboard.

## Profile Retrieval

When your application receives a token via the exposed endpoint (it will be assigned to a query string parameter named `token`), you can easily retrieve the user profile by adding the following to your endpoint handler:

```php
<?php
// The token can be used only once
// Reusing the same token will result to a 404 error
$token = $_GET['token'];
$activityDetails = $client->getActivityDetails($token);
```

Before you inspect the user profile, you might want to check whether the user validation was successful.
This is done as follows:

```php
<?php
$activityDetails = $client->getActivityDetails($token);
if ($client->getOutcome() !== \Yoti\YotiClient::OUTCOME_SUCCESS)
{
    // handle unhappy path
}
```

### Available User Profile Attributes Through Getters

We have exposed user profile attributes through getters. You will find in the example below the getters available to you and how to use them:

```php
<?php
$activityDetails    = $client->getActivityDetails($token);

$userId             = $activityDetails->getUserId();

$profile            = $activityDetails->getProfile();

$familyName         = $profile->getFamilyName()->getValue();

$givenNames         = $profile->getGivenNames()->getValue();

$fullName           = $profile->getFullName()->getValue();

$dateOfBirth        = $profile->getDateOfBirth()->getValue();

$gender             = $profile->getGender()->getValue();

$nationality        = $profile->getNationality()->getValue();

$phoneNumber        = $profile->getPhoneNumber()->getValue();

$selfie             = $profile->getSelfie()->getValue();

$emailAddress       = $profile->getEmailAddress()->getValue();

$postalAddress      = $profile->getPostalAddress()->getValue();

$ageVerified        = $profile->getAgeCondition()->getValue();

$verifiedAge        = $profile->getVerifiedAge()->getValue();
```

## Handling Users

When you retrieve the user profile, you receive a user ID generated by Yoti exclusively for your application.
This means that if the same individual logs into another app, Yoti will assign her/him a different ID.
You can use this ID to verify whether (for your application) the retrieved profile identifies a new or an existing user.
Here is an example of how this works:

```php
<?php
$activityDetails = $client->getActivityDetails($token);
$profile = $activityDetails->getProfile();

if ($client->getOutcome() == \Yoti\YotiClient::OUTCOME_SUCCESS) {
    $user = yourUserSearchFunction($activityDetails->getUserId());
    if ($user) {
        // handle login
    } else {
        // handle registration
        $givenNames = $profile->getGivenNames()->getValue();
        $familyName = $profile->getFamilyName()->getValue();
    }
} else {
    // handle unhappy path
}
```

Where `yourUserSearchMethod` is a piece of logic in your app that is supposed to find a user, given a userId.
No matter if the user is a new or an existing one, Yoti will always provide her/his profile, so you don't necessarily need to store it.

The `profile` object provides a set of attributes corresponding to user attributes. Whether the attributes are present or not depends on the settings you have applied to your app on Yoti Dashboard.

You can retrieve the sources and verifiers for each attribute as follows:

```php
<?php 
$givenNamesSources = $profile->getGivenNames()->getSources(); // list or array of anchors
$givenNamesVerifiers = $profile->getGivenNames()->getVerifiers(); // list or array of anchors
```

You can also retrieve further properties from these respective anchors in the following way:

```php
<?php
// Retrieving properties of the first anchor
$value = $givenNamesSources[0]->getValue(); // string
$subType = $givenNamesSources[0]->getSubType(); // string
$signature = $givenNamesSources[0]->getSignature(); // bytes
$timeStamp = $givenNamesSources[0]->getSignedTimeStamp()->getTimeStamp(); // int
$originServerCerts = $givenNamesSources[0]->getOriginServerCerts(); // list of X509 certificates
```

### YotiClient

Allows your app to retrieve a user profile, given an encrypted token.

## AML Integration

Yoti provides an AML (Anti Money Laundering) check service to allow a deeper KYC process to prevent fraud. This is a chargeable service, so please contact [sdksupport@yoti.com](mailto:sdksupport@yoti.com) for more information.

Yoti will provide a boolean result on the following checks:
* PEP list - Verify against Politically Exposed Persons list
* Fraud list - Verify against  US Social Security Administration Fraud (SSN Fraud) list
* Watch list - Verify against watch lists from the Office of Foreign Assets Control

To use this functionality you must ensure:
* Your application is assigned to your Organisation in the Yoti Dashboard - please see [here]('https://www.yoti.com/developers/documentation') for further information.
* Within your application please ensure that you have selected the 'given names' and 'family name' attributes from the data tab. This is the minimum requirement for the AML check.

The AML check uses a simplified view of the User Profile.  You need only provide the following:
* profile->givenNames
* profile->familyName
* Country of residence - you will need to collect this from the user yourself

To check a US citizen, you must provide two more attributes in addition to the three above:
* Social Security Number - you will need to collect this from the user yourself
* Postcode/Zip code

### Consent

Performing an AML check on a person *requires* their consent.
**You must ensure you have user consent *before* using this service.**

### Code Example

Given a YotiClient initialised with your SDK ID and KeyPair (see [Client Initialisation](#client-initialisation)) performing an AML check is a straightforward case of providing basic profile data.

```php
<?php
use Yoti\Entity\Country;
use Yoti\Entity\AmlAddress;
use Yoti\Entity\AmlProfile;

// Address of the user profile to check
$amlAddress = new AmlAddress(new Country('GBR'));
$amlProfile = new AmlProfile('Edward Richard George', 'Heath', $amlAddress);
// Perform the check
$amlResult = $client->performAmlCheck($amlProfile);

// Result returned for this profile
var_dump($amlResult->isOnPepList());
var_dump($amlResult->isOnFraudList());
var_dump($amlResult->isOnWatchList());

// Or
echo $amlResult;
```
 

## How to Run the Examples

The examples can be found in the [examples folder](https://github.com/getyoti/php/tree/master/examples). The steps required for the setup are explained below.

### Profile sharing

* Create your application in the Yoti Dashboard (this requires having a Yoti account)
* Point your Yoti application callback URL to `http://your-local-url.domain/profile.php`
* Do the steps below inside the [examples folder](https://github.com/getyoti/php/tree/master/examples)
* Copy `.env.dist` to `.env`
* Open `.env` file and fill in the environment variables `YOTI_APPLICATION_ID`, `YOTI_SCENARIO_ID`, `YOTI_SDK_ID`, and `YOTI_KEY_FILE_PATH`
* Run the `composer update` command
* Run the `php -S localhost:8000` command and navigate to [http://localhost:8000](http://localhost:8000)

### AML Check

* Create your application in the Yoti Dashboard (this requires having a Yoti account)
* Do the steps below inside the [examples folder](https://github.com/getyoti/php/tree/master/examples)
* Copy `.env.dist` to `.env` and fill in the environment variables.
* Run the `composer update` command
* For AML check outside the USA:
    * Run the script `php scripts/aml-check.php`
* For AML check within the USA:
    * Run the script `php scripts/aml-check-usa.php`

## Running the tests

PHPUnit requires `PHP 5.6` or above.

Run the following commands from the root folder:
```console
$ composer update
$ ./vendor/bin/phpunit tests
```

## API Coverage

* Activity Details
    * [X] User ID `getUserId()`
    * [X] profile `getProfile()`
    * [X] Photo `getSelfie()`
    * [X] Given Names `getGivenNames()`
    * [X] Family Name `getFamilyName()`
    * [X] Full Name `getFullName()`
    * [X] Mobile Number `getPhoneNumber()`
    * [X] Email Address `getEmailAddress()`
    * [X] Age / Date of Birth `getDateOfBirth()`
    * [X] Age / Verify Condition `isAgeVerified()`
    * [x] Age / Verified Age `getVerifiedAge()`
    * [X] Address `getPostalAddress()`
    * [X] Gender `getGender()`
    * [X] Nationality `getNationality()`

## Support

For any questions or support please email [sdksupport@yoti.com](mailto:sdksupport@yoti.com).
Please provide the following to get you up and working as quickly as possible:

* Computer type
* OS version
* Version of PHP being used
* Screenshot

Once we have answered your question we may contact you again to discuss Yoti products and services. If you’d prefer us not to do this, please let us know when you e-mail.
