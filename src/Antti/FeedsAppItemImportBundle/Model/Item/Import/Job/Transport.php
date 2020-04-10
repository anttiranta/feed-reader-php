<?php
namespace App\Antti\FeedsAppItemImportBundle\Model\Item\Import\Job;

use Psr\Log\LoggerInterface;
use App\Antti\FeedsAppItemImportBundle\Model\Item\Import\Transporter;
use App\Antti\FeedsAppItemImportBundle\Model\Item\Import\Job\Transport\Mapper\ItemMapper;

class Transport extends AbstractJob 
{
    use \App\Antti\FeedsAppItemImportBundle\Reusable\DataTraversableTrait;
    
    /**
     * @var Transporter
     */
    private $transporter;
    
    /**
     * @var ItemMapper 
     */
    private $itemImportMapper;
    
    public function __construct(
        Transporter $transporter,
        ItemMapper $itemImportMapper,
        LoggerInterface $logger = null
    ) {
        parent::__construct($logger);
        
        $this->transporter = $transporter;
        $this->itemImportMapper = $itemImportMapper;
    }
    
    public function process($url): array
    {
        $this->getLogger()->debug(
            sprintf('Sending request to %s', $url)
        );
        
        $data = $this->transporter->readData($url);
        
        $this->getLogger()->debug(
            sprintf('Received response for sent request to %s', $url),
            $data
        );
        
        return $this->processResponseData(!empty($data) ? $data : []);
    }

    protected function processResponseData(array $data): array 
    {
        $result = [];

        $channelNodeData = $this->getDataValue($data, 'channel', []);
        $itemsData = $this->getDataValue($channelNodeData, 'item', []);
        
        foreach ($itemsData as $entry) {
            $mappedItem = $this->mapItemData($entry);

            $preparedItem = $this->prepareItem($mappedItem);
            if ($preparedItem !== null) {
                $result[] = $preparedItem;
            }
            
            $title = $this->itemImportMapper->getTitle();
            
            $logContext = [
                'preparedItem' => $preparedItem,
                'mappedItem'   => $mappedItem,
                'itemData'  => $entry
            ];

            $msg = sprintf('Processing item with title [%s]', $title);
            $this->getLogger()->debug($msg, $logContext);
        }
        
        return $result;
    }
    
    /**
     * @param array $data
     *
     * @return array
     * @throws \Exception
     */
    private function mapItemData(array $data): array
    {
        try {
            $result = $this->itemImportMapper->map($data);
        } catch (\Exception $exception) {
            $this->getLogger()->error(
                'Error while mapping item data: ' . $exception->getMessage(), 
                ['data' => $data]
            );
            throw $exception;
        }

        return $result;
    }
    
    private function prepareItem(array $data): ?array 
    {
        if (!isset($data['title']) || !isset($data['pubDate'])) {
            return null;
        }
        return $data;
    }
}
