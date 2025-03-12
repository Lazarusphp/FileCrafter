<?php

namespace LazarusPhp\FileHandler\Interface;

interface WriterInterface
{
    public function decodeData();

    // Adds a new keypair
    public function set(string $key,string|int $value);
    // Removes a specific Key pair
    public function remove(string $key);
    // Destroys entire section
    public function destroy():void;
    

}