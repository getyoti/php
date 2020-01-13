<?php

namespace Yoti\ShareUrl\Extension;

use Yoti\Profile\ExtraData\AttributeDefinition;
use Yoti\Util\DateTime;
use Yoti\Util\Validation;

/**
 * Defines a third party attribute.
 */
class ThirdPartyAttributeContent implements \JsonSerializable
{
    /**
     * @var \Yoti\Profile\ExtraData\AttributeDefinition[]
     */
    private $definitions = [];

    /**
     * @var \DateTime
     */
    private $expiryDate;

    /**
     * @param \DateTime $expiryDate
     * @param \Yoti\Profile\ExtraData\AttributeDefinition[] $definitions
     */
    public function __construct(\DateTime $expiryDate, array $definitions)
    {
        $this->expiryDate = $expiryDate;

        Validation::isArrayOfType($definitions, [AttributeDefinition::class], 'definitions');
        $this->definitions = $definitions;
    }

    /**
     * @inheritDoc
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'expiry_date' => $this->expiryDate
                ->setTimezone(new \DateTimeZone('UTC'))
                ->format(DateTime::RFC3339),
            'definitions' => $this->definitions,
        ];
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return json_encode($this);
    }
}
