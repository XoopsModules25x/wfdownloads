<{include file='db:wfdownloads_header.tpl'}>

<h4 title="<{$smarty.const._MD_WFDOWNLOADS_TITLE}>"><{$download.title}></h4>
<div title="<{$smarty.const._MD_WFDOWNLOADS_DESCRIPTION}>">
    <{$download.description}>
</div>

<br />

<div>
    <ul>
         <li><{$smarty.const._MD_WFDOWNLOADS_VOTEONCE}></li>
         <li><{$smarty.const._MD_WFDOWNLOADS_RATINGSCALE}></li>
         <li><{$smarty.const._MD_WFDOWNLOADS_BEOBJECTIVE}></li>
         <li><{$smarty.const._MD_WFDOWNLOADS_DONOTVOTE}></li>
    </ul>
</div>
<div>
    <{$voteform}>
</div>

<{include file='db:wfdownloads_footer.tpl'}>
