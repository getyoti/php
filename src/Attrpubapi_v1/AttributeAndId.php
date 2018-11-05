<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: List.proto

namespace Attrpubapi_v1;

use Google\Protobuf\Internal\GPBUtil;

/**
 * AttributeAndId is a simple container for holding an attribute's value
 * alongside its ID.
 *
 * Generated from protobuf message <code>attrpubapi_v1.AttributeAndId</code>
 */
class AttributeAndId extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>.attrpubapi_v1.Attribute attribute = 1;</code>
     */
    private $attribute = null;
    /**
     * Generated from protobuf field <code>bytes attribute_id = 2;</code>
     */
    private $attribute_id = '';

    public function __construct() {
        \GPBMetadata\ProtoList::initOnce();
        parent::__construct();
    }

    /**
     * Generated from protobuf field <code>.attrpubapi_v1.Attribute attribute = 1;</code>
     * @return \Attrpubapi_v1\Attribute
     */
    public function getAttribute()
    {
        return $this->attribute;
    }

    /**
     * Generated from protobuf field <code>.attrpubapi_v1.Attribute attribute = 1;</code>
     * @param \Attrpubapi_v1\Attribute $var
     * @return $this
     */
    public function setAttribute($var)
    {
        GPBUtil::checkMessage($var, \Attrpubapi_v1\Attribute::class);
        $this->attribute = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>bytes attribute_id = 2;</code>
     * @return string
     */
    public function getAttributeId()
    {
        return $this->attribute_id;
    }

    /**
     * Generated from protobuf field <code>bytes attribute_id = 2;</code>
     * @param string $var
     * @return $this
     */
    public function setAttributeId($var)
    {
        GPBUtil::checkString($var, False);
        $this->attribute_id = $var;

        return $this;
    }

}
