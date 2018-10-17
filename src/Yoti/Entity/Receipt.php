<?php

namespace Yoti\Entity;

use Yoti\Util\Profile\AttributeConverter;
use Yoti\Exception\ReceiptException;

class Receipt
{
    const ATTR_TIMESTAMP = 'timestamp';
    const ATTR_RECEIPT_ID = 'receipt_id';
    const ATTR_REMEMBER_ME_ID = 'remember_me_id';
    const ATTR_PARENT_REMEMBER_ME_ID = 'parent_remember_me_id';
    const ATTR_PROFILE_CONTENT = 'profile_content';
    const ATTR_SHARING_OUT_COME = 'sharing_outcome';
    const ATTR_WRAPPED_RECEIPT_KEY = 'wrapped_receipt_key';
    const ATTR_OTHER_PARTY_PROFILE_CONTENT = 'other_party_profile_content';

    /**
     * @var array
     */
    private $receiptData;

    /**
     * Receipt constructor.
     *
     * @param array $receiptData
     *
     * @throws ReceiptException
     */
    public function __construct(array $receiptData)
    {
        $this->validateReceipt($receiptData);

        $this->receiptData = $receiptData;
    }

    public function getReceiptId()
    {
        return $this->getAttribute(self::ATTR_RECEIPT_ID);
    }

    public function getRememberMeId()
    {
        return $this->getAttribute(self::ATTR_REMEMBER_ME_ID);
    }

    public function getParentRememberMeId()
    {
        return $this->getAttribute(self::ATTR_PARENT_REMEMBER_ME_ID);
    }

    public function getSharingOutcome()
    {
        return $this->getAttribute(self::ATTR_SHARING_OUT_COME);
    }

    public function getWrappedReceiptKey()
    {
        return $this->getAttribute(self::ATTR_WRAPPED_RECEIPT_KEY);
    }

    public function getProfileContent()
    {
        return $this->getAttribute(self::ATTR_PROFILE_CONTENT);
    }

    public function getOtherPartyProfileContent()
    {
        return $this->getAttribute(self::ATTR_OTHER_PARTY_PROFILE_CONTENT);
    }

    public function getTimestamp()
    {
        return $this->getAttribute(self::ATTR_TIMESTAMP);
    }

    /**
     * @throws ReceiptException
     */
    private function validateReceipt(array $receiptData)
    {
        if (!isset($receiptData[self::ATTR_WRAPPED_RECEIPT_KEY])) {
            throw new ReceiptException('Wrapped Receipt key attr is missing');
        }
    }

    public function getAttribute($attributeName)
    {
        if (!empty($attributeName) && isset($this->receiptData[$attributeName])) {
            return $this->receiptData[$attributeName];
        }
        return NULL;
    }

    /**
     * Return list of ProtobufAttributes.
     *
     * @param $attributeName
     * @param $pem
     *
     * @return \Attrpubapi_v1\AttributeList
     */
    public function parseAttribute($attributeName, $pem)
    {
        $data = $this->getAttribute($attributeName);
        $encryptedData = AttributeConverter::getEncryptedData($data);

        return AttributeConverter::convertToAttributesList(
            $encryptedData,
            $this->getWrappedReceiptKey(),
            $pem
        );
    }
}