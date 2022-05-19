<?php
namespace League\Flysystem;
class File extends Handler
{
    public function exists()
    {
        return $this->filesystem->has($this->path);
    }
    public function read()
    {
        return $this->filesystem->read($this->path);
    }
    public function readStream()
    {
        return $this->filesystem->readStream($this->path);
    }
    public function write($content)
    {
        return $this->filesystem->write($this->path, $content);
    }
    public function writeStream($resource)
    {
        return $this->filesystem->writeStream($this->path, $resource);
    }
    public function update($content)
    {
        return $this->filesystem->update($this->path, $content);
    }
    public function updateStream($resource)
    {
        return $this->filesystem->updateStream($this->path, $resource);
    }
    public function put($content)
    {
        return $this->filesystem->put($this->path, $content);
    }
    public function putStream($resource)
    {
        return $this->filesystem->putStream($this->path, $resource);
    }
    public function rename($newpath)
    {
        if ($this->filesystem->rename($this->path, $newpath)) {
            $this->path = $newpath;

            return true;
        }
        return false;
    }
    public function copy($newpath)
    {
        if ($this->filesystem->copy($this->path, $newpath)) {
            return new File($this->filesystem, $newpath);
        }
        return false;
    }
    public function getTimestamp()
    {
        return $this->filesystem->getTimestamp($this->path);
    }
    public function getMimetype()
    {
        return $this->filesystem->getMimetype($this->path);
    }
    public function getVisibility()
    {
        return $this->filesystem->getVisibility($this->path);
    }
    public function getMetadata()
    {
        return $this->filesystem->getMetadata($this->path);
    }
    public function getSize()
    {
        return $this->filesystem->getSize($this->path);
    }
    public function delete()
    {
        return $this->filesystem->delete($this->path);
    }
}
