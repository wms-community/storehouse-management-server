<?php
namespace League\Flysystem;
interface AdapterInterface extends ReadInterface
{
    const VISIBILITY_PUBLIC = 'public';
    const VISIBILITY_PRIVATE = 'private';
    public function write($path, $contents, Config $config);
    public function writeStream($path, $resource, Config $config);
    public function update($path, $contents, Config $config);
    public function updateStream($path, $resource, Config $config);
    public function rename($path, $newpath);
    public function copy($path, $newpath);
    public function delete($path);
    public function deleteDir($dirname);
    public function createDir($dirname, Config $config);
    public function setVisibility($path, $visibility);
}
