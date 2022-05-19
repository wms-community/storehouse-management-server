<?php
namespace League\Flysystem\Plugin;
class EmptyDir extends AbstractPlugin
{
    public function getMethod()
    {
        return 'emptyDir';
    }
    public function handle($dirname)
    {
        $listing = $this->filesystem->listContents($dirname, false);
        foreach ($listing as $item) {
            if ($item['type'] === 'dir') {
                $this->filesystem->deleteDir($item['path']);
            } else {
                $this->filesystem->delete($item['path']);
            }
        }
    }
}
