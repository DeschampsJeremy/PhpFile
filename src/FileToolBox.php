<?php

namespace DeschampsJeremy;

use RecursiveArrayIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ZipArchive;

class FileToolBox
{

    //////////////////////////////////////////////////////////////////////////////
    //TYPE
    //////////////////////////////////////////////////////////////////////////////

    /**
     * Check if it's file root (file or folder)
     * @return bool True if it's file, else return false
     */
    protected static function isFileTool(string $root): bool
    {
        return is_file($root);
    }

    /**
     * Check if it's folder root (file or folder)
     * @return bool True if it's folder, else return false
     */
    protected static function isFolderTool(string $root): bool
    {
        return is_dir($root);
    }

    /**
     * Get mime type from root (file or folder)
     * @return string|null The mime type, or 'directory', or null if no exist
     */
    protected static function typeTool(string $root): ?string
    {
        return (self::isFileTool($root) || self::isFolderTool($root)) ? mime_content_type($root) : null;
    }

    /**
     * Get the root size (file or folder)
     * @return float Size in Mo
     */
    protected static function sizeTool(string $root): float
    {
        $recursiveCounterSizeMo = 0;
        if (self::isFolderTool($root)) {
            foreach (self::folderScanTools($root) as $files) {
                $recursiveCounterSizeMo += self::sizeTool($root . '/' . $files);
            }
            return $recursiveCounterSizeMo;
        } else {
            return round(filesize($root) / 1000000, 2);
        }
    }

    /**
     * Get the root infos (file or folder)
     * @return array|null Return ['type' => __TYPE_MIME__, 'sizeMo' => __SIZE__]
     */
    protected static function infoTools(string $root): ?array
    {
        return (self::isFileTool($root) || self::isFolderTool($root)) ? [
            'type' => self::typeTool($root),
            'sizeMo' => self::sizeTool($root),
        ] : null;
    }

    //////////////////////////////////////////////////////////////////////////////
    //RESIZE
    //////////////////////////////////////////////////////////////////////////////

    /**
     * Check if it's image PNG or JPG/JPEG from root (use from file or folder)
     * @return bool True if it's, else return false
     */
    protected static function isImageResizeTool(string $root): bool
    {
        $typeMime = self::typeTool($root);
        return ($typeMime === "image/png" || $typeMime === "image/jpeg");
    }

    //////////////////////////////////////////////////////////////////////////////
    //JSON
    //////////////////////////////////////////////////////////////////////////////

    /**
     * Create a JSON file
     */
    protected static function jsonAddTool(string $root, array $arrays): void
    {
        $fp = fopen($root . '.json', 'w');
        fwrite($fp, json_encode($arrays));
        fclose($fp);
    }

    /**
     * Read a JSON file and return an array
     * @return array|null
     */
    protected static function jsonReadTool(string $root): ?array
    {
        $jsonIterator = new RecursiveIteratorIterator(
            new RecursiveArrayIterator(json_decode(file_get_contents($root . '.json', true))),
            RecursiveIteratorIterator::SELF_FIRST
        );
        $array = [];
        foreach ($jsonIterator as $key => $value) {
            $array[$key] = $value;
        }
        return $array;
    }

    //////////////////////////////////////////////////////////////////////////////
    //ZIP
    //////////////////////////////////////////////////////////////////////////////

