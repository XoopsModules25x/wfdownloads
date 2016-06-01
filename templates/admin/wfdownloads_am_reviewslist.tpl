<{if $use_reviews == false}>
<div class="errorMsg"><{$smarty.const._AM_WFDOWNLOADS_REVIEW_DISABLED}></div>
<{/if}>
<fieldset>
    <legend style='font-weight: bold;'><{$smarty.const._AM_WFDOWNLOADS_AREVIEWS_INFO}></legend>
    <p>
        <{$smarty.const._AM_WFDOWNLOADS_AREVIEWS_WAITING}>:&nbsp;<{$reviews_waiting_count}>
    </p>

    <p>
        <{$smarty.const._AM_WFDOWNLOADS_REV_REVIEW_TOTAL}>:&nbsp;<{$reviews_published_count}>
    </p>

    <p>
        <img src="<{xoModuleIcons16 1.png}>" title="<{$smarty.const._AM_WFDOWNLOADS_AREVIEWS_APPROVE_ALT}>"
             alt="<{$smarty.const._AM_WFDOWNLOADS_AREVIEWS_APPROVE_ALT}>"/> <{$smarty.const._AM_WFDOWNLOADS_AREVIEWS_APPROVE_DESC}>
        <br/>
        <img src="<{xoModuleIcons16 edit.png}>" title="<{$smarty.const._AM_WFDOWNLOADS_AREVIEWS_EDIT_ALT}>"
             alt="<{$smarty.const._AM_WFDOWNLOADS_AREVIEWS_EDIT_ALT}>"/> <{$smarty.const._AM_WFDOWNLOADS_AREVIEWS_EDIT_DESC}>
        <br/>
        <img src="<{xoModuleIcons16 delete.png}>" title="<{$smarty.const._AM_WFDOWNLOADS_AREVIEWS_DELETE_ALT}>"
             alt="<{$smarty.const._AM_WFDOWNLOADS_AREVIEWS_DELETE_ALT}>"/> <{$smarty.const._AM_WFDOWNLOADS_AREVIEWS_DELETE_DESC}>
    </p>
</fieldset>

<br/>

<fieldset>
    <legend style='font-weight: bold; color: #900;'><{$smarty.const._AM_WFDOWNLOADS_REV_REVIEW_WAITING}></legend>
    <{if ($reviews_waiting_count == 0)}>
    <{$smarty.const._AM_WFDOWNLOADS_REV_NOWAITINGREVIEWS}>
    <{else}>
    <table class='outer'>
        <tr>
            <th><{$smarty.const._AM_WFDOWNLOADS_REV_ID}></th>
            <th><{$smarty.const._AM_WFDOWNLOADS_REV_TITLE}></th>
            <th><{$smarty.const._AM_WFDOWNLOADS_REV_REVIEWTITLE}></th>
            <th><{$smarty.const._AM_WFDOWNLOADS_REV_POSTER}></th>
            <th><{$smarty.const._AM_WFDOWNLOADS_REV_SUBMITDATE}></th>
            <th><{$smarty.const._AM_WFDOWNLOADS_REV_ACTION}></th>
        </tr>
        <{foreach item=review_waiting from=$reviews_waiting}>
        <tr class="<{cycle values='even, odd'}>">
            <td><{$review_waiting.review_id}></td>
            <td>
                <a href='downloads.php?op=download.edit&amp;lid=<{$review_waiting.lid}>'><{$review_waiting.download_title}></a>
            </td>
            <td>
                <a href='?op=review.edit&amp;review_id=<{$review_waiting.review_id}>'><{$review_waiting.title}></a>
            </td>
            <td>
                <{if ($review_waiting.reviewer_email) == ''}>
                <{$review_waiting.reviewer_uname}>
                <{else}>
                <a href='mailto:<{$report.submitter_email}>'><{$review_waiting.reviewer_uname}></a>
                <{/if}>
            </td>
            <td><{$review_waiting.formatted_date}></td>
            <td align='center'>
                <a href='?op=review.approve&amp;review_id=<{$review_waiting.review_id}>'><img src="<{xoModuleIcons16 on.png}>"
                                                                                              title="<{$smarty.const._AM_WFDOWNLOADS_AREVIEWS_APPROVE}>"
                                                                                              alt="<{$smarty.const._AM_WFDOWNLOADS_AREVIEWS_APPROVE}>"/></a>
                <a href='?op=review.edit&amp;review_id=<{$review_waiting.review_id}>'><img src="<{xoModuleIcons16 edit.png}>"
                                                                                           title="<{$smarty.const._AM_WFDOWNLOADS_AREVIEWS_EDIT}>"
                                                                                           alt="<{$smarty.const._AM_WFDOWNLOADS_AREVIEWS_EDIT}>"/></a>
                <a href='?op=review.delete&amp;review_id=<{$review_waiting.review_id}>'><img src="<{xoModuleIcons16 delete.png}>"
                                                                                             title="<{$smarty.const._AM_WFDOWNLOADS_AREVIEWS_DELETE}>"
                                                                                             alt="<{$smarty.const._AM_WFDOWNLOADS_AREVIEWS_DELETE}>"/></a>
            </td>
        </tr>
        <{/foreach}>
    </table>
    <{$reviews_waiting_pagenav}>
    <{/if}>
