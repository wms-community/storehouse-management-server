<?php
namespace League\Flysystem;
use Exception as BaseException;
class FileNotFoundException extends Exception
{
    protected $path;
    public function __construct($path, $code = 0, BaseException $previous = null)
    {
        $this->path = $path;
        parent::__construct('File not found at path: ' . $this->getPath(), $code, $previous);
    }
    public function getPath()
    {
        return $this->path;
    }
}
