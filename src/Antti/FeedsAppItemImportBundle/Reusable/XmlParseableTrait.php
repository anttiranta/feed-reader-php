<?php
namespace App\Antti\FeedsAppItemImportBundle\Reusable;

trait XmlParseableTrait
{
    /**
     * @param array $data
     * @return mixed
     */
    private function getXmlNodeData(array $data)
    {
        return \array_key_exists('#', $data) ? $data['#'] : $data;
    }
    
    /**
     * @param array $data
     * @param string $code
     * @param string $defaultValue
     * @return mixed
     */
    private function getXmlNodeAttribute(array $data, string $code, $defaultValue = null)
    {
        return isset($data['@'.$code]) ? $data['@'.$code] : $defaultValue;
    }
}
