<h4><{$error_heading}></h4>

<div class="errorMsg">
    <{section name=err loop=$errors}>
        <{$errors[err]}>
        <br>
    <{/section}>
    <a href="<{$liaise_url}>" onclick="history.go(-1); return false;"><{$go_back}></a>
</div>
