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
the FileWriter itself uses a mix of both static and object based methods. Writer.php which is the entrypoint to the FileWriter requires the file path and the injected class to be tied to a name this can be done using the bind() method like so.

```php
use Lazarusphp\FileHandler\Writer;
use Lazarusphp\FileHandler\Writers\JsonWriter.
Writer::bind("Settings","Path/to/file",[JsonWriter::class]);
```

Be aware that the class must be encased in and array in order to function.