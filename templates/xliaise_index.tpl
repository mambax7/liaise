<{include file='db:xliaise_header.tpl'}>

<{if $forms_breadcrumb}>
    <div class="breadcrumbs">
        <a href="<{$xoops_url}>"><{$smarty.const._LIAISE_HOMEPAGE}></a>
        <{$smarty.const._LIAISE_BRDCRMB_SEP}>
        <{$smarty.const._LIAISE_ROOT}>
    </div>
    <div class="clear"></div>
<{/if}>

<p></p>

<p><{$forms_intro}></p>

<p>
<ul>
    <{foreach item=form from=$forms}>
        <li class="vignette">
            <a href="<{$smarty.const.LIAISE_URL}>index.php?form_id=<{$form.id}>">
                <{$form.title|truncate:50:".."}>
            </a>
        </li>
    <{/foreach}>
</ul>
</p>
