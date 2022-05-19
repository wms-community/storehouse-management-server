<?php
namespace League\Flysystem\Adapter;
use League\Flysystem\AdapterInterface;
abstract class AbstractAdapter implements AdapterInterface
{
    protected $pathPrefix;
    protected $pathSeparator = '/';
    public function setPathPrefix($prefix)
    {
        $prefix = (string) $prefix;
        if ($prefix === '') {
            $this->pathPrefix = null;
            return;
        }
        $this->pathPrefix = rtrim($prefix, '\\/') . $this->pathSeparator;
    }
    public function getPathPrefix()
    {
        return $this->pathPrefix;
    }
    public function applyPathPrefix($path)
    {
        return $this->getPathPrefix() . ltrim($path, '\\/');
    }
    public function removePathPrefix($path)
    {
        return substr($path, strlen((string) $this->getPathPrefix()));
    }
}
