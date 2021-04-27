 <style>
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
    </style>
{if $error}
    <div class="alert alert-danger text-center">
        {$error}
    </div>
{/if}

<h3>Reset Password</h3>
<hr />

{if $smarty.get.newpassword}
    <p>Your password was reset:</p>
    <pre>Your new password is:  <strong>{$smarty.get.newpassword}</strong></pre>
    <hr />
{/if}
<h5>Do you really want to reset your Password ? </h5>


<form action="{$currentpagelinkback}" method="post" class="text-center">
    <input type="hidden" name="confirm" value="1" />
    <button class="btn btn-primary" type="submit"><i class="fa fa-key"></i> Reset Password</button>
    or <a href="javascript:history.back()" class="btn btn-primary">Cancel</a>
</form>
