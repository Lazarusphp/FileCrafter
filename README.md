# Installation 

```sh
git clone https://github.com/mbamber1986/PhpDataHandler.git
```
```sh
composer install firecore/phpdatahandler
```

**Description**
A php Datahandler Designed to Write Data to a file.

**Supported Files**

* Json



# Usage

## Binding a class
in order for a file to be Writeable it is a requirement that the file is binds a name to the path and the class it uses.

```php
 Writer::bind("Settings",ROOT."/Path/to/File",[JsonWriter::class]);
```

in order to then write and save to the file 
```php

Writer::generate("Settings",function($writer)
{
    $writer->section("Name")->add("username","Jack");
    $writer->save();
})

```