<?php

namespace ntentan\utils\tests\cases;

use ntentan\utils\Text;
use PHPUnit\Framework\TestCase;

class InflectionTest extends TestCase
{
    /**
     * @param $plural
     * @param $singular
     * @dataProvider inflectionWords
     */
    public function testPlurals($plural, $singular)
    {
        $this->assertEquals($plural, Text::pluralize($singular));
    }

    public function inflectionWords()
    {
        $words = [];
        $file = fopen(__DIR__ . '/../fixtures/english_inflection.csv', 'r');
        while(!feof($file)) {
            $words[] = fgetcsv($file);
        }
        return $words;
    }
}
