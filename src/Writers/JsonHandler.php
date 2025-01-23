<?php
namespace FireCore\DataHandler\Writers;
use FireCore\DataHandler\Traits\Resources;


 class JsonHandler 
{

    use Resources;


    public function setpath($pathname,$path,$hasSections=true)
    {
        DataWriter::setPath($pathname,$pathname,$hassections);
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
                    file_put_contents($this->path[$this->name],"{".PHP_EOL."}");
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

    public function read(string $name, bool $assoc = false, int $depth = 512, int $flags = 0)
    {

        $this->assoc = $assoc;
        if (array_key_exists($name, $this->path)) {
            $jsonContent = file_get_contents($this->path[$name]);
            $this->data = json_decode($jsonContent, $assoc, $depth, $flags);
        } else {
            trigger_error("File not found", E_USER_WARNING);
            return null;
        }
    }



    public function fetch(string $section, string $name):mixed
    {

        if($this->hasSections === true)
            {
                return $this->assoc === false ? $this->data->$section->$name : $this->data[$section][$name];
            }
            else
            {
                return $this->assoc === false ? $this->data->$section : $this->data[$section];
            }
        
    }


    //TODO rename Add to something else. 
  public function add(string $section, string | int | array  $key, $value = null)
    {
        // echo count($this->data[$section]);
        if ($this->hasSections === true) {
            if ($this->assoc === false) {

                if (!isset($this->data->$section) || !is_object($this->data->$section)) {
                    $this->data->$section = new \stdClass;
                }

                if(is_array($key))
                {
                    foreach($key as $key => $value)
                    {
                        $this->data->$section->$key = $value;
                    }
                }
                else
                {
                    $this->data->$section->$key = $value;
                }
                
                
            } else {

                if(is_array($key))
                {
                    foreach($key as $key => $value)
                    {
                        $this->data[$section][$key] = $value;
                    }
                }
                else
                {
                    $this->data[$section][$key] = $value;
                }
                $this->data[$section][$key] = $value;
            }
        } else {
            if ($this->assoc === false) {
                if (!isset($this->data) || !is_object($this->data)) {
                    $this->data = new \stdClass;
                }

                   if(is_array($key))
                {
                    foreach($key as $value)
                    {
                        $this->data->$section = $value;
                    }
                }
                else
                {
                    $this->data->$section = $value;
                }
        
            } else {

                
                if(is_array($key))
                {
                    foreach($key as $value)
                    {
                       $this->data[$section] = $value;
                    }
                }
                else
                {
                    $this->data[$section] = $value;
                }
               
            }
        }
    }

 
    
    // TODO Add option to remove multiple values;
    public function remove(string $section, string | int | array$key=null)
    {
        // check if there are sections

        if($this->hasSections === true)
        { 
            if(is_array($key))
            {
                foreach($key as $key)
                {
                    unset($this->data[$section][$key]);
                }
            }

            elseif(array_key_exists($key,$this->data[$section]))
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

            if(is_array($key))
            {
                foreach($section as $section)
                {
                    unset($this->data[$section]);
                }
            }
            elseif(array_key_exists($section,$this->data))
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


    public function save($name)
    {
        $content = [];
        if ($this->hasSections === true) {
            foreach ($this->data as $section => $values) {
            $content[$section] = [];
            foreach ($values as $key => $value) {
                $content[$section][$key] = $value;
            }
            }
        } else {
            foreach ($this->data as $key => $value) {
            $content[$key] = $value;
            }
        }

        if(is_writable($this->path[$name])){
         $saved = json_encode($content, JSON_PRETTY_PRINT);
        $this->writeFile($this->path[$name],$saved);
        }
    }

}