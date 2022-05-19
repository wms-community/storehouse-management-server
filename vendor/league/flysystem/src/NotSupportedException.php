<?php
namespace League\Flysystem;
use RuntimeException;
use SplFileInfo;
class NotSupportedException extends RuntimeException implements FilesystemException
{
    public static function forLink(SplFileInfo $file)
    {
        $message = 'Links are not supported, encountered link at ';
        return new static($message . $file->getPathname());
    }
    public static function forFtpSystemType($systemType)
    {
        $message = "The FTP system type '$systemType' is currently not supported.";
        return new static($message);
    }
}
