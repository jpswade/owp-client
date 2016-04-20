[![Packagist](https://img.shields.io/packagist/l/Erliz/owp-client.svg?maxAge=2592000)](https://packagist.org/packages/erliz/owp-client)
[![Packagist](https://img.shields.io/packagist/v/Erliz/owp-client.svg?maxAge=2592000)](https://packagist.org/packages/erliz/owp-client)
[![Build Status](https://travis-ci.org/Erliz/owp-client.svg?branch=master)](https://travis-ci.org/Erliz/owp-client)
[![Dependency Status](https://gemnasium.com/Erliz/owp-client.svg)](https://gemnasium.com/Erliz/owp-client)
[![codecov.io](https://img.shields.io/codecov/c/github/Erliz/owp-client.svg?maxAge=2592000)](https://codecov.io/github/Erliz/owp-client?branch=master)
[![SensioLabs Insight](https://img.shields.io/sensiolabs/i/2e2bcc1f-d11f-4afb-8515-21d53b78cb8d.svg?maxAge=2592000)](https://insight.sensiolabs.com/projects/2e2bcc1f-d11f-4afb-8515-21d53b78cb8d)
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
