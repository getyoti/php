<?php
namespace Yoti\Http;

class Payload
{
    /**
     * @var mixed
     */
    private $data;

    public function __construct($data = '')
    {
        $this->data = $data;
    }

    /**
     * Get byte array of a string or an array.
     *
     * @return mixed
     */
    public function getByteArray()
    {
        // Convert data into a string
        $data = $this->convertData($this->data);
        // Convert string into byte array
        $byteArray = array_values(unpack('C*', $data));

        return $byteArray;
    }

    /**
     * Get base64 encoded of payload byte array.
     *
     * @return string
     */
    public function getBase64Payload()
    {
        return base64_encode(serialize($this->getByteArray()));
    }

    /**
     * Convert data into a binary string.
     *
     * @param mixed $data
     *
     * @return string
     */
    public function convertData($data)
    {
        if(is_array($data)) {
            // If the payload data is an array convert it into a binary string
            $data = serialize($data);
        }
        else if(is_string($data)) {
            // If payload data is a string convert it into utf-8.
            $data = mb_convert_encoding($data, 'UTF-8');
        }

        return $data;
    }

    /**
     * @return mixed
     */
    public function getRawData()
    {
        return $this->data;
    }
}