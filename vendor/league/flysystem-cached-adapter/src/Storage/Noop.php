<?php
namespace League\Flysystem\Cached\Storage;
class Noop extends AbstractCache
{
    protected $autosave = false;
    public function updateObject($path, array $object, $autosave = false)
    {
        return $object;
    }
    public function isComplete($dirname, $recursive)
    {
        return false;
    }
    public function setComplete($dirname, $recursive)
    {
        //
    }
    public function copy($path, $newpath)
    {
        return false;
    }
    public function rename($path, $newpath)
    {
        return false;
    }
    public function storeContents($directory, array $contents, $recursive = false)
    {
        return $contents;
    }
    public function storeMiss($path)
    {
        return $this;
    }
    public function flush()
    {
        //
    }
    public function autosave()
    {
        //
    }
    public function save()
    {
        //
    }
    public function load()
    {
        //
    }
    public function has($path)
    {
        return;
    }
    public function read($path)
    {
        return false;
    }
    public function readStream($path)
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
}
