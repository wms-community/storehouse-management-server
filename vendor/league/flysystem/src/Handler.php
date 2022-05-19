<?php
namespace League\Flysystem;
use BadMethodCallException;
abstract class Handler
{
    protected $path;
    protected $filesystem;
    public function __construct(FilesystemInterface $filesystem = null, $path = null)
    {
        $this->path = $path;
        $this->filesystem = $filesystem;
    }
    public function isDir()
    {
        return $this->getType() === 'dir';
    }
    public function isFile()
    {
        return $this->getType() === 'file';
    }
    public function getType()
    {
        $metadata = $this->filesystem->getMetadata($this->path);
        return $metadata ? $metadata['type'] : 'dir';
    }
    public function setFilesystem(FilesystemInterface $filesystem)
    {
        $this->filesystem = $filesystem;
        return $this;
    }
    public function getFilesystem()
    {
        return $this->filesystem;
    }
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }
    public function getPath()
    {
        return $this->path;
    }
    public function __call($method, array $arguments)
    {
        array_unshift($arguments, $this->path);
        $callback = [$this->filesystem, $method];
        try {
            return call_user_func_array($callback, $arguments);
        } catch (BadMethodCallException $e) {
            throw new BadMethodCallException(
                'Call to undefined method '
                . get_called_class()
                . '::' . $method
            );
        }
    }
}
