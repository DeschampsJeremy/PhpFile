<?php

namespace DeschampsJeremy;

class FileService extends FileToolBox
{
    const UPLOAD_FOLDER_ROOT = "./../uploaded/";
    private $caches = [
        ['prefix' => '', 'size' => 1000],
        ['prefix' => 'c1_', 'size' => 800],
        ['prefix' => 'c2_', 'size' => 200],
    ];
    private $folder;
    private $folderRoot;

    function __construct(string $folder)
    {
        //Secure
        $folder = str_replace(".", "", $folder);

        //Define vars
        $this->folder = $folder . "/";
        $this->folderRoot = self::UPLOAD_FOLDER_ROOT . $this->folder;
        $scans = parent::folderScanTools(self::UPLOAD_FOLDER_ROOT);

        //If files uploaded folder not exist create it
        if (!parent::isFolderTool(self::UPLOAD_FOLDER_ROOT)) {
            parent::folderAddTool(self::UPLOAD_FOLDER_ROOT);
        }

        //If "tmp" folder not exist create it
        if (!in_array("tmp", $scans)) {
            parent::folderAddTool(self::UPLOAD_FOLDER_ROOT . "tmp");
        }

        //If target folder not exist create it
        if (!in_array($folder, $scans)) {
            parent::folderAddTool($this->folderRoot);
        }
    }

    //////////////////////////////////////////////////////////////////////////////
    //RESIZE
    //////////////////////////////////////////////////////////////////////////////

    /**
     * Check if it's image PNG or JPG/JPEG from root (use from file or folder)
     * @return bool True if it's, else return false
     */
    function isImageResize(string $root): bool
    {
        $root = str_replace(".", "", $root);
        return parent::isImageResizeTool($this->folderRoot . $root);
    }

    //////////////////////////////////////////////////////////////////////////////
    //TYPE
    //////////////////////////////////////////////////////////////////////////////

    /**
     * Check if it's file root (use from file or folder)
     * @return bool True if it's file, else return false
     */
    function isFile(string $root): bool
    {
        $root = str_replace(".", "", $root);
        return parent::isFileTool($this->folderRoot . $root);
    }

    /**
     * Check if it's folder root (use from file or folder)
     * @return bool True if it's folder, else return false
     */
    function isFolder(string $root): bool
    {
        $root = str_replace(".", "", $root);
        return parent::isFolderTool($this->folderRoot . $root);
    }

    /**
     * Get the root infos (use from file or folder)
     * @return array|null Return ['type' => __TYPE_MIME__, 'sizeMo' => __SIZE__]
     */
    function infos(string $root): ?array
    {
        $root = str_replace(".", "", $root);
        return parent::infoTools($this->folderRoot . $root);
    }

    //////////////////////////////////////////////////////////////////////////////
    //JSON
    //////////////////////////////////////////////////////////////////////////////

    /**
     * Create a JSON file
     */
    function jsonAdd(string $root, array $arrays): void
    {
        $root = str_replace(".", "", $root);
        parent::jsonAddTool($this->folderRoot . $root, $arrays);
    }

    /**
     * Read a JSON file and return an array
     * @return array|null
     */
    function jsonRead(string $root): ?array
    {
        $root = str_replace(".", "", $root);
        return parent::jsonReadTool($this->folderRoot . $root);
    }

    //////////////////////////////////////////////////////////////////////////////
    //ZIP
    //////////////////////////////////////////////////////////////////////////////

    /**
     * Compress root to ZIP format, recursive, keep the original, replace if exist (use from file or folder)
     */
    function zipCompress(string $oldRoot, string $newRoot, string $name): void
    {
        $root = str_replace(".", "", $oldRoot);
        parent::zipCompressTool($this->folderRoot . $oldRoot, $this->folderRoot . $newRoot, $name);
    }

    /**
     * Unpress ZIP root to folder
     */
    function zipUnpress(string $oldRoot, string $newRoot, string $name): void
    {
        $root = str_replace(".", "", $oldRoot);
        parent::zipUnpressTool($this->folderRoot . $oldRoot, $this->folderRoot . $newRoot, $name);
    }

    //////////////////////////////////////////////////////////////////////////////
    //BASE 64
    //////////////////////////////////////////////////////////////////////////////

    /**
     * Get the base64 string file infos, generate a base64 file on [base64.guru](https://base64.guru/converter/encode/file)
     * @return array|null Return ['type' => __TYPE_MIME__, 'sizeMo' => __SIZE__]
     */
    static function base64Infos(string $base64): ?array
    {
        return parent::base64InfoTools($base64);
    }

    //////////////////////////////////////////////////////////////////////////////
    //FILE & FOLDER
    //////////////////////////////////////////////////////////////////////////////

    /**
     * Get a file to base64 string, in folder case it's will create a zip file
     * @return string|null File in base64 string
     */
    function download(string $root): ?string
    {
        if (parent::isFileTool($this->folderRoot . $root)) {
            return parent::fileDownloadTool($this->folderRoot . $root);
        } else {
            if (strstr($root, "/")) {
                $explodes = explode("/", $root);
                $folderName = end($explodes);
            } else {
                $folderName = $root;
            }
            parent::zipCompressTool($this->folderRoot . $root, self::UPLOAD_FOLDER_ROOT . 'tmp', $folderName);
            $fileString = parent::fileDownloadTool(self::UPLOAD_FOLDER_ROOT . 'tmp/' . $folderName . '.zip');
            parent::fileDeleteTool(self::UPLOAD_FOLDER_ROOT . 'tmp/' . $folderName . '.zip');
            return $fileString;
        }
    }

