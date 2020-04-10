<?php
namespace App\Antti\FeedsAppItemImportBundle\Model\Item\Import;

class Helper 
{
    public function normalizeUrl(string $url): string
    {
        return ($url !== null
                && substr($url, 0, 4) !== "http"
                && substr($url, 0, 5) !== "https")
                ? "http://" . $url
                : $url;
    }
}