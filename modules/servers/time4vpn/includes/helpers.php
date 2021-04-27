<?php

use Time4VPN\API\Server;
use Time4VPN\Base\Endpoint;
use WHMCS\Database\Capsule;

/**
 * VPN API Initialisation function
 *
 * @param $params
 */
function time4vpn_InitAPI($params)
{
    Endpoint::BaseURL("{$params['serverhttpprefix']}://{$params['serverhostname']}/api/");
    Endpoint::Auth($params['serverusername'], $params['serverpassword']);
}

/**
 * Get VPN server ID from params
 *
 * @param $params
 * @return Server|false External server ID or false
 * @throws Exception
 */
function time4vpn_ExtractServer($params)
{
    if ($server = Capsule::table(TIME4VPN_TABLE)->where('service_id', $params['serviceid'])->first()) {
        /** @var Server $s */
        return new Server($server->external_id);
    }

    throw new Exception('Unable to find related server');
}

/**
 * Get component ID by it's name
 *
 * @param string $name
 * @param int $pid
 * @return object|null
 */
function time4vpn_GetComponentIdByName($name, $pid)
{
    $component = Capsule::table('tblproductconfigoptions')
        ->select('tblproductconfigoptions.id')
        ->join('tblproductconfiglinks', 'tblproductconfiglinks.gid', '=', 'tblproductconfigoptions.gid')
        ->where('tblproductconfigoptions.name', $name)
        ->where('tblproductconfiglinks.pid', $pid)
        ->first();

    return $component ? $component->id : null;
}

/**
 * Return main page link
 *
 * @param $params
 * @return string
 */
function time4vpn_ProductDetailsLink($params)
{
    return "clientarea.php?action=productdetails&id={$params['serviceid']}";
}

/**
 * Return action link
 *
 * @param $params
 * @param $action
 * @return string
 */
function time4vpn_ActionLink($params, $action)
{
    return "clientarea.php?action=productdetails&id={$params['serviceid']}&act={$action}";
}

/**
 * Redirect user to URL
 *
 * @param $url
 */
function time4vpn_Redirect($url)
{
    header("Location: {$url}");
    exit();
}

/**
 * Extract billing cycle
 *
 * @param $cycle
 * @return string
 */
function time4vpn_BillingCycle($cycle)
{
    switch ($cycle) {
        case 'Monthly':
            return 'm';
        case 'Quarterly':
            return 'q';
        case 'Semi-Annually':
            return 's';
        case 'Annually':
            return 'a';
        case 'Biennially':
            return 'b';
    }

    return null;
}

/**
 * Extract package options from params
 *
 * @param $params
 * @param bool $skip_disabled
 * @return array
 */
function time4vpn_ExtractComponents($params, $skip_disabled = true)
{
    $custom = [];
    $map = json_decode($params['configoption5'], true);
    if ($params['configoptions'] && $map['components']) {
        foreach ($params['configoptions'] as $configoption => $enabled) {
            if (!$enabled && $skip_disabled) {
                continue;
            }

            $option = Capsule::table('tblproductconfigoptions')
                ->select('tblproductconfigoptions.id')
                ->join('tblproductconfiggroups', 'tblproductconfiggroups.id', '=', 'tblproductconfigoptions.gid')
                ->join('tblproductconfiglinks', 'tblproductconfiglinks.gid', '=', 'tblproductconfiggroups.id')
                ->where('tblproductconfiglinks.pid', $params['pid'])
                ->where('tblproductconfigoptions.optionname', $configoption)
                ->first();

            if (!$option || empty($map['components'][$option->id])) {
                continue;
            }

            $component = $map['components'][$option->id];
            $custom[$component['category_id']][$component['item_id']] = $enabled;
        }
    }

    return $custom;
}

/**
 * Added by HT - 2021-04-22
 * 
 * Get service details from external billing system
 *
 * @param $params
 * @return array
 */
function vpn_GetServiceDetails($params)
{
    time4vpn_InitAPI($params);

  $serviceid = $params['serviceid'];

  $result = Capsule::table(TIME4VPN_TABLE)
    ->select('external_id')
    ->where('service_id', $serviceid)
    ->first();

  if (!$result)
    throw new Exception("Service ID [{$serviceid}] not found in table mod_vpn.");

  $externalid = $result->external_id;

  $service = new Service($externalid);
  return $service->details();
}