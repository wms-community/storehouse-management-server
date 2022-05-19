<?php
namespace League\Flysystem;
use InvalidArgumentException;
interface FilesystemInterface
{
    public function has($path);
    public function read($path);
    public function readStream($path);
    public function listContents($directory = '', $recursive = false);
    public function getMetadata($path);
    public function getSize($path);
    public function getMimetype($path);
    public function getTimestamp($path);
    public function getVisibility($path);
    public function write($path, $contents, array $config = []);
    public function writeStream($path, $resource, array $config = []);
    public function update($path, $contents, array $config = []);
    public function updateStream($path, $resource, array $config = []);
    public function rename($path, $newpath);
    public function copy($path, $newpath);
    public function delete($path);
    public function deleteDir($dirname);
    public function createDir($dirname, array $config = []);
    public function setVisibility($path, $visibility);
    public function put($path, $contents, array $config = []);
    public function putStream($path, $resource, array $config = []);
    public function readAndDelete($path);
    public function get($path, Handler $handler = null);
    public function addPlugin(PluginInterface $plugin);
}
