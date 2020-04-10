<?php
namespace App\Antti\FeedsAppItemImportBundle\Model\Item\Import\Job\Transport\Mapper;

class ItemMapper extends AbstractMapper
{
    use \App\Antti\FeedsAppItemImportBundle\Reusable\XmlParseableTrait;
    use \App\Antti\FeedsAppItemImportBundle\Reusable\DataTraversableTrait;
    
    public function getTitle(): ?string
    {
        return $this->getDataValue($this->data, 'title', null);
    }
    
    public function getDescription(): string
    {
        $dataValue = $this->getDataValue($this->data, 'description', null);
        return $dataValue !== null ? $dataValue : '';
    }
    
    public function getLink(): string
    {
        $dataValue = $this->getDataValue($this->data, 'link', null);
        return $dataValue !== null ? $dataValue : '';
    }
    
    public function getComments(): string
    {
        $dataValue = $this->getDataValue($this->data, 'comments', null);
        return $dataValue !== null ? $dataValue : '';
    }
    
    public function getPubDate(): ?string
    {
        return $this->getDataValue($this->data, 'pubDate', '');
    }
    
    public function getCategory(): array
    {
        $dataValue = $this->getDataValue($this->data, 'category', null);
        
        if ($dataValue !== null) {
            return [
                'name' => $this->getXmlNodeData($dataValue),
                'domain' => $this->getXmlNodeAttribute($dataValue, 'domain')
            ];
        }
        return [];
    }
}
