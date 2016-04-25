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
 * Class DiskUsageStatEntity
 *
 * @author Stanislav Vetlovskiy <mrerliz@gmail.com>
 */
class DiskUsageStatEntity implements JsonSerializable
{
    /** @var DiskUsageMountEntity[] */
    private $mountPoints = [];

    /**
     * @return array
     */
    public function __toArray()
    {
        $mountPoints = [];
        foreach ($this->getMountPoints() as $mountPoint) {
            $mountPoints[] = $mountPoint->__toArray();
        }

        return [
            'mountPoints' => $mountPoints,
        ];
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->__toArray();
    }

    /**
     * @return DiskUsageMountEntity[]
     */
    public function getMountPoints()
    {
        return $this->mountPoints;
    }

    /**
     * @param DiskUsageMountEntity[] $mountPoints
     *
     * @return $this
     */
    public function setMountPoints(array $mountPoints)
    {
        $this->mountPoints = $mountPoints;

        return $this;
    }

    /**
     * @param DiskUsageMountEntity $mountPoint
     *
     * @return $this
     */
    public function addMountPoint(DiskUsageMountEntity $mountPoint)
    {
        if (!in_array($mountPoint, $this->getMountPoints())) {
            $this->mountPoints[] = $mountPoint;
        }

        return $this;
    }

    /**
     * @param string $location
     *
     * @return bool|DiskUsageMountEntity
     */
    public function getMountPointByLocation($location)
    {
        foreach ($this->getMountPoints() as $mountPoint) {
            if ($mountPoint->getMountPoint() == $location) {
                return $mountPoint;
            }
        }

        return false;
    }

    /**
     * Get total disk size in all mount points
     *
     * @return int|null in MB
     */
    public function getTotalSize()
    {
        $size = null;
        foreach ($this->getMountPoints() as $mountPoint) {
            $size += $mountPoint->getTotalSize();
        }

        return $size;
    }

    /**
     * Get free disk size in all mount points
     *
     * @return int|null in MB
     */
    public function getFreeSize()
    {
        $size = null;
        foreach ($this->getMountPoints() as $mountPoint) {
            $size += $mountPoint->getFreeSize();
        }

        return $size;
    }

    /**
     * Get used disk size in all mount points
     *
     * @return int|null in MB
     */
    public function getUsedSize()
    {
        $size = null;
        foreach ($this->getMountPoints() as $mountPoint) {
            $size += $mountPoint->getUsedSize();
        }

        return $size;
    }

    /**
     * Get used disk percent in all mount points
     *
     * @return int|null in MB
     */
    public function getUsagePercent()
    {
        if (empty($this->getTotalSize())) {
            return 0;
        }

        return (int) round($this->getUsedSize() / $this->getTotalSize() * 100);
    }
}
