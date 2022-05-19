<?php
namespace League\Flysystem\Cached\Storage;
use Predis\Client;
class Predis extends AbstractCache
{
    protected $client;
    protected $key;
    protected $expire;
    public function __construct(Client $client = null, $key = 'flysystem', $expire = null)
    {
        $this->client = $client ?: new Client();
        $this->key = $key;
        $this->expire = $expire;
    }
    public function load()
    {
        if (($contents = $this->executeCommand('get', [$this->key])) !== null) {
            $this->setFromStorage($contents);
        }
    }
    public function save()
    {
        $contents = $this->getForStorage();
        $this->executeCommand('set', [$this->key, $contents]);
        if ($this->expire !== null) {
            $this->executeCommand('expire', [$this->key, $this->expire]);
        }
    }
    protected function executeCommand($name, array $arguments)
    {
        $command = $this->client->createCommand($name, $arguments);
        return $this->client->executeCommand($command);
    }
}
