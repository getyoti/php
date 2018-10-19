<?php

namespace Yoti\Util\Age;

use Yoti\Entity\AgeVerification;
use Yoti\Entity\Attribute;

class Processor
{
    private $profileData;

    public function __construct(array $profileData)
    {
        $this->profileData = $profileData;
    }

    /**
     * @return Condition
     */
    public function getCondition()
    {
        $ageData = $this->getAgeData();
        return new Condition($ageData['result'], $ageData['verifiedAge']);
    }

    public function getAgeVerification()
    {
        $ageData = $this->getAgeData();
        return new Condition($ageData['result'], $ageData['verifiedAge']);
    }

    /**
     * @return array
     */
    public function getAgeData()
    {
        $ageData = ['result'=> '', 'verifiedAge'=> ''];
        $processors = $this->getAgeProcessors();

        // Process profile data by applying age processors defined in the config
        // And extract the row containing age attribute and value e.g 'age_over:18'=>'true'
        $found = FALSE;
        while(!empty($processors) && !$found)
        {
            $processorClass = array_shift($processors);
            $parentClass = '\\Yoti\\Util\\Age\\AbstractAgeProcessor';

            if(class_exists($processorClass) && is_subclass_of($processorClass, $parentClass))
            {
                $processorObj = new $processorClass($this->profileData);
                $data = $processorObj->process();
                if($data)
                {
                    $ageData = $data;
                    $found = TRUE;
                }
            }
        }

        return $ageData;
    }

    public function findAgeVerifications()
    {
        $ageVerificationsArr = [];
        foreach($this->profileData as $attrName => $attributeObj)
        {
            foreach($this->getAgeProcessors() as $processorClass)
            {
                $procesorObj = new $processorClass($attributeObj);
                if ($result = $procesorObj->parseAttribute()) {
                    $ageVerificationsArr[$attrName] = new AgeVerification(
                        $attributeObj,
                        $result['checkType'],
                        $result['age'],
                        $result['result']
                    );
                }
            }
        }

        return $ageVerificationsArr;
    }

    /**
     * Age processor config
     * Add more processor to this array if you want them applied as age filter
     *
     * @return array
     */
    public function getAgeProcessors()
    {
        return [
            '\\Yoti\\Util\\Age\\AgeUnderOverProcessor',
        ];
    }
}