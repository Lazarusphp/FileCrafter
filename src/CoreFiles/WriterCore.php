<?php

namespace LazarusPhp\FileCrafter\CoreFiles;

use Exception;
use RuntimeException;

class WriterCore
{
    // Class implementation goes here
    protected static array $data;
    protected static array $path = [];
    protected static array $class = [];
    protected static $errors = [];
    protected static $name;
    protected static $format;
    protected $section;
    protected static  $preventOverwrite = [];

    protected static $modifier;
    protected static $selectedModifiers = [];

    protected static function unset()
    {
        self::$data = [];
        self::$name = "";
        self::$preventOverwrite = [];
    }

    protected static function bindWritable(string $file)
    {
        $exploded = explode("/",$file);
        array_pop($exploded);
        $path = implode("/",$exploded);
        if(is_dir($path) && !is_writable($path) or (!is_dir($path)))
        {
            self::$errors[] = "$path doesnt exist or is not writable";
        }
    }



    protected static function supportedModifier(...$modifers)
    {
        if (in_array(self::$modifier, $modifers)) {
            return true;
        } else {
            return false;
        }
    }


    protected static function getPathExtention($file)
    {
        return pathinfo($file)["extension"];
    }

    protected static function detectExtention($file, $format)
    {
        $extention = self::getPathExtention($file);
        if ($extention === $format) {
            return true;
        } else {
            return false;
        }
    }

    protected static function writeFile(string $file, string | int | array $contents)
    {
        return file_put_contents($file, $contents);
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



    public function preventOverwrite(string $section,...$keys)
    {
        if(self::supportedModifier("generate")) {
        foreach($keys as $key)
        {
            if(isset($this->fetch()->$section->$key))
            self::$preventOverwrite[$section][$key] = true;
        }
    }
    else
        {
            throw new Exception("Cannot load Modifier, Please check supported modifiers or spellings and try again");
        }
        // unset After use
        // self::$preventOverwrite = [];
    }

    /**
     * Set the value of the section $this->section is required
     *
     * @param string $key
     * @param string|integer $value
     * @param boolean $preventOverwrite
     * @return void
     */
    public function set(string $section,string $key, string|int $value)
    {
        if (self::supportedModifier("generate")) {
            if(isset(self::$preventOverwrite[$section][$key]))
            {
                $preventOverwrite = true;
            }
            else
            {
                $preventOverwrite = false;
            }
            ($preventOverwrite === false) ? self::$data[$section][$key] = $value : false;
        } else {
            throw new Exception("Cannot load Modifier, Please check supported modifiers or spellings and try again");
        }
    }

    public function remove(string $section,string $key)
    {
        if (self::supportedModifier("generate")) {
            if ($this->section) {
                if (isset(self::$data[$ssection][$key])) {
                    self::$data[$section][$key];
                    unset(self::$data[$section][$key]);
                } else {
                    trigger_error(" failed to find $key, cannot Delete key");
                }

                if (count(self::$data[$section]) == 0) {
                    unset(self::$data[$section]);
                }
            }
            return $this;
        } else {
            throw new Exception("Cannot load Modifier, Please check supported modifiers or spellings and try again");
        }
    }

    public function fetch(?string $section = null, ?string $key = null)
    {
        if (self::supportedModifier("generate")) {
            if (isset(self::$data[$section]) && isset(self::$data[$section][$key])) {
                return self::$data[$section][$key];
            } else {
                $data = (object) self::$data;
                foreach ($data as $section => $values) {
                    $data->$section = (object) $values;
                }
                return $data;
            }
        } else {
            throw new Exception("Cannot load Modifier, Please check supported modifiers or spellings and try again");
        }
    }
}
