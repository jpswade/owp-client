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

use JsonSerializable;

/**
 * Class RamUsageStatEntity
 *
 * @author Stanislav Vetlovskiy <mrerliz@gmail.com>
 */
class RamUsageStatEntity extends AbstractMemoryEntity implements JsonSerializable
{
    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->__toArray();
    }
}
