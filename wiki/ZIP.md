- [Home page](/wiki)
# ZIP

## zipCompress(string $oldRoot, string $newRoot, string $name): void
Compress root to ZIP format, recursive, keep the original, replace if exist (use from file or folder)
```
var_dump([
    0 => $fileService->zipCompress('jpg_1200x800.jpg','','myArchive1'),
    1 => $fileService->zipCompress('myFolder','','myArchive1'),
    2 => $fileService->zipCompress('jpg_1200x800.jpg','myFolder/otherFolder','myArchive2'),
    3 => $fileService->zipCompress('myFolder/jpg_1200x800.jpg','','myArchive3'),
    4 => $fileService->zipCompress('myFolder/otherFolder','','myArchive4'),
    5 => $fileService->zipCompress('noFile.jpg', '', 'myArchive5'),
]);

array(4) {
    [0]=> //Create a ZIP file myArchive1 on testFolder with jpg_1200x800.jpg content
    [1]=> //Replace a ZIP file myArchive1 on testFolder with myFolder content
    [2]=> //Create a ZIP file myArchive2 on testFolder/myFolder/otherFolder with jpg_1200x800.jpg content
    [3]=> //Create a ZIP file myArchive3 on testFolder with myFolder/jpg_1200x800.jpg content
    [4]=> //Create a ZIP file myArchive4 on testFolder with myFolder/otherFolder content
    [5]=> //Nothing
}
```

## zipUnpress(string $oldRoot, string $newRoot, string $name): void
Unpress ZIP root to folder
```
var_dump([
    0 => $fileService->zipUnpress('myArchive1.zip', '', 'myArchive1'),
    1 => $fileService->zipUnpress('myArchive1.zip', 'myFolder/otherFolder', 'myArchive1'),
    2 => $fileService->zipUnpress('myFolder/otherFolder/myArchive2.zip', '', 'myArchive2'),
    3 => $fileService->zipUnpress('noFile.zip', '', 'myArchive3'),
]);

array(4) {
    [0]=> //Create a myArchive1 folder on testFolder
    [1]=> //Create a myArchive1 folder on testFolder/myFolder/otherFolder
    [2]=> //Create a myArchive2 folder on testFolder
    [3]=> //Nothing
}
```