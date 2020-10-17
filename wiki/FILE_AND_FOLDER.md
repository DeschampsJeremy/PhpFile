- [Home page](/wiki)
# FILE & FOLDER

## download(string $root): ?string
Get a file to base64 string, in folder case it's will create a zip file
```
var_dump([
    0 => $fileService->download('jpg_1200x800.jpg'),
    1 => $fileService->download('png_1200x800.png'),
    2 => $fileService->download('pdf.pdf'),
    3 => $fileService->download('myFolder'),
    4 => $fileService->download('myFolder/jpg_1200x800.jpg'),
    5 => $fileService->download('myFolder/otherFolder'),
    6 => $fileService->download('myFolder/otherFolder/jpg_1200x800.jpg'),
    7 => $fileService->download('noFile.jpg'),
]);

array(8) {
    [0]=> //Return string base64 with file content
    [1]=> //Return string base64 with file content
    [2]=> //Return string base64 with file content
    [3]=> //Return string base64 with folder ZIP compressed content
    [4]=> //Return string base64 with file content
    [5]=> //Return string base64 with folder ZIP compressed content
    [6]=> //Return string base64 with file content
    [7]=> //Nothing
}
```

## isFile(string $root): bool
Check if it's file root (use from file or folder)
```
var_dump([
    0 => $fileService->isFile('jpg_1200x800.jpg'),
    1 => $fileService->isFile('png_1200x800.png'),
    2 => $fileService->isFile('pdf.pdf'),
    3 => $fileService->isFile('myFolder'),
    4 => $fileService->isFile('myFolder/jpg_1200x800.jpg'),
    5 => $fileService->isFile('myFolder/otherFolder'),
    6 => $fileService->isFile('myFolder/otherFolder/jpg_1200x800.jpg'),
    7 => $fileService->isFile('noFile.jpg'),
]);

array(8) {
    [0]=> bool(true)
    [1]=> bool(true)
    [2]=> bool(true)
    [3]=> bool(false)
    [4]=> bool(true)
    [5]=> bool(false)
    [6]=> bool(true)
    [7]=> bool(false)
}
```

## isFolder(string $root): bool
Check if it's folder root (use from file or folder)
```
var_dump([
    0 => $fileService->isFolder('jpg_1200x800.jpg'),
    1 => $fileService->isFolder('png_1200x800.png'),
    2 => $fileService->isFolder('pdf.pdf'),
    3 => $fileService->isFolder('myFolder'),
    4 => $fileService->isFolder('myFolder/jpg_1200x800.jpg'),
    5 => $fileService->isFolder('myFolder/otherFolder'),
    6 => $fileService->isFolder('myFolder/otherFolder/jpg_1200x800.jpg'),
    7 => $fileService->isFolder('noFile.jpg'),
]);

array(8) {
    [0]=> bool(false)
    [1]=> bool(false)
    [2]=> bool(false)
    [3]=> bool(true)
    [4]=> bool(false)
    [5]=> bool(true)
    [6]=> bool(false)
    [7]=> bool(false)
}
```

## infos(string $root): ?array
Get the root infos (use from file or folder)
```
var_dump([
    0 => $fileService->infos('jpg_1200x800.jpg'),
    1 => $fileService->infos('png_1200x800.png'),
    2 => $fileService->infos('pdf.pdf'),
    3 => $fileService->infos('myFolder'),
    4 => $fileService->infos('myFolder/jpg_1200x800.jpg'),
    5 => $fileService->infos('myFolder/otherFolder'),
    6 => $fileService->infos('myFolder/otherFolder/jpg_1200x800.jpg'),
    7 => $fileService->infos('noFile.jpg'),
]);

array(8) {
    [0]=> array(2) {
        ["type"]=> string(10) "image/jpeg"
        ["sizeMo"]=> float(0.16)
    }
    [1]=> array(2) {
        ["type"]=> string(9) "image/png"
        ["sizeMo"]=> float(1.34)
    }
    [2]=> array(2) {
        ["type"]=> string(15) "application/pdf"
        ["sizeMo"]=> float(0.01)
    }
    [3]=> array(2) {
        ["type"]=> string(9) "directory"
        ["sizeMo"]=> float(3.18)
    }
    [4]=> array(2) {
        ["type"]=> string(10) "image/jpeg"
        ["sizeMo"]=> float(0.16)
    }
    [5]=> array(2) {
        ["type"]=> string(9) "directory"
        ["sizeMo"]=> float(1.67)
    }
    [6]=> array(2) {
        ["type"]=> string(10) "image/jpeg"
        ["sizeMo"]=> float(0.16)
    }
    [7]=> NULL
}
```

## folderScans(string $folder): array
Get a folder content
```
var_dump([
    0 => $fileService->folderScans('jpg_1200x800.jpg'),
    1 => $fileService->folderScans('png_1200x800.png'),
    2 => $fileService->folderScans('pdf.pdf'),
    3 => $fileService->folderScans('myFolder'),
    4 => $fileService->folderScans('myFolder/jpg_1200x800.jpg'),
    5 => $fileService->folderScans('myFolder/otherFolder'),
    6 => $fileService->folderScans('myFolder/otherFolder/jpg_1200x800.jpg'),
    7 => $fileService->folderScans('noFile.jpg'),
]);

array(8) {
    [0]=> array(0) {}
    [1]=> array(0) {}
    [2]=> array(0) {}
    [3]=> array(4) {
        [0]=> string(16) "jpg_1200x800.jpg"
        [1]=> string(11) "otherFolder"
        [2]=> string(7) "pdf.pdf"
        [3]=> string(16) "png_1200x800.png"
    }
    [4]=> array(0) {}
    [5]=> array(3) {
        [0]=> string(16) "jpg_1200x800.jpg"
        [1]=> string(7) "pdf.pdf"
        [2]=> string(16) "png_1200x800.png"
    }
    [6]=> array(0) {}
    [7]=> array(0) {}
}
```

