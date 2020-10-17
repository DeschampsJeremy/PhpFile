- [Home page](/wiki)
# IMAGE RESIZE 

## isImageResize(string $root): bool
Check if it's image PNG or JPG/JPEG from root (use from file or folder)
```
var_dump([
    0 => $fileService->isImageResize('jpg_1200x800.jpg'),
    1 => $fileService->isImageResize('png_1200x800.png'),
    2 => $fileService->isImageResize('pdf.pdf'),
    3 => $fileService->isImageResize('myFolder'),
    4 => $fileService->isImageResize('myFolder/jpg_1200x800.jpg'),
    5 => $fileService->isImageResize('myFolder/otherFolder'),
    6 => $fileService->isImageResize('myFolder/otherFolder/jpg_1200x800.jpg'),
    7 => $fileService->isImageResize('noFile.jpg'),
]);

array(8) {
    [0]=> bool(true)
    [1]=> bool(true)
    [2]=> bool(false)
    [3]=> bool(false)
    [4]=> bool(true)
    [5]=> bool(false)
    [6]=> bool(true)
    [7]=> bool(false)
}
```