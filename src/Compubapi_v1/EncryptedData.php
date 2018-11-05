<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: EncryptedData.proto

namespace Compubapi_v1;

use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>compubapi_v1.EncryptedData</code>
 */
class EncryptedData extends \Google\Protobuf\Internal\Message
{
    /**
     * the iv will be used in conjunction with the secret key
     * received via other channel in order to decrypt the cipher_text
     *
     * Generated from protobuf field <code>bytes iv = 1;</code>
     */
    private $iv = '';
    /**
     * block of bytes to be decrypted
     *
     * Generated from protobuf field <code>bytes cipher_text = 2;</code>
     */
    private $cipher_text = '';

    public function __construct() {
        \GPBMetadata\EncryptedData::initOnce();
        parent::__construct();
    }

    /**
     * the iv will be used in conjunction with the secret key
     * received via other channel in order to decrypt the cipher_text
     *
     * Generated from protobuf field <code>bytes iv = 1;</code>
     * @return string
     */
    public function getIv()
    {
        return $this->iv;
    }

    /**
     * the iv will be used in conjunction with the secret key
     * received via other channel in order to decrypt the cipher_text
     *
     * Generated from protobuf field <code>bytes iv = 1;</code>
     * @param string $var
     * @return $this
     */
    public function setIv($var)
    {
        GPBUtil::checkString($var, False);
        $this->iv = $var;

        return $this;
    }

    /**
     * block of bytes to be decrypted
     *
     * Generated from protobuf field <code>bytes cipher_text = 2;</code>
     * @return string
     */
    public function getCipherText()
    {
        return $this->cipher_text;
    }

    /**
     * block of bytes to be decrypted
     *
     * Generated from protobuf field <code>bytes cipher_text = 2;</code>
     * @param string $var
     * @return $this
     */
    public function setCipherText($var)
    {
        GPBUtil::checkString($var, False);
        $this->cipher_text = $var;

        return $this;
    }

}
