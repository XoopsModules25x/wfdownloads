<{if $com_rule <> 0}>
<a name="comments"></a>
<div class="wfdownloads_foot_commentnav">
    <{$commentsnav}>
    <{$lang_notice}>
</div>

<div class="wfdownloads_foot_comments">
    <!-- start comments loop -->
<{if $comment_mode == "flat"}>
    <{include file="db:system_comments_flat.html"}>
<{elseif $comment_mode == "thread"}>
    <{include file="db:system_comments_thread.html"}>
<{elseif $comment_mode == "nest"}>
    <{include file="db:system_comments_nest.html"}>
<{/if}>
    <!-- end comments loop -->
</div>
<{/if}>

<{include file='db:system_notification_select.html'}>

<!-- footer menu -->
<div class="wfdownloads_adminlinks">
<{foreach item='footerMenuItem' from=$wfdownloadModuleInfoSub}>
    <a href='<{$smarty.const.WFDOWNLOADS_URL}>/<{$footerMenuItem.url}>'><{$footerMenuItem.name}></a>
<{/foreach}>
<{if $isAdmin == true}>
    <br />
    <a href="<{$smarty.const.WFDOWNLOADS_URL}>/admin/index.php"><{$smarty.const._MD_WFDOWNLOADS_ADMIN_PAGE}></a>
<{/if}>
</div>
