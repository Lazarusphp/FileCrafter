<?php
namespace LazarusPhp\FileHandler\Writers;

use LazarusPhp\FileHandler\CoreFiles\WriterCore;
use LazarusPhp\FileHandler\Interface\WriterInterface;

class IniWriter extends WriterCore  implements WriterInterface
{

    private static $hasSections;


    public function parseFile($hasSections=true)
    {
        $name = self::$name;
        self::$hasSections = $hasSections;
        $file = self::$path[$name];
        self::$data = parse_ini_file($file,self::$hasSections);
    }



    public function decodedata(?string $name=null,$hasSections=true)
    {
        self::$hasSections = $hasSections;
        $name = $name ?? self::$name;
        $data = parse_ini_file(self::$path[$name],self::$hasSections);

        foreach($data as $section => $value)
        {
            foreach($value as $key => $value)
            {
                self::$data[$section][$key] = $value;
            }
        }
    }

    public function fetch(?string $section=null,?string $key=null)
    {
        if(isset(self::$data[$section]) && isset(self::$data[$section][$key]))
        {
            return self::$data[$section][$key];
        }
        else
        {
        $data = (object) self::$data;
        foreach ($data as $section => $values) {
            $data->$section = (object) $values;
        }
        return $data;
        }
    }

    public function save(?string $name = null)
    {
  
    // exit(print_r(self::$data));
    $name = $name ?? self::$name;
    $file = self::$path[$name];
    if(self::detectExtention($file,"ini"))
    {
        $content = "";
        if (self::$hasSections) {
            foreach (self::$data as $section => $value) {
                $content .= "[$section]\n";
                foreach ($value as $key => $val) {
                    $content .= "$key = " . (is_numeric($val) ? $val : '"' . addslashes($val) . '"') . "\n";
                }
            }
        } else {
            foreach (self::$data as $key => $val) {
                $content .= "{$key}={$val} \n";
            }
        }
        // echo $content;
        file_put_contents($file, $content);

    }
    else
    {
        trigger_error("Failed to save file : ".self::$path[$name]."Incompatible file");
    }
    }


}