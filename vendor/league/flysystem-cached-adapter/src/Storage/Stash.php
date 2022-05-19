<?php
namespace League\Flysystem\Cached\Storage;
use Stash\Pool;
class Stash extends AbstractCache
{
    protected $key;
    protected $expire;
    protected $pool;
    public function __construct(Pool $pool, $key = 'flysystem', $expire = null)
    {
        $this->key = $key;
        $this->expire = $expire;
        $this->pool = $pool;
    }
    public function load()
    {
        $item = $this->pool->getItem($this->key);
        $contents = $item->get();
        if ($item->isMiss() === false) {
            $this->setFromStorage($contents);
        }
    }
    public function save()
    {
        $contents = $this->getForStorage();
        $item = $this->pool->getItem($this->key);
        $item->set($contents, $this->expire);
    }
}
