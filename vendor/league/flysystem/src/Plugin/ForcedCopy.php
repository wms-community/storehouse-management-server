<?php
namespace League\Flysystem\Plugin;
use League\Flysystem\FileExistsException;
use League\Flysystem\FileNotFoundException;
class ForcedCopy extends AbstractPlugin
{
    public function getMethod()
    {
        return 'forceCopy';
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
            return $this->filesystem->copy($path, $newpath);
        }
        return false;
    }
}
