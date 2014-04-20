<{include file='db:wfdownloads_header.tpl'}>

<!-- Start ranking loop -->
<{foreach item=ranking from=$rankings}>
<div style="margin-bottom: 5px; font-weight: bold;"><{$smarty.const._MD_WFDOWNLOADS_CATEGORY}>: <{$ranking.title}></div>
<table cellpadding="0" cellspacing="1" width="100%" class="outer">
    <tr>
        <th><{$smarty.const._MD_WFDOWNLOADS_RANK}></th>
        <th><{$smarty.const._MD_WFDOWNLOADS_TITLE}></th>
        <th><{$smarty.const._MD_WFDOWNLOADS_CATEGORY}></th>
        <th><{$smarty.const._MD_WFDOWNLOADS_HITS}></th>
        <th><{$smarty.const._MD_WFDOWNLOADS_RATING}></th>
        <th><{$smarty.const._MD_WFDOWNLOADS_VOTE}></th>
    </tr>
    <!-- Start files loop -->
<{foreach item=file from=$ranking.file}>
    <tr>
        <td class="head" style="font-weight: bold;"><{$file.rank}></td>
        <td class="even"><a href="singlefile.php?cid=<{$file.cid}>&amp;lid=<{$file.id}>"><{$file.title}></a></td>
        <td class="even"><a href="viewcat.php?cid=<{$file.cid}>"><{$file.category}></a></td>
        <td class="even"><{$file.hits}></td>
        <td class="even"><{$file.rating}></td>
        <td class="even"><{$file.votes}></td>
    </tr>
<{/foreach}>
  <!-- End links loop-->
</table>
<br />
<{/foreach}>
<!-- End ranking loop -->
<{include file='db:wfdownloads_footer.tpl'}>