    /**
     * Get a folder content
     * @return array|[] The folder content
     */
    function folderScans(string $folder): array
    {
        $folder = str_replace(".", "", $folder);
        return parent::folderScanTools($this->folderRoot . $folder);
    }

    /**
     * Clean folder, recursice delete content
     */
    function folderClean(string $folder): void
    {
        $folder = str_replace(".", "", $folder);
        parent::folderCleanTool($this->folderRoot . $folder);
    }

    /**
     * Create a file from base64 string, generate images caches, replace if exist, generate a base64 file on [base64.guru](https://base64.guru/converter/encode/file)
     * @return string The file root
     */
    function fileAdd(string $base64, string $name, string $newRoot = null, string $rename = null): string
    {
        $newRoot = str_replace(".", "", $newRoot);

        //Define vars
        $cleanBase64 = parent::base64CleanPrefixTool($base64);
        $extands = explode(".", $name);
        $extand = end($extands);
        $fileString = base64_decode($cleanBase64);
        $newRoot = (empty($newRoot)) ? "" : $newRoot . '/';

        //Select new name or generate it
        if (!empty($rename)) {
            $newName = $rename . '.' . $extand;
        } else {
            $newName = date("YmdHis") . "_" . uniqid() . '.' . $extand;
        }

        //Create tmp file
        $rootTmp = self::UPLOAD_FOLDER_ROOT . "tmp/" . $newName;
        parent::fileAddTool($rootTmp, $fileString);

        //Create file
        if (parent::isImageResizeTool($rootTmp)) {

            //Create resize caches copies
            foreach ($this->caches as $caches) {
                parent::fileAddTool($this->folderRoot . $newRoot . $caches['prefix'] . $newName, parent::imageResizeTool($rootTmp, $caches['size']));
            }
        } else {
            parent::fileAddTool($this->folderRoot . $newRoot . $newName, $fileString);
        }

        //Delete tmp file
        parent::fileDeleteTool($rootTmp);

        //Return
        return $newName;
    }

    /**
     * Create a folder (or replace if exist)
     */
    function folderAdd(string $folder): void
    {
        $folder = str_replace(".", "", $folder);
        if (!empty($folder)) {
            parent::folderAddTool($this->folderRoot . $folder);
        }
    }

    /**
     * Remove a root (use from file or folder)
     */
    function delete(string $root): void
    {
        $root = str_replace(".", "", $root);
        if (!empty($root)) {
            $absoluteRoot = $this->folderRoot . $root;
            if (!parent::isFileTool($absoluteRoot)) {
                parent::folderDeleteTool($absoluteRoot);
            } else {
                if (strstr($root, "/")) {
                    $explodes = explode("/", $root);
                    $rootName = "";
                    foreach ($explodes as $key => $value) {
                        if ($value != end($explodes)) {
                            $rootName .= $value . '/';
                        }
                    }
                    $fileName = end($explodes);
                } else {
                    $rootName = "";
                    $fileName = $root;
                }
                if (parent::isImageResizeTool($absoluteRoot)) {
                    foreach ($this->caches as $caches) {
                        parent::fileDeleteTool($this->folderRoot . $rootName . $caches['prefix'] . $fileName);
                    }
                } else {
                    parent::fileDeleteTool($absoluteRoot);
                }
            }
        }
    }

    /**
     * Rename a root (use from file or folder), or replace if exist
     */
    function rename(string $oldRoot, string $newRoot): void
    {
        $oldRoot = str_replace(".", "", $oldRoot);
        $newRoot = str_replace(".", "", $newRoot);
        if (!empty($oldRoot)) {
            if (parent::isFileTool($this->folderRoot . $oldRoot)) {
                parent::fileRenameTool($this->folderRoot . $oldRoot, $this->folderRoot . $newRoot);
            } else {
                parent::folderRenameTool($this->folderRoot . $oldRoot, $this->folderRoot . $newRoot);
            }
        }
    }

    /**
     * Copy a root (use from file or folder), or replace if exist
     */
    function copy(string $oldRoot, string $newRoot): void
    {
        $oldRoot = str_replace(".", "", $oldRoot);
        $newRoot = str_replace(".", "", $newRoot);
        if (!empty($oldRoot)) {
            if (parent::isFileTool($this->folderRoot . $oldRoot)) {
                parent::fileCopyTool($this->folderRoot . $oldRoot, $this->folderRoot . $newRoot);
            } else {
                parent::folderCopyTool($this->folderRoot . $oldRoot, $this->folderRoot . $newRoot);
            }
        }
    }

    /**
     * Move a root (use from file or folder), or replace if exist
     */
    function move(string $oldRoot, string $newRoot): void
    {
        $oldRoot = str_replace(".", "", $oldRoot);
        $newRoot = str_replace(".", "", $newRoot);
        if (!empty($oldRoot)) {
            if (parent::isFileTool($this->folderRoot . $oldRoot)) {
                parent::fileMoveTool($this->folderRoot . $oldRoot, $this->folderRoot . $newRoot);
            } else {
                parent::folderMoveTool($this->folderRoot . $oldRoot, $this->folderRoot . $newRoot);
            }
        }
    }
}
