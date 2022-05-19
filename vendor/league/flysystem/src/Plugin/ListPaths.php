<?php
namespace League\Flysystem\Plugin;
class ListPaths extends AbstractPlugin
{
    public function getMethod()
    {
        return 'listPaths';
    }
    public function handle($directory = '', $recursive = false)
    {
        $result = [];
        $contents = $this->filesystem->listContents($directory, $recursive);
        foreach ($contents as $object) {
            $result[] = $object['path'];
        }
        return $result;
    }
}
