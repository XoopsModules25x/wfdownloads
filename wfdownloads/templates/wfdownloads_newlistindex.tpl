<{include file='db:wfdownloads_header.tpl'}>

<div>
        <span style="font-weight: bold;"><{$smarty.const._MD_WFDOWNLOADS_TOTALNEWDOWNLOADS}>:</span>
        <br />
        <{$smarty.const._MD_WFDOWNLOADS_LASTWEEK}>: <{$allweekdownloads}>
        &nbsp;|&nbsp;
        <{$smarty.const._MD_WFDOWNLOADS_LAST30DAYS}>: <{$allmonthdownloads}>
        <br />
        <{$smarty.const._MD_WFDOWNLOADS_SHOW}>:&nbsp;
        <a href="<{$xoops_url}>/modules/wfdownloads/newlist.php?newdownloadshowdays=7"><{$smarty.const._MD_WFDOWNLOADS_1WEEK}></a>
        &nbsp;|&nbsp;
        <a href="<{$xoops_url}>/modules/wfdownloads/newlist.php?newdownloadshowdays=14"><{$smarty.const._MD_WFDOWNLOADS_2WEEKS}></a>
        &nbsp;|&nbsp;
        <a href="<{$xoops_url}>/modules/wfdownloads/newlist.php?newdownloadshowdays=30"><{$smarty.const._MD_WFDOWNLOADS_30DAYS}></a>
    <div class="wfdownloads_newlist_totallast">
        <{$smarty.const._MD_WFDOWNLOADS_DTOTALFORLAST}> <{$newdownloadshowdays}> <{$smarty.const._MD_WFDOWNLOADS_DAYS}>
    </div>
</div>
<br />

<!-- Start link loop -->
<{foreach item=download from=$file}>
<{include file='db:wfdownloads_download.tpl'}>
<br />
<{/foreach}>
<!-- End link loop -->

<{include file='db:wfdownloads_footer.tpl'}>
