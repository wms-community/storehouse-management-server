<?php
namespace League\Flysystem\Cached\Storage;
use Psr\Cache\CacheItemPoolInterface;
class Psr6Cache extends AbstractCache
{
    private $pool;
    protected $key;
    protected $expire;
    public function __construct(CacheItemPoolInterface $pool, $key = 'flysystem', $expire = null)
    {
        $this->pool = $pool;
        $this->key = $key;
        $this->expire = $expire;
    }
    public function save()
    {
        $item = $this->pool->getItem($this->key);
        $item->set($this->getForStorage());
        $item->expiresAfter($this->expire);
        $this->pool->save($item);
    }
    public function load()
    {
        $item = $this->pool->getItem($this->key);
        if ($item->isHit()) {
            $this->setFromStorage($item->get());
        }
    }
}
