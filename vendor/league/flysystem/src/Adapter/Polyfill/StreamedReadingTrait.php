<?php
namespace League\Flysystem\Adapter\Polyfill;
trait StreamedReadingTrait
{
    public function readStream($path)
    {
        if ( ! $data = $this->read($path)) {
            return false;
        }
        $stream = fopen('php://temp', 'w+b');
        fwrite($stream, $data['contents']);
        rewind($stream);
        $data['stream'] = $stream;
        unset($data['contents']);
        return $data;
    }
    abstract public function read($path);
}
