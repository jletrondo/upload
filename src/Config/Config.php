<?php
namespace Jletrondo\Upload\Config;

class Config
{
    public $upload_path     = './uploads/';
    public $allowed_types   = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'zip'];
    public $max_size        = 2048; // In KB (2MB)
    public $overwrite       = false;
    public $file_name       = null; // Custom filename or null to use upload name

    public function __construct(array $params = [])
    {
        foreach ($params as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }
}