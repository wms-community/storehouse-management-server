<?php
namespace League\Flysystem\Adapter\Polyfill;
use League\Flysystem\Config;
use League\Flysystem\Util;
trait StreamedWritingTrait
{
    protected function stream($path, $resource, Config $config, $fallback)
    {
        Util::rewindStream($resource);
        $contents = stream_get_contents($resource);
        $fallbackCall = [$this, $fallback];
        return call_user_func($fallbackCall, $path, $contents, $config);
    }
    public function writeStream($path, $resource, Config $config)
    {
        return $this->stream($path, $resource, $config, 'write');
    }
    public function updateStream($path, $resource, Config $config)
    {
        return $this->stream($path, $resource, $config, 'update');
    }
    abstract public function write($pash, $contents, Config $config);
    abstract public function update($pash, $contents, Config $config);
}
