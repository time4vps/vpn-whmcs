<?php

require 'defines.php';
require ROOTPATH . '/init.php';

use WHMCS\Database\Capsule;

session_start();

if (!$_SESSION['adminid']) {
    die('Access denied');
}

/** Disable "Configure Server" on order page */
$tpl_path = ROOTPATH . "/templates/orderforms/standard_cart/configureproduct.tpl";
$tpl_path_copy = $tpl_path . '.orig.tpl';
$tpl_path_copy_v1 = $tpl_path . '.orig.v1.tpl';

if (!file_exists($tpl_path_copy_v1)) {
    /** Save current configureproduct.tpl */
    file_put_contents($tpl_path_copy_v1, file_get_contents($tpl_path));

    if (file_exists($tpl_path_copy)) {
        /** Restore original template in case users already has installed this module and we need to re-patch it **/
        file_put_contents($tpl_path, file_get_contents($tpl_path_copy));
    } else {
        /** Make a copy */
        file_put_contents($tpl_path_copy, file_get_contents($tpl_path));
    }

    /** Change template */
    $tpl = file_get_contents($tpl_path);
    $repl = '$1 && $productinfo.module eq "time4vpn"}' . PHP_EOL;
    $repl .= "\t\t\t\t" . '<input type="hidden" name="hostname" value="{$smarty.now}.serverhost.name" />' . PHP_EOL;
    $repl .= "\t\t\t\t" . '<input type="hidden" name="rootpw" value="rootpwd" />' . PHP_EOL;
    $repl .= "\t\t\t\t" . '<input type="hidden" name="ns1prefix" value="ns1" />' . PHP_EOL;
    $repl .= "\t\t\t\t" . '<input type="hidden" name="ns2prefix" value="ns2" />' . PHP_EOL;
    $repl .= "\t\t\t" . '{else $1 && $productinfo.module neq "vpn"}' . PHP_EOL;
    $tpl = preg_replace('/(if \$productinfo\.type eq "server")}/', $repl, $tpl);
    file_put_contents($tpl_path, $tpl);
}

/** Update products */
require_once dirname(__FILE__) . '/vendor/autoload.php';
require_once dirname(__FILE__) . '/includes/helpers.php';

$data = Capsule::table('tblservers')->where('type', 'time4vpn')->first();

if (!$data) {
    die('No module server found');
}

$decrypt = localAPI('DecryptPassword', ['password2' => $data->password]);

$api = [
    'serverhttpprefix' => $data->secure === 'on' ? 'https' : 'http',
    'serverhostname' => $data->hostname,
    'serverusername' => $data->username,
    'serverpassword' => $decrypt['password']
];

time4vpn_InitAPI($api);

$products = (new Time4VPN\API\Product())->getAvailableVPN()['products'];

/** Create product group */
$gid = Capsule::table('tblproductgroups')->where(['name' => 'Time4VPN'])->first();
if (!$gid) {
    $gid = Capsule::table('tblproductgroups')->insertGetId(['name' => 'Time4VPN']);
} else {
    $gid = $gid->id;
}

/**
 *
 * Import TIME4VPN products
 *
 */

/** Map t4v product id => whmcs product id */
$product_map = [];

/** Iterate each product */
foreach ($products as $product) {

    /** Create or Update WHMCS Product */
    Capsule::table('tblproducts')->updateOrInsert([
        'configoption1' => $product['id'],
        'servertype' => 'time4vpn'
    ], [
        'name' => $product['name'],
        'gid' => $gid,
        'type' => 'other',
        'description' => $product['description'],
        'autosetup' => 'payment',
        'paytype' => 'recurring',
        'tax' => 1,
    ]);

    /** Get product */
    $p = Capsule::table('tblproducts')->where([
        'configoption1' => $product['id'],
        'servertype' => 'time4vpn'
    ])->first();

    if (!$p) {
        throw new Exception("Product {$product['id']} was not found");
    }

    /** Map it for later use */
    $product_map[$product['id']] = $p->id;

    $pprice = array();
    $pprice['currency'] = 1;
    $pprice['msetupfee']    = '0';
    $pprice['monthly']      = '-1';
    $pprice['qsetupfee']    = '0';
    $pprice['quarterly']    = '-1';
    $pprice['ssetupfee']    = '0';
    $pprice['semiannually'] = '-1';
    $pprice['asetupfee']    = '0';
    $pprice['annually']     = '-1';
    $pprice['bsetupfee']    = '0';
    $pprice['biennially']   = '-1';
    $pprice['tsetupfee']    = '0';
    $pprice['triennially']   = '-1';
    foreach ($product['periods'] as $period) {
        if (strtolower($period['title']) == 'monthly') {
            $pprice['msetupfee'] = $period['setup'];
            $pprice['monthly'] = $period['price'] == '0.00' ? '-1' : $period['price'];
        } elseif (strtolower($period['title']) == 'quarterly') {
            $pprice['qsetupfee'] = $period['setup'];
            $pprice['quarterly'] = $period['price'] == '0.00' ? '-1' : $period['price'];
        } elseif (strtolower($period['title']) == 'semi-annually') {
            $pprice['ssetupfee'] = $period['setup'];
            $pprice['semiannually'] = $period['price'] == '0.00' ? '-1' : $period['price'];
        } elseif (strtolower($period['title']) == 'annually') {
            $pprice['asetupfee'] = $period['setup'];
            $pprice['annually'] = $period['price'] == '0.00' ? '-1' : $period['price'];
        } elseif (strtolower($period['title']) == 'biennially') {
            $pprice['bsetupfee'] = $period['setup'];
            $pprice['biennially'] = $period['price'] == '0.00' ? '-1' : $period['price'];
        } elseif (strtolower($period['title']) == 'triennially') {
            $pprice['tsetupfee'] = $period['setup'];
            $pprice['triennially'] = $period['price'] == '0.00' ? '-1' : $period['price'];
        }
    }
    /** Add product prices */
    Capsule::table('tblpricing')->updateOrInsert([
    'relid' => $p->id,
    'type' => 'product'
    ], $pprice);
}

echo 'Update complete!';
