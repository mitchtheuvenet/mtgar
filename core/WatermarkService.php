<?php

namespace app\core;

class WatermarkService {

    const MAX_FILE_SIZE = 1000 * 1024; // 1 MB

    const STAMP_WIDTH = 531;
    const STAMP_HEIGHT = 190;

    private string $imageDirectory;

    public function __construct() {
        $this->imageDirectory = Application::$ROOT_DIR . '\\public_html\\images';
    }

    public function stampImage(array $file) {
        $uploadedImg = $this->uploadImage($file);

        $uploadedImgSize = getimagesize($uploadedImg);

        if ($uploadedImgSize[0] < self::STAMP_WIDTH || $uploadedImgSize[1] < self::STAMP_HEIGHT) {
            throw new Exception('The uploaded image is too small (minimum dimensions: 140 x 50).');
        }

        $stamp = imagecreatefrompng($this->imageDirectory . '\\logo_small.png');

        $image = imagecreatefromjpeg($uploadedImg);
        imagefilter($image, IMG_FILTER_GRAYSCALE);
        imageFilter($image, IMG_FILTER_BRIGHTNESS, 96);

        unlink($uploadedImg);

        imagecopy($image, $stamp, ($uploadedImgSize[0] - self::STAMP_WIDTH) / 2.0,
                ($uploadedImgSize[1] - self::STAMP_HEIGHT) / 2.0, 0, 0, self::STAMP_WIDTH, self::STAMP_HEIGHT);

        header('Content-type: image/png');

        imagepng($image);
    }

    private function uploadImage(array $file) {
        $fileExtension = explode('.', $file['name']);
        $fileActualExtension = strtolower(end($fileExtension));
        $fileSizeInBytes = $file['size'];
        $filePath = $file['tmp_name'];

        if ($file['error'] !== 0) {
            throw new Exception('Something went wrong while uploading the image.');
        }

        if ($fileActualExtension !== 'jpg') {
            throw new Exception('The format of the uploaded file is not supported (.jpg only).');
        }

        if ($fileSizeInBytes > self::MAX_FILE_SIZE) {
            throw new Exception('The uploaded file (' . $this->convertBytesToReadableFileSize($fileSizeInBytes)
                    . ') exceeds the maximum file size of ' . $this->convertBytesToReadableFileSize(self::MAX_FILE_SIZE) . '.');
        }

        $uploadDirectory = $this->imageDirectory . '\\uploads';

        if (!is_dir($uploadDirectory)) {
            mkdir($uploadDirectory);
        }

        $newFileName = uniqid('img') . '.jpg';
        $newFilePath = $uploadDirectory . '\\' . $newFileName;

        move_uploaded_file($filePath, $newFilePath);

        return $newFilePath;
    }

    private function convertBytesToReadableFileSize($bytes, $decimals = 2) {
        $sz = ['B', 'kB', 'MB', 'GB', 'TB', 'PB'];
        $factor = floor((strlen($bytes) - 1) / 3);

        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . ' ' . $sz[$factor];
    }

}