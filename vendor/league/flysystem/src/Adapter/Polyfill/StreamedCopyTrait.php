<?php
namespace League\Flysystem\Adapter\Polyfill;
use League\Flysystem\Config;
trait StreamedCopyTrait
{
    public function copy($path, $newpath)
    {
        $response = $this->readStream($path);
        if ($response === false || ! is_resource($response['stream'])) {
            return false;
        }
        $result = $this->writeStream($newpath, $response['stream'], new Config());
        if ($result !== false && is_resource($response['stream'])) {
            fclose($response['stream']);
        }
        return $result !== false;
    }
    abstract public function readStream($path);
    abstract public function writeStream($path, $resource, Config $config);
}
