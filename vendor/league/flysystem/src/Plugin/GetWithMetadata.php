<?php
namespace League\Flysystem\Plugin;
use InvalidArgumentException;
use League\Flysystem\FileNotFoundException;
class GetWithMetadata extends AbstractPlugin
{
    public function getMethod()
    {
        return 'getWithMetadata';
    }
    public function handle($path, array $metadata)
    {
        $object = $this->filesystem->getMetadata($path);
        if ( ! $object) {
            return false;
        }
        $keys = array_diff($metadata, array_keys($object));
        foreach ($keys as $key) {
            if ( ! method_exists($this->filesystem, $method = 'get' . ucfirst($key))) {
                throw new InvalidArgumentException('Could not fetch metadata: ' . $key);
            }
            $object[$key] = $this->filesystem->{$method}($path);
        }
        return $object;
    }
}
