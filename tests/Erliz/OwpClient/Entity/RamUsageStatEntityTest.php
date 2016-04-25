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
 * Class RamUsageStatEntityTest
 *
 * @author Stanislav Vetlovskiy <mrerliz@gmail.com>
 */
class RamUsageStatEntityTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test json serialization
     */
    public function testJsonSerialize()
    {
        $entity = new RamUsageStatEntity();

        $this->assertTrue(is_array($entity->jsonSerialize()));
        $this->assertNotEmpty($entity->jsonSerialize());

        $json = json_encode($entity);
        $this->assertEmpty(json_last_error());
        $this->assertTrue(is_string($json));
        $this->assertNotEmpty($json);
        $this->assertContains('freeSize', $json);
    }
}
