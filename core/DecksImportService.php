<?php

namespace app\core;

use app\models\DbDeck;

use Exception;

class DecksImportService {

    const MAX_FILE_SIZE = 1000 * 1024; // 1 MB

    private string $tempDirectory;

    public function __construct() {
        $this->tempDirectory = Application::$ROOT_DIR . '\\public\\temp';
    }

    public function importFromCsv(array $file) {
        $uploadedFile = $this->uploadFile($file);

        $csvData = array_map('str_getcsv', file($uploadedFile));

        unlink($uploadedFile);

        if (empty($csvData)) {
            throw new Exception('The uploaded file is either empty or unproperly formatted.');
        }

        foreach ($csvData as $element) {
            $deck = new DbDeck();
            $deck->loadData([
                'title' => $element[0] ?? '',
                'description' => $element[1] ?? '',
                'colors' => $element[2] ?? ''
            ]);

            if ($deck->validateCsv() === false) {
                throw new Exception('The uploaded file is unproperly formatted.');
            }

            if ($deck->save() === false) {
                throw new Exception('Something went wrong while importing the decks.');
            }
        }
    }

    private function uploadFile(array $file) {
        $fileExtension = explode('.', $file['name']);
        $fileActualExtension = strtolower(end($fileExtension));
        $fileSizeInBytes = $file['size'];
        $filePath = $file['tmp_name'];

        if ($file['error'] !== 0) {
            throw new Exception('Something went wrong while uploading the file.');
        }

        if ($fileActualExtension !== 'csv') {
            throw new Exception('The format of the uploaded file is not supported (.csv only).');
        }

        if ($fileSizeInBytes > self::MAX_FILE_SIZE) {
            throw new Exception('The uploaded file (' . $this->convertBytesToReadableFileSize($fileSizeInBytes)
                    . ') exceeds the maximum file size of ' . $this->convertBytesToReadableFileSize(self::MAX_FILE_SIZE) . '.');
        }

        if (!is_dir($this->tempDirectory)) {
            mkdir($this->tempDirectory);
        }

        $newFileName = uniqid('decks') . '.csv';
        $newFilePath = $this->tempDirectory . '\\' . $newFileName;

        move_uploaded_file($filePath, $newFilePath);

        return $newFilePath;
    }

    private function convertBytesToReadableFileSize($bytes, $decimals = 2) {
        $sz = ['B', 'kB', 'MB', 'GB', 'TB', 'PB'];
        $factor = floor((strlen($bytes) - 1) / 3);

        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . ' ' . $sz[$factor];
    }

}