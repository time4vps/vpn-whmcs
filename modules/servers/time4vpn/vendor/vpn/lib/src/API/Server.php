<?php

namespace Time4VPN\API;

use Time4VPN\Base\Endpoint;
use Time4VPN\Exceptions\APIException;
use Time4VPN\Exceptions\AuthException;
use Time4VPN\Exceptions\Exception;
use Time4VPN\Exceptions\InvalidTaskException;

class Server extends Endpoint
{

    /**
     * Server constructor
     *
     * @param $server_id
     * @throws Exception
     */
    public function __construct($server_id = null)
    {
        parent::__construct('vpn');

        if ($server_id) {
            $this->server_id = (int)$server_id;
            if ($this->server_id <= 0) {
                throw new Exception("Server ID '{$server_id}' is invalid");
            }
        }
    }

    /**
     * Server ID
     *
     * @return int
     * @throws APIException|AuthException
     */
    public function id()
    {
        $this->mustHave('server_id');
        return $this->server_id;
    }

    /**
     * Get all active servers
     *
     * @return array
     * @throws APIException|AuthException
     */
    public function all()
    {
        return $this->get('/servers');
    }
    /**
     * Password reset
     *
     * @return int Task ID
     * @throws APIException|AuthException
     */
    public function resetPassword()
    {
        $this->mustHave('server_id');
        $response = $this->post("/{$this->server_id}/resetpassword");
        return $response;
    }

    /**
     * Get server details
     *
     * @return array Server Details
     * @throws APIException|AuthException
     */
    public function details()
    {
        $this->mustHave('server_id');

        return $this->get("/{$this->server_id}/details");
    }

    /**
     * Fetch Available server List
     *
     * @return int Task ID
     * @throws APIException|AuthException
     */
    public function server_list()
    {
        $this->mustHave('server_id');

        $response = $this->post("/{$this->server_id}/reboot");
        return (int) $response['task_id'];
    }

    /**
     * Server usage history
     *
     * @return array Usage history array
     * @throws APIException|AuthException
     */
    public function usageHistory()
    {
        $this->mustHave('server_id');
        return $this->get("/{$this->server_id}/history");
    }

    /**
     * Server login Details
     *
     * @return array server login details array
     * @throws APIException|AuthException
     */
    public function serverDetails()
    {
        $this->mustHave('server_id');
        return $this->get("/{$this->server_id}/details");
    }

    /**
     * Server Download Links
     *
     * @return array server download links array
     * @throws APIException|AuthException
     */
    public function DownloadLinks()
    {
        return $this->get("/clients");
    }


    // /**
    //  * Reboot server
    //  *
    //  * @return int Task ID
    //  * @throws APIException|AuthException
    //  */
    // public function reboot()
    // {
    //     $this->mustHave('server_id');

    //     $response = $this->post("/{$this->server_id}/reboot");
    //     return (int) $response['task_id'];
    // }

    // /**
    //  * Reinstall server
    //  *
    //  * @param string $os OS code from available OS list
    //  * @param string $ssh_key SSH Key
    //  * @param int $script Init Script ID
    //  * @return int Task ID
    //  * @throws APIException|AuthException
    //  */
    // public function reinstall($os, $ssh_key = null, $script = null)
    // {
    //     $this->mustHave('server_id');

    //     $response = $this->post("/{$this->server_id}/reinstall", [
    //         'os' => $os,
    //         'ssh_key' => $ssh_key,
    //         'script' => $script
    //     ]);

    //     return (int) $response['task_id'];
    // }

    // /**
    //  * Launch emergency console
    //  *
    //  * @param string $timeout 1h, 3h, 12h, etc.
    //  * @param bool $whitelabel Use whitelabel hostname for web based console
    //  * @return int Task ID
    //  * @throws APIException|AuthException
    //  */
    // public function emergencyConsole($timeout = '1h', $whitelabel = true)
    // {
    //     $this->mustHave('server_id');

