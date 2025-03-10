<?php

namespace FireCore\DataHandler\Interface;

interface WriterInterface
{
    public function decodeData();

    // Adds a new keypair
    public function set(string $key,string|int $value):void;
    // Removes a specific Key pair
    public function remove(string $key,string|int $value):void;
    // Destroys entire section
    public function destroy():void;
    

}