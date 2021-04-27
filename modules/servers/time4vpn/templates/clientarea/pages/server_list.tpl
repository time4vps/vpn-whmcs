{if $error}
    <div class="alert alert-danger text-center">
        {$error}
    </div>
{/if}

<h3>Available Servers List</h3>

{if $servers}
    <style>
    code {
        padding: 2px 4px;
        color: #d14;
        white-space: nowrap;
        background-color: #f7f7f9;
        border: 1px solid #e1e1e8;
    }
    table th {
        font-weight: 700;
        color: #000;
        background: #f1f4f8;
    }
    .btn {
        background: #000;
        box-shadow: none;
        border: none;
        padding: 5px 12px;
        color: #fff;
        text-shadow: none;
        font-weight: 700;
        font-size: 12px;
    }
    .btn:hover , .btn-primary.active.focus, .btn-primary.active:focus, .btn-primary.active:hover, .btn-primary:active.focus, .btn-primary:active:focus, .btn-primary:active:hover, .open>.dropdown-toggle.btn-primary.focus, .open>.dropdown-toggle.btn-primary:focus, .open>.dropdown-toggle.btn-primary:hover {
        background-color: #ed1c24;
    }
    table.dataTable thead .sorting:after,
    table.dataTable thead .sorting:before,
    table.dataTable thead .sorting_asc:after,
    table.dataTable thead .sorting_asc:before,
    table.dataTable thead .sorting_asc_disabled:after,
    table.dataTable thead .sorting_asc_disabled:before,
    table.dataTable thead .sorting_desc:after,
    table.dataTable thead .sorting_desc:before,
    table.dataTable thead .sorting_desc_disabled:after,
    table.dataTable thead .sorting_desc_disabled:before {
        bottom: .5em;
    }
    </style>

    <table class="sortable checker table table-striped" style="width: 100%">
    </table>
    <table id="servers_list" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
        <thead>
        <tr style="cursor: pointer;">
            <th class="text-center th-sm">Server Name</th>
            <th class="text-center th-sm">Connection Info</th>
            <th class="text-center th-sm">Location</th>
            <th class="text-center th-sm">Network Load</th>
            <th class="text-center th-sm">Config Files</th>
        </tr>
        </thead>
        {foreach from=$servers item=server}
            <tr>
                <td class="text-center "><strong>{$server['name']}</strong></td>
                <td class="text-center "><code>{$server['ip']}</code></td>
                <td class="text-center ">{$server['city']} , {$server['region']}</td>
                <td class="text-center "> <i class="fa fa-tachometer" aria-hidden="true"></i><br> <code>{$server['load']}%</code> </td>
                <td class="text-center ">
                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Download<span class="caret"></span></button>
                        <ul class="dropdown-menu">
                            <li class="dropdown-header">OpenVPN</li>
                            <li><a href="{$host_url}/?cmd=iv_vpn&amp;action=config&amp;template=ovpn_default&amp;id={$server['id']}">Default (UDP 1194)</a></li>
                            <li><a href="{$host_url}/?cmd=iv_vpn&amp;action=config&amp;template=ovpn_443&amp;id={$server['id']}">Alternative (TCP 443)</a></li>
                            <li class="dropdown-header">Windows RAS</li>
                            <li><a href="{$host_url}/?cmd=iv_vpn&amp;action=config&amp;template=pbk_default&amp;id={$server['id']}">PBK File</a></li>
                        </ul>
                    </div>
                </td>
            </tr>
        {/foreach}
    </table>
{else}
    <div class="alert alert-info">
        Currently No Servers Available.
    </div>
{/if}
<script>
    $(document).ready(function () {
        $('#servers_list').DataTable();
        $('.dataTables_length').addClass('bs-select');
    });
</script>
