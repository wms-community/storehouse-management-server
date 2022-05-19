<?php
namespace League\Flysystem\Cached\Storage;
use Redis;
class PhpRedis extends AbstractCache
{
    protected $client;
    protected $key;
    protected $expire;
    public function __construct(Redis $client = null, $key = 'flysystem', $expire = null)
    {
        $this->client = $client ?: new Redis();
        $this->key = $key;
        $this->expire = $expire;
    }
    public function load()
    {
        $contents = $this->client->get($this->key);
        if ($contents !== false) {
            $this->setFromStorage($contents);
        }
    }
    public function save()
    {
        $contents = $this->getForStorage();
        $this->client->set($this->key, $contents);
        if ($this->expire !== null) {
            $this->client->expire($this->key, $this->expire);
        }
    }
}
