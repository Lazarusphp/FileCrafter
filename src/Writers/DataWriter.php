<?php
namespace FireCore\DataHandler\Writers;

class DataWriter
{
 
    private static $hassections = [];

    private  $path = [];
    private $data = [];


    public static function  setPath($pathname,$path,$hassections=true)
    {
        self::$path[$pathname] = $path;
        self::$hasSections[$pathname] = true;
    }

    protected static function hasSection(string $name)
    {
        if(array_key_exists($name,self::$hassections)){
            if (self::$hasSection === true) {
                return true;
            } else {
                return false;
            }
        }
        else
        {
            trigger_error("Array {$name} Cannot be found");
            exit();
        }
    }

    public static function add(string $name,string $section, string | int $key,string | int $value=null)
    {
        if(self::hasSection($name) === true)
        {
            self::data[$name][$section] = [];
            return self::$data[$section][$key] = $value;
        }
        else
        {
            return self::$data[$name][$section] = $key;
        }
        
    }

}