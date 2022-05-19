<?php
namespace League\Flysystem\Util;
class StreamHasher
{
    private $algo;
    public function __construct($algo)
    {
        $this->algo = $algo;
    }
    public function hash($resource)
    {
        rewind($resource);
        $context = hash_init($this->algo);
        hash_update_stream($context, $resource);
        fclose($resource);
        return hash_final($context);
    }
}
