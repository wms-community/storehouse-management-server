<?php
namespace League\Flysystem;
class Config
{
    protected $settings = [];
    protected $fallback;
    public function __construct(array $settings = [])
    {
        $this->settings = $settings;
    }
    public function get($key, $default = null)
    {
        if ( ! array_key_exists($key, $this->settings)) {
            return $this->getDefault($key, $default);
        }
        return $this->settings[$key];
    }
    public function has($key)
    {
        if (array_key_exists($key, $this->settings)) {
            return true;
        }
        return $this->fallback instanceof Config
            ? $this->fallback->has($key)
            : false;
    }
    protected function getDefault($key, $default)
    {
        if ( ! $this->fallback) {
            return $default;
        }
        return $this->fallback->get($key, $default);
    }
    public function set($key, $value)
    {
        $this->settings[$key] = $value;
        return $this;
    }
    public function setFallback(Config $fallback)
    {
        $this->fallback = $fallback;
        return $this;
    }
}
