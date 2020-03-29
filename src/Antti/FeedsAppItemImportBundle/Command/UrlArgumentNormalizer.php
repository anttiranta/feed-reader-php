<?php
namespace App\Antti\FeedsAppItemImportBundle\Command;

class UrlArgumentNormalizer 
{
    /**
     * Normalizing data for url argument.
     * 
     * Expecting argument to be either items separated by commas 
     * in a form "url1","url2","url3".  
     * 
     * @param string|string[] $argument
     * @return array
     */
    public function normalizeUrlArgument($argument): array
    {
        $argument = (array)$argument;
        
        $urlList = [];
        foreach($argument as $item) {
            $urlList = array_merge($urlList, explode(',', $item));
        }
        
        return array_map(function ($url) {
            return trim($url, '"');
        }, $urlList);
    }
}
