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
 * Class HardwareServerStatsEntity
 *
 * @author Stanislav Vetlovskiy <mrerliz@gmail.com>
 */
class HardwareServerStatsEntity extends AbstractServerStatsEntity implements JsonSerializable
{
    /** @var string */
    private $osVersion;

    /**
     * @return array
     */
    public function __toArray()
    {
        return array_merge(
            parent::__toArray(),
            [
                'osVersion' => $this->getOSVersion(),
            ]
        );
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->__toArray();
    }

    /**
     * @param string $osVersion
     *
     * @return $this
     */
    public function setOSVersion($osVersion)
    {
        $this->osVersion = $osVersion;

        return $this;
    }

    /**
     * @return string
     */
    public function getOSVersion()
    {
        return $this->osVersion;
    }
}
