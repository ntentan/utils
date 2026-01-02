<?php

namespace ntentan\utils\tests\cases;

use ntentan\utils\Text;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class InflectionTest extends TestCase
{
    /**
     * @param $plural
     * @param $singular
     */
    #[DataProvider('inflectionWords')]
    public function testPlurals($plural, $singular)
    {
        $this->assertEquals($plural, Text::pluralize($singular));
    }

    /**
     * @param $plural
     * @param $singular
     */
    #[DataProvider('inflectionPlurals')]
    public function testSpecialPlurals($plural, $singular)
    {
        $this->assertEquals($plural, Text::pluralize($singular));
    }

    /**
     * @param $plural
     * @param $singular
     */
    #[DataProvider('inflectionWords')]
    public function testSingulars($plural, $singular)
    {
        $this->assertEquals($singular, Text::singularize($plural));
    }

    public static function inflectionWords(): array
    {
        $words = [];
        $file = fopen(__DIR__ . '/../fixtures/english_inflection.csv', 'r');
        while(!feof($file)) {
            $words[] = fgetcsv($file, escape: '\\');
        }
        return $words;
    }

    public static function inflectionPlurals(): array
    {
        $words = [];
        $file = fopen(__DIR__ . '/../fixtures/english_inflections_plurals_only.csv', 'r');
        while(!feof($file)) {
            $words[] = fgetcsv($file, escape: '\\');
        }
        return $words;
    }
}
