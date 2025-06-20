<?php
namespace Jletrondo\Upload\Core;

use Jletrondo\Upload\Config\Config;

interface ValidatorInterface {
    public function validate(array $file, \Jletrondo\Upload\Config\Config $config): ?string;
}