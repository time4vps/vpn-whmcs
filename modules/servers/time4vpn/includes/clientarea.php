<?php

use Time4VPN\Exceptions\InvalidTaskException;

/**
 * Client Area request parser
 *
 * @param $params
 * @return array|mixed|string
 */
function time4vpn_ParseClientAreaRequest($params)
{
    $action = !empty($_REQUEST['act']) ? $_REQUEST['act'] : null;

    if ($action) {
        return call_user_func("time4vpn_ClientArea{$action}", $params);
    }

    return time4vpn_clientAreaDefault($params);
}

/**
 * Default Client Area action
 *
 * @param array $params POST Params
 * @return array|string
 */
function time4vpn_ClientAreaDefault($params)
{
    $error = null;
    $server_details = null;
    $download_links = null;
    $usage_history = null;
    $current_usage_history = null;
    try {
        time4vpn_InitAPI($params);
        $server_details = time4vpn_ServerDetails($params);
        $download_links = time4vpn_DownloadLinks($params);
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
    return [
        'tabOverviewReplacementTemplate' => 'templates/clientarea/clientarea.tpl',
        'templateVariables' => [
            'error' => $error,
            'server_details' => $server_details,
            'download_links' => $download_links,
        ]
    ];
}

/**
 * Client Area Get Available Servers
 *
 * @param $params
 * @return array
 */
function time4vpn_ClientAreaServerList($params)
{
    $response = null;
    $error = null;
    $servers = null;
    $host_url = 'https://';
    if (!empty($params)) {
        $response = time4vpn_serversList($params);
        if ($response['status'] == 'success') {
            $servers = $response['servers'];
            $host_url .= isset($params['serverhostname']) ? $params['serverhostname'] : '';
        } elseif ($response['status'] == 'error') {
            $error = $response['error'];
        }
    }
    return [
        'tabOverviewReplacementTemplate' => 'templates/clientarea/pages/server_list.tpl',
        'templateVariables' => [
            'servers' => $servers,
            'host_url' => $host_url,
            'error' => $error
        ]
    ];
}

/**
 * To reset Server Password
 *
 * @param $params
 * @return array
 */
function time4vpn_ClientAreaPasswordReset($params)
{
    $error = null;

    if (!empty($_POST['confirm'])) {
        $error =  time4vpn_ResetPassword($params);
        if (isset($error['password'])) {
            time4vpn_MarkServerDetailsObsolete($params);
            time4vpn_Redirect(vpn_ActionLink($params, 'PasswordReset&newpassword=' . $error['password']));
        }
    }

    return [
        'tabOverviewReplacementTemplate' => 'templates/clientarea/pages/resetpassword.tpl',
        'templateVariables' => [
            'error' => $error
        ]
    ];
}


/**
 * Usage history
 *
 * @param $params
 * @return array
 */
function time4vpn_ClientAreaUsageHistory($params)
{
    $error = null;
    $usage_history = null;

    try {
        time4vpn_InitAPI($params);
        $usage_history =  time4vpn_UsageHistory($params);
        $units = [' Bytes',' KB',' MB',' GB',' TB'];
        foreach ($usage_history as $key => $history) {
            $usage_history[$key]['usage'] = round(( ($usage_history[$key]['received'] + $usage_history[$key]['sent']) / ($usage_history[$key]['quota']) ) * 100, 2);
            $binary_unit = 1;
            while ($history['received'] / 1024 > 1023) {
                $history['received'] = $history['received'] / 1024;
                $binary_unit++;
            }
            $usage_history[$key]['received'] = round($history['received'] / 1024, 2) . $units[$binary_unit];

            $binary_unit = 1;
            while ($history['sent'] / 1024 > 1023) {
                $history['sent'] = $history['sent'] / 1024;
                $binary_unit++;
            }
            $usage_history[$key]['sent'] = round($history['sent'] / 1024, 2) . $units[$binary_unit];

            $binary_unit = 1;
            while ($history['quota'] / 1024 > 1023) {
                $history['quota'] = $history['quota'] / 1024;
                $binary_unit++;
            }
            $usage_history[$key]['quota'] = round($history['quota'] / 1024, 2) . $units[$binary_unit];
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }

    return [
        'tabOverviewReplacementTemplate' => 'templates/clientarea/pages/usagehistory.tpl',
        'templateVariables' => [
            'error' => $error,
            'usage_history' => $usage_history
        ]
    ];
}
