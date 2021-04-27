{if $error}
    <div class="alert alert-danger text-center">
        {$error}
    </div>
{/if}

<h3>Usage History</h3>

{if $usage_history}
    <table class="checker table table-striped" style="width: 100%">
        <thead>
        <tr>
            <th class="text-center">Period</th>
            <th class="text-center">Received</th>
            <th class="text-center">Sent</th>
            <th class="text-center">Limit</th>
            <th class="text-center">Usage</th>
        </tr>
        </thead>
        {foreach from=$usage_history item=item}
            <tr>
                <td class="text-center"><strong>{$item.period|date_format:"%B, %Y"}</strong></td>
                <td class="text-center">{$item.received}</td>
                <td class="text-center">{$item.sent}</td>
                <td class="text-center">{$item.quota}</td>
                <td class="text-center">
                    <label for="usage">{$item.usage}%</label>
                    <br>
                    <progress id="usage" value="{$item.usage}" max="100"> {$item.usage}% </progress>
                </td>
            </tr>
        {/foreach}
    </table>
{else}
    <div class="alert alert-info">
        Server usage history is not available yet.
    </div>
{/if}