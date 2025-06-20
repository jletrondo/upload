<?php
namespace Jletrondo\Upload\Validators;

use Jletrondo\Upload\Core\ValidatorInterface;
use Jletrondo\Upload\Config\Config;

class ImagesValidator implements ValidatorInterface {
    public function validate(array $file, Config $config): ?string {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mime, ['image/jpeg', 'image/png', 'image/gif'])) {
            return "Only image files are allowed.";
        }
        return null;
    }
}