## folderClean(string $folder): void
Clean folder, recursice delete content
```
var_dump([
    0 => $fileService->folderClean('jpg_1200x800.jpg'),
    1 => $fileService->folderClean('png_1200x800.png'),
    2 => $fileService->folderClean('pdf.pdf'),
    3 => $fileService->folderClean('myFolder'),
    4 => $fileService->folderClean('myFolder/jpg_1200x800.jpg'),
    5 => $fileService->folderClean('myFolder/otherFolder'),
    6 => $fileService->folderClean('myFolder/otherFolder/jpg_1200x800.jpg'),
    7 => $fileService->folderClean('noFile.jpg'),
]);

array(8) {
    [0]=> //Nothing
    [1]=> //Nothing
    [2]=> //Nothing
    [3]=> //Delete all the folder content
    [4]=> //Nothing
    [5]=> //Delete all the folder content
    [6]=> //Nothing
    [7]=> //Nothing
}
```

## fileAdd(string $base64, string $name, string $newRoot = null, string $rename = null): string
Create a file from base64 string, generate images caches, replace if exist, generate a base64 file on [base64.guru](https://base64.guru/converter/encode/file)
```
var_dump([
    0 => $fileService->fileAdd($base64Jpg, 'newJpg.jpg', '', null),
    1 => $fileService->fileAdd($base64Png, 'newPng.png', 'myFolder/otherFolder', null),
    2 => $fileService->fileAdd($base64Pdf, 'newPdf.pdf', '', 'renamedPdf'),
    3 => $fileService->fileAdd('', 'newJpg.jpg', '', null),
    4 => $fileService->fileAdd('noBase64File', 'newJpg.jpg', '', null),
]);

array(5) {
    [0]=> string(32) "20201007001416_5f7d08587807f.jpg" //Create file and caches resized on testFolder
    [1]=> string(32) "20201007001417_5f7d085983570.png" //Create file and caches rezized on testFolder/myFolder/otherFolder
    [2]=> string(14) "renamedPdf.pdf" //Create file on testFolder
    [3]=> string(32) "20201007001420_5f7d085cd4531.jpg" //Create an unreadable file on testFolder
    [4]=> string(32) "20201007001420_5f7d085cd56f1.jpg" //Create an unreadable file on testFolder
}
```

## folderAdd(string $folder): void
Create a folder (or replace if exist)
```
var_dump([
    0 => $fileService->folderAdd('newFolder1.jpg'),
    1 => $fileService->folderAdd('newFolder2'),
    2 => $fileService->folderAdd('myFolder/newFolder3'),
    3 => $fileService->folderAdd(''),
]);

array(4) {
    [0]=> //Nothing
    [1]=> //Create newFolder2 folder on testFolder
    [2]=> //Create newFolder3 folder on testFolder/myFolder
    [3]=> //Nothing
}
```

## delete(string $root): void
Remove a root (use from file or folder)
```
var_dump([
    0 => $fileService->delete('jpg_1200x800.jpg'),
    1 => $fileService->delete('myFolder/jpg_1200x800.jpg'),
    2 => $fileService->delete('myFolder/otherFolder'),
    3 => $fileService->delete(''),
]);

array(3) {
    [0]=> //Delete file
    [1]=> //Delete file
    [2]=> //Delete folder and content
    [3]=> //Nothing
}
```

## rename(string $oldRoot, string $newRoot): void
Rename a root (use from file or folder), or replace if exist
```
var_dump([
    0 => $fileService->rename('jpg_1200x800.jpg', 'newFile1.jpg'),
    1 => $fileService->rename('myFolder/jpg_1200x800.jpg', 'newFile2.jpg'),
    2 => $fileService->rename('myFolder/otherFolder', 'newFolder'),
    3 => $fileService->rename('', 'newFile3.jpg'),
]);

array(3) {
    [0]=> //Rename the file
    [1]=> //Rename the file
    [2]=> //Rename folder
    [3]=> //Nothing
}
```

## copy(string $oldRoot, string $newRoot): void
Copy a root (use from file or folder), or replace if exist
```
var_dump([
    0 => $fileService->copy('jpg_1200x800.jpg', 'newFile1.jpg'),
    1 => $fileService->copy('myFolder/jpg_1200x800.jpg', 'newFile2.jpg'),
    2 => $fileService->copy('myFolder/otherFolder', 'newFolder'),
    3 => $fileService->copy('', 'newFile3.jpg'),
]);

array(3) {
    [0]=> //Copy the file
    [1]=> //Copy the file
    [2]=> //Copy folder
    [3]=> //Nothing
}
```

## move(string $oldRoot, string $newRoot): void
Move a root (use from file or folder), or replace if exist
```
var_dump([
    0 => $fileService->move('jpg_1200x800.jpg', 'newFile1.jpg'),
    1 => $fileService->move('myFolder/jpg_1200x800.jpg', 'newFile2.jpg'),
    2 => $fileService->move('myFolder/otherFolder', 'newFolder'),
    3 => $fileService->move('', 'newFile3.jpg'),
]);

array(3) {
    [0]=> //Move the file
    [1]=> //Move the file
    [2]=> //Move folder
    [3]=> //Nothing
}
```