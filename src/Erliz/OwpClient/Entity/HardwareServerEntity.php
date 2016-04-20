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
 * Class HardwareServerEntity
 *
 * @author Stanislav Vetlovskiy <mrerliz@gmail.com>
 */
class HardwareServerEntity implements JsonSerializable
{
    /** @var int */
    private $daemonPort;
    /** @var string */
    private $defaultOSTemplate;
    /** @var string */
    private $defaultServerTemplate;
    /** @var string */
    private $description;
    /** @var string */
    private $host;
    /** @var int */
    private $id;
    /** @var bool */
    private $useSSL;
    /** @var bool */
    private $vSwap;
    /** @var array */
    private $virtualServers;

    /**
     * HardwareServerEntity constructor.
     */
    public function __construct()
    {
        $this->virtualServers = [];
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->host;
    }

    /**
     * @return array
     */
    public function __toArray()
    {
        $virtualServerArray = [];
        foreach ($this->getVirtualServers() as $virtualServer) {
            $virtualServerArray[] = $virtualServer->__toArray();
        }

        return [
            'allocatedMemory'       => $this->getAllocatedMemory(),
            'allocatedDiskSpace'    => $this->getAllocatedDiskSpace(),
            'daemonPort'            => $this->getDaemonPort(),
            'defaultOSTemplate'     => $this->getDefaultOSTemplate(),
            'defaultServerTemplate' => $this->getDefaultServerTemplate(),
            'description'           => $this->getDescription(),
            'host'                  => $this->getHost(),
            'id'                    => $this->getId(),
            'useSSL'                => $this->isUseSSL(),
            'vSwap'                 => $this->isVSwap(),
            'virtualServers'        => $virtualServerArray,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return $this->__toArray();
    }

    /**
     * @return int
     */
    public function getAllocatedMemory()
    {
        $allocatedMemory = 0;
        foreach ($this->getVirtualServers() as $virtualServer) {
            $allocatedMemory += $virtualServer->getMemory();
        }

        return $allocatedMemory;
    }

    /**
     * @return int
     */
    public function getAllocatedDiskSpace()
    {
        $allocatedDisk = 0;
        foreach ($this->getVirtualServers() as $virtualServer) {
            $allocatedDisk += $virtualServer->getDiskSpace();
        }

        return $allocatedDisk;
    }

    /**
     * @return int
     */
    public function getDaemonPort()
    {
        return $this->daemonPort;
    }

    /**
     * @param int $daemonPort
     *
     * @return $this
     */
    public function setDaemonPort($daemonPort)
    {
        $this->daemonPort = (int) $daemonPort;

        return $this;
    }

    /**
     * @return string
     */
    public function getDefaultOSTemplate()
    {
        return $this->defaultOSTemplate;
    }

    /**
     * @param string $defaultOSTemplate
     *
     * @return $this
     */
    public function setDefaultOSTemplate($defaultOSTemplate)
    {
        $this->defaultOSTemplate = (string) $defaultOSTemplate;

        return $this;
    }

    /**
     * @return string
     */
    public function getDefaultServerTemplate()
    {
        return $this->defaultServerTemplate;
    }

    /**
     * @param string $defaultServerTemplate
     *
     * @return $this
     */
    public function setDefaultServerTemplate($defaultServerTemplate)
    {
        $this->defaultServerTemplate = (string) $defaultServerTemplate;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = (string) $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @param string $host
     *
     * @return $this
     */
    public function setHost($host)
    {
        $this->host = (string) $host;

        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->id = (int) $id;

        return $this;
    }

    /**
     * @return bool
     */
    public function isUseSSL()
    {
        return $this->useSSL;
    }

    /**
     * @param bool $useSSL
     *
     * @return $this
     */
    public function setUseSSL($useSSL)
    {
        $this->useSSL = (bool) $useSSL;

        return $this;
    }

    /**
     * @return bool
     */
    public function isVSwap()
    {
        return $this->vSwap;
    }

    /**
     * @param bool $vSwap
     *
     * @return $this
     */
    public function setVSwap($vSwap)
    {
        $this->vSwap = (bool) $vSwap;

        return $this;
    }

    /**
     * @return VirtualServerEntity[]
     */
    public function getVirtualServers()
    {
        return $this->virtualServers;
    }

    /**
     * @param VirtualServerEntity[] $virtualServers
     *
     * @return $this
     */
    public function setVirtualServers(array $virtualServers)
    {
        $this->virtualServers = $virtualServers;

        return $this;
    }

    /**
     * @param VirtualServerEntity $virtualServer
     *
     * @return $this
     */
    public function addVirtualServers(VirtualServerEntity $virtualServer)
    {
        if (!in_array($virtualServer, $this->virtualServers)) {
            $this->virtualServers[] = $virtualServer;
        }

        return $this;
    }
}
