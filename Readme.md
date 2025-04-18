<!-- Installation Documentation  -->
# Installation 

```sh
git clone https://github.com/lazarusphp/FileHandlerFramework.git
```
alternativly this can be installed using composer
```sh
composer require lazarusphp/filehandlerframework
```

<!-- Bind method Documentation -->
# What is Lazarusphp | FileCrafter?

FileCrafter is a Flat file Data Generator, it has the ability to write data in either json or ini based files.

## Binding a class

in order to give global access to a file a bind method is required this prevents the need to repeat code and saves on time.

A bind method is a way of linking both a filename and writer class together by a single named reference.

once the bind method is called if the file does not exist the method will create it.

**Note**
It is recommended to have the correct permissions set in order for the bind to function.

```php
/**
 * 
 * @method bind // name of the method called in order to bind a file and name to a class.
 * @param string $name // this is the reference which will be used to reference the file.
 * @param string $file // filename associated with flat file.
 * @param array $classname // classname which must be called for the chosen Writer ie : JsonWriter or IniWriter must also be encased in an array
 * 
 */

FileCrafter::bind("Settings","PathToFile",[JsonWriter::class]);
```

<!-- The Generate method -->

## The Generate Method

once a bind has been created it becomes possible to to use the generate method,

the generate Method adds the ability to read edit and delete values from the created file.

```php

/**
 * 
 * @method generate // Setup intial Read Write/ edit options
 * @param string $name // this is the name reference, this name must match the bind name.
 * @param callable $writer // callable function must be callable function.
 * 
 */

FileCrafter::generate("Settings",function($writer)
{
    // Called methods go here
    $writer->save();
});
```

The genrate method once started utilises a internal functions set, remove and save these function cannot be used without using the generate() method

```php

Using Set and Remove Methods

FileCrafter::generate("Settings",function($writer)
{
    // Called methods go here
    /**
     * @method set @param $section,$key,$value;
     * @method remove @param $section,$key
     * @method save
     */ 
    $writer->set("user1","username","jack");
    $writer->set("user1","email","jack@jackswebsite.com");
    $writer->save();
});
```

```php

FileCrafter::generate("Settings",function($writer)
{
    // Called methods go here
    /**
     * @method set @param $section,$key,$value;
     * @method remove @param $section,$key
     * @method save
     */ 
    $writer->remove("user1","email");
    $writer->save();
});
```

**Note**

the save method is required in order to write/update the file.

<!-- The Destroy method -->

## The Destroy method

if the file is no longer reqired the destroy method can be used to delete all traces of the created bind and its file.

```php

/**
 * 
 * @method destroy
 * @param string $name // this is the reference to the created bind.
 */

FileCrafter::destroy("Settings");

```