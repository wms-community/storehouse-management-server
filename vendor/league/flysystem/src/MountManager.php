<?php
namespace League\Flysystem;
use InvalidArgumentException;
use League\Flysystem\Plugin\PluggableTrait;
use League\Flysystem\Plugin\PluginNotFoundException;
class MountManager implements FilesystemInterface
{
    use PluggableTrait;
    protected $filesystems = [];
    public function __construct(array $filesystems = [])
    {
        $this->mountFilesystems($filesystems);
    }
    public function mountFilesystems(array $filesystems)
    {
        foreach ($filesystems as $prefix => $filesystem) {
            $this->mountFilesystem($prefix, $filesystem);
        }
        return $this;
    }
    public function mountFilesystem($prefix, FilesystemInterface $filesystem)
    {
        if ( ! is_string($prefix)) {
            throw new InvalidArgumentException(__METHOD__ . ' expects argument #1 to be a string.');
        }
        $this->filesystems[$prefix] = $filesystem;
        return $this;
    }
    public function getFilesystem($prefix)
    {
        if ( ! isset($this->filesystems[$prefix])) {
            throw new FilesystemNotFoundException('No filesystem mounted with prefix ' . $prefix);
        }
        return $this->filesystems[$prefix];
    }
    public function filterPrefix(array $arguments)
    {
        if (empty($arguments)) {
            throw new InvalidArgumentException('At least one argument needed');
        }
        $path = array_shift($arguments);
        if ( ! is_string($path)) {
            throw new InvalidArgumentException('First argument should be a string');
        }
        list($prefix, $path) = $this->getPrefixAndPath($path);
        array_unshift($arguments, $path);
        return [$prefix, $arguments];
    }
    public function listContents($directory = '', $recursive = false)
    {
        list($prefix, $directory) = $this->getPrefixAndPath($directory);
        $filesystem = $this->getFilesystem($prefix);
        $result = $filesystem->listContents($directory, $recursive);
        foreach ($result as &$file) {
            $file['filesystem'] = $prefix;
        }
        return $result;
    }
    public function __call($method, $arguments)
    {
        list($prefix, $arguments) = $this->filterPrefix($arguments);
        return $this->invokePluginOnFilesystem($method, $arguments, $prefix);
    }
    public function copy($from, $to, array $config = [])
    {
        list($prefixFrom, $from) = $this->getPrefixAndPath($from);
        $buffer = $this->getFilesystem($prefixFrom)->readStream($from);
        if ($buffer === false) {
            return false;
        }
        list($prefixTo, $to) = $this->getPrefixAndPath($to);
        $result = $this->getFilesystem($prefixTo)->writeStream($to, $buffer, $config);
        if (is_resource($buffer)) {
            fclose($buffer);
        }
        return $result;
    }
    public function listWith(array $keys = [], $directory = '', $recursive = false)
    {
        list($prefix, $directory) = $this->getPrefixAndPath($directory);
        $arguments = [$keys, $directory, $recursive];
        return $this->invokePluginOnFilesystem('listWith', $arguments, $prefix);
    }
    public function move($from, $to, array $config = [])
    {
        list($prefixFrom, $pathFrom) = $this->getPrefixAndPath($from);
        list($prefixTo, $pathTo) = $this->getPrefixAndPath($to);
        if ($prefixFrom === $prefixTo) {
            $filesystem = $this->getFilesystem($prefixFrom);
            $renamed = $filesystem->rename($pathFrom, $pathTo);
            if ($renamed && isset($config['visibility'])) {
                return $filesystem->setVisibility($pathTo, $config['visibility']);
            }
            return $renamed;
        }
        $copied = $this->copy($from, $to, $config);
        if ($copied) {
            return $this->delete($from);
        }
        return false;
    }
    public function invokePluginOnFilesystem($method, $arguments, $prefix)
    {
        $filesystem = $this->getFilesystem($prefix);
        try {
            return $this->invokePlugin($method, $arguments, $filesystem);
        } catch (PluginNotFoundException $e) {
            // Let it pass, it's ok, don't panic.
        }
        $callback = [$filesystem, $method];
        return call_user_func_array($callback, $arguments);
    }
    protected function getPrefixAndPath($path)
    {
        if (strpos($path, '://') < 1) {
            throw new InvalidArgumentException('No prefix detected in path: ' . $path);
        }
        return explode('://', $path, 2);
    }
    public function has($path)
    {
        list($prefix, $path) = $this->getPrefixAndPath($path);
        return $this->getFilesystem($prefix)->has($path);
    }
    public function read($path)
    {
        list($prefix, $path) = $this->getPrefixAndPath($path);
        return $this->getFilesystem($prefix)->read($path);
    }
    public function readStream($path)
    {
        list($prefix, $path) = $this->getPrefixAndPath($path);
        return $this->getFilesystem($prefix)->readStream($path);
    }
    public function getMetadata($path)
    {
        list($prefix, $path) = $this->getPrefixAndPath($path);
        return $this->getFilesystem($prefix)->getMetadata($path);
    }
    public function getSize($path)
    {
        list($prefix, $path) = $this->getPrefixAndPath($path);
        return $this->getFilesystem($prefix)->getSize($path);
    }
    public function getMimetype($path)
    {
        list($prefix, $path) = $this->getPrefixAndPath($path);
        return $this->getFilesystem($prefix)->getMimetype($path);
    }
    public function getTimestamp($path)
    {
        list($prefix, $path) = $this->getPrefixAndPath($path);
        return $this->getFilesystem($prefix)->getTimestamp($path);
    }
    public function getVisibility($path)
    {
        list($prefix, $path) = $this->getPrefixAndPath($path);
        return $this->getFilesystem($prefix)->getVisibility($path);
    }
    public function write($path, $contents, array $config = [])
    {
        list($prefix, $path) = $this->getPrefixAndPath($path);
        return $this->getFilesystem($prefix)->write($path, $contents, $config);
    }
    public function writeStream($path, $resource, array $config = [])
    {
        list($prefix, $path) = $this->getPrefixAndPath($path);
        return $this->getFilesystem($prefix)->writeStream($path, $resource, $config);
    }
    public function update($path, $contents, array $config = [])
    {
        list($prefix, $path) = $this->getPrefixAndPath($path);
        return $this->getFilesystem($prefix)->update($path, $contents, $config);
    }
    public function updateStream($path, $resource, array $config = [])
    {
        list($prefix, $path) = $this->getPrefixAndPath($path);
        return $this->getFilesystem($prefix)->updateStream($path, $resource, $config);
    }
    public function rename($path, $newpath)
    {
        list($prefix, $path) = $this->getPrefixAndPath($path);
        return $this->getFilesystem($prefix)->rename($path, $newpath);
    }
    public function delete($path)
    {
        list($prefix, $path) = $this->getPrefixAndPath($path);
        return $this->getFilesystem($prefix)->delete($path);
    }
    public function deleteDir($dirname)
    {
        list($prefix, $dirname) = $this->getPrefixAndPath($dirname);
        return $this->getFilesystem($prefix)->deleteDir($dirname);
    }
    public function createDir($dirname, array $config = [])
    {
        list($prefix, $dirname) = $this->getPrefixAndPath($dirname);
        return $this->getFilesystem($prefix)->createDir($dirname);
    }
    public function setVisibility($path, $visibility)
    {
        list($prefix, $path) = $this->getPrefixAndPath($path);
        return $this->getFilesystem($prefix)->setVisibility($path, $visibility);
    }
    public function put($path, $contents, array $config = [])
    {
        list($prefix, $path) = $this->getPrefixAndPath($path);
        return $this->getFilesystem($prefix)->put($path, $contents, $config);
    }
    public function putStream($path, $resource, array $config = [])
    {
        list($prefix, $path) = $this->getPrefixAndPath($path);
        return $this->getFilesystem($prefix)->putStream($path, $resource, $config);
    }
    public function readAndDelete($path)
    {
        list($prefix, $path) = $this->getPrefixAndPath($path);
        return $this->getFilesystem($prefix)->readAndDelete($path);
    }
    public function get($path, Handler $handler = null)
    {
        list($prefix, $path) = $this->getPrefixAndPath($path);
        return $this->getFilesystem($prefix)->get($path);
    }
}