    /**
     * Compress root to ZIP format, keep the original (file or folder)
     */
    protected static function zipCompressTool(string $oldRoot, string $newRoot, string $name): void
    {
        if (self::isFileTool($oldRoot) || self::isFolderTool($oldRoot)) {
            $rootPath = realpath($oldRoot);
            $zip = new ZipArchive();
            $zip->open($newRoot . '/' . $name . '.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE);

            if (!is_dir($oldRoot)) {
                $fileRootFormat = str_replace('\\', '/', $rootPath);
                $fileRoots = explode("/", $fileRootFormat);
                $fileName = end($fileRoots);
                $zip->addFile($rootPath, $fileName);
            } else {
                $files = new RecursiveIteratorIterator(
                    new RecursiveDirectoryIterator($rootPath),
                    RecursiveIteratorIterator::LEAVES_ONLY
                );
                foreach ($files as $name => $file) {
                    if (!$file->isDir()) {
                        $filePath = $file->getRealPath();
                        $relativePath = substr($filePath, strlen($rootPath) + 1);
                        $zip->addFile($filePath, $relativePath);
                    }
                }
            }

            $zip->close();
        }
    }

    /**
     * Unpress ZIP root to folder
     */
    protected static function zipUnpressTool(string $oldRoot, string $newRoot, string $name): void
    {
        $newFileRoot = $newRoot . '/' . $name;
        self::fileDeleteTool($newFileRoot);
        $zip = new ZipArchive;
        $res = $zip->open($oldRoot);
        if ($res === true) {
            $zip->extractTo($newFileRoot);
            $zip->close();
        }
    }

    //////////////////////////////////////////////////////////////////////////////
    //BASE 64
    //////////////////////////////////////////////////////////////////////////////

    /**
     * Get the base64 string file infos
     * @return array|null Return ['type' => __TYPE_MIME__, 'sizeMo' => __SIZE__]
     */
    protected static function base64InfoTools(string $base64): ?array
    {
        $file = base64_decode(self::base64CleanPrefixTool($base64));
        $f = finfo_open();
        return [
            'type' => finfo_buffer($f, $file, FILEINFO_MIME_TYPE),
            'sizeMo' => round(floatval(strlen($file)) / 1000000, 2),
        ];
    }

    /**
     * Clean a base 64 prefix
     * @return string The clean file string
     */
    protected static function base64CleanPrefixTool(string $base64): string
    {
        $searchBase64 = "base64,";
        $searchBase64Position = strpos($base64, $searchBase64);
        if ($searchBase64Position) {
            return substr($base64, $searchBase64Position + strlen($searchBase64));
        } else {
            return $base64;
        }
    }

    //////////////////////////////////////////////////////////////////////////////
    //FILE
    //////////////////////////////////////////////////////////////////////////////

    /**
     * Resize an image file (keep automatically the rate width/height)
     * @return string The image string resize
     */
    protected static function imageResizeTool(string $imageFile, int $newFileWidth): string
    {
        list($fileWidth, $fileHeight) = getimagesize($imageFile);
        $fileRate = round($fileWidth / $fileHeight, 2);
        $newFileHeight = $newFileWidth / $fileRate;
        switch (mime_content_type($imageFile)) {
            case "image/png":
                $fileRes = imagecreatefrompng($imageFile);
                break;
            default:
                $fileRes = imagecreatefromjpeg($imageFile);
        }
        $newFileRes = imagecreatetruecolor($newFileWidth, $newFileHeight);
        imagecopyresampled($newFileRes, $fileRes, 0, 0, 0, 0, $newFileWidth, $newFileHeight, $fileWidth, $fileHeight);
        ob_start();
        switch (mime_content_type($imageFile)) {
            case "image/png":
                imagepng($newFileRes);
                break;
            default:
                imagejpeg($newFileRes);
        }
        $imageString = ob_get_clean();
        return $imageString;
    }

    /**
     * Get a file to base64 string
     * @return string|null File in base64 string
     */
    protected static function fileDownloadTool(string $file): ?string
    {
        if (self::isFileTool($file)) {
            $data = file_get_contents($file);
            $type = mime_content_type($file);
            return 'data:' . $type . ';base64,' . base64_encode($data);
        }
        return null;
    }

    /**
     * Create a file (or replace if exist)
     */
    protected static function fileAddTool(string $root, string $content): void
    {
        $explodes = explode("/", $root);
        if (strstr(end($explodes), ".")) {
            $handle = fopen($root, 'w');
            fwrite($handle, $content);
            fclose($handle);
        }
    }

    /**
     * Remove a file
     */
    protected static function fileDeleteTool(string $file): void
    {
        if (self::isFileTool($file)) {
            unlink($file);
        }
    }

    /**
     * Rename file (or replace if exist)
     */
    protected static function fileRenameTool(string $oldFile, string $newFile): void
    {
        if (self::isFileTool($oldFile)) {
            rename($oldFile, $newFile);
        }
    }

    /**
     * Copy file (or replace if exist)
     */
    protected static function fileCopyTool(string $oldFile, string $newFile): void
    {
        if (self::isFileTool($oldFile)) {
            copy($oldFile, $newFile);
        }
    }

    /**
     * Move file (or replace if exist)
     */
    protected static function fileMoveTool(string $oldFile, string $newFile): void
    {
        if (self::isFileTool($oldFile)) {
            self::fileCopyTool($oldFile, $newFile);
            self::fileDeleteTool($oldFile);
        }
    }

    //////////////////////////////////////////////////////////////////////////////
    //FOLDER
    //////////////////////////////////////////////////////////////////////////////

    /**
     * Get a folder content
     * @return array|[] The folder content
     */
    protected static function folderScanTools(string $folder): array
    {
        $returns = [];
        if (self::isFolderTool($folder)) {
            foreach (scandir($folder . '/') as $file) {
                if ($file !== "." && $file !== "..") {
                    $returns[] = $file;
                }
            }
        }
        return $returns;
    }

    /**
     * Clean folder
     */
    protected static function folderCleanTool(string $folder): void
    {
        foreach (self::folderScanTools($folder) as $file) {
            $newRoot = $folder . '/' . $file;
            if (self::isFolderTool($newRoot)) {
                self::folderCleanTool($newRoot);
                rmdir($newRoot);
            } else {
                self::fileDeleteTool($newRoot);
            }
        }
    }

    /**
     * Create a folder (or replace if exist)
     */
    protected static function folderAddTool(string $folder): void
    {
        $explodes = explode("/", $folder);
        if (!(strstr(end($explodes), "."))) {
            self::folderDeleteTool($folder);
            mkdir($folder);
        }
    }

    /**
     * Remove a folder
     */
    protected static function folderDeleteTool(string $folder): void
    {
        if (self::isFolderTool($folder)) {
            self::folderCleanTool($folder);
            rmdir($folder);
        }
    }

    /**
     * Rename folder (or replace if exist)
     */
    protected static function folderRenameTool(string $oldFolder, string $newFolder): void
    {
        if (self::isFolderTool($oldFolder)) {
            self::folderDeleteTool($newFolder);
            rename($oldFolder, $newFolder);
        }
    }

    /**
     * Copy folder (or replace if exist)
     */
    protected static function folderCopyTool(string $oldFolder, string $newFolder): void
    {
        if (self::isFolderTool($oldFolder)) {
            self::folderAddTool($newFolder);
            foreach (self::folderScanTools($oldFolder) as $file) {
                $testOldRoot = $oldFolder . '/' . $file;
                $testNewRoot = $newFolder . '/' . $file;
                if (self::isFolderTool($testOldRoot)) {
                    self::folderCopyTool($testOldRoot, $testNewRoot);
                } else {
                    self::fileCopyTool($testOldRoot, $testNewRoot);
                }
            }
        }
    }

    /**
     * Move folder (or replace if exist)
     */
    protected static function folderMoveTool(string $oldFolder, string $newFolder): void
    {
        if (self::isFolderTool($oldFolder)) {
            self::folderCopyTool($oldFolder, $newFolder);
            self::folderDeleteTool($oldFolder);
        }
    }
}
