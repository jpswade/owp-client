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

use Erliz\OwpClient\Entity\HardwareServerEntity;
use Erliz\OwpClient\Entity\VirtualServerEntity;
use Erliz\OwpClient\Exception\ClientException;
use SimpleXMLElement;

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
     * @param bool $fetchVirtualServers
     *
     * @return Entity\HardwareServerEntity[]
     * @throws ClientException
     */
    public function getHardwareServers($fetchVirtualServers = false)
    {
        $hardwareServers = [];
        $response = $this->makeRequest('hardware_servers/list');
        foreach ($response->hardware_server as $node) {
            $hardwareServer = $this->generateHardwareServerEntity($node);
            if ($fetchVirtualServers) {
                $hardwareServer->setVirtualServers(
                    $this->getVirtualServersByHardwareServerId($hardwareServer->getId())
                );
            }
            $hardwareServers[] = $hardwareServer;
        }

        return $hardwareServers;
    }

    /**
     * @param int $id
     *
     * @return VirtualServerEntity[]
     * @throws ClientException
     */
    public function getVirtualServersByHardwareServerId($id)
    {
        $virtualServers = [];
        $response = $this->makeRequest(sprintf('hardware_servers/virtual_servers?id=%d', $id));
        foreach ($response->virtual_server as $node) {
            $virtualServers[] = $this->generateVirtualServerEntity($node);
        }

        return $virtualServers;
    }

    /**
     * @return VirtualServerEntity[]
     */
    public function getAllVirtualServers()
    {
        $virtualServers = [];
        /** @var HardwareServerEntity $hardwareServer */
        foreach ($this->getHardwareServers() as $hardwareServer) {
            $hwVtServers = $this->getVirtualServersByHardwareServerId($hardwareServer->getId());
            $virtualServers = array_merge($virtualServers, $hwVtServers);
        }

        return $virtualServers;
    }

    /**
     * @param string $method
     *
     * @return mixed
     * @throws ClientException
     */
    protected function makeRequest($method)
    {
        $curlHandler = curl_init();

        curl_setopt($curlHandler, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curlHandler, CURLOPT_VERBOSE, 0);
        curl_setopt($curlHandler, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curlHandler, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curlHandler, CURLOPT_HTTPHEADER, []);
        curl_setopt($curlHandler, CURLOPT_HEADER, 0);
        curl_setopt($curlHandler, CURLOPT_POST, 0);
        curl_setopt($curlHandler, CURLOPT_URL, sprintf("%s://%s:%s/api/%s", $this->scheme, $this->host, $this->port, $method));
        if (!empty($this->user) && !empty($this->pass)) {
            curl_setopt($curlHandler, CURLOPT_USERPWD, sprintf('%s:%s', $this->user, $this->pass));
        }
        $result = curl_exec($curlHandler);
        $curlError = curl_error($curlHandler);

        if ($curlError) {
            throw new ClientException(sprintf('cURL Error: %s', $curlError));
        }
        curl_close($curlHandler);
        $doc = simplexml_load_string($result);

        return $doc;
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

}
