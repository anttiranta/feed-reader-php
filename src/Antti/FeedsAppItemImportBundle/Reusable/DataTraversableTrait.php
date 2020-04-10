<?php
namespace App\Antti\FeedsAppItemImportBundle\Reusable;

trait DataTraversableTrait
{
    /**
     * @param array $data
     * @param string $field
     * @param mixed $default
     * @return mixed
     */
    protected function getDataValue(array $data, string $field, $default = null)
    {
        return isset($data[$field]) ? $data[$field] : $default;
    }
}

