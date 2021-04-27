<?php

use WHMCS\Database\Capsule;

function time4vpn_UpdateServerDetails($params)
{
    try{

        time4vpn_InitAPI($params);

        $server = time4vpn_ExtractServer($params);
        $details = $server->details();

        if (isset($details['username']) && empty($details['username']) == false){

            // Update username on product in case any changes
            Capsule::table('tblhosting')
            ->where('id', $params['serviceid'])
            ->update([ 'username' => $details['username']]);
        }

        if (isset($details['password']) && empty($details['password']) == false){

            // Update password on product in case any changes
            Capsule::table('tblhosting')
            ->where('id', $params['serviceid'])
            ->update([ 'password' => encrypt($details['password'])]);
        }
    }
    catch (Exception $e) {
        return 'Could not update product details. ' . $e->getMessage();
    }

    return 'success';
}


function time4vpn_AdminResetPassword($params)
{
    try{
        time4vpn_InitAPI($params);
        $server = time4vpn_ExtractServer($params);
        $details = $server->resetPassword();

        if (isset($details['password']) && empty($details['password']) == false){
            // Update password on product in case any changes
            Capsule::table('tblhosting')
            ->where('id', $params['serviceid'])
            ->update([ 'password' => encrypt($details['password'])]);
        }
        else throw new Exception("No password returned from Time4VPS");

    }
    catch (Exception $e) {
        return 'Could not reset password: . ' . $e->getMessage();
    }
}