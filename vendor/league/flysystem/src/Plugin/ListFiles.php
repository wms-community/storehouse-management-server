<?php
namespace League\Flysystem\Plugin;
class ListFiles extends AbstractPlugin
{
    public function getMethod()
    {
        return 'listFiles';
    }
    public function handle($directory = '', $recursive = false)
    {
        $contents = $this->filesystem->listContents($directory, $recursive);
        $filter = function ($object) {
            return $object['type'] === 'file';
        };
        return array_values(array_filter($contents, $filter));
    }
}
