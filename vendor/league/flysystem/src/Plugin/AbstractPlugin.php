<?php
namespace League\Flysystem\Plugin;
use League\Flysystem\FilesystemInterface;
use League\Flysystem\PluginInterface;
abstract class AbstractPlugin implements PluginInterface
{
    protected $filesystem;
    public function setFilesystem(FilesystemInterface $filesystem)
    {
        $this->filesystem = $filesystem;
    }
}
