<?php

namespace CSVConverter;

use Rakit\Validation\Validator;

class Converter
{
    protected array $data = [];
    protected array $headers_from_file = [];
    protected array $reversed_headers_from_file = [];
    protected array $validation_rules = [];
    protected array $columns = [];
    protected string $separator = ';';

    public function __construct($columns)
    {
        $this->columns = $columns;
    }


    public function readData($filepath): void
    {
        $row = 1;
        if (($handle = fopen($filepath, "r")) !== false) {
            while (($dataFromFile = fgetcsv($handle, 1000, $this->separator)) !== false) {
                if ($row == 1) {
                    $this->headers_from_file = $dataFromFile;
                    $this->reversed_headers_from_file = array_flip($this->headers_from_file);
                } elseif (!empty($this->headers_from_file)) {
                    $validatedData = $this->saveDataFromRowToFinalArray($dataFromFile,$row);
                    if ($validatedData !== false) {
                        $this->data[] = $validatedData;
                    }
                }
                $row++;
            }
        }
    }

    private function saveDataFromRowToFinalArray(array $data,int $row): array|bool
    {
        $data = $this->prepareData($data);
        $validator = new Validator;
        if(empty($data) || empty($this->validation_rules)){
            return false;
        }
        $validation = $validator->make($data, $this->validation_rules);

        $validation->validate();
        if (!$validation->fails()) {
            return $data;
        }

        return false;
    }

    private function prepareData($data): array
    {
        $prepared_data = [];
        if (!empty($this->columns)) {
            foreach ($this->columns as $key => $validators) {
                if (array_key_exists($key, $this->reversed_headers_from_file)) {
                    if (array_key_exists($this->reversed_headers_from_file[$key], $data)) {
                        $prepared_data[$key] = $data[$this->reversed_headers_from_file[$key]];
                        if (!array_key_exists($key, $this->validation_rules)) {
                            $this->validation_rules[$key] = $validators;
                        }
                    }
                }
            }
        }
        return $prepared_data;
    }


    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param string $separator
     */
    public function setSeparator(string $separator): void
    {
        $this->separator = $separator;
    }


}