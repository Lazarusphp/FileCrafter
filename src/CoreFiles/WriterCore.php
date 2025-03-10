<?php

namespace FireCore\DataHandler\CoreFiles;
use RuntimeException;

class WriterCore
{
    // Class implementation goes here
    protected static array $data;
    protected static array $path = [];
    protected static array $class = [];
    protected static $name;
    protected static $modifierFlag;
    protected $section;
    protected $preventOverwrite = false;

    protected static function unset()
    {
            self::$data = [];
    }

    protected static function detectExtention($file,$format)
    {
        $pathinfo = pathinfo($file);
        $extention = $pathinfo["extension"];
        if($extention === $format)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    protected static function writeFile(string $file, string | int | array $contents)
    {
        return file_put_contents($file,$contents);
    }

    protected static function hasFile($file)
    {
        return file_exists($file) && is_writable($file) ? true : false;
    }

 

    protected static function checkFile($path): bool
    {
        if (!is_writable(dirname($path))) {
            trigger_error("Directory: " . dirname($path) . " is not writable", E_USER_WARNING);
            return false;
        } else {
            if (file_exists($path)) {
                return true;
            } else {
                return false;
            }
        }
    }

    // Public methods shared

    public function section(string $section)
    {
        if (!array_key_exists($section, self::$data)) {
            self::$data[$section] = [];
        }

        if (isset(self::$data[$section])) {
            $this->section = $section;
            return $this;
        } else {
            throw new RuntimeException("Failed to find or create Section: $section");
        }
    }

    
    public function preventOverwrite()
    {
        $this->preventOverwrite = true;
       return $this;
    }

    /**
     * Set the value of the section $this->section is required
     *
     * @param string $key
     * @param string|integer $value
     * @param boolean $preventOverwrite
     * @return void
     */
    public function set(string $key,string|int $value,$preventOverwrite=false):void
    {
        if($this->section)
        {
        ($preventOverwrite === false) ? self::$data[$this->section][$key] = $value : false;
        }
        else
        {
            echo "failed";
            throw new RuntimeException("Failed to Load Section");
        }
    }

    public function update(string $key,string|int $value):void
    {
        if($this->section)
        {
            (isset(self::$data[$this->section][$key])) ? self::$data[$this->section][$key] = $value : throw new RuntimeException("Failed to update Key pair doesnt Exists");   
        }
          else
        {
            throw new RuntimeException("Failed to Load Section");
        }
    }

    public function remove(string $key,string|int $value):void
    {
        if($this->section)
        {
            if(isset(self::$data[$this->section][$key]))
            {
                unset(self::$data[$this->section][$key]);
            }
            else
            {
                throw new RuntimeException("Failed to Remove Key pair doesnt Exists");
            }
        }
    }


    public function destroy():void
    {
        if(isset($this->section))
        {
            unset(self::$data[$this->section]);
        }
        else
        {
            throw new RuntimeException("Failed to destroy Section : does not exist");
        }
    }
}