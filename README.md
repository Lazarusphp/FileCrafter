# Installation 

```sh
git clone https://github.com/lazarusphp/FileHandlerFramework.git
```
alternativly this can be installed using composer
```sh
composer require lazarusphp/filehandlerframework
```

## what is the FileHandlerFramework?

FileHandler Framework is a Dependency injection based FileWriter, it currently supports ini and json file.

## Instantiating the class.
the FileWriter itself uses a mix of both static and object based methods. Writer.php which is the entrypoint to the FileWriter requires the file path and the injected class to be tied to a name this can be done using the bind() method.

**set up Binding**

```php
use Lazarusphp\FileHandler\Writer;
use Lazarusphp\FileHandler\Writers\JsonWriter.
Writer::bind("Settings","Path/to/file",[JsonWriter::class]);
```


## Writing and Managing Binded Files.

Writing a to a new binded file is now possible using the generate() method. this method opens up the ability or using set remove and destroy methods and are done like so.

```php

Writer::generate("Settings",function($writer)
{
    $writer->section("system")->set("Drive_path","C:\\users\Home\mike")
    ->set("Username","mike")->remove("Drive_path");
    ->save();
});
```

Writer method set and remove can be chainloaded, when creating a new section the chainload must end in order for a new chainload to be started.

```php

$writer->section("emails")->set("email","name")->set("password","test")->remove("email");
$writer->section("emails")->set("users",500);
// Doing this would not carry the section password over
$writer->section("passwords")->set("hr","wazaadooo")->
section("emails")->set("mine","one");
$writer->save();
```
set() methods key parameter (the second value) supports both integer and string formats.

the save method must be called last in order for everything to be compiled correctly and will save to the file specified in the bind() method;

## Writer Methods

The Writer class comes with some built in methods these include 

* section
* set
* remove
* destroy

all information before being saved is stored in a section and key pair array
```php
self::$data[$section][$key];
```

**The section method**

when writing data adding a section is required in order for the other required methods to function.

```php
Writer("generate",function($writer)
{
    $writer->section("users");
});
```
once a section is created methods such as set, remove and destroy become avaialble.


**The Set Method**
the set method is designed to add a key and its value to the binded File. set can be chainloaded to add multiple Records but must be done on the same section.
```php
Writer("generate",function($writer)
{
    $writer->section("users")->set("username","mike");
});
```

changng the value within set will override the exisiting value and will not create a new one.

**Remove**

like the set method, the remove method removed data from the file, removing the final key will remove the section, the remove method can be chainloaded along with the set method and must be on the same section.

```php
Writer("generate",function($writer)
{
    $writer->section("users")->set("username","mike")->set("email","me@me.com")
    ->remove("username");
});
```

**additional parameters**

the set method supports an additional parameter called preventOverride if set to to true, this value once created will not be changed even if the a new set value with the same name is created

```php
Writer("generate",function($writer)
{
    $writer->section("users")->set("username","mike",true);
});
```




