<?php

namespace LazarusPhp\FileHandler\CoreFiles;

use Exception;
use RuntimeException;

class WriterCore
{
    // Class implementation goes here
    protected static array $data;
    protected static array $path = [];
    protected static array $class = [];
    protected static $name;
    protected static $format;
    protected $section;
    protected $preventOverwrite = false;

    protected static $modifier;
    protected static $selectedModifiers = [];

    protected static function unset()
    {
        self::$data = [];
        self::$name = "";
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

    public function section(string $section)
    {
        if (self::supportedModifier("generate")) {
            if (!array_key_exists($section, self::$data)) {
                self::$data[$section] = [];
            }

            if (isset(self::$data[$section])) {
                $this->section = $section;
                return $this;
            } else {
                throw new RuntimeException("Failed to find or create Section: $section");
            }
        } else {
            throw new Exception("Cannot load Modifier, Please check supported modifiers or spellings and try again");
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
    public function set(string $key, string|int $value, $preventOverwrite = false)
    {
        if (self::supportedModifier("generate")) {
            if ($this->section) {
                ($preventOverwrite === false) ? self::$data[$this->section][$key] = $value : false;
            } else {
                echo "failed";
                throw new RuntimeException("Failed to Load Section");
            }
            return $this;
        } else {
            throw new Exception("Cannot load Modifier, Please check supported modifiers or spellings and try again");
        }
    }

    public function remove(string $key)
    {
        if (self::supportedModifier("generate")) {
            if ($this->section) {
                if (isset(self::$data[$this->section][$key])) {
                    self::$data[$this->section][$key];
                    unset(self::$data[$this->section][$key]);
                } else {
                    trigger_error(" failed to find $key, cannot Delete key");
                }

                if (count(self::$data[$this->section]) == 0) {
                    unset(self::$data[$this->section]);
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


    public function destroy(): void
    {
        if (self::supportedModifier("generate")) {
            if (isset($this->section)) {
                unset(self::$data[$this->section]);
            } else {
                throw new RuntimeException("Failed to destroy Section : does not exist");
            }
        } else {
            throw new Exception("Cannot load Modifier, Please check supported modifiers or spellings and try again");
        }
    }
}
