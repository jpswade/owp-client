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
 * Class AbstractMemoryEntity
 *
 * @author Stanislav Vetlovskiy <mrerliz@gmail.com>
 */
abstract class AbstractMemoryEntity
{
    /** @var int */
    protected $usagePercent;
    /** @var int */
    protected $freeSize;
    /** @var int */
    protected $usedSize;
    /** @var int */
    protected $totalSize;

    /**
     * @return array
     */
    public function __toArray()
    {
        return [
            'usagePercent' => $this->getUsagePercent(),
            'freeSize'     => $this->getFreeSize(),
            'usedSize'     => $this->getUsedSize(),
            'totalSize'    => $this->getTotalSize(),
        ];
    }

    /**
     * @param int $usagePercent size on MB
     *
     * @return $this
     */
    public function setUsagePercent($usagePercent)
    {
        $this->usagePercent = (int) $usagePercent;

        return $this;
    }

    /**
     * @return int
     */
    public function getUsagePercent()
    {
        return $this->usagePercent;
    }

    /**
     * @param int $freeSize size on MB
     *
     * @return $this
     */
    public function setFreeSize($freeSize)
    {
        $this->freeSize = (int) $freeSize;

        return $this;
    }

    /**
     * @return int
     */
    public function getFreeSize()
    {
        return $this->freeSize;
    }

    /**
     * @param int $usedSize size on MB
     *
     * @return $this
     */
    public function setUsedSize($usedSize)
    {
        $this->usedSize = (int) $usedSize;

        return $this;
    }

    /**
     * @return int
     */
    public function getUsedSize()
    {
        return $this->usedSize;
    }

    /**
     * @param int $totalSize size on MB
     *
     * @return $this
     */
    public function setTotalSize($totalSize)
    {
        $this->totalSize = (int) $totalSize;

        return $this;
    }

    /**
     * @return int size on MB
     */
    public function getTotalSize()
    {
        return $this->totalSize;
    }
}
