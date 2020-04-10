<?php
namespace App\Antti\FeedsAppItemImportBundle\Model\Item\Import\Job\Transport\Mapper;

abstract class AbstractMapper
{
    /**
     * @var array
     */
    protected $data;

    /**
     * @param array $data
     */
    public function setData(array $data): void
    {
        $this->data = $data;
    }

    /**
     * @param array $itemData
     *
     * @return array
     */
    public function map(array $itemData): array
    {
        $this->setData($itemData);

        $result = [
            'title' => $this->getTitle($itemData),
            'description' => $this->getDescription($itemData),
            'link' => $this->getLink($itemData),
            'comments' => $this->getComments($itemData),
            'pubDate' => $this->getPubDate($itemData),
        ];
        
        $category = $this->getCategory($itemData);
        $result['category'] = !empty($category) ? $category : '';
        
        return $this->cleanResult($result);
    }

    abstract public function getTitle(): ?string;

    abstract public function getDescription(): string;
    
    abstract public function getLink(): string;
    
    abstract public function getComments(): string;
    
    abstract public function getPubDate(): ?string;
    
    abstract public function getCategory(): array;

    /**
     * Remove NULL values from array.
     * @param array $data
     *
     * @return array
     */
    protected function cleanResult(array $data): array
    {
        foreach ($data as $key => $_data) {
            if ($_data === null) {
                unset($data[$key]);
            } elseif (is_array($_data)) {
                $data[$key] = $this->cleanResult($_data);
            }
        }
        return $data;
    }
}
