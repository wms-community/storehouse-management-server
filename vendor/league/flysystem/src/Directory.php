<?php
namespace League\Flysystem;
class Directory extends Handler
{
    public function delete()
    {
        return $this->filesystem->deleteDir($this->path);
    }
    public function getContents($recursive = false)
    {
        return $this->filesystem->listContents($this->path, $recursive);
    }
}
