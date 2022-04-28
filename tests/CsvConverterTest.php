<?php

use CSVConverter\Converter;
use PHPUnit\Framework\TestCase;


final class CsvConverterTest extends TestCase
{
    public function testConvertToArray(): void
    {
        $converter = new Converter(
            [
                'id'=>'required|min:1',
                'firstname'=>'required|max:50',
                'lastname'=>'required|max:10',
                'date'=>'required|date:Y-m-d'
            ]
        );

        $converter->setSeparator(';');
        $converter->readData(__DIR__."/tests.csv");
        $this->assertIsArray(
            $converter->getData(),
            "assert variable is array or not"
        );
        $this->assertNotEmpty(
            $converter->getData(),
            "assert variable is empty"
        );
    }

    public function testConvertToArrayNoEmptyData(): void
    {
        $converter = new Converter(
            [
                'id'=>'required|min:1',
                'firstname'=>'required|max:50',
                'lastname'=>'required|max:10',
                'date'=>'required|date:Y-m-d'
            ]
        );

        $converter->setSeparator(';');
        $converter->readData(__DIR__."/tests.csv");

        foreach ($converter->getData() as $row) {
            foreach ($row AS $field){
                $this->assertNotEmpty(
                    $field,
                    "assert variable is empty"
                );
            }

        }
    }

    public function testConvertToArrayReturnedColumnCountEqualsToGiven(): void
    {
        $converter = new Converter(
            [
                'id'=>'required|min:1',
                'firstname'=>'required|max:50',
                'lastname'=>'required|max:10'
            ]
        );

        $converter->setSeparator(';');
        $converter->readData(__DIR__."/tests.csv");

        foreach ($converter->getData() as $row) {
            $this->assertEquals(
                3,
                count($row),
                "assert column count not equals to given"
            );
        }
    }

}