<{include file='db:wfdownloads_header.tpl'}>

<h4 title="<{$smarty.const._MD_WFDOWNLOADS_TITLE}>"><{$download.title}></h4>
<div title="<{$smarty.const._MD_WFDOWNLOADS_DESCRIPTION}>">
    <{$download.description}>
</div>

<br />

<{if ($brokenreportexists)}>
<div>
    <h4><{$smarty.const._MD_WFDOWNLOADS_RESOURCEREPORTED}></h4>
    <p>
        <{$smarty.const._MD_WFDOWNLOADS_RESOURCEREPORTED}>
        <br />
        <span style="font-weight: bold;"><{$smarty.const._MD_WFDOWNLOADS_FILETITLE}></span><{$broken.title}>
        <br />
        <span style="font-weight: bold;"><{$smarty.const._MD_WFDOWNLOADS_RESOURCEID}></span><{$broken.id}>
        <br />
        <span style="font-weight: bold;"><{$smarty.const._MD_WFDOWNLOADS_REPORTER}></span> <{$broken.reporter}>
        <br />
        <span style="font-weight: bold;"><{$smarty.const._MD_WFDOWNLOADS_DATEREPORTED}></span> <{$broken.date}>
        <br />
        <span style="font-weight: bold;"><{$smarty.const._MD_WFDOWNLOADS_WEBMASTERACKNOW}></span> <{$broken.acknowledged}>
        <br />
        <span style="font-weight: bold;"><{$smarty.const._MD_WFDOWNLOADS_WEBMASTERCONFIRM}></span> <{$broken.confirmed}>
    </p>
</div>
<{else}>
<div>
    <h4><{$smarty.const._MD_WFDOWNLOADS_BROKENREPORT}></h4>
    <ul>
        <li><{$smarty.const._MD_WFDOWNLOADS_THANKSFORHELP}></li>
        <li><{$smarty.const._MD_WFDOWNLOADS_FORSECURITY}></li>
        <li><{$smarty.const._MD_WFDOWNLOADS_BEFORESUBMIT}></li>
    </ul>
    <p>
        <span style="font-weight: bold;"><{$smarty.const._MD_WFDOWNLOADS_HOMEPAGEC}></span><{$down.homepage}>
        <br />
        <span style="font-weight: bold;"><{$smarty.const._MD_WFDOWNLOADS_FILETITLE}></span><{$down.title}>
        <br />
        <span style="font-weight: bold;"><{$smarty.const._MD_WFDOWNLOADS_PUBLISHER}>:</span> <{$down.publisher}>
        <br /><span style="font-weight: bold;"><{$lang_subdate}>:</span> <{$down.updated}>
    </p>
    <{$reportform}>
</div>
<{/if}>

<{include file='db:wfdownloads_footer.tpl'}>
