<?php
namespace League\Flysystem\Cached\Storage;
use League\Flysystem\AdapterInterface;
use League\Flysystem\Config;
class Adapter extends AbstractCache
{
    protected $adapter;
    protected $file;
    protected $expire = null;
    public function __construct(AdapterInterface $adapter, $file, $expire = null)
    {
        $this->adapter = $adapter;
        $this->file = $file;
        $this->setExpire($expire);
    }
    protected function setExpire($expire)
    {
        if ($expire) {
            $this->expire = $this->getTime($expire);
        }
    }
    protected function getTime($time = 0)
    {
        return intval(microtime(true)) + $time;
    }
    public function setFromStorage($json)
    {
        list($cache, $complete, $expire) = json_decode($json, true);
        if (! $expire || $expire > $this->getTime()) {
            $this->cache = is_array($cache) ? $cache : [];
            $this->complete = is_array($complete) ? $complete : [];
        } else {
            $this->adapter->delete($this->file);
        }
    }
    public function load()
    {
        if ($this->adapter->has($this->file)) {
            $file = $this->adapter->read($this->file);
            if ($file && !empty($file['contents'])) {
                $this->setFromStorage($file['contents']);
            }
        }
    }
    public function getForStorage()
    {
        $cleaned = $this->cleanContents($this->cache);
        return json_encode([$cleaned, $this->complete, $this->expire]);
    }
    public function save()
    {
        $config = new Config();
        $contents = $this->getForStorage();
        if ($this->adapter->has($this->file)) {
            $this->adapter->update($this->file, $contents, $config);
        } else {
            $this->adapter->write($this->file, $contents, $config);
        }
    }
}
