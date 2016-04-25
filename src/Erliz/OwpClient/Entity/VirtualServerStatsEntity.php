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
 * Class VirtualServerStatsEntity
 *
 * @author Stanislav Vetlovskiy <mrerliz@gmail.com>
 */
class VirtualServerStatsEntity extends AbstractServerStatsEntity implements JsonSerializable
{
    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->__toArray();
    }
}
