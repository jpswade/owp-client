<?php

/**
 * OwpClient package
 *
 * @package   Erliz\OwpClient
 * @author    Stanislav Vetlovskiy <mrerliz@gmail.com>
 * @copyright Copyright (c) Stanislav Vetlovskiy
 * @license   MIT
 */

namespace Erliz\OwpClient\Entity;

use PHPUnit_Framework_TestCase;

/**
 * Class VirtualServerStatsEntityTest
 *
 * @author Stanislav Vetlovskiy <mrerliz@gmail.com>
 */
class VirtualServerStatsEntityTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test json serialize
     */
    public function testJsonSerialize()
    {
        $entity = new VirtualServerStatsEntity();
        $this->assertTrue(is_array($entity->jsonSerialize()));
        $this->assertNotEmpty($entity->jsonSerialize());

        $jsonDecode = json_encode($entity);
        $this->assertEmpty(json_last_error());
        $this->assertTrue(is_string($jsonDecode));
        $this->assertNotEmpty($jsonDecode);
        $this->assertContains('ramUsage', $jsonDecode);
    }
}
