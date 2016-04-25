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
 * Class DiskUsageMountEntity
 *
 * @author Stanislav Vetlovskiy <mrerliz@gmail.com>
 */
class DiskUsageMountEntity extends AbstractMemoryEntity implements JsonSerializable
{
    /** @var string */
    private $mountPoint;

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getMountPoint();
    }

    /**
     * @return array
     */
    public function __toArray()
    {
        return array_merge(
            parent::__toArray(),
            [
                'mountPoint' => $this->getMountPoint(),
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
     * @param string $mountPoint size on MB
     *
     * @return $this
     */
    public function setMountPoint($mountPoint)
    {
        $this->mountPoint = $mountPoint;

        return $this;
    }

    /**
     * @return string
     */
    public function getMountPoint()
    {
        return $this->mountPoint;
    }
}
