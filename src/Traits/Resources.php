<?php
namespace FireCore\DataHandler\Traits;

trait Resources
{

    private $path = [];
    private $name;
    private $data = [];
    private $hasSections;
    private $assoc;


    public function listProperties(): array
    {
        return get_object_vars($this);
    }

    
    public function group($name, callable $handler)
    {
        if (is_callable($handler))
        {
            return $handler($this, $name);
        }
    }

    public function getstd()
    {
        return new \stdClass;
    }

    private  function getType($value):mixed
    {
        return (is_numeric($value) ? $value : '"' . addslashes((string)$value) . '"');
    }

    

    private  function countSection($section):int
    {
        return count($this->data[$section]);
    }

    private function writeFile($path,$content)
    {
        return file_put_contents($path,$content);
    }

}