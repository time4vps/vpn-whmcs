jQuery('a#Primary_Sidebar-Service_Details_Overview-Information').removeAttr('data-toggle').removeAttr('role');
var urlParams = new URLSearchParams(window.location.search);
if (urlParams.has('act')) {
    console.log(urlParams.get('act'));
    if (urlParams.get('act') == 'ServerList') {
        jQuery('a#Primary_Sidebar-actionsMenu-actionsMenuServersList').addClass('active');
    }
    else if (urlParams.get('act') == 'UsageHistory') {
        jQuery('a#Primary_Sidebar-actionsMenu-actionsMenuUsageHistory').addClass('active');
    }
    else if (urlParams.get('act') == 'PasswordReset') {
        jQuery('a#Primary_Sidebar-actionsMenu-actionsMenuResetPassword').addClass('active');
    }
}
