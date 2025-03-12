<?php
namespace LazarusPhp\FileHandler\Writers;

use LazarusPhp\FileHandler\CoreFiles\WriterCore;
use LazarusPhp\FileHandler\Interface\WriterInterface;

class IniWriter extends WriterCore implements WriterInterface
{

    private static $hasSections;
    public function __construct(string $name)
    {
        self::$name = $name;
        $this->parseFile($name,true);
    }

    public function parseFile($name,$hasSections=true)
    {
        self::$hasSections = $hasSections;
        $file = self::$path[$name];
        self::$data = parse_ini_file($file,self::$hasSections);
    }



    public function decodeData(?string $name=null,$hasSections=true)
    {
        foreach(self::$data as $section => $value)
        {
            foreach($value as $key => $value)
            {
                self::$data[$section][$key] = $value;
            }
        }
    

    public function save(?string $name = null)
    {

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