    //     $response = $this->post("/{$this->server_id}/webconsole", [
    //         'timeout' => $timeout,
    //         'whitelabel' => $whitelabel ? 'true' : 'false'
    //     ]);

    //     return (int) $response['task_id'];
    // }

    // /**
    //  * Changes the hostname of your server. Hostname must pointed to your server main IP address.
    //  *
    //  * @param string $hostname FQDN hostname pointed to main server IP address
    //  * @return int Task ID
    //  * @throws APIException|AuthException
    //  */
    // public function rename($hostname)
    // {
    //     $this->mustHave('server_id');

    //     $response = $this->post("/{$this->server_id}/rename", [
    //         'hostname' => $hostname
    //     ]);

    //     return (int) $response['task_id'];
    // }



    // /**
    //  * Changes PTR record for the additional IP (if server has more than one IPv4 address).
    //  *
    //  * @param string $ip_address Additional IP address
    //  * @param string $hostname FQDN hostname pointed to additional IP address
    //  * @return int Task ID
    //  * @throws APIException|AuthException
    //  */
    // public function setPTR($ip_address, $hostname)
    // {
    //     $this->mustHave('server_id');

    //     $response = $this->post("/{$this->server_id}/changeptr", [
    //         'ip_address' => $ip_address,
    //         'hostname' => $hostname
    //     ]);

    //     return (int) $response['task_id'];
    // }

    // /**
    //  * Flush server firewall
    //  *
    //  * @return int Task ID
    //  * @throws APIException|AuthException
    //  */
    // public function flushFirewall()
    // {
    //     $this->mustHave('server_id');

    //     $response = $this->post("/{$this->server_id}/flushfirewall");
    //     return (int) $response['task_id'];
    // }

    // /**
    //  * Change DNS servers
    //  *
    //  * @param string $ns1 Nameserver 1 IP
    //  * @param string $ns2 Nameserver 2 IP
    //  * @param string $ns3 Nameserver 3 IP
    //  * @param string $ns4 Nameserver 4 IP
    //  * @return int Task ID
    //  * @throws APIException|AuthException
    //  */
    // public function setDNS($ns1, $ns2 = '', $ns3 = '', $ns4 = '')
    // {
    //     $this->mustHave('server_id');

    //     $response = $this->post("/{$this->server_id}/changedns", [
    //         'ns1' => $ns1,
    //         'ns2' => $ns2,
    //         'ns3' => $ns3,
    //         'ns4' => $ns4
    //     ]);

    //     return (int) $response['task_id'];
    // }

    // /**
    //  * Get available OS list for server
    //  *
    //  * @return array OS List
    //  * @throws APIException|AuthException
    //  */
    // public function availableOS()
    // {
    //     $this->mustHave('server_id');
    //     return $this->get("/{$this->server_id}/oses");
    // }

    // /**
    //  * Server usage graphs
    //  *
    //  * @param $width int Image width
    //  * @return array Usage graph array
    //  * @throws APIException|AuthException
    //  */
    // public function usageGraphs($width = 576)
    // {
    //     $this->mustHave('server_id');
    //     return $this->get("/{$this->server_id}/graphs/{$width}");
    // }
    // /**
    //  * Additional IPs
    //  *
    //  * @return array
    //  * @throws APIException|AuthException
    //  */
    // public function additionalIPs()
    // {
    //     $this->mustHave('server_id');
    //     return $this->get("/{$this->server_id}/ips");
    // }

    // /**
    //  * Get task result
    //  *
    //  * @param $task
    //  * @return array
    //  * @throws APIException|AuthException|InvalidTaskException
    //  */
    // public function taskResult($task)
    // {
    //     $this->mustHave('server_id');

    //     try {
    //         $response = $this->get("/{$this->server_id}/task/{$task}");
    //     } catch (APIException $e) {
    //         if ($e->getMessage() === 'Invalid task ID') {
    //             throw new InvalidTaskException($e);
    //         }
    //         throw $e;
    //     }

    //     return $response;
    // }

}