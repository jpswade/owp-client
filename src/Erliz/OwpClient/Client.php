<?php

/**
 * OwpClient package
 *
 * @package   Erliz\OwpClient
 * @author    Stanislav Vetlovskiy <mrerliz@gmail.com>
 * @copyright Copyright (c) Stanislav Vetlovskiy
 * @license   MIT
 */

namespace Erliz\OwpClient;

use Erliz\OwpClient\Entity\DiskUsageMountEntity;
use Erliz\OwpClient\Entity\DiskUsageStatEntity;
use Erliz\OwpClient\Entity\HardwareServerEntity;
use Erliz\OwpClient\Entity\HardwareServerStatsEntity;
use Erliz\OwpClient\Entity\RamUsageStatEntity;
use Erliz\OwpClient\Entity\VirtualServerEntity;
use Erliz\OwpClient\Entity\VirtualServerStatsEntity;
use Erliz\OwpClient\Exception\ClientException;
use Erliz\OwpClient\Exception\InvalidArgumentException;
use Erliz\OwpClient\Parser\MemoryParser;
use SimpleXMLElement;
use stdClass;

/**
 * Class Client
 *
 * @author Stanislav Vetlovskiy <mrerliz@gmail.com>
 */
class Client
{
    /** @var string */
    private $host;
    /** @var string */
    private $port;
    /** @var string */
    private $user;
    /** @var string */
    private $pass;
    /** @var string */
    private $scheme;

    /**
     * Owp constructor.
     * @param string $host
     * @param string $user
     * @param string $pass
     * @param int    $port
     * @param string $scheme
     */
    public function __construct($host, $user = null, $pass = null, $port = 80, $scheme = 'http')
    {
        $this->host = $host;
        $this->user = $user;
        $this->pass = $pass;
        $this->port = $port;
        $this->scheme = $scheme;
    }

    /**
     * @param int  $id
     * @param bool $fetchVirtualServers
     * @param bool $fetchStats
     *
     * @return HardwareServerEntity
     * @throws ClientException
     */
    public function getHardwareServerById($id, $fetchVirtualServers = false, $fetchStats = false)
    {
        $response = $this->makeRequest(sprintf('hardware_servers/get?id=%d', $id));

        return $this->getHardwareServerFromNode($response, $fetchVirtualServers, $fetchStats);
    }

    /**
     * @param bool $fetchVirtualServers
     * @param bool $fetchStats
     *
     * @return HardwareServerEntity[]
     * @throws ClientException
     */
    public function getHardwareServers($fetchVirtualServers = false, $fetchStats = false)
    {
        $hardwareServers = [];
        $response = $this->makeRequest('hardware_servers/list');
        foreach ($response->hardware_server as $node) {
            $hardwareServers[] = $this->getHardwareServerFromNode($node, $fetchVirtualServers, $fetchStats);
        }

        return $hardwareServers;
    }

    /**
     * @param SimpleXMLElement $node
     * @param bool             $fetchVirtualServers
     * @param bool             $fetchStats
     *
     * @return HardwareServerEntity
     */
    private function getHardwareServerFromNode($node, $fetchVirtualServers = false, $fetchStats = false)
    {
        $hardwareServer = $this->generateHardwareServerEntity($node);
        if ($fetchVirtualServers) {
            $hardwareServer->setVirtualServers(
                $this->getVirtualServersByHardwareServerId($hardwareServer->getId(), $fetchStats)
            );
        }
        if ($fetchStats) {
            $hardwareServer->setStats(
                $this->getHardwareServerStatsById($hardwareServer->getId())
            );
        }

        return $hardwareServer;
    }

    /**
     * @param int  $id
     * @param bool $fetchStats
     * 
     * @return VirtualServerEntity[]
     * @throws ClientException
     */
    public function getVirtualServersByHardwareServerId($id, $fetchStats = false)
    {
        $virtualServers = [];
        $response = $this->makeRequest(sprintf('hardware_servers/virtual_servers?id=%d', $id));
        foreach ($response->virtual_server as $node) {
            $virtualServer = $this->generateVirtualServerEntity($node);
            if ($fetchStats) {
                $virtualServer->setStats(
                    $this->getVirtualServerStatsById($virtualServer->getId())
                );
            }
            $virtualServers[] = $virtualServer;
        }

        return $virtualServers;
    }

    /**
     * @param bool $fetchStats
     * @return VirtualServerEntity[]
     */
    public function getAllVirtualServers($fetchStats = false)
    {
        $virtualServers = [];
        /** @var HardwareServerEntity $hardwareServer */
        foreach ($this->getHardwareServers() as $hardwareServer) {
            $hwVtServers = $this->getVirtualServersByHardwareServerId($hardwareServer->getId(), $fetchStats);
            $virtualServers = array_merge($virtualServers, $hwVtServers);
        }

        return $virtualServers;
    }

