<?php

namespace FireCore\DataHandler;

use FireCore\DataHandler\Injections\CallIni;

class Handler
{

    private $path = [];
    private $data = [];
    private $name;
    private $class;
    private $hasSection = false;
    private $continue;

    private $extension;

    public function __construct($path, $name, array $class, $hasSection = true)
    {


        $count = count($class);
        if ($count === 1) {
            if (class_exists($class[0])) {
                $this->continue = true;
                $this->class = new $class[0]($path, $name, $hasSection);
            } else {
                trigger_error("Class " . $class[0] . "Does not exist");
            }
        } else {
            trigger_error("No Class, or too many classes Added");
            exit();
        }
    }

    
    public function checkFile(...$args)
    {
       
            if (method_exists($this->class, "checkFile")) {
                return call_user_func_array([$this->class, "checkFile"], $args);
            }
            return null;
     
    }

    public function read(...$args)
    {
       
            if (method_exists($this->class, "read")) {
                return call_user_func_array([$this->class, "read"], $args);
            }
            return null;
     
    }

    public function remove(...$args)
    {
             if (method_exists($this->class, "remove")) {
                return call_user_func_array([$this->class, "remove"], $args);
            }
            return null;
        
    }


    public function fetch(...$args)
    {

           if (method_exists($this->class, "fetch")) {
                return call_user_func_array([$this->class, "fetch"], $args);
            }
            return null;
      
    }

    public function add(...$args)
    {
           if (method_exists($this->class, "add")) {
                return call_user_func_array([$this->class, "add"], $args);
            }
            return null;
      
    }


    public function group(...$args)
    {
        
            if (method_exists($this->class, "groupadd")) {
                return call_user_func_array([$this->class, "groupadd"], $args);
            }
            return null;
    
    }


    public function save(...$args)
    {
          if (method_exists($this->class, "save")) {
                return call_user_func_array([$this->class, "save"], $args);
            }
            return null;
       
    }
    
}
