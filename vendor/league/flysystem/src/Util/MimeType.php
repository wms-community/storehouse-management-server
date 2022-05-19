<?php
namespace League\Flysystem\Util;
use League\MimeTypeDetection\FinfoMimeTypeDetector;
use League\MimeTypeDetection\GeneratedExtensionToMimeTypeMap;
use League\MimeTypeDetection\MimeTypeDetector;
class MimeType
{
    protected static $extensionToMimeTypeMap = GeneratedExtensionToMimeTypeMap::MIME_TYPES_FOR_EXTENSIONS;
    protected static $detector;
    public static function useDetector(MimeTypeDetector $detector)
    {
        static::$detector = $detector;
    }
    protected static function detector()
    {
        if ( ! static::$detector instanceof MimeTypeDetector) {
            static::$detector = new FinfoMimeTypeDetector();
        }
        return static::$detector;
    }
    public static function detectByContent($content)
    {
        if (is_string($content)) {
            return static::detector()->detectMimeTypeFromBuffer($content);
        }
        return 'text/plain';
    }
    public static function detectByFileExtension($extension)
    {
        return static::detector()->detectMimeTypeFromPath('artificial.' . $extension) ?: 'text/plain';
    }
    public static function detectByFilename($filename)
    {
        return static::detector()->detectMimeTypeFromPath($filename) ?: 'text/plain';
    }
    public static function getExtensionToMimeTypeMap()
    {
        return static::$extensionToMimeTypeMap;
    }
}
