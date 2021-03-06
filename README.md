# WHMCS Time4VPS VPN Module  
  
This is Time4VPS VPN service provisioning module for WHMCS platform.   
  
## Installation  
  
 1. Download latest module [release](https://github.com/time4vps/vpn-whmcs/releases);
 2. Upload archive folder contents to your WHMCS installation root directory;
 3. Login to WHMCS admin panel;
 4. Navigate to `Setup -> Products / Services -> Servers`;
 5. Click `Add new Server` button;
 6. Set following fields:
	- Name: `Time4VPN`;
	- Hostname: `billing.time4vps.com`;
	- Type: `Time4VPN Reseller Module`;
	- Set your Time4VPS username and password accordingly.
7. Create DB tables by navigating to `http://<your whmcs url>/modules/servers/time4vpn/install.php` as Admin.
  
## Product import
To import Time4VPS VPN products:
1. Navigate to `http://<your whmcs url>/modules/servers/time4vpn/update.php` as Admin;
**Run it once, as every other request will reset any changes you made for existing Time4VPS products.**
2. Save a product in product configuration window without making changes.

  
## License  
[MIT](https://github.com/time4vps/vpn-whmcs/blob/main/LICENSE)
