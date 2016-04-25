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

/**
 * Class MemoryParser
 *
 * @author Stanislav Vetlovskiy <mrerliz@gmail.com>
 */
class MemoryParser
{
    /**
     * @param string $string
     *
     * @return array
     */
    public static function parse($string)
    {
        preg_match("/([\d]+)%, ([\d\.]+ [\w]+) of ([\d\.]+ [\w]+) \/ ([\d\.]+ [\w]+)/", $string, $matches);

        return [
            'percent' => (int) $matches[1],
            'total'   => self::normalizeStringToMegaByte($matches[3]),
            'used'    => self::normalizeStringToMegaByte($matches[2]),
            'free'    => self::normalizeStringToMegaByte($matches[4]),
        ];
    }

    /**
     * @param string $string
     * @return int
     */
    protected static function normalizeStringToMegaByte($string)
    {
        list($count, $multiplierString) = explode(' ', $string);

        return (int) round($count * self::getMultiplierByString($multiplierString));
    }

    /**
     * @param string $string
     * @return int
     */
    protected static function getMultiplierByString($string)
    {
        $multiplier = 1;
        $step = 1024;
        switch (strtoupper($string)) {
            case 'TB':
                $multiplier *= $step;
            case 'GB':
                $multiplier *= $step;
            case 'MB':
                $multiplier *= 1;
                break;
            case 'B':
                $multiplier /= 1024;
            case 'KB':
                $multiplier /= 1024;
                break;
            default:
                throw new InvalidArgumentException(sprintf('Unknown multiplier "%s"', $string));
        }

        return $multiplier;
    }
}
