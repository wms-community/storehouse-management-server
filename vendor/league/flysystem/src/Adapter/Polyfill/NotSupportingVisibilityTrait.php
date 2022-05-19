<?php
namespace League\Flysystem\Adapter\Polyfill;
use LogicException;
trait NotSupportingVisibilityTrait
{
    public function getVisibility($path)
    {
        throw new LogicException(get_class($this) . ' does not support visibility. Path: ' . $path);
    }
    public function setVisibility($path, $visibility)
    {
        throw new LogicException(get_class($this) . ' does not support visibility. Path: ' . $path . ', visibility: ' . $visibility);
    }
}
