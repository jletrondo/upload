<?php
namespace Jletrondo\Upload\Core;

interface ProcessorInterface {
    public function process(string $filepath, array $file, \Jletrondo\Upload\Config\Config $config): void;
}