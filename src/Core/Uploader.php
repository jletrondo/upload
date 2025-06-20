<?php
namespace Jletrondo\Upload\Core;

use Jletrondo\Upload\Config\Config;

class Uploader
{
    /** @var Config */
    public $config;

    /** @var ValidatorInterface[] */
    protected $validators = [];

    /** @var ProcessorInterface[] */
    protected $processors = [];

    protected $error = "";

    protected $uploaded_data = [];

    public function __construct(Config $config = null)
    {
        $this->config = $config ?: new Config();
    }

    public function setConfig(Config $config)
    {
        $this->config = $config;
        return $this;
    }

    // Allow adding custom validators or processors (for extensibility)
    public function addValidator(ValidatorInterface $validator)
    {
        $this->validators[] = $validator; return $this;
    }
    public function addProcessor(ProcessorInterface $processor)
    {
        $this->processors[] = $processor; return $this;
    }

    public function error()
    {
        return $this->error;
    }

    public function data()
    {
        return $this->uploaded_data;
    }

    /**
     * Main upload logic (pass the $_FILES['input_name'] array)
     */
    public function upload(array $file)
    {
        // Check for upload error
        if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
            $this->error = 'Upload failed. Error code: '.$file['error'];
            return false;
        }

        // Custom validators
        foreach ($this->validators as $validator) {
            if ($msg = $validator->validate($file, $this->config)) {
                $this->error = $msg;
                return false;
            }
        }

        // Validate extension
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $this->config->allowed_types)) {
            $this->error = "File type not allowed: .$ext";
            return false;
        }

        // Validate size
        $size_kb = $file['size'] / 1024;
        if ($size_kb > $this->config->max_size) {
            $this->error = "File too large. Max: {$this->config->max_size} KB";
            return false;
        }

        // Destination folder
        $dest_dir = rtrim($this->config->upload_path, '/').'/';
        if (!is_dir($dest_dir) && !mkdir($dest_dir, 0775, true)) {
            $this->error = "Upload path does not exist and cannot be created.";
            return false;
        }

        // Filename
        $filename = $this->config->file_name ?: $file['name'];
        $target = $dest_dir. $filename;

        // Prevent overwrite?
        if (!$this->config->overwrite && file_exists($target)) {
            $this->error = "File already exists.";
            return false;
        }

        // Move uploaded file
        if (!move_uploaded_file($file['tmp_name'], $target)) {
            $this->error = "Failed to move uploaded file.";
            return false;
        }

        // Post-processors
        foreach ($this->processors as $processor) {
            $processor->process($target, $file, $this->config);
        }

        $this->uploaded_data = [
            'file_name'    => $filename,
            'file_path'    => $target,
            'file_size'    => $file['size'],
            'file_ext'     => $ext,
            'orig_name'    => $file['name'],
            'mime_type'    => $file['type'],
        ];
        return true;
    }
}