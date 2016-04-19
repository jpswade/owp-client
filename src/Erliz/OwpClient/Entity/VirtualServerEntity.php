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

use DateTime;
use Erliz\OwpClient\Exception\InvalidArgumentException;
use JsonSerializable;

/**
 * Class VirtualServerEntity
 *
 * @author Stanislav Vetlovskiy <mrerliz@gmail.com>
 */
class VirtualServerEntity implements JsonSerializable
{
    const STATE_RUNNING = 'running';
    const STATE_STOPPED = 'stopped';

    /** @var int|bool */
    private $cpuLimit = false;
    /** @var int|bool */
    private $cpuUnits = false;
    /** @var int|bool */
    private $cpus = false;
    /** @var bool */
    private $dailyBackup;
    /** @var string */
    private $description;
    /** @var int */
    private $diskSpace;
    /** @var DateTime|bool */
    private $expirationDate = false;
    /** @var int */
    private $hardwareServerId;
    /** @var string */
    private $hostName;
    /** @var int */
    private $id;
    /** @var int */
    private $identity;
    /** @var string */
    private $ipAddress;
    /** @var int */
    private $memory;
    /** @var string|bool */
    private $nameServer = false;
    /** @var string */
    private $originalOSTemplate;
    /** @var string */
    private $originalServerTemplate;
    /** @var string|bool */
    private $searchDomain = false;
    /** @var bool */
    private $startOnBoot;
    /** @var string */
    private $state;
    /** @var int|bool */
    private $userId = false;
    /** @var int */
    private $vSwap;

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->hostName;
    }

    /**
     * @return array
     */
    public function __toArray()
    {
        return [
            'cpuLimit' => $this->getCpuLimit(),
            'cpuUnits' => $this->getCpuUnits(),
            'cpus' => $this->getCpus(),
            'dailyBackup' => $this->isDailyBackup(),
            'description' => $this->getDescription(),
            'diskSpace' => $this->getDiskSpace(),
            'expirationDate' => $this->getExpirationDate(),
            'hardwareServerId' => $this->getHardwareServerId(),
            'hostName' => $this->getHostName(),
            'id' => $this->getId(),
            'identity' => $this->getIdentity(),
            'ipAddress' => $this->getIpAddress(),
            'memory' => $this->getMemory(),
            'nameServer' => $this->getNameServer(),
            'originalOSTemplate' => $this->getOriginalOSTemplate(),
            'originalServerTemplate' => $this->getOriginalServerTemplate(),
            'searchDomain' => $this->getSearchDomain(),
            'startOnBoot' => $this->isStartOnBoot(),
            'state' => $this->getState(),
            'userId' => $this->getUserId(),
            'vSwap' => $this->getVSwap(),
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
     * @param bool|int $cpuLimit
     *
     * @return VirtualServerEntity
     */
    public function setCpuLimit($cpuLimit)
    {
        $this->cpuLimit = $cpuLimit === false ? false : (int) $cpuLimit;

        return $this;
    }

    /**
     * @return bool|int
     */
    public function getCpuLimit()
    {
        return $this->cpuLimit;
    }

    /**
     * @param bool|int $cpuUnits
     *
     * @return VirtualServerEntity
     */
    public function setCpuUnits($cpuUnits)
    {
        $this->cpuUnits = $cpuUnits === false ? false : (int) $cpuUnits;

        return $this;
    }

    /**
     * @return bool|int
     */
    public function getCpuUnits()
    {
        return $this->cpuUnits;
    }

    /**
     * @param bool|int $cpus
     *
     * @return VirtualServerEntity
     */
    public function setCpus($cpus)
    {
        $this->cpus = $cpus === false ? false : (int) $cpus;

        return $this;
    }

    /**
     * @return bool|int
     */
    public function getCpus()
    {
        return $this->cpus;
    }

    /**
     * @param boolean $dailyBackup
     *
     * @return VirtualServerEntity
     */
    public function setDailyBackup($dailyBackup)
    {
        $this->dailyBackup = (bool) $dailyBackup;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isDailyBackup()
    {
        return $this->dailyBackup;
    }

    /**
     * @param string $description
     *
     * @return VirtualServerEntity
     */
    public function setDescription($description)
    {
        $this->description = (string) $description;

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
     * @param int $diskSpace
     *
     * @return VirtualServerEntity
     */
    public function setDiskSpace($diskSpace)
    {
        $this->diskSpace = (int) $diskSpace;

        return $this;
    }

    /**
     * @return int
     */
    public function getDiskSpace()
    {
        return $this->diskSpace;
    }

    /**
     * @param bool|DateTime $expirationDate
     *
     * @return VirtualServerEntity
     */
    public function setExpirationDate($expirationDate)
    {
        if ($expirationDate !== false && !($expirationDate instanceof DateTime)) {
            throw new InvalidArgumentException('Expiration date should be an instance of DateTime or false if not set');
        }
        $this->expirationDate = $expirationDate;

        return $this;
    }

    /**
     * @return bool|DateTime
     */
    public function getExpirationDate()
    {
        return $this->expirationDate;
    }

    /**
     * @param int $hardwareServerId
     *
     * @return VirtualServerEntity
     */
    public function setHardwareServerId($hardwareServerId)
    {
        $this->hardwareServerId = (int) $hardwareServerId;

        return $this;
    }

    /**
     * @return int
     */
    public function getHardwareServerId()
    {
        return $this->hardwareServerId;
    }

    /**
     * @param string $hostName
     *
     * @return VirtualServerEntity
     */
    public function setHostName($hostName)
    {
        $this->hostName = (string) $hostName;

        return $this;
    }

    /**
     * @return string
     */
    public function getHostName()
    {
        return $this->hostName;
    }

    /**
     * Alias for host name, cause in hw servers there is "host" prop
     *
     * @return string
     */
    public function getHost()
    {
        return $this->getHostName();
    }

    /**
     * Return host zones, explodes by dot in reverse order
     * if no dots in host name return false
     *
     * @return string|bool
     */
    public function getHostZones()
    {
        $zones = array_reverse(explode('.', $this->hostName));
        if (count($zones) < 2) {
            return false;
        }

        return $zones;
    }

    /**
     * @param int $id
     *
     * @return VirtualServerEntity
     */
    public function setId($id)
    {
        $this->id = (int) $id;

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
     * @param int $identity
     *
     * @return VirtualServerEntity
     */
    public function setIdentity($identity)
    {
        $this->identity = (int) $identity;

        return $this;
    }

    /**
     * @return int
     */
    public function getIdentity()
    {
        return $this->identity;
    }

    /**
     * @param string $ipAddress
     *
     * @return VirtualServerEntity
     */
    public function setIpAddress($ipAddress)
    {
        $this->ipAddress = (string) $ipAddress;

        return $this;
    }

    /**
     * @return string
     */
    public function getIpAddress()
    {
        return $this->ipAddress;
    }

    /**
     * Return subnet string like '192.168.88' generated from ip address
     *
     * @param int $octetCount
     *
     * @return string
     */
    public function getIpAddressSubNetwork($octetCount = 3)
    {
        $octets = explode('.', $this->getIpAddress());

        return implode('.', array_slice($octets, 0, $octetCount));
    }

    /**
     * @param int $memory
     *
     * @return VirtualServerEntity
     */
    public function setMemory($memory)
    {
        $this->memory = (int) $memory;

        return $this;
    }

    /**
     * @return int
     */
    public function getMemory()
    {
        return $this->memory;
    }

    /**
     * @param bool|string $nameServer
     *
     * @return VirtualServerEntity
     */
    public function setNameServer($nameServer)
    {
        $this->nameServer = $nameServer;

        return $this;
    }

    /**
     * @return bool|string
     */
    public function getNameServer()
    {
        return $this->nameServer;
    }

    /**
     * @param string $originalOSTemplate
     *
     * @return VirtualServerEntity
     */
    public function setOriginalOSTemplate($originalOSTemplate)
    {
        $this->originalOSTemplate = (string) $originalOSTemplate;

        return $this;
    }

    /**
     * @return string
     */
    public function getOriginalOSTemplate()
    {
        return $this->originalOSTemplate;
    }

    /**
     * @param string $originalServerTemplate
     *
     * @return VirtualServerEntity
     */
    public function setOriginalServerTemplate($originalServerTemplate)
    {
        $this->originalServerTemplate = (string) $originalServerTemplate;

        return $this;
    }

    /**
     * @return string
     */
    public function getOriginalServerTemplate()
    {
        return $this->originalServerTemplate;
    }

    /**
     * @param bool|string $searchDomain
     *
     * @return VirtualServerEntity
     */
    public function setSearchDomain($searchDomain)
    {
        $this->searchDomain = $searchDomain;

        return $this;
    }

    /**
     * @return bool|string
     */
    public function getSearchDomain()
    {
        return $this->searchDomain;
    }

    /**
     * @param boolean $startOnBoot
     *
     * @return VirtualServerEntity
     */
    public function setStartOnBoot($startOnBoot)
    {
        $this->startOnBoot = (bool) $startOnBoot;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isStartOnBoot()
    {
        return $this->startOnBoot;
    }

    /**
     * @param string $state
     *
     * @return VirtualServerEntity
     */
    public function setState($state)
    {
        $states = $this->getStates();
        if (!in_array($state, $states)) {
            throw new InvalidArgumentException(sprintf('Unknown state "%s", should be %s', $state, implode(', ', $states)));
        }
        $this->state = $state;

        return $this;
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param bool|int $userId
     *
     * @return VirtualServerEntity
     */
    public function setUserId($userId)
    {
        $this->userId = $userId === false ? false : (int) $userId;

        return $this;
    }

    /**
     * @return bool|int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param int $vSwap
     *
     * @return VirtualServerEntity
     */
    public function setVSwap($vSwap)
    {
        $this->vSwap = (int) $vSwap;

        return $this;
    }

    /**
     * @return int
     */
    public function getVSwap()
    {
        return $this->vSwap;
    }

    /**
     * Return list of all available virtual server states
     *
     * @return array
     */
    private function getStates()
    {
        return [
            self::STATE_STOPPED,
            self::STATE_RUNNING,
        ];
    }
}
