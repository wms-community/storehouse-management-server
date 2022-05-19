<?php
namespace League\Flysystem\Plugin;
use League\Flysystem\FileExistsException;
use League\Flysystem\FileNotFoundException;
class ForcedRename extends AbstractPlugin
{
    public function getMethod()
    {
        return 'forceRename';
    }
    public function handle($path, $newpath)
    {
        try {
            $deleted = $this->filesystem->delete($newpath);
        } catch (FileNotFoundException $e) {
            // The destination path does not exist. That's ok.
            $deleted = true;
        }
        if ($deleted) {
            return $this->filesystem->rename($path, $newpath);
        }
        return false;
    }
}
