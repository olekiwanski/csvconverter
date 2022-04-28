CsvConverter - Library for converting a csv file to an array
======================================================

```php
$converter = new Converter(
    [
        //'column_name'           => 'validation_rules',
        'name'                  => 'required',
        'email'                 => 'required|email',
    ]
);

$converter->setSeparator(',');
$converter->readData($file_path);
$converter->getData();
```
Library is based on the rakit/validation library. <br>
Information on the validation rules can be found  <a href="https://github.com/rakit/validation">here</a>


