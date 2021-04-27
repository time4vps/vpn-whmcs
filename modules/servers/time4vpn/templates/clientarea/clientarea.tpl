{if $error}
    <div class="alert alert-danger text-center">
        {$error}
    </div>
{/if}

<h3>Server Login Info</h3>
{if $server_details}
    <div class="spacing">
        <table class="checker table table-striped">
            <tbody>
            <tr>
                <td width="200">Username</td>
                <td><code>{$server_details.username}</code></td>
            </tr>
            <tr>
                <td width="200">Password</td>
                <td><a href="#" class="show-password" data-value="{$server_details.password}">Click here to show password</a></td>
            </tr>
            <tr>
                <td width="200">Pre-shared Key</td>
                <td><code>VPN</code></td>
            </tr>
            {if $usage_history}
            <tr>
                <td width="200">Monthly Usage</td>
                <td>
                    <small>
                        <i class="fas fa-download"></i> {$usage_history['received']} &nbsp; &nbsp;
                        <i class="fas fa-upload"></i> {$usage_history['sent']}
                    </small>
                    &nbsp; &nbsp; <i class="fas fa-signal"></i> {$usage_history['total']} / {$usage_history['quota']} ({$usage_history['usage']}%)
                    <div style="width: 340px;">
                        <div class="progress progress-danger" style="margin-bottom: 0;">
                            <div class="bar" style="width: {$usage_history['usage']}%;"></div>
                        </div>
                    </div>
                </td>
            </tr>
            {/if}
            </tbody>
        </table>
        {if $download_links}
        <h3>Available VPN clients</h3>
        <div class="downloads row">
            {if $download_links[1]['name']=='WindowsVPN' &&  $download_links[1]['url'] != ''}

            <div class="download-col col-sm-4">
                <p>
                    <strong>Windows 7, 8, 10</strong>
                </p>
                <a href="{$download_links[1]['url']}" download>
                    <img src="https://billing.time4vps.com/includes/modules/Hosting/iv_vpn/assets/img/dl_win.png" style="width: 200px;" alt="Download for Windows">
                </a>
            </div>
            {/if}
            {if $download_links[0]['name']=='AndroidVPN' &&  $download_links[0]['url'] != ''}
            <div class="download-col col-sm-4">
                <p>
                    <strong>Android</strong>
                </p>
                <a href="{$download_links[0]['url']}" target="_blank">
                    <img src="https://billing.time4vps.com/includes/modules/Hosting/iv_vpn/assets/img/dl_droid.png" style="width: 200px;" alt="Download for Android">
                </a>
            </div>
            {/if}
            <div class="download-col col-sm-4">
                <p>
                    <strong>Debian / Ubuntu</strong>
                </p>
                <p>
                    Download OpenVPN client <br>from <a href="https://community.openvpn.net/openvpn/wiki/OpenvpnSoftwareRepos" target="_blank">repository</a>.<br>
                </p>
                <p>
                    Download OpenVPN config files<br> for our <a href="?action=productdetails&id={$smarty.get.id}&act=ServerList">available servers</a>.
                </p>
            </div>
        </div>
        {/if}
    </div>
    <script type="text/javascript">
        $(document).ready(function () {
            $('.show-password').on('click', function (e) {
                e.preventDefault();
                $(this).replaceWith($('<code></code>').text($(this).data('value')));
            });
        });

    </script>
{else}
    <div class="alert alert-info">
        Server Details is not available yet.
    </div>
{/if}