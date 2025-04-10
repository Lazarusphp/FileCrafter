<?php

namespace LazarusPhp\FileCrafter\Interface;

interface WriterInterface
{
    public function __construct(string $name);

    public function parseFile($name);
    // Adds a new keypair
    public function set(string $section,string $key,string|int $value);
    // Removes a specific Key pair
    public function remove(string $key);

    public function fetch(?string $section,?string $key);
    

}