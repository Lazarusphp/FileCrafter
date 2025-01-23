<?php
namespace FireCore\DataHandler\Writers;
use FireCore\DataHandler\Traits\Resources;

class IniHandler
{
    use Resources;
    public function __construct($path,$name,$hassections)
    {
        $this->name = $name;
        $this->path[$name] = $path;
        $this->hasSections = $hassections;
    }

    public function Test()
    {
        return "Fucktarts";
    }

    public function checkFile($writeFile=false):bool
    {

        if (!is_writable(dirname($this->path[$this->name]))) {
            trigger_error("Directory :". dirname($this->path[$this->name])." is not Writable");
            return false;
        }
        else{
            if(file_exists($this->path[$this->name]))
            {
                return true;
            }
            else
            {
                if($writeFile === true)
                {
                    touch($this->path[$this->name]);
                  return true;
                }
                else
                {
                    trigger_error("File {$this->path[$this->name]} Does not exist");
                    return false;
                }
            }
        }
    }

    public function addToPath($path)
    {

    }
    public function read($name):void
    {
        if (array_key_exists($name, $this->path)) {
            $this->data = parse_ini_file($this->path[$this->name], $this->hasSections);
        }
        else
        {
            trigger_error("File not found", E_USER_WARNING);
          
        }
    }



    public function fetch(string $section, string $name=null):mixed
    {

        if($this->hasSections === true)
        {
            return array_key_exists($name,$this->data[$section]) ? $this->data[$section][$name] : false;
        }
        else
        {
            return array_key_exists($section,$this->data[]) ? $this->data[$section]: false;
        
        }
    }


  public function add(string $section,string | int | array $key, $value=null):void
    {
        if($this->hasSections === true)
        {
            if(is_array($key))
            {
                foreach($key as $key => $value)
                {
                    $this->data[$section][$key] = $value;
                }
            }
            else{
            $this->data[$section][$key] = $value;
            }
        }
        else
        {
            if(is_array($key))
            {
                foreach($key as $value)
                {
                $this->data[$section] = $key;
                }
            }
            else
            {
                $this->data[$section] = $key;
            }   
        }
    }

    public function remove(string $section,string | int $key=null):void
    {
        if($this->hasSections === true)
        { 
            if(array_key_exists($key,$this->data[$section]))
            {
                if(count($this->data[$section]) >= 1) {
                    unset($this->data[$section][$key]);
                } else {
                    unset($this->data[$section]);
                }
            }
            else
            {
                trigger_error("Could not find key");
            }
        }
        else
        {
            if(array_key_exists($section,$this->data))
            {
                if(count($this->data) >= 1) {
                    unset($this->data[$section]);
                } else {
                    unset($this->data);
                }
            }
            else
            {
                trigger_error("Could not find key");
            }
        }
    }


    public function save(string $name,bool $confirm=false)
    {
        $content = '';
        if($this->hasSections)
        {  
            foreach($this->data as $section => $values)
            {
                $content .= '[' . $section . ']' . PHP_EOL;
                foreach($values as $key => $value)
                {
                    $content .= $key . ' = ' . $this->getType($value) . PHP_EOL;
                }
            } 
             $content .= PHP_EOL;
        }
        else
        {
            foreach($this->data as $key => $value)
            {
                $content .= $key . ' = ' . $this->getType($value) . PHP_EOL;
            }
        }


        if($this->data !== null)
        {
           $this->writeFile($this->path[$name],$content);
           
        }
        else
        {
            trigger_error("No Data to Save");
        }
       
    }

}