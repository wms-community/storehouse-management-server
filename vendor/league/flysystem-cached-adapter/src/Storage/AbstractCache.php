<?php
namespace League\Flysystem\Cached\Storage;
use League\Flysystem\Cached\CacheInterface;
use League\Flysystem\Util;
abstract class AbstractCache implements CacheInterface
{
    protected $autosave = true;
    protected $cache = [];
    protected $complete = [];
    public function __destruct()
    {
        if (! $this->autosave) {
            $this->save();
        }
    }
    public function getAutosave()
    {
        return $this->autosave;
    }
    public function setAutosave($autosave)
    {
        $this->autosave = $autosave;
    }
    public function storeContents($directory, array $contents, $recursive = false)
    {
        $directories = [$directory];
        foreach ($contents as $object) {
            $this->updateObject($object['path'], $object);
            $object = $this->cache[$object['path']];
            if ($recursive && $this->pathIsInDirectory($directory, $object['path'])) {
                $directories[] = $object['dirname'];
            }
        }
        foreach (array_unique($directories) as $directory) {
            $this->setComplete($directory, $recursive);
        }
        $this->autosave();
    }
    public function updateObject($path, array $object, $autosave = false)
    {
        if (! $this->has($path)) {
            $this->cache[$path] = Util::pathinfo($path);
        }
        $this->cache[$path] = array_merge($this->cache[$path], $object);
        if ($autosave) {
            $this->autosave();
        }
        $this->ensureParentDirectories($path);
    }
    public function storeMiss($path)
    {
        $this->cache[$path] = false;
        $this->autosave();
    }
    public function listContents($dirname = '', $recursive = false)
    {
        $result = [];
        foreach ($this->cache as $object) {
            if ($object === false) {
                continue;
            }
            if ($object['dirname'] === $dirname) {
                $result[] = $object;
            } elseif ($recursive && $this->pathIsInDirectory($dirname, $object['path'])) {
                $result[] = $object;
            }
        }
        return $result;
    }
    public function has($path)
    {
        if ($path !== false && array_key_exists($path, $this->cache)) {
            return $this->cache[$path] !== false;
        }
        if ($this->isComplete(Util::dirname($path), false)) {
            return false;
        }
    }
    public function read($path)
    {
        if (isset($this->cache[$path]['contents']) && $this->cache[$path]['contents'] !== false) {
            return $this->cache[$path];
        }
        return false;
    }
    public function readStream($path)
    {
        return false;
    }
    public function rename($path, $newpath)
    {
        if ($this->has($path)) {
            $object = $this->cache[$path];
            unset($this->cache[$path]);
            $object['path'] = $newpath;
            $object = array_merge($object, Util::pathinfo($newpath));
            $this->cache[$newpath] = $object;
            $this->autosave();
        }
    }
    public function copy($path, $newpath)
    {
        if ($this->has($path)) {
            $object = $this->cache[$path];
            $object = array_merge($object, Util::pathinfo($newpath));
            $this->updateObject($newpath, $object, true);
        }
    }
    public function delete($path)
    {
        $this->storeMiss($path);
    }
    public function deleteDir($dirname)
    {
        foreach ($this->cache as $path => $object) {
            if ($this->pathIsInDirectory($dirname, $path) || $path === $dirname) {
                unset($this->cache[$path]);
            }
        }
        unset($this->complete[$dirname]);
        $this->autosave();
    }
    public function getMimetype($path)
    {
        if (isset($this->cache[$path]['mimetype'])) {
            return $this->cache[$path];
        }
        if (! $result = $this->read($path)) {
            return false;
        }
        $mimetype = Util::guessMimeType($path, $result['contents']);
        $this->cache[$path]['mimetype'] = $mimetype;
        return $this->cache[$path];
    }
    public function getSize($path)
    {
        if (isset($this->cache[$path]['size'])) {
            return $this->cache[$path];
        }
        return false;
    }
    public function getTimestamp($path)
    {
        if (isset($this->cache[$path]['timestamp'])) {
            return $this->cache[$path];
        }
        return false;
    }
    public function getVisibility($path)
    {
        if (isset($this->cache[$path]['visibility'])) {
            return $this->cache[$path];
        }
        return false;
    }
    public function getMetadata($path)
    {
        if (isset($this->cache[$path]['type'])) {
            return $this->cache[$path];
        }
        return false;
    }
    public function isComplete($dirname, $recursive)
    {
        if (! array_key_exists($dirname, $this->complete)) {
            return false;
        }
        if ($recursive && $this->complete[$dirname] !== 'recursive') {
            return false;
        }
        return true;
    }
    public function setComplete($dirname, $recursive)
    {
        $this->complete[$dirname] = $recursive ? 'recursive' : true;
    }
    public function cleanContents(array $contents)
    {
        $cachedProperties = array_flip([
            'path', 'dirname', 'basename', 'extension', 'filename',
            'size', 'mimetype', 'visibility', 'timestamp', 'type',
            'md5',
        ]);
        foreach ($contents as $path => $object) {
            if (is_array($object)) {
                $contents[$path] = array_intersect_key($object, $cachedProperties);
            }
        }
        return $contents;
    }
    public function flush()
    {
        $this->cache = [];
        $this->complete = [];
        $this->autosave();
    }
    public function autosave()
    {
        if ($this->autosave) {
            $this->save();
        }
    }
    public function getForStorage()
    {
        $cleaned = $this->cleanContents($this->cache);
        return json_encode([$cleaned, $this->complete]);
    }
    public function setFromStorage($json)
    {
        list($cache, $complete) = json_decode($json, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($cache) && is_array($complete)) {
            $this->cache = $cache;
            $this->complete = $complete;
        }
    }
    public function ensureParentDirectories($path)
    {
        $object = $this->cache[$path];
        while ($object['dirname'] !== '' && ! isset($this->cache[$object['dirname']])) {
            $object = Util::pathinfo($object['dirname']);
            $object['type'] = 'dir';
            $this->cache[$object['path']] = $object;
        }
    }
    protected function pathIsInDirectory($directory, $path)
    {
        return $directory === '' || strpos($path, $directory . '/') === 0;
    }
}
