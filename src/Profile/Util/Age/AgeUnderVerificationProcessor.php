<?php

declare(strict_types=1);

namespace Yoti\Profile\Util\Age;

class AgeUnderVerificationProcessor extends AbstractAgeProcessor
{
    const AGE_RULE_PATTERN = '/^age_under:[1-9][0-9]*$/';

    public function getAgePattern()
    {
        return self::AGE_RULE_PATTERN;
    }
}
