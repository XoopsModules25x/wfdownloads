<{if $use_ratings == false}>
<div class="errorMsg"><{$smarty.const._AM_WFDOWNLOADS_RATING_DISABLED}></div>
<{/if}>
<fieldset>
    <legend style='font-weight: bold;'><{$smarty.const._AM_WFDOWNLOADS_VOTE_DISPLAYVOTES}></legend>
    <p>
        <{$smarty.const._AM_WFDOWNLOADS_VOTE_USERAVG}>:&nbsp;<{$useravgrating}>
    </p>

    <p>
        <{$smarty.const._AM_WFDOWNLOADS_VOTE_TOTALRATE}>:&nbsp;<{$votes}>
    </p>

    <p>
        <img src="<{xoModuleIcons16 delete.png}>" title="<{$smarty.const._DELETE}>" alt="<{$smarty.const._DELETE}>"/>
        <{$smarty.const._AM_WFDOWNLOADS_VOTE_DELETEDSC}>
    </p>
</fieldset>

<br/>

<fieldset>
    <legend style='font-weight: bold; color: #900;'><{$smarty.const._AM_WFDOWNLOADS_VOTE_VOTES}></legend>
    <{if ($votes == 0)}>
    <{$smarty.const._AM_WFDOWNLOADS_VOTE_NOVOTES}>
    <{else}>
    <table class='outer'>
        <tr>
            <th><{$smarty.const._AM_WFDOWNLOADS_VOTE_ID}></th>
            <th><{$smarty.const._AM_WFDOWNLOADS_VOTE_USER}></th>
            <th><{$smarty.const._AM_WFDOWNLOADS_VOTE_IP}></th>
            <th><{$smarty.const._AM_WFDOWNLOADS_VOTE_FILETITLE}></th>
            <th><{$smarty.const._AM_WFDOWNLOADS_VOTE_RATING}></th>
            <th><{$smarty.const._AM_WFDOWNLOADS_VOTE_DATE}></th>
            <th><{$smarty.const._AM_WFDOWNLOADS_MINDEX_ACTION}></th>
        </tr>
        <{foreach item=rating from=$ratings}>
        <tr class="<{cycle values='even, odd'}>">
            <td class='head'><{$rating.ratingid}></td>
            <td class='even'><a href='../../../userinfo.php?uid=<{$rating.submitter_uid}>'><{$rating.submitter_uname}></a></td>
            <td class='even'><{$rating.ratinghostname}></td>
            <td class='even'><{$rating.download_title}></td>
            <td class='even' align='center'><{$rating.rating}></td>
            <td class='even'><{$rating.formatted_date}></td>
            <td class='even' align='center'>
                <a href='?op=vote.delete&amp;lid=<{$rating.lid}>&amp;rid=<{$rating.rid}>'><img src="<{xoModuleIcons16 delete.png}>"
                                                                                               title="<{$smarty.const._DELETE}>"
                                                                                               alt="<{$smarty.const._DELETE}>"/></a>
            </td>
        </tr>
        <{/foreach}>
    </table>
    <{$ratings_pagenav}>
    <{/if}>
</fieldset>
