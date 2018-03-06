<?php

namespace Yoti\Util\Age;

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

    /**
     * @return array
     */
    protected function getAgeData()
    {
        $ageData = ['result'=> '', 'verifiedAge'=> ''];
        $processors = $this->getProcessors();

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

    /**
     * @return array
     */
    public function getProcessors()
    {
        return [
            '\\Yoti\\Util\\Age\\AgeUnderOverProcessor',
        ];
    }
}