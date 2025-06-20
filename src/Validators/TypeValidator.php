<?php
namespace Jletrondo\Upload\Validators;

use Jletrondo\Upload\Core\ValidatorInterface;
use Jletrondo\Upload\Config\Config;
use Jletrondo\Upload\Config\TypeGroups;

class TypeValidator implements ValidatorInterface
{
    protected $allowed_types = [];
    protected $check_mime;
    
    public function __construct($allowed_types, $check_mime = true)
    {
        // $allowed_types: string ('image' / 'document') or array of extensions
        if (is_string($allowed_types)) {
            if ($allowed_types === 'image') {
                $this->allowed_types = TypeGroups::IMAGES;
            } elseif ($allowed_types === 'document') {
                $this->allowed_types = TypeGroups::DOCUMENTS;
            } else {
                throw new \InvalidArgumentException("Unknown allowed_types group: $allowed_types");
            }
        } elseif (is_array($allowed_types)) {
            $this->allowed_types = $allowed_types;
        } else {
            throw new \InvalidArgumentException("allowed_types must be string or array");
        }
        $this->check_mime = $check_mime;
    }

    public function validate(array $file, Config $config): ?string {
        $filename = $file['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        if (!in_array($ext, $this->allowed_types)) {
            return "File type not allowed for .$ext";
        }
        if ($this->check_mime && isset(TypeGroups::MIME_TYPES[$ext])) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);
            if ($mime !== TypeGroups::MIME_TYPES[$ext]) {
                return "MIME type not allowed for .$ext";
            }
        }
        return null;
    }
}