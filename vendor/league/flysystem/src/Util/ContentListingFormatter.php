<?php
namespace League\Flysystem\Util;
use League\Flysystem\Util;
class ContentListingFormatter
{
    private $directory;
    private $recursive;
    private $caseSensitive;
    public function __construct($directory, $recursive, $caseSensitive = true)
    {
        $this->directory = rtrim($directory, '/');
        $this->recursive = $recursive;
        $this->caseSensitive = $caseSensitive;
    }
    public function formatListing(array $listing)
    {
        $listing = array_filter(array_map([$this, 'addPathInfo'], $listing), [$this, 'isEntryOutOfScope']);
        return $this->sortListing(array_values($listing));
    }
    private function addPathInfo(array $entry)
    {
        return $entry + Util::pathinfo($entry['path']);
    }
    private function isEntryOutOfScope(array $entry)
    {
        if (empty($entry['path']) && $entry['path'] !== '0') {
            return false;
        }
        if ($this->recursive) {
            return $this->residesInDirectory($entry);
        }
        return $this->isDirectChild($entry);
    }
    private function residesInDirectory(array $entry)
    {
        if ($this->directory === '') {
            return true;
        }
        return $this->caseSensitive
            ? strpos($entry['path'], $this->directory . '/') === 0
            : stripos($entry['path'], $this->directory . '/') === 0;
    }
    private function isDirectChild(array $entry)
    {
        return $this->caseSensitive
            ? $entry['dirname'] === $this->directory
            : strcasecmp($this->directory, $entry['dirname']) === 0;
    }
    private function sortListing(array $listing)
    {
        usort($listing, function ($a, $b) {
            return strcasecmp($a['path'], $b['path']);
        });
        return $listing;
    }
}
