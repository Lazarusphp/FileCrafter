<?php

namespace LazarusPhp\FileCrafter\Writers;

use LazarusPhp\FileCrafter\CoreFiles\WriterCore;
use LazarusPhp\FileCrafter\Interface\WriterInterface;
use RuntimeException;

class JsonWriter extends WriterCore implements WriterInterface
{

    private $flags = JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES|FILTER_SANITIZE_ADD_SLASHES;
    private $depth = 512;
    private static $file ;
    // Initial bindings


    public function __construct(string $name)
    {
        self::$name = $name;
        self::$format = self::getPathExtention(self::$path[$name]);
        $this->parseFile($name);
    }

    public function parseFile($name)
    {
        self::$file = file_get_contents(self::$path[$name]);
        $name = $name ?? self::$name;
        // Validate the Array name for path exists;
        if (array_key_exists($name, self::$path)) {
            // check if errors are present
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new RuntimeException("There was an error parsing the JSON document: " . json_last_error_msg());
            }
            else{
                // Loop through the file and set the data as an array ;
            foreach (json_decode(self::$file,false) as $section => $values) {
                foreach ($values as $key => $value) {
                    self::$data[$section][$key] = $value;
                }
            }
        }
        }
    }



    // Clean this code a little better;
    public function save(?string $name = null)
    {
            $name = $name ?? self::$name;
            $file = self::$path[$name];
        if(self::detectExtention($file,self::$format))
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

  
}