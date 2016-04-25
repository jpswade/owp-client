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

/**
 * Class AbstractServerStatsEntity
 *
 * @author Stanislav Vetlovskiy <mrerliz@gmail.com>
 */
abstract class AbstractServerStatsEntity
{
    /** @var float[] */
    private $cpuLoadAverage = [];
    /** @var DiskUsageStatEntity */
    private $diskUsage;
    /** @var RamUsageStatEntity */
    private $ramUsage;

    /**
     * @return array
     */
    public function __toArray()
    {
        return [
            'cpuLoadAverage' => $this->getCPULoadAverage(),
            'diskUsage'      => $this->getDiskUsage() ? $this->getDiskUsage()->__toArray() : null,
            'ramUsage'       => $this->getRamUsage() ? $this->getRamUsage()->__toArray() : null,
        ];
    }

    /**
     * @param DiskUsageStatEntity $diskUsage
     *
     * @return $this
     */
    public function setDiskUsage(DiskUsageStatEntity $diskUsage)
    {
        $this->diskUsage = $diskUsage;

        return $this;
    }

    /**
     * @return DiskUsageStatEntity
     */
    public function getDiskUsage()
    {
        return $this->diskUsage;
    }

    /**
     * @param float[] $cpuLoadAverage
     *
     * @return $this
     */
    public function setCPULoadAverage($cpuLoadAverage)
    {
        $this->cpuLoadAverage = $cpuLoadAverage;

        return $this;
    }

    /**
     * @return float[]
     */
    public function getCPULoadAverage()
    {
        return $this->cpuLoadAverage;
    }

    /**
     * @param RamUsageStatEntity $ramUsage
     *
     * @return $this
     */
    public function setRamUsage(RamUsageStatEntity $ramUsage)
    {
        $this->ramUsage = $ramUsage;

        return $this;
    }

    /**
     * @return RamUsageStatEntity
     */
    public function getRamUsage()
    {
        return $this->ramUsage;
    }
}
