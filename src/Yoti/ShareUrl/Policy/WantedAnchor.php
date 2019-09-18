<?php

namespace Yoti\ShareUrl\Policy;

use Yoti\Util\Validation;

/**
 * Defines the wanted anchor value and sub type.
 */
class WantedAnchor implements \JsonSerializable
{
    /**
     * Passport value.
     */
    const VALUE_PASSPORT = 'PASSPORT';

    /**
     * Driving Licence value.
     */
    const VALUE_DRIVING_LICENSE = 'DRIVING_LICENCE';

    /**
     * National ID value.
     */
    const VALUE_NATIONAL_ID = 'NATIONAL_ID';

    /**
     * Passcard value.
     */
    const VALUE_PASSCARD = 'PASS_CARD';

    /**
     * @param string $value
     * @param string $subType
     */
    public function __construct($value, $subType = '')
    {
        Validation::isString($value, 'value');
        $this->value = $value;

        Validation::isString($subType, 'subType');
        $this->subType = $subType;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return [
            'name' => $this->value,
            'sub_type' => $this->subType,
        ];
    }
}
