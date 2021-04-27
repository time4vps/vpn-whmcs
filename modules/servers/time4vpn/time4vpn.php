<?php

if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

require 'defines.php';

use WHMCS\Database\Capsule;
use Time4VPN\API\Product;
use Time4VPN\API\Script;
use Time4VPN\Exceptions\APIException;
use Time4VPN\Exceptions\AuthException;

require_once dirname(__FILE__) . '/vendor/autoload.php';
require_once dirname(__FILE__) . '/includes/helpers.php';
require_once dirname(__FILE__) . '/includes/server.php';
require_once dirname(__FILE__) . '/includes/clientarea.php';
require_once dirname(__FILE__) . '/includes/adminarea.php';

/**
 * Module metadata
 *
 * @return array
 */
function time4vpn_MetaData()
{
    return [
        'DisplayName'    => 'TIME4VPN Reseller Module',
        'APIVersion'     => '1.0',
        'RequiresServer' => true,
    ];
}

/**
 * Module configuration options
 *
 * @return array
 */
function time4vpn_ConfigOptions()
{
    return [
        "product" => [
            "FriendlyName" => "Product",
            "Type" => "dropdown",
            "Loader" => "time4vpn_ProductLoaderFunction",
            "SimpleMode" => true
        ]
    ];
}

/**
 * Loads product list in Module configuration menu
 *
 * @param $params
 * @return array
 * @throws APIException
 * @throws AuthException
 */
function time4vpn_ProductLoaderFunction($params)
{
    time4vpn_InitAPI($params);

    $products = new Product();
    $available_products = [];
    foreach ($products->getAvailableVPN()['products'] as $product) {
        $available_products[$product['id']] = $product['name'];
    }
    return $available_products;
}

/**
 * Test API connection
 *
 * @param $params
 * @return array
 */
function time4vpn_TestConnection($params)
{
    try {
        time4vpn_ProductLoaderFunction($params);
        $success = true;
        $errorMsg = '';
    } catch (Exception $e) {
        $success = false;
        $errorMsg = $e->getMessage();
    }

    return [
        'success' => $success,
        'error' => $errorMsg
    ];
}

/**
 * Custom Admin Area Buttons
 *
 * @return array
 */
function time4vpn_AdminCustomButtonArray()
{
    return [
        'Update Details' => 'UpdateServerDetails',
        'Reset Password' => 'AdminResetPassword'
    ];
}

/**
 * Show Client Area
 *
 * @param $params
 * @return array|string
 */
function time4vpn_ClientArea($params)
{
    return time4vpn_ParseClientAreaRequest($params);
}
