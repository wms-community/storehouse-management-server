<?php
namespace League\Flysystem\Adapter;
use DirectoryIterator;
use FilesystemIterator;
use finfo as Finfo;
use League\Flysystem\Config;
use League\Flysystem\Exception;
use League\Flysystem\NotSupportedException;
use League\Flysystem\UnreadableFileException;
use League\Flysystem\Util;
use LogicException;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;
class Local extends AbstractAdapter
{
    const SKIP_LINKS = 0001;
    const DISALLOW_LINKS = 0002;
    protected static $permissions = [
        'file' => [
            'public' => 0644,
            'private' => 0600,
        ],
        'dir' => [
            'public' => 0755,
            'private' => 0700,
        ],
    ];
    protected $pathSeparator = DIRECTORY_SEPARATOR;
    protected $permissionMap;
    protected $writeFlags;
    private $linkHandling;
    public function __construct($root, $writeFlags = LOCK_EX, $linkHandling = self::DISALLOW_LINKS, array $permissions = [])
    {
        $root = is_link($root) ? realpath($root) : $root;
        $this->permissionMap = array_replace_recursive(static::$permissions, $permissions);
        $this->ensureDirectory($root);
        if ( ! is_dir($root) || ! is_readable($root)) {
            throw new LogicException('The root path ' . $root . ' is not readable.');
        }
        $this->setPathPrefix($root);
        $this->writeFlags = $writeFlags;
        $this->linkHandling = $linkHandling;
    }
    protected function ensureDirectory($root)
    {
        if ( ! is_dir($root)) {
            $umask = umask(0);
            if ( ! @mkdir($root, $this->permissionMap['dir']['public'], true)) {
                $mkdirError = error_get_last();
            }
            umask($umask);
            clearstatcache(false, $root);
            if ( ! is_dir($root)) {
                $errorMessage = isset($mkdirError['message']) ? $mkdirError['message'] : '';
                throw new Exception(sprintf('Impossible to create the root directory "%s". %s', $root, $errorMessage));
            }
        }
    }
    public function has($path)
    {
        $location = $this->applyPathPrefix($path);
        return file_exists($location);
    }
    public function write($path, $contents, Config $config)
    {
        $location = $this->applyPathPrefix($path);
        $this->ensureDirectory(dirname($location));
        if (($size = file_put_contents($location, $contents, $this->writeFlags)) === false) {
            return false;
        }
        $type = 'file';
        $result = compact('contents', 'type', 'size', 'path');
        if ($visibility = $config->get('visibility')) {
            $result['visibility'] = $visibility;
            $this->setVisibility($path, $visibility);
        }
        return $result;
    }
    public function writeStream($path, $resource, Config $config)
    {
        $location = $this->applyPathPrefix($path);
        $this->ensureDirectory(dirname($location));
        $stream = fopen($location, 'w+b');
        if ( ! $stream || stream_copy_to_stream($resource, $stream) === false || ! fclose($stream)) {
            return false;
        }
        $type = 'file';
        $result = compact('type', 'path');
        if ($visibility = $config->get('visibility')) {
            $this->setVisibility($path, $visibility);
            $result['visibility'] = $visibility;
        }
        return $result;
    }
    public function readStream($path)
    {
        $location = $this->applyPathPrefix($path);
        $stream = fopen($location, 'rb');
        return ['type' => 'file', 'path' => $path, 'stream' => $stream];
    }
    public function updateStream($path, $resource, Config $config)
    {
        return $this->writeStream($path, $resource, $config);
    }
    public function update($path, $contents, Config $config)
    {
        $location = $this->applyPathPrefix($path);
        $size = file_put_contents($location, $contents, $this->writeFlags);
        if ($size === false) {
            return false;
        }
        $type = 'file';
        $result = compact('type', 'path', 'size', 'contents');
        if ($visibility = $config->get('visibility')) {
            $this->setVisibility($path, $visibility);
            $result['visibility'] = $visibility;
        }
        return $result;
    }
    public function read($path)
    {
        $location = $this->applyPathPrefix($path);
        $contents = @file_get_contents($location);
        if ($contents === false) {
            return false;
        }
        return ['type' => 'file', 'path' => $path, 'contents' => $contents];
    }
    public function rename($path, $newpath)
    {
        $location = $this->applyPathPrefix($path);
        $destination = $this->applyPathPrefix($newpath);
        $parentDirectory = $this->applyPathPrefix(Util::dirname($newpath));
        $this->ensureDirectory($parentDirectory);
        return rename($location, $destination);
    }
    public function copy($path, $newpath)
    {
        $location = $this->applyPathPrefix($path);
        $destination = $this->applyPathPrefix($newpath);
        $this->ensureDirectory(dirname($destination));
        return copy($location, $destination);
    }
    public function delete($path)
    {
        $location = $this->applyPathPrefix($path);
        return @unlink($location);
    }
    public function listContents($directory = '', $recursive = false)
    {
        $result = [];
        $location = $this->applyPathPrefix($directory);
        if ( ! is_dir($location)) {
            return [];
        }
        $iterator = $recursive ? $this->getRecursiveDirectoryIterator($location) : $this->getDirectoryIterator($location);
        foreach ($iterator as $file) {
            $path = $this->getFilePath($file);
            if (preg_match('#(^|/|\\\\)\.{1,2}$#', $path)) {
                continue;
            }
            $result[] = $this->normalizeFileInfo($file);
        }
        unset($iterator);
        return array_filter($result);
    }
    public function getMetadata($path)
    {
        $location = $this->applyPathPrefix($path);
        clearstatcache(false, $location);
        $info = new SplFileInfo($location);
        return $this->normalizeFileInfo($info);
    }
    public function getSize($path)
    {
        return $this->getMetadata($path);
    }
    public function getMimetype($path)
    {
        $location = $this->applyPathPrefix($path);
        $finfo = new Finfo(FILEINFO_MIME_TYPE);
        $mimetype = $finfo->file($location);
        if (in_array($mimetype, ['application/octet-stream', 'inode/x-empty', 'application/x-empty'])) {
            $mimetype = Util\MimeType::detectByFilename($location);
        }
        return ['path' => $path, 'type' => 'file', 'mimetype' => $mimetype];
    }
    public function getTimestamp($path)
    {
        return $this->getMetadata($path);
    }
    public function getVisibility($path)
    {
        $location = $this->applyPathPrefix($path);
        clearstatcache(false, $location);
        $permissions = octdec(substr(sprintf('%o', fileperms($location)), -4));
        $type = is_dir($location) ? 'dir' : 'file';
        foreach ($this->permissionMap[$type] as $visibility => $visibilityPermissions) {
            if ($visibilityPermissions == $permissions) {
                return compact('path', 'visibility');
            }
        }
        $visibility = substr(sprintf('%o', fileperms($location)), -4);
        return compact('path', 'visibility');
    }
    public function setVisibility($path, $visibility)
    {
        $location = $this->applyPathPrefix($path);
        $type = is_dir($location) ? 'dir' : 'file';
        $success = chmod($location, $this->permissionMap[$type][$visibility]);
        if ($success === false) {
            return false;
        }
        return compact('path', 'visibility');
    }
    public function createDir($dirname, Config $config)
    {
        $location = $this->applyPathPrefix($dirname);
        $umask = umask(0);
        $visibility = $config->get('visibility', 'public');
        $return = ['path' => $dirname, 'type' => 'dir'];
        if ( ! is_dir($location)) {
            if (false === @mkdir($location, $this->permissionMap['dir'][$visibility], true)
                || false === is_dir($location)) {
                $return = false;
            }
        }
        umask($umask);
        return $return;
    }
    public function deleteDir($dirname)
    {
        $location = $this->applyPathPrefix($dirname);
        if ( ! is_dir($location)) {
            return false;
        }
        $contents = $this->getRecursiveDirectoryIterator($location, RecursiveIteratorIterator::CHILD_FIRST);
        foreach ($contents as $file) {
            $this->guardAgainstUnreadableFileInfo($file);
            $this->deleteFileInfoObject($file);
        }
        unset($contents);
        return rmdir($location);
    }
    protected function deleteFileInfoObject(SplFileInfo $file)
    {
        switch ($file->getType()) {
            case 'dir':
                rmdir($file->getRealPath());
                break;
            case 'link':
                unlink($file->getPathname());
                break;
            default:
                unlink($file->getRealPath());
        }
    }
    protected function normalizeFileInfo(SplFileInfo $file)
    {
        if ( ! $file->isLink()) {
            return $this->mapFileInfo($file);
        }
        if ($this->linkHandling & self::DISALLOW_LINKS) {
            throw NotSupportedException::forLink($file);
        }
    }
    protected function getFilePath(SplFileInfo $file)
    {
        $location = $file->getPathname();
        $path = $this->removePathPrefix($location);
        return trim(str_replace('\\', '/', $path), '/');
    }
    protected function getRecursiveDirectoryIterator($path, $mode = RecursiveIteratorIterator::SELF_FIRST)
    {
        return new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS),
            $mode
        );
    }
    protected function getDirectoryIterator($path)
    {
        $iterator = new DirectoryIterator($path);
        return $iterator;
    }
    protected function mapFileInfo(SplFileInfo $file)
    {
        $normalized = [
            'type' => $file->getType(),
            'path' => $this->getFilePath($file),
        ];
        $normalized['timestamp'] = $file->getMTime();
        if ($normalized['type'] === 'file') {
            $normalized['size'] = $file->getSize();
        }
        return $normalized;
    }
    protected function guardAgainstUnreadableFileInfo(SplFileInfo $file)
    {
        if ( ! $file->isReadable()) {
            throw UnreadableFileException::forFileInfo($file);
        }
    }
}
