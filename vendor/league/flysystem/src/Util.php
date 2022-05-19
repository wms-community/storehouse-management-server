<?php
namespace League\Flysystem;
use League\Flysystem\Util\MimeType;
use LogicException;
use function strcmp;
class Util
{
    public static function pathinfo($path)
    {
        $pathinfo = compact('path');
        if ('' !== $dirname = dirname($path)) {
            $pathinfo['dirname'] = static::normalizeDirname($dirname);
        }
        $pathinfo['basename'] = static::basename($path);
        $pathinfo += pathinfo($pathinfo['basename']);
        return $pathinfo + ['dirname' => ''];
    }
    public static function normalizeDirname($dirname)
    {
        return $dirname === '.' ? '' : $dirname;
    }
    public static function dirname($path)
    {
        return static::normalizeDirname(dirname($path));
    }
    public static function map(array $object, array $map)
    {
        $result = [];
        foreach ($map as $from => $to) {
            if ( ! isset($object[$from])) {
                continue;
            }
            $result[$to] = $object[$from];
        }
        return $result;
    }
    public static function normalizePath($path)
    {
        return static::normalizeRelativePath($path);
    }
    public static function normalizeRelativePath($path)
    {
        $path = str_replace('\\', '/', $path);
        $path =  static::removeFunkyWhiteSpace($path);
        $parts = [];
        foreach (explode('/', $path) as $part) {
            switch ($part) {
                case '':
                case '.':
                break;
            case '..':
                if (empty($parts)) {
                    throw new LogicException(
                        'Path is outside of the defined root, path: [' . $path . ']'
                    );
                }
                array_pop($parts);
                break;
            default:
                $parts[] = $part;
                break;
            }
        }
        $path = implode('/', $parts);
        return $path;
    }
    protected static function removeFunkyWhiteSpace($path)
    {
        if (preg_match('#\p{C}+#u', $path)) {
            throw CorruptedPathDetected::forPath($path);
        }
        return $path;
    }
    public static function normalizePrefix($prefix, $separator)
    {
        return rtrim($prefix, $separator) . $separator;
    }
    public static function contentSize($contents)
    {
        return defined('MB_OVERLOAD_STRING') ? mb_strlen($contents, '8bit') : strlen($contents);
    }
    public static function guessMimeType($path, $content)
    {
        $mimeType = MimeType::detectByContent($content);
        if ( ! (empty($mimeType) || in_array($mimeType, ['application/x-empty', 'text/plain', 'text/x-asm']))) {
            return $mimeType;
        }
        return MimeType::detectByFilename($path);
    }
    public static function emulateDirectories(array $listing)
    {
        $directories = [];
        $listedDirectories = [];
        foreach ($listing as $object) {
            [$directories, $listedDirectories] = static::emulateObjectDirectories($object, $directories, $listedDirectories);
        }
        $directories = array_diff(array_unique($directories), array_unique($listedDirectories));
        foreach ($directories as $directory) {
            $listing[] = static::pathinfo($directory) + ['type' => 'dir'];
        }
        return $listing;
    }
    public static function ensureConfig($config)
    {
        if ($config === null) {
            return new Config();
        }
        if ($config instanceof Config) {
            return $config;
        }
        if (is_array($config)) {
            return new Config($config);
        }
        throw new LogicException('A config should either be an array or a Flysystem\Config object.');
    }
    public static function rewindStream($resource)
    {
        if (ftell($resource) !== 0 && static::isSeekableStream($resource)) {
            rewind($resource);
        }
    }
    public static function isSeekableStream($resource)
    {
        $metadata = stream_get_meta_data($resource);
        return $metadata['seekable'];
    }
    public static function getStreamSize($resource)
    {
        $stat = fstat($resource);
        if ( ! is_array($stat) || ! isset($stat['size'])) {
            return null;
        }
        return $stat['size'];
    }
    protected static function emulateObjectDirectories(array $object, array $directories, array $listedDirectories)
    {
        if ($object['type'] === 'dir') {
            $listedDirectories[] = $object['path'];
        }
        if ( ! isset($object['dirname']) || trim($object['dirname']) === '') {
            return [$directories, $listedDirectories];
        }
        $parent = $object['dirname'];
        while (isset($parent) && trim($parent) !== '' && ! in_array($parent, $directories)) {
            $directories[] = $parent;
            $parent = static::dirname($parent);
        }
        if (isset($object['type']) && $object['type'] === 'dir') {
            $listedDirectories[] = $object['path'];
            return [$directories, $listedDirectories];
        }
        return [$directories, $listedDirectories];
    }
    private static function basename($path)
    {
        $separators = DIRECTORY_SEPARATOR === '/' ? '/' : '\/';
        $path = rtrim($path, $separators);
        $basename = preg_replace('#.*?([^' . preg_quote($separators, '#') . ']+$)#', '$1', $path);
        if (DIRECTORY_SEPARATOR === '/') {
            return $basename;
        }
        while (preg_match('#^[a-zA-Z]{1}:[^\\\/]#', $basename)) {
            $basename = substr($basename, 2);
        }
        if (preg_match('#^[a-zA-Z]{1}:$#', $basename)) {
            $basename = rtrim($basename, ':');
        }
        return $basename;
    }
}
