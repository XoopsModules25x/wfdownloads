<{include file='db:wfdownloads_header.tpl'}>

<h4 title="<{$smarty.const._MD_WFDOWNLOADS_TITLE}>"><{$down_arr.title}></h4>
<div title="<{$smarty.const._MD_WFDOWNLOADS_DESCRIPTION}>">
    <{$down_arr.description}>
</div>

<br />

<div><{$lang_mirror_found}></div>

<br />

<table class="wfdownloads_mirrors" cellspacing="0">
    <tr>
        <th><{$smarty.const._MD_WFDOWNLOADS_MIRROR_HHOST}></th>
        <th><{$smarty.const._MD_WFDOWNLOADS_MIRROR_HLOCATION}></th>
        <th><{$smarty.const._MD_WFDOWNLOADS_MIRROR_HCONTINENT}></th>
        <th><{$smarty.const._MD_WFDOWNLOADS_MIRROR_HDOWNLOAD}></th>
    </tr>
    <!-- Start mirror loop -->
<{foreach item=mirror from=$down_mirror}>
    <tr class="wfdownloads_mirrors_elist">
        <td><a href="<{$mirror.homeurl}>"><{$mirror.title}></a></td>
        <td><{$mirror.location}></td>
        <td><{$mirror.continent}></td>
        <td>
        <{if $mirror.isonline == 1}>
            <img src="<{xoModuleIcons16 green.gif}>" alt="Online" title="Online" />
            &nbsp;
            <a href="<{$mirror.downurl}>"><{$smarty.const._MD_WFDOWNLOADS_MIRROR_HDOWNLOAD}></a>
        <{/if}>
        <{if $mirror.isonline == 0}>
            <img src="<{xoModuleIcons16 red.gif}>" alt="Offline" title="Offline" />
            &nbsp;
            <a href="<{$mirror.downurl}>"><{$smarty.const._MD_WFDOWNLOADS_MIRROR_HDOWNLOAD}></a>
        <{/if}>
        <{if $mirror.isonline == 2}>
            <img src="<{xoModuleIcons16 green_off.gif}>" alt="Disabled" title="Disabled" />
            &nbsp;
            <a href="<{$mirror.downurl}>"><{$smarty.const._MD_WFDOWNLOADS_MIRROR_HDOWNLOAD}></a>
        <{/if}>
        </td>
    </tr>
<{/foreach}>
    <!-- End mirror loop -->
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td>
            <span style="font-size: small;">
            <{$smarty.const._CO_WFDOWNLOADS_LEGEND}>
            <br />
            <img src="<{xoModuleIcons16 green.gif}>" />&nbsp;<{$smarty.const._MD_WFDOWNLOADS_MIRROR_ONLINE}>
            <br />
            <img src="<{xoModuleIcons16 red.gif}>" />&nbsp;<{$smarty.const._MD_WFDOWNLOADS_MIRROR_OFFLINE}>
            <br />
            <img src="<{xoModuleIcons16 green_off.gif}>" />&nbsp;<{$smarty.const._MD_WFDOWNLOADS_MIRROR_DISABLED}>
            </span>
        </td>
    </tr>
</table>
<{if $navbar.navbar }>
<div>
    <span style="font-weight: bold;"><{$smarty.const._MD_WFDOWNLOADS_PAGES}></span>: <{$navbar.navbar}>
</div>
<{/if}>

<{if $mirror.add_mirror == 1}>
    <br />
    <a href="<{$xoops_url}>/modules/wfdownloads/mirror.php?cid=<{$down_arr.cid}>&amp;lid=<{$down_arr.lid}>"><{$smarty.const._MD_WFDOWNLOADS_ADDMIRROR}></a>
<{/if}>

<{include file='db:wfdownloads_footer.tpl'}>