</fieldset>

<br/>

<fieldset>
    <legend style='font-weight: bold; color: #900;'><{$smarty.const._AM_WFDOWNLOADS_REV_REVIEW_PUBLISHED}></legend>
    <{if ($reviews_published_count == 0)}>
    <{$smarty.const._AM_WFDOWNLOADS_REV_NOPUBLISHEDREVIEWS}>
    <{else}>
    <table class='outer'>
        <tr>
            <th><{$smarty.const._AM_WFDOWNLOADS_REV_ID}></th>
            <th><{$smarty.const._AM_WFDOWNLOADS_REV_TITLE}></th>
            <th><{$smarty.const._AM_WFDOWNLOADS_REV_REVIEWTITLE}></th>
            <th><{$smarty.const._AM_WFDOWNLOADS_REV_POSTER}></th>
            <th><{$smarty.const._AM_WFDOWNLOADS_REV_SUBMITDATE}></th>
            <th><{$smarty.const._AM_WFDOWNLOADS_REV_ACTION}></th>
        </tr>
        <{foreach item=review_published from=$reviews_published}>
        <tr class="<{cycle values='even, odd'}>">
            <td><{$review_published.review_id}></td>
            <td>
                <a href='downloads.php?op=download.edit&amp;lid=<{$review_published.lid}>'><{$review_published.download_title}></a>
            </td>
            <td>
                <a href='?op=review.edit&amp;review_id=<{$review_published.review_id}>'><{$review_published.title}></a>
            </td>
            <td>
                <{if ($review_published.reviewer_email) == ''}>
                <{$review_published.reviewer_uname}>
                <{else}>
                <a href='mailto:<{$review_published.reviewer_email}>'><{$review_published.reviewer_uname}></a>
                <{/if}>
            </td>
            <td><{$review_published.formatted_date}></td>
            <td align='center'>
                <a href='?op=review.edit&amp;review_id=<{$review_published.review_id}>'><img src="<{xoModuleIcons16 edit.png}>"
                                                                                             title="<{$smarty.const._AM_WFDOWNLOADS_AREVIEWS_EDIT}>"
                                                                                             alt="<{$smarty.const._AM_WFDOWNLOADS_AREVIEWS_EDIT}>"/></a>
                <a href='?op=review.delete&amp;review_id=<{$review_published.review_id}>'><img src="<{xoModuleIcons16 delete.png}>"
                                                                                               title="<{$smarty.const._AM_WFDOWNLOADS_AREVIEWS_DELETE}>"
                                                                                               alt="<{$smarty.const._AM_WFDOWNLOADS_AREVIEWS_DELETE}>"/></a>
            </td>
        </tr>
        <{/foreach}>
    </table>
    <{$reviews_published_pagenav}>
    <{/if}>
</fieldset>
