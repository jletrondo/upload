<?php 

use Jletrondo\Upload\Config\Config;
use Jletrondo\Upload\Core\Uploader;
use Jletrondo\Upload\Validators\TypeValidator;

class FileController {
    public function upload() {
        $success = false;
        $error = '';
        $info = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['userfile'])) {
            $config = new Config([
                'upload_path' => __DIR__ . '/../public/uploads',
                'allowed_types' => 'image',
                'max_size' => 4096,
                'overwrite' => false,
            ]);
            $uploader = new Uploader($config);
            $uploader->addValidator(new TypeValidator($config->allowed_types));

            if ($uploader->upload($_FILES['userfile'])) {
                $success = true;
                $info = $uploader->data();
            } else {
                $error = $uploader->error();
            }
        }

        // Now pass $success, $error, and $info to your view/template
        require 'upload_view.php';
    }
}