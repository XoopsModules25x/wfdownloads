<{if $use_mirrors == false}>
<div class="errorMsg"><{$smarty.const._AM_WFDOWNLOADS_MIRROR_DISABLED}></div>
<{/if}>
<fieldset>
    <legend style='font-weight: bold;'><{$smarty.const._AM_WFDOWNLOADS_AMIRRORS_INFO}></legend>
    <p>
        <{$smarty.const._AM_WFDOWNLOADS_AMIRRORS_WAITING}>:&nbsp;<{$mirrors_waiting_count}>
        <br/>
        <{$smarty.const._AM_WFDOWNLOADS_MIRROR_MIRROR_TOTAL}>:&nbsp;<{$mirrors_published_count}>
    </p>

    <p>
        <img src="<{xoModuleIcons16 1.png}>" title="<{$smarty.const._AM_WFDOWNLOADS_AMIRRORS_APPROVE_ALT}>"
             alt="<{$smarty.const._AM_WFDOWNLOADS_AMIRRORS_APPROVE_ALT}>"/> <{$smarty.const._AM_WFDOWNLOADS_AMIRRORS_APPROVE_DESC}>
        <br/>
        <img src="<{xoModuleIcons16 edit.png}>" title="<{$smarty.const._AM_WFDOWNLOADS_AMIRRORS_EDIT_ALT}>"
             alt="<{$smarty.const._AM_WFDOWNLOADS_AMIRRORS_EDIT_ALT}>"/> <{$smarty.const._AM_WFDOWNLOADS_AMIRRORS_EDIT_DESC}>
        <br/>
        <img src="<{xoModuleIcons16 delete.png}>" title="<{$smarty.const._AM_WFDOWNLOADS_AMIRRORS_DELETE_ALT}>"
             alt="<{$smarty.const._AM_WFDOWNLOADS_AMIRRORS_DELETE_ALT}>"/> <{$smarty.const._AM_WFDOWNLOADS_AMIRRORS_DELETE_DESC}>
    </p>
</fieldset>

<br/>

<fieldset>
    <legend style='font-weight: bold; color: #900;'><{$smarty.const._AM_WFDOWNLOADS_MIRROR_MIRROR_WAITING}></legend>
    <{if ($mirrors_waiting_count == 0)}>
    <{$smarty.const._AM_WFDOWNLOADS_MIRROR_NOWAITINGMIRRORS}>
    <{else}>
    <table class='outer'>
        <tr>
            <th><{$smarty.const._AM_WFDOWNLOADS_MIRROR_ID}></th>
            <th><{$smarty.const._AM_WFDOWNLOADS_MIRROR_TITLE}></th>
            <th><{$smarty.const._AM_WFDOWNLOADS_MIRROR_POSTER}></th>
            <th><{$smarty.const._AM_WFDOWNLOADS_MIRROR_SUBMITDATE}></th>
            <th><{$smarty.const._AM_WFDOWNLOADS_REV_ACTION}></th>
        </tr>
        <{foreach item=mirror_waiting from=$mirrors_waiting}>
        <tr class="<{cycle values='even, odd'}>">
            <td class='head'><{$mirror_waiting.mirror_id}></td>
            <td class='even'>
                <a href='download.php?op=download.edit&amp;lid=<{$mirror_waiting.lid}>'><{$mirror_waiting.download_title}></a>
            </td>
            <td class='even'><{$mirror_waiting.submitter_uname}></td>
            <td class='even'><{$mirror_waiting.formatted_date}></td>
            <td class='even' align='center'>
                <a href='?op=mirror.approve&amp;mirror_id=<{$mirror_waiting.mirror_id}>'>
                    <img
                        src="<{xoModuleIcons16 1.png}>"
                        title="<{$smarty.const._AM_WFDOWNLOADS_BAPPROVE}>"
                        alt="<{$smarty.const._AM_WFDOWNLOADS_BAPPROVE}>"/>
                </a>
                <a href='?op=mirror.edit&amp;mirror_id=<{$mirror_waiting.mirror_id}>'>
                    <img
                        src="<{xoModuleIcons16 edit.png}>"
                        title="<{$smarty.const._EDIT}>"
                        alt="<{$smarty.const._EDIT}>"/>
                </a>
                <a href='?op=mirror.delete&amp;mirror_id=<{$mirror_waiting.mirror_id}>'>
                    <img
                        src="<{xoModuleIcons16 delete.png}>"
                        title="<{$smarty.const._DELETE}>"
                        alt="<{$smarty.const._DELETE}>"/>
                </a>
            </td>
        </tr>
        <{/foreach}>
    </table>
    <{$mirrors_waiting_pagenav}>
    <{/if}>
</fieldset>

<br/>

<fieldset>
    <legend style='font-weight: bold; color: #900;'><{$smarty.const._AM_WFDOWNLOADS_MIRROR_MIRROR_PUBLISHED}></legend>
    <{if ($mirrors_published_count == 0)}>
    <{$smarty.const._AM_WFDOWNLOADS_MIRROR_NOPUBLISHEDMIRRORS}>
    <{else}>
    <table class='outer'>
        <tr>
            <th><{$smarty.const._AM_WFDOWNLOADS_MIRROR_ID}></th>
            <th><{$smarty.const._AM_WFDOWNLOADS_MIRROR_TITLE}></th>
            <th><{$smarty.const._AM_WFDOWNLOADS_MIRROR_POSTER}></th>
            <th><{$smarty.const._AM_WFDOWNLOADS_MIRROR_SUBMITDATE}></th>
            <th><{$smarty.const._AM_WFDOWNLOADS_REV_ACTION}></th>
        </tr>
        <{foreach item=mirror_published from=$mirrors_published}>
        <tr class="<{cycle values='even, odd'}>">
            <td class='head'><{$mirror_published.mirror_id}></td>
            <td class='even'>
                <a href='download.php?op=download.edit&amp;lid=<{$mirror_published.lid}>'><{$mirror_published.download_title}></a>
            </td>
            <td class='even'><{$mirror_published.submitter_uname}></td>
            <td class='even'><{$mirror_published.formatted_date}></td>
            <td class='even' align='center'>
                <a href='?op=mirror.edit&amp;mirror_id=<{$mirror_published.mirror_id}>'><img src="<{xoModuleIcons16 edit.png}>"
                                                                                             title="<{$smarty.const._EDIT}>"
                                                                                             alt="<{$smarty.const._EDIT}>"/></a>
                <a href='?op=mirror.delete&amp;mirror_id=<{$mirror_published.mirror_id}>'><img src="<{xoModuleIcons16 delete.png}>"
                                                                                               title="<{$smarty.const._DELETE}>"
                                                                                               alt="<{$smarty.const._DELETE}>"/></a>
            </td>
        </tr>
        <{/foreach}>
    </table>
    <{$mirrors_published_pagenav}>
    <{/if}>
</fieldset>
