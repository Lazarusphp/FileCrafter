<?php
namespace LazarusPhp\FileHandler;

use Exception;
use LazarusPhp\FileHandler\Interface\WriterInterface;
use RuntimeException;
use LazarusPhp\FileHandler\CoreFiles\WriterCore;

class Writer extends WriterCore
{

    private static WriterInterface $writerInterface;
    public static function bind(string $name, string $file, array $class)
    {
        // Detect the file;
        (!self::hasFile($file) && self::detectExtention($file,"json")) ? self::writeFile($file,"{}"): false;
        (!self::hasFile($file) && self::detectExtention($file,"ini")) ? self::writeFile($file,""): false;

        // Bind Class
        if(self::hasFile($file))
        {
            self::$path[$name] = $file;
            if($class !== null)
            {
                if(is_array($class))
                if (class_exists($class[0])) {
                    self::$class[$name] = new $class[0]($name);
                } else {
                    throw new Exception("Class: " . $class[0] . " not found");
                }
            }
        }

     
    }


    public static function generate($name, callable $writer)
    {
        self::$data = [];
        // Need to work away of preventing create and update to do the same thing
        self::$name = $name;
        
        if (!isset(self::$class[$name])) {
            throw new RuntimeException("Class for name: " . $name . " not found");
        }
        self::$writerInterface = self::$class[$name];
        self::$writerInterface->decodeData();
        if (is_callable($writer)) {
            return $writer(self::$writerInterface, $name);
        }
    }
}