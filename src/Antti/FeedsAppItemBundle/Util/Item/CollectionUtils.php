<?php
namespace App\Antti\FeedsAppItemBundle\Util\Item;

use App\Antti\FeedsAppItemBundle\Entity\Item;
use Doctrine\Common\Collections\Collection;

class CollectionUtils
{
    public static function inCollection(Item $needle, Collection $haystack): bool
    {
        foreach ($haystack as $item) {
            if ($item->getId() === $needle->getId()) {
                return true;
            }
        }
        return false;
    }
    
    public static function collectionSearch(Item $needle, Collection $haystack): int
    {
        foreach ($haystack as $key => $item) {
            if ($item->getId() === $needle->getId()) {
                return $key;
            }
        }
        return -1;
    }
}
