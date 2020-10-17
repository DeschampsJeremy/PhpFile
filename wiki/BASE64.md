- [Home page](/wiki)
# BASE64

## static base64Infos(string $base64): ?array
Get the base64 string file infos, generate a base64 file on [base64.guru](https://base64.guru/converter/encode/file)
```
var_dump([
    0 => DeschampsJeremyFileService::base64Infos($base64Jpg),
    1 => DeschampsJeremyFileService::base64Infos($base64Png),
    2 => DeschampsJeremyFileService::base64Infos($base64Pdf),
    3 => DeschampsJeremyFileService::base64Infos(''),
    4 => DeschampsJeremyFileService::base64Infos('noBase64File'),
]);

array(5) {
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
        ["type"]=> string(19) "application/x-empty"
        ["sizeMo"]=> float(0)
    }
    [4]=> array(2) {
        ["type"]=> string(24) "application/octet-stream"
        ["sizeMo"]=> float(0)
    }
}
```