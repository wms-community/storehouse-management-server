<?php
namespace League\Flysystem\Adapter;
use League\Flysystem\Adapter\Polyfill\StreamedCopyTrait;
use League\Flysystem\Adapter\Polyfill\StreamedTrait;
use League\Flysystem\Config;
class NullAdapter extends AbstractAdapter
{
    use StreamedTrait;
    use StreamedCopyTrait;
    public function has($path)
    {
        return false;
    }
    public function write($path, $contents, Config $config)
    {
        $type = 'file';
        $result = compact('contents', 'type', 'path');
        if ($visibility = $config->get('visibility')) {
            $result['visibility'] = $visibility;
        }
        return $result;
    }
    public function update($path, $contents, Config $config)
    {
        return false;
    }
    public function read($path)
    {
        return false;
    }
    public function rename($path, $newpath)
    {
        return false;
    }
    public function delete($path)
    {
        return false;
    }
    public function listContents($directory = '', $recursive = false)
    {
        return [];
    }
    public function getMetadata($path)
    {
        return false;
    }
    public function getSize($path)
    {
        return false;
    }
    public function getMimetype($path)
    {
        return false;
    }
    public function getTimestamp($path)
    {
        return false;
    }
    public function getVisibility($path)
    {
        return false;
    }
    public function setVisibility($path, $visibility)
    {
        return compact('visibility');
    }
    public function createDir($dirname, Config $config)
    {
        return ['path' => $dirname, 'type' => 'dir'];
    }
    public function deleteDir($dirname)
    {
        return false;
    }
}
