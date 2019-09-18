<?php

namespace Yoti\ShareUrl\Policy;

use Yoti\Util\Validation;

/**
 * Defines the wanted attribute name and derivation.
 */
class WantedAttribute implements \JsonSerializable
{
    /**
     * @param string $name
     * @param string $derivation
     * @param boolean $acceptSelfAsserted
     * @param \Yoti\ShareUrl\Policy\Constraints $constraints
     */
    public function __construct($name, $derivation = '', $acceptSelfAsserted = null, Constraints $constraints = null)
    {
        Validation::isString($name, 'name');
        $this->name = $name;

        Validation::isString($derivation, 'derivation');
        $this->derivation = $derivation;

        if ($acceptSelfAsserted !== null) {
            Validation::isBoolean($acceptSelfAsserted, 'acceptSelfAsserted');
        }
        $this->acceptSelfAsserted = $acceptSelfAsserted;

        $this->constraints = $constraints;
    }

    /**
     * Name identifying the WantedAttribute
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Additional derived criteria.
     *
     * @return string
     */
    public function getDerivation()
    {
        return $this->derivation;
    }

    /**
     * List of constraints to add to an attribute.
     *
     * If you do not provide any particular constraints, Yoti will provide you with the
     * information from the most recently added source.
     *
     * @return \Yoti\ShareUrl\Policy\Constraints
     */
    public function getConstraints()
    {
        return $this->constraints;
    }

    /**
     * Accept self asserted attributes.
     *
     * These are attributes that have been self-declared, and not verified by Yoti.
     *
     * @return boolean
     */
    public function getAcceptSelfAsserted()
    {
        return $this->acceptSelfAsserted;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        $json = [
            'name' => $this->getName(),
            'derivation' => $this->getDerivation(),
            'optional' => false,
        ];

        if ($this->getConstraints() !== null) {
            $json['constraints'] = $this->getConstraints();
        }

        if ($this->getAcceptSelfAsserted() !== null) {
            $json['accept_self_asserted'] = $this->getAcceptSelfAsserted();
        }

        return $json;
    }
}
