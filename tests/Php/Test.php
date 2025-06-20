<?php
// Autoload required (e.g. Composer's autoloader)
require_once 'vendor/autoload.php';

use Jletrondo\Upload\Config\Config;
use Jletrondo\Upload\Core\Uploader;
use Jletrondo\Upload\Validators\TypeValidator;

// Example HTML form (render with your favorite template engine)
?>
<form method="post" enctype="multipart/form-data">
    <input type="file" name="thefile">
    <button type="submit">Upload</button>
</form>
<?php

// Controller code
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['thefile'])) {
    // Configure upload
    $config = new Config([
        'upload_path' => __DIR__ . '/uploads',
        'max_size' => 2048,              // 2 MB
        'overwrite' => false,
        // 'file_name' could be set if you want to force a name
    ]);

    $uploader = new Uploader($config);

    // For example: accept only standard documents
    $uploader->addValidator(new TypeValidator('document'));

    // Or for images: $uploader->addValidator(new TypeValidator('image'));
    // Or combine: $uploader->addValidator(new TypeValidator(['pdf', 'txt', 'jpg']));

    if ($uploader->upload($_FILES['thefile'])) {
        // Upload successful!
        $data = $uploader->data();
        echo "<p>Upload success!</p>";
        echo "<pre>" . print_r($data, true) . "</pre>";
        // $data['file_path'] etc. available for DB save, etc.
    } else {
        // Show error
        echo "<p>Error: " . $uploader->error() . "</p>";
    }
}