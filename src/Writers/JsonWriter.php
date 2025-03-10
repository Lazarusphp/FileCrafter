<?php

namespace FireCore\DataHandler\Writers;

use FireCore\DataHandler\CoreFiles\WriterCore;
use FireCore\DataHandler\Interface\WriterInterface;
use RuntimeException;

class JsonWriter extends WriterCore implements WriterInterface
{

    private $flags = JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES|FILTER_SANITIZE_ADD_SLASHES;
    private $depth = 512;
    // Initial bindings


    public static function readFile($name,$asjson=false)
    {
        $file = file_get_contents(self::$path[$name]);
        if($asjson === true)
        {
            header("content-type: application/json");        
        }
        return $file;
    }


    // Clean this code a little better;
    public function save(?string $name = null)
    {
        
            $name = $name ?? self::$name;
            $file = self::$path[$name];
        if(self::detectExtention($file,"json"))
        {
                if (array_key_exists($name, self::$path)) {
                    if(self::hasFile($file))
                    {
                    if(self::writeFile($file, json_encode(self::$data,$this->flags,$this->depth)))
                    {
                        unset($file);
                    }
                    }
                }
        }
        else
        {
            trigger_error("Failed to save file : ".self::$path[$name]."Incompatible file");
        }
    }

    /**
     * Private classes below
     */

     public static function fetch($name,$array = false)
     {
            $file = self::readFile($name);
            $data = json_decode($file,$array);
            return $data;
     }

    public function decodeData(?string $name = null, $array = false)
    {
        $name = $name ?? self::$name;
        // Validate the Array name for path exists;
        if (array_key_exists($name, self::$path)) {
            // check if errors are present
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new RuntimeException("There was an error parsing the JSON document: " . json_last_error_msg());
            }
            else{
                // Loop through the file and set the data as an array ;
            foreach (self::fetch($name,$array) as $section => $values) {
                foreach ($values as $key => $value) {
                    self::$data[$section][$key] = $value;
                }
            }
        }
        }
    }
}