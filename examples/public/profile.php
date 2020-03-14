<?php

use Yoti\Media\Image;
use Yoti\Profile\Attribute\AgeVerification;
use Yoti\Profile\Attribute\DocumentDetails;
use Yoti\Profile\Attribute\MultiValue;

require_once __DIR__ . '/profile.inc.php';

/**
 * Formats attribute value based on type.
 *
 * @param mixed $value
 */
function formatAttributeValue($value)
{
    if ($value instanceof MultiValue) {
        foreach ($value as $multiValue) {
            formatAttributeValue($multiValue);
        }
    } elseif ($value instanceof Image) {
        formatImage($value);
    } elseif ($value instanceof DocumentDetails) {
        formatDocumentDetails($value);
    } elseif ($value instanceof \DateTime) {
        echo htmlspecialchars($value->format('d-m-Y'));
    } else {
        echo htmlspecialchars($value);
    }
}

/**
 * Format Image.
 *
 * @param \Yoti\Entity\Image $value
 */
function formatImage(Image $image)
{
    ?>
    <img src="<?php echo htmlspecialchars($image->getBase64Content()); ?>" />
    <?php
}

/**
 * Format Document Details.
 *
 * @param \Yoti\Entity\DocumentDetails $value
 */
function formatDocumentDetails(DocumentDetails $documentDetails)
{
    ?>
    <table>
        <tr>
            <td>Type</td>
            <td><?php echo htmlspecialchars($documentDetails->getType()); ?></td>
        </tr>
        <tr>
            <td>Issuing Country</td>
            <td><?php echo htmlspecialchars($documentDetails->getIssuingCountry()); ?></td>
        </tr>
        <tr>
            <td>Document Number</td>
            <td><?php echo htmlspecialchars($documentDetails->getDocumentNumber()); ?></td>
        </tr>
        <tr>
            <td>Expiration Date</td>
            <td><?php echo htmlspecialchars($documentDetails->getExpirationDate()->format('d-m-Y')); ?></td>
        </tr>
    </table>
    <?php
}

/**
 * Format Age Verification.
 *
 * @param \Yoti\Entity\AgeVerification $ageVerification
 */
function formatAgeVerification(AgeVerification $ageVerification)
{
    ?>
    <table>
        <tr>
            <td>Check Type</td>
            <td><?php echo htmlspecialchars($ageVerification->getCheckType()); ?></td>
        </tr>
        <tr>
            <td>Age</td>
            <td><?php echo htmlspecialchars($ageVerification->getAge()); ?></td>
        </tr>
        <tr>
            <td>Result</td>
            <td><?php echo htmlspecialchars($ageVerification->getResult()); ?></td>
        </tr>
    </table>
    <?php
}


/**
 * Format Structured Postal Address.
 *
 * @param array $structuredAddress
 */
function formatStructuredAddress(array $structuredAddress)
{
    ?>
    <table>
        <?php foreach ($structuredAddress as $key => $value): ?>
            <tr>
                <td><?php echo htmlspecialchars($key); ?></td>
                <td><?php echo htmlspecialchars($value); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
    <?php
}

?>
<!DOCTYPE html>
<html class="yoti-html">
   <head>
       <meta charset="utf-8">
       <title>Yoti client example</title>
       <link rel="stylesheet" type="text/css" href="assets/css/profile.css">
       <link href="https://fonts.googleapis.com/css?family=Roboto:400,700" rel="stylesheet" />
   </head>
   <body class="yoti-body">
       <main class="yoti-profile-layout">
           <section class="yoti-profile-user-section">

               <div class="yoti-profile-picture-powered-section">
                   <span class="yoti-profile-picture-powered">Powered by</span>
                   <a href="https://www.yoti.com" target="_blank">
                       <img class="yoti-logo-image" src="assets/images/logo.png" srcset="assets/images/logo@2x.png 2x" alt="Yoti" />
                   </a>
               </div>

               <div class="yoti-profile-picture-section">
                   <?php if ($profile->getSelfie()) : ?>
                       <div class="yoti-profile-picture-area">
                           <img src="<?php echo $profile->getSelfie()->getValue()->getBase64Content(); ?>" class="yoti-profile-picture-image" alt="Yoti" />
                           <i class="yoti-profile-picture-verified-icon"></i>
                       </div>
                   <?php endif; ?>

                   <div class="yoti-profile-name">
                       <?php echo $fullName ? $fullName->getValue() : '' ?>
                   </div>
               </div>
           </section>

           <section class="yoti-attributes-section">
                <a href="/">
                    <img class="yoti-company-logo" src="assets/images/company-logo.jpg" alt="company logo">
                </a>

                <div class="yoti-attribute-list-header">
                    <div class="yoti-attribute-list-header-attribute">Attribute</div>
                    <div class="yoti-attribute-list-header-value">Value</div>
                    <div>Anchors</div>
                </div>

                <div class="yoti-attribute-list-subheader">
                    <div class="yoti-attribute-list-subhead-layout">
                        <div>S / V</div>
                        <div>Value</div>
                        <div>Sub type</div>
                    </div>
                </div>

                <div class="yoti-attribute-list">

                    <?php foreach($profileAttributes as $item): ?>
                        <?php if ($item['obj']) : ?>
                            <div class="yoti-attribute-list-item">
                                <div class="yoti-attribute-name">
                                    <div class="yoti-attribute-name-cell">
                                        <i class="<?php echo $item['icon'] ?>"></i>
                                        <span class="yoti-attribute-name-cell-text"><?php echo htmlspecialchars($item['name']) ?></span>
                                    </div>
                                </div>

                                <div class="yoti-attribute-value">
                                   <div class="yoti-attribute-value-text">
                                   <?php
                                   switch ($item['name']) {
                                        case 'Age Verification':
                                            formatAgeVerification($item['age_verification']);
                                            break;
                                        case 'Structured Postal Address':
                                            formatStructuredAddress($item['obj']->getValue());
                                            break;
                                        default:
                                            formatAttributeValue($item['obj']->getValue());
                                   }
                                   ?>
                                   </div>
                               </div>
                               <div class="yoti-attribute-anchors-layout">
                                   <div class="yoti-attribute-anchors-head -s-v">S / V</div>
                                   <div class="yoti-attribute-anchors-head -value">Value</div>
                                   <div class="yoti-attribute-anchors-head -subtype">Sub type</div>

                                   <?php foreach($item['obj']->getAnchors() as $anchor) : ?>
                                       <div class="yoti-attribute-anchors -s-v"><?php echo htmlspecialchars($anchor->getType()); ?></div>
                                       <div class="yoti-attribute-anchors -value"><?php echo htmlspecialchars($anchor->getValue()); ?></div>
                                       <div class="yoti-attribute-anchors -subtype"><?php echo htmlspecialchars($anchor->getSubType()); ?></div>
                                   <?php endforeach; ?>

                               </div>
                           </div>
                       <?php endif; ?>
                   <?php endforeach; ?>
               </div>
           </section>
       </main>
   </body>
 </html>
