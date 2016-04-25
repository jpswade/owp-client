<?php

/**
 * OwpClient package
 *
 * @package   Erliz\OwpClient
 * @author    Stanislav Vetlovskiy <mrerliz@gmail.com>
 * @copyright Copyright (c) Stanislav Vetlovskiy
 * @license   MIT
 */

namespace Erliz\OwpClient\Parser;

use Erliz\OwpClient\Exception\InvalidArgumentException;
use PHPUnit_Framework_TestCase;

/**
 * Class MemoryParserTest
 *
 * @author Stanislav Vetlovskiy <mrerliz@gmail.com>
 */
class MemoryParserTest extends PHPUnit_Framework_TestCase
{
    /**
     * @param int $percent
     * @param int $used
     * @param int $total
     * @param int $free
     * @return array
     */
    public static function generateExpectedArray($percent, $used, $total, $free)
    {
        return [
            'percent' => $percent,
            'total'   => $total,
            'used'    => $used,
            'free'    => $free,
        ];
    }

    /**
     * @return array
     */
    public function goodDataProvider()
    {
        return [
            [
                '42%, 395.1 MB of 991.8 MB / 545.6 MB free',
                self::generateExpectedArray(42, 395, 992, 546),
            ],
            [
                '20%, 18.2 GB of 98.2 GB / 75 GB free',
                self::generateExpectedArray(20, 18637, 100557, 76800),
            ],
            [
                '1%, 3 MB of 1.9 GB / 1.8 GB free',
                self::generateExpectedArray(1, 3, 1946, 1843),
            ],
            [
                '70%, 758.5 GB of 1.1 TB / 340.2 GB free',
                self::generateExpectedArray(70, 776704, 1153434, 348365),
            ],
            [
                '100%, 395 KB of 80 MB / 79.6 MB free',
                self::generateExpectedArray(100, 0, 80, 80),
            ],
            [
                '0%, 1024 B of 1 MB / 1023 KB free',
                self::generateExpectedArray(0, 0, 1, 1),
            ],
        ];
    }

    /**
     * @dataProvider goodDataProvider
     *
     * @param string $origin
     * @param array  $expected
     */
    public function testGetMultiplierByStringWithGoodData($origin, array $expected)
    {
        $this->assertEquals(
            $expected,
            MemoryParser::parse($origin)
        );
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Unknown multiplier
     */
    public function testGetMultiplierByStringWithBadMultiplierString()
    {
        MemoryParser::parse(
            '0%, 1024 B of 1 PB / 1023 KB free'
        );
    }
}
