<?php
namespace League\Flysystem\Cached;
use League\Flysystem\ReadInterface;
interface CacheInterface extends ReadInterface
{
    public function isComplete($dirname, $recursive);
    public function setComplete($dirname, $recursive);
    public function storeContents($directory, array $contents, $recursive);
    public function flush();
    public function autosave();
    public function save();
    public function load();
    public function rename($path, $newpath);
    public function copy($path, $newpath);
    public function delete($path);
    public function deleteDir($dirname);
    public function updateObject($path, array $object, $autosave = false);
    public function storeMiss($path);
}
