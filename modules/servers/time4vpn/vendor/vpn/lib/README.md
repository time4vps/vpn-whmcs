# Time4VPN User API PHP Library

Time4VPN lib is a PHP library that makes easy to interact with Time4VPN UserAPI.

## Installation
Use `composer` package manager to install library:
```bash
composer install time4vps/lib
```

## Usage

```php
require_once '/vendor/autoload.php';

// Auth details
$username = 'user';
$password = 'pass123';

// Setup endpoint
Time4VPN\Base\Endpoint::BaseURL('https://billing.time4vps.com/api/');
Time4VPN\Base\Endpoint::Auth($username, $password);

// Get server details
$server_id = 748457;
$server = new Time4VPN\API\Server($server_id);

var_dump($server->details());

// Reboot server
try {
    $server->reboot();
} catch (Time4VPN\Exceptions\APIException $e) {
    die("Failed to reboot server: {$e->getMessage()}");
}
```

## License
[MIT](https://github.com/time4vps/time4vps-lib/blob/master/LICENSE)