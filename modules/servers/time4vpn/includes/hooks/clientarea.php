<?php

add_hook('ClientAreaPrimarySidebar', 1, function ($primarySidebar) {

    $service = Menu::context("service");
    if ($service->status != 'Active' || $service->product->module != 'time4vpn') {
        return;
    }
    $id = $service->id;
    // change URl for information link
    $primarySidebar->getChild("Service Details Overview")
        ->getChild("Information")
        ->setUri("clientarea.php?action=productdetails&id={$id}");

    // add custom Panel for VPN service
    $actions = $primarySidebar->addChild(
        'actionsMenu',
        [
            'name' => 'Server Management',
            'label' => Lang::trans('Server Management'),
            'order' => 15,
            'icon' => 'fas fa-cogs',
        ]
    );

    // add custom Panel Link for VPN service
    $actions->addChild(
        'actionsMenuServersList',
        [
            'name' => 'Available Servers',
            'label' => Lang::trans('Available Servers'),
            'uri' => "clientarea.php?action=productdetails&id={$id}&act=ServerList&modop=custom",
            'order' => 1,
            'icon' => 'fas fa-list'
        ]
    );

    // add custom Panel Link for VPN service
    $actions->addChild(
        'actionsMenuUsageHistory',
        [
            'name' => 'usage History',
            'label' => Lang::trans('Usage History'),
            'uri' => "clientarea.php?action=productdetails&id={$id}&act=UsageHistory&modop=custom",
            'order' => 2,
            'icon' => 'fas fa-chart-line'
        ]
    );

    // add custom Panel Link for VPN service
    $actions->addChild(
        'actionsMenuResetPassword',
        [
            'name' => 'Reset Password',
            'label' => Lang::trans('Change Password'),
            'uri' => "clientarea.php?action=productdetails&id={$id}&act=PasswordReset&modop=custom",
            'order' => 3,
            'icon' => 'fa-fw fa-edit'
        ]
    );
});


add_hook('ClientAreaFooterOutput', 1, function ($params) {
    return '<script type="text/javascript" src="modules/servers/time4vpn/assets/js/clientarea.js"></script>';
});
