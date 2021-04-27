<?php

use Time4VPN\API\Order;
use Time4VPN\API\Service;
use WHMCS\Database\Capsule;

/**
 * Create account
 *
 * @param $params
 * @return string|void
 * @throws Exception
 */
function time4vpn_CreateAccount($params)
{

    time4vpn_InitAPI($params);

    try {
        if ($server = time4vpn_ExtractServer($params)) {
            return 'Service is already created';
        }
    } catch (Exception $e) {
    }

    $product_id = $params['configoption1'];

    try {
        $order = new Order();
        $order = $order->create($product_id, 'serverhost.name', time4vpn_BillingCycle($params['model']['billingcycle']), time4vpn_ExtractComponents($params));

        $service_id = (new Service())->fromOrder($order['order_num']);
        Capsule::table(TIME4VPN_TABLE)->insert([
            'external_id' => $service_id,
            'service_id' => $params['serviceid'],
            'details_updated' => null
        ]);
    } catch (Exception $e) {
        return 'Cannot create account. ' . $e->getMessage();
    }

    // Now that account is created, get the details
    time4vpn_UpdateServerDetails($params);

    return 'success';
}


/**
 * Change package
 *
 * @param $params
 * @return string|void
 * @throws Exception
 */

 
function time4vpn_ChangePackage($params)
{

    time4vpn_InitAPI($params);

    try {
        $server = time4vpn_ExtractServer($params);
        $service = new Service($server->id());

        // The external package id for new plan
        $product_id = (int) $params['configoption1'];

        // Upgrades array
        $upgradeArray = array();
        $upgradeArray['package'] = $product_id;

        $result = $service->orderUpgrade($upgradeArray);

    } catch (Exception $e) { return 'Error: ' . $e->getMessage(); }

    return 'success';
}


/**
 * Terminate account
 *
 * @param $params
 * @return string|void
 * @throws Exception
 */
function time4vpn_TerminateAccount($params)
{

    time4vpn_InitAPI($params);

    try {
        $server = time4vpn_ExtractServer($params);

        $service = new Service($server->id());

        $result = $service->cancel("Terminated via WHMCS", true);

        if (empty($result) || $result["info"][0] != "cancell_sent"){
            throw new Exception("Failed to terminate service ID " . $server->id() . " in Time4VPS.");
        }

    } catch (Exception $e) { return 'Error: ' . $e->getMessage(); }

    return 'success';
}


/**
 * Fetch All Available servers
 *
 * @param $params
 * @return string|void
 * @throws Exception
 */
function time4vpn_serversList($params)
{
    $servers_list = [];
    try {
        time4vpn_InitAPI($params);
        $server = time4vpn_ExtractServer($params);
        $servers_list['servers'] = $server->all(); // to fetch all available servers
        $servers_list['status'] = 'success';
        $servers_list['error'] = '';
    } catch (Exception $e) {
        $servers_list['status'] = 'error';
        $servers_list['servers'] = [];
        $servers_list['error'] = $e->getMessage();
    }
    return $servers_list;
}

/**
 * Changes server password
 *
 * @param $params
 * @return string
 */
function time4vpn_ResetPassword($params)
{
    time4vpn_InitAPI($params);
    $new_password = 'error';
    try {
        $server = time4vpn_ExtractServer($params);
        $new_password  = $server->resetPassword();
    } catch (Exception $e) {
        return 'Cannot change server password. ' . $e->getMessage();
    }

    return $new_password;
}

/**
 * Update server details table and mark details as obsolete
 *
 * @param $params
 */
function time4vpn_MarkServerDetailsObsolete($params)
{
    /** @noinspection PhpUndefinedClassInspection */
    Capsule::table(TIME4VPN_TABLE)
        ->where('service_id', $params['serviceid'])
        ->update([
            'details_updated' => null
        ]);
}

/**
 * Fetch Usage Graph
 *
 * @param $params
 * @return string
 */
function time4vpn_UsageHistory($params)
{
    time4vpn_InitAPI($params);
    $usage_history = [];
    try {
        $server = time4vpn_ExtractServer($params);
        $usage_history = $server->usageHistory();
    } catch (Exception $e) {
    }
    return $usage_history;
}

/**
 * Fetch Server Login Details
 *
 * @param $params
 * @return string
 */
function time4vpn_ServerDetails($params)
{
    time4vpn_InitAPI($params);
    $server_details = [];
    try {
        $server = time4vpn_ExtractServer($params);
        $server_details = $server->serverDetails();
    } catch (Exception $e) {
    }
    return $server_details;
}

/**
 * Fetch Server Download Links
 *
 * @param $params
 * @return string
 */
function time4vpn_DownloadLinks($params)
{
    time4vpn_InitAPI($params);
    $server_details = [];
    try {
        $server = time4vpn_ExtractServer($params);
        $server_details = $server->DownloadLinks();
    } catch (Exception $e) {
    }
    return $server_details;
}
