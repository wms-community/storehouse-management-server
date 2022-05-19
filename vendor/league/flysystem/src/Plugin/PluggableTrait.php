<?php
namespace League\Flysystem\Plugin;
use BadMethodCallException;
use League\Flysystem\FilesystemInterface;
use League\Flysystem\PluginInterface;
use LogicException;
trait PluggableTrait
{
    protected $plugins = [];
    public function addPlugin(PluginInterface $plugin)
    {
        if ( ! method_exists($plugin, 'handle')) {
            throw new LogicException(get_class($plugin) . ' does not have a handle method.');
        }
        $this->plugins[$plugin->getMethod()] = $plugin;
        return $this;
    }
    protected function findPlugin($method)
    {
        if ( ! isset($this->plugins[$method])) {
            throw new PluginNotFoundException('Plugin not found for method: ' . $method);
        }
        return $this->plugins[$method];
    }
    protected function invokePlugin($method, array $arguments, FilesystemInterface $filesystem)
    {
        $plugin = $this->findPlugin($method);
        $plugin->setFilesystem($filesystem);
        $callback = [$plugin, 'handle'];
        return call_user_func_array($callback, $arguments);
    }
    public function __call($method, array $arguments)
    {
        try {
            return $this->invokePlugin($method, $arguments, $this);
        } catch (PluginNotFoundException $e) {
            throw new BadMethodCallException(
                'Call to undefined method '
                . get_class($this)
                . '::' . $method
            );
        }
    }
}
