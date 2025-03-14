<?php
namespace LazarusPhp\FileHandler;

use Exception;
use LazarusPhp\FileHandler\Interface\WriterInterface;
use RuntimeException;
use LazarusPhp\FileHandler\CoreFiles\WriterCore;

class Writer extends WriterCore
{

    private static WriterInterface $writerInterface;
/**
 * Documentation for bind
 *
 * @param string $name
 * @param string $file
 * @param array $class : class must be an array to function.
 * @return void
 */    
    public static function bind(string $name, string $file, array $class)
    {
        // Detect the file;
        (!self::hasFile($file) && self::detectExtention($file,"json")) ? self::writeFile($file,"{}"): false;
        (!self::hasFile($file) && self::detectExtention($file,"ini")) ? self::writeFile($file,""): false;

        // check the file exisits
        if(self::hasFile($file))
        {
            self::$path[$name] = $file;
            if($class !== null)
            {
                // check if array exists
                if(is_array($class))
                // check if class exists
                if (class_exists($class[0])) {
                    // bind the class and the name to self::$class[$name]
                    self::$class[$name] = new $class[0]($name);
                } else {
                    // throw Exception class to state class doesn't exist
                    throw new Exception("Class: " . $class[0] . " not found");
                }
            }
        }
    }


/**
 * Generate method
 *
 * @param string $name
 * @param callable $writer
 * @return void
 * 
 * $writer must be encased in a function()
 *  in order access binded methods from injected class.
 */
    public static function generate(string $name, callable $writer)
    {
        self::$modifier = __FUNCTION__;
        // Reset the self::$data array
        self::$data = [];
        //  check if the class is not set if it isnt throw error class not found.
        if (!isset(self::$class[$name])) {
            throw new RuntimeException("Class for name: " . $name . " not found");
        }
        // Bind Writer Interface to self::$class;
        self::$writerInterface = self::$class[$name];
        // Check if the function $writer is callable
        if (is_callable($writer)) {
            // If $writer is callable return self::$writerinterface $name is optional
            return $writer(self::$writerInterface, $name);
        }
    }

    public static  function destroy($name)
    {
        $file = self::$path[$name];
        if(file_exists($file))
        {
            if(array_key_exists($name,self::$path))
            {
                if(unlink($file)){
                self::$data = [];
                unset(self::$class[$name]);
                unset(self::$path[$name]);
                }
            }
            else
            {
                throw new Exception("Array valaue $name cannot be found");
            }
        }
        else
        {
            throw new Exception("Cannot find file");
        }
    }
}