- [Home page](/wiki)
# JSON

## jsonAdd(string $root, array $array): void
Create a JSON file
```
var_dump([
    0 => $fileService->jsonAdd('newJson1', ['key1' => 'val1', 'key2' => 'val2']),
    1 => $fileService->jsonAdd('myFolder/newJson2', ['key1' => 'val1', 'key2' => 'val2']),
]);

array(2) {
    [0]=> //Create a JSON file
    [1]=> //Create a JSON file
}
```

## jsonRead(string $root): ?array
Read a JSON file and return an array
```
var_dump([
    0 => $fileService->jsonRead('newJson1'),
    1 => $fileService->jsonRead('myFolder/newJson2'),
]);

array(2) {
    [0]=> array(2) {
        ["key1"]=> string(4) "val1"
        ["key2"]=> string(4) "val2"
    }
    [1]=> array(2) {
        ["key1"]=> string(4) "val1"
        ["key2"]=> string(4) "val2"
    }
}
```