    /**
     * @param string $method
     * @param string $responseType
     *
     * @return SimpleXMLElement|stdClass
     * @throws ClientException
     */
    public function makeRequest($method, $responseType = 'xml')
    {
        $result = $this->getResult($method, $responseType);
        return $this->parseResult($method, $responseType, $result);
    }

    /**
     * @param SimpleXMLElement $serverData
     *
     * @return HardwareServerEntity
     */
    private function generateHardwareServerEntity(SimpleXMLElement $serverData)
    {
        $server = new HardwareServerEntity();
        $server
            ->setDaemonPort((int) $serverData->daemon_port)
            ->setDefaultOSTemplate((string) $serverData->default_os_template)
            ->setDefaultServerTemplate((string) $serverData->default_server_template)
            ->setDescription((string) $serverData->description)
            ->setHost((string) $serverData->host)
            ->setId((int) $serverData->id)
            ->setUseSSL(filter_var((string) $serverData->use_ssl, FILTER_VALIDATE_BOOLEAN))
            ->setVSwap(filter_var((string) $serverData->vswap, FILTER_VALIDATE_BOOLEAN))
        ;

        return $server;
    }

    /**
     * @param SimpleXMLElement $serverData
     *
     * @return VirtualServerEntity
     */
    private function generateVirtualServerEntity(SimpleXMLElement $serverData)
    {
        $server = new VirtualServerEntity();
        $server
            ->setCpuLimit($this->isNilProperty($serverData->cpu_limit) ? false : $serverData->cpu_limit)
            ->setCpuUnits($this->isNilProperty($serverData->cpu_units) ? false : $serverData->cpu_units)
            ->setCpus($this->isNilProperty($serverData->cpus) ? false : $serverData->cpus)
            ->setDailyBackup(filter_var((string) $serverData->daily_backup, FILTER_VALIDATE_BOOLEAN))
            ->setDescription($serverData->description)
            ->setDiskSpace((int) $serverData->diskspace)
            ->setExpirationDate(
                $this->isNilProperty($serverData->expiration_date) ?
                    false :
                    date_create_from_format('Y-m-d|', (string) $serverData->expiration_date)
            )
            ->setHardwareServerId((int) $serverData->hardware_server_id)
            ->setHostName($serverData->host_name)
            ->setId((int) $serverData->id)
            ->setIdentity((int) $serverData->identity)
            ->setIpAddress($serverData->ip_address)
            ->setMemory((int) $serverData->memory)
            ->setNameServer($this->isNilProperty($serverData->nameserver) ? false : (string) $serverData->nameserver)
            ->setOriginalOSTemplate($serverData->orig_os_template)
            ->setOriginalServerTemplate($serverData->orig_server_template)
            ->setSearchDomain(
                $this->isNilProperty($serverData->search_domain) ? false : (string) $serverData->search_domain
            )
            ->setStartOnBoot(filter_var((string) $serverData->start_on_boot, FILTER_VALIDATE_BOOLEAN))
            ->setState((string) $serverData->state)
            ->setUserId($this->isNilProperty($serverData->user_id) ? false : $serverData->user_id)
            ->setVSwap((int) $serverData->vswap)
        ;

        return $server;
    }

    /**
     * Detect if property have nil attribute and it`s set to "true"
     *
     * @param $prop
     *
     * @return bool
     */
    private function isNilProperty($prop)
    {
        if (!isset($prop['nil'])) {
            return false;
        }

        return filter_var((string) $prop['nil'], FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * @param int $id
     *
     * @return HardwareServerStatsEntity
     */
    private function getHardwareServerStatsById($id)
    {
        $response = $this->makeRequest(
            sprintf(
                'hardware-servers/get_stats?id=%d&_dc=%s',
                $id,
                time()
            ),
            'json'
        );
        $diskString = 'Disk usage, partition ';
        $diskUsageStat = new DiskUsageStatEntity();
        $stats = new HardwareServerStatsEntity();
        $stats->setDiskUsage($diskUsageStat);
        foreach ($response->data as $property) {
            switch ($property->parameter) {
                case 'OS version':
                    if ($property->value !== '-') {
                        $stats->setOSVersion(str_replace(["\n", "\r"], '', $property->value));
                    }
                    break;
                case 'CPU load average':
                    if ($property->value !== '-') {
                        $stats->setCPULoadAverage(
                            array_map(
                                'floatval',
                                explode(' ', $property->value)
                            )
                        );
                    }
                    break;
                case (strpos($property->parameter, $diskString) !== false):
                    $stat = MemoryParser::parse($property->value->text);
                    $diskMount = new DiskUsageMountEntity();
                    $diskMount
                        ->setMountPoint(str_replace($diskString, '', $property->parameter))
                        ->setUsagePercent($stat['percent'])
                        ->setTotalSize($stat['total'])
                        ->setFreeSize($stat['free'])
                        ->setUsedSize($stat['used']);
                    $diskUsageStat->addMountPoint($diskMount);
                    break;
                case 'Memory usage':
                    $stat = MemoryParser::parse($property->value->text);
                    $ramUsage = new RamUsageStatEntity();
                    $ramUsage
                        ->setUsagePercent($stat['percent'])
                        ->setTotalSize($stat['total'])
                        ->setFreeSize($stat['free'])
                        ->setUsedSize($stat['used']);
                    $stats->setRamUsage($ramUsage);
                    break;
                default:
                    throw new InvalidArgumentException(sprintf('Unknown property "%s"'), $property->parameter);
            }
        }

        return $stats;
    }

    /**
     * @param int $id
     *
     * @return VirtualServerStatsEntity
     */
    private function getVirtualServerStatsById($id)
    {
        $response = $this->makeRequest(
            sprintf(
                'virtual-servers/get_stats?id=%d&_dc=%s',
                $id,
                time()
            ),
            'json'
        );
        $diskUsageStat = new DiskUsageStatEntity();
        $stats = new VirtualServerStatsEntity();
        $stats->setDiskUsage($diskUsageStat);
        foreach ($response->data as $property) {
            switch ($property->parameter) {
                case 'CPU load average':
                    if ($property->value !== '-') {
                        $stats->setCPULoadAverage([(float) $property->value->percent]);
                    }
                    break;
                case 'Disk usage':
                    if ($property->value !== '-') {
                        $stat = MemoryParser::parse($property->value->text);
                        $diskMount = new DiskUsageMountEntity();
                        $diskMount
                            ->setMountPoint('/')
                            ->setUsagePercent($stat['percent'])
                            ->setTotalSize($stat['total'])
                            ->setFreeSize($stat['free'])
                            ->setUsedSize($stat['used']);
                        $diskUsageStat->addMountPoint($diskMount);
                    }
                    break;
                case 'Memory usage':
                    if ($property->value !== '-') {
                        $stat = MemoryParser::parse($property->value->text);
                        $ramUsage = new RamUsageStatEntity();
                        $ramUsage
                            ->setUsagePercent($stat['percent'])
                            ->setTotalSize($stat['total'])
                            ->setFreeSize($stat['free'])
                            ->setUsedSize($stat['used']);
                        $stats->setRamUsage($ramUsage);
                    }
                    break;
                default:
                    throw new InvalidArgumentException(sprintf('Unknown property "%s"'), $property->parameter);
            }
        }

        return $stats;
    }

    /**
     * @param $method
     * @param $responseType
     * @param $result
     * @return mixed|SimpleXMLElement
     * @throws ClientException
     */
    private function parseResult($method, $responseType, $result)
    {
        if ($responseType == 'xml') {
            $doc = simplexml_load_string($result);
            if ($doc->code == 'object_not_found') {
                throw new ClientException(sprintf('Server error response with message "%s"', $doc->message));
            }
        } elseif ($responseType == 'json') {
            $doc = json_decode($result);
            if ($doc->success === false) {
                throw new ClientException(sprintf('Fail to get valid response on "%s" method', $method));
            }
        } else {
            throw new InvalidArgumentException(sprintf('Unknown response type "%s"', $responseType));
        }

        return $doc;
    }

    /**
     * @param $method
     * @param $responseType
     * @return bool|string
     * @throws ClientException
     */
    public function getResult($method, $responseType)
    {
        $curlHandler = curl_init();

        if ($responseType == 'xml') {
            $url = sprintf("%s://%s:%s/api/%s", $this->scheme, $this->host, $this->port, $method);
        } elseif ($responseType == 'json') {
            $url = sprintf("%s://%s:%s/admin/%s", $this->scheme, $this->host, $this->port, $method);
        } else {
            throw new InvalidArgumentException(sprintf('Unknown response type "%s"', $responseType));
        }

        curl_setopt($curlHandler, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curlHandler, CURLOPT_VERBOSE, 0);
        curl_setopt($curlHandler, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curlHandler, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curlHandler, CURLOPT_HTTPHEADER, []);
        curl_setopt($curlHandler, CURLOPT_HEADER, 0);
        curl_setopt($curlHandler, CURLOPT_POST, 0);
        curl_setopt($curlHandler, CURLOPT_URL, $url);
        if (!empty($this->user) && !empty($this->pass)) {
            curl_setopt($curlHandler, CURLOPT_USERPWD, sprintf('%s:%s', $this->user, $this->pass));
        }
        $result = curl_exec($curlHandler);
        $curlError = curl_error($curlHandler);

        if ($curlError) {
            throw new ClientException(sprintf('cURL Error: %s', $curlError));
        }
        curl_close($curlHandler);
        return $result;
    }
}
