<?php

require 'defines.php';
require ROOTPATH . '/init.php';

use WHMCS\Database\Capsule;

if (!$_SESSION['adminid']) {
    die('Access denied');
}

/** Create service ID maping table */
if (!Capsule::schema()->hasTable(TIME4VPN_TABLE)) {
    Capsule::schema()->create(
        TIME4VPN_TABLE,
        function ($table) {
            /** @var $table Object */
            $table->integer('service_id')->unique();
            $table->integer('external_id')->index();
            $table->text('details')->nullable();
            $table->timestamp('details_updated')->nullable();
        }
    );
}
echo 'If you want to import all products from TIME4VPN, run <a href="update.php">update</a>.';
