# Client for OpenVZ web panel (OWP)

## Usage
### Create client
```
use Erliz\OwpClient\Client;
$owpClient = new Client('localhost', 'admin', 'passwd', 443);
```

### Get hardware servers
```
$harwareServers = $owpClient->getHardwareServers()
```

### Get list of all virtual servers
```
$virtualServers = $owpClient->getAllVirtualServers()
```
