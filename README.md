# PhpFile
By [Jeremy Deschamps](https://www.jddev.net)

A file manager

## Contrib

- Download the repository, in a CLI :
```
git clone https://github.com/DeschampsJeremy/PhpFile.git
```
Push on the `staging` branch.

## Install

- Download the package, in a CLI :
```
composer require deschamps-jeremy/php-file
```

- Import :
```
use DeschampsJeremy\FileService;
```

- Construct on your controller :
```
$fileService = new FileService('testFolder'));
```
Script will automatically create an `uploaded` folder to contains `uploaded\tmp`, `uploaded\testFolder` directories.

- Use any methods, the security apply actions only on `uploaded\testFolder`, you can create any PhpFileService objects you needed.

## Methods

On this examples, I have an `uploaded\testFolder` directory to contains :
```
"jpg_1200x800.jpg"
"png_1200x800.png"
"pdf.pdf"
"myFolder": [
    "jpg_1200x800.jpg",
    "pdf.pdf",
    "png_1200x800.png"
    "otherFolder": [
        "jpg_1200x800.jpg",
        "pdf.pdf",
        "png_1200x800.png"
    ],
],
```

- [BASE 64](/wiki/BASE64.md)

- [FILE & FOLDER](/wiki/FILE_AND_FOLDER.md)

- [IMAGE RESIZE](/wiki/IMAGE_RESIZE.md)

- [JSON](/wiki/JSON.md)

- [ZIP](/wiki/ZIP.md)