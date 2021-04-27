<?php /** @noinspection ALL */

use WHMCS\Database\Capsule;
use Time4VPN\API\Order;
use Time4VPN\API\Service;
use Time4VPN\API\Server;
use WHMCS\Service\Service as whmcsservice;

require dirname(__FILE__) . '/../../defines.php';
require dirname(__FILE__) . '/../helpers.php';

/** Set service as pending after service creation */
add_hook('AfterModuleCreate', 1, function ($params) {
    Capsule::table('tblhosting')->where('id', $params['serviceid'])->update(['domainstatus' => 'Pending']);
});


add_hook('AdminClientServicesTabFields', 1, function($vars) {

    try{
        // Check to see if this is an active VPN product
        if ($vpn = Capsule::table(TIME4VPN_TABLE)->where('service_id', $vars['id'])->first()) {

            $whmcsservice = whmcsservice::findOrFail($vars['id']);

            if ($serverInfo = Capsule::table('tblservers')->where('id', $whmcsservice->server)->first()) {
                
                $params = array(
                    'serverhttpprefix' => $serverInfo->secure ? 'https' : 'http',
                    'serverhostname'   => $serverInfo->hostname,
                    'serverusername'   => $serverInfo->username,
                    'serverpassword'   => decrypt($serverInfo->password)
                );

                time4vpn_InitAPI($params);

                $service = new Service($vpn->external_id);

                $result = $service->details();

                $html = <<< EOT
                <style>
                .vpn-service-info {
                    margin: 8px 5px;
                }
                .vpn-service-info-bg {
                    padding: 15px;
                    background-color: #fff;
                    border-radius: 4px;
                }
                .pb-3{
                    padding-bottom: 1rem!important;
                }
                .border-bottom {
                    border-bottom: 1px solid #dee2e6!important;
                }
                </style>
EOT;

                $html .= '<div class="vpn-service-info vpn-service-info-bg"><h6 class="border-bottom pb-3 mb-0">VPN Service Details @ Time4VPS</h6>';
                

                switch ($result['status']){
                    case "Active": $html .= '<span class="label label-success pull-right" style="padding:5px 10px;">Active</span>';
                        break;
                    case "Pending": $html .= '<span class="label label-warning pull-right" style="padding:5px 10px;">Pending</span>';
                        break;
                    case "Cancelled": $html .= '<span class="label label-default pull-right" style="padding:5px 10px;">Cancelled</span>';
                        break;
                    case "Terminated": $html .= '<span class="label label-danger pull-right" style="padding:5px 10px;">Terminated</span>';
                        break;
                    default: $html .= '<span class="label label-info pull-right" style="padding:5px 10px;">N/A</span>';
                        break;
                }

                $html .= "<p class='mb-0 small lh-sm'><strong class='d-block text-gray-dark'>External ID: </strong>" . $result['id'] . "</p>";
                $html .= "<p class='mb-0 small lh-sm'><strong class='d-block text-gray-dark'>Package: </strong>" . $result['name'] . "</p>";
                
                if (isset($result['username']) && empty($result['username'] == false)){
                    $html .= "<p class='mb-0 small lh-sm'><strong class='d-block text-gray-dark'>VPN Username: </strong>" . $result['username'] . "</p>";
                }
                
                $html .= "<p class='mb-0 small lh-sm'><strong class='d-block text-gray-dark'>Date Created: </strong>" . $result['date_created'] . "</p>";
                $html .= "<p class='mb-0 small lh-sm'><strong class='d-block text-gray-dark'>Billing Cycle: </strong>" . $result['billingcycle'] . "</p>";


                $html .= '</div></div>';
                return [
                    'VPN Service Info' => $html
                ];
            }
            else throw new Exception("Failed to obtain server information to query Tim4VPS");
        }
    }
    catch (Exception $e) { return ['VPN Service Info' => 'Error: ' . $e->getMessage() ]; }
    
});