<{if $use_brokenreports == false}>
    <div class="errorMsg"><{$smarty.const._AM_WFDOWNLOADS_BROKENREPORT_DISABLED}></div>
<{/if}>
<fieldset>
    <legend style='font-weight: bold;'><{$smarty.const._AM_WFDOWNLOADS_BROKEN_REPORTINFO}></legend>
    <p>
        <{$smarty.const._AM_WFDOWNLOADS_BROKEN_REPORTSNO}>:&nbsp;<{$reports_count}>
    </p>

    <p>
        <img src="<{xoModuleIcons16 1.png}>" title="<{$smarty.const._AM_WFDOWNLOADS_BROKEN_IGNORE_ALT}>"
             alt="<{$smarty.const._AM_WFDOWNLOADS_BROKEN_IGNORE_ALT}>"/> <{$smarty.const._AM_WFDOWNLOADS_BROKEN_IGNOREDESC}>
        <br/>
        <img src="<{xoModuleIcons16 edit.png}>" title="<{$smarty.const._AM_WFDOWNLOADS_BROKEN_EDIT_ALT}>"
             alt="<{$smarty.const._AM_WFDOWNLOADS_BROKEN_EDIT_ALT}>"/> <{$smarty.const._AM_WFDOWNLOADS_BROKEN_EDITDESC}>
        <br/>
        <img src="<{xoModuleIcons16 delete.png}>" title="<{$smarty.const._AM_WFDOWNLOADS_BROKEN_DELETE_ALT}>"
             alt="<{$smarty.const._AM_WFDOWNLOADS_BROKEN_DELETE_ALT}>"/> <{$smarty.const._AM_WFDOWNLOADS_BROKEN_DELETEDESC}>
        <br/>
        <img src="<{xoModuleIcons16 1.png}>" title="<{$smarty.const._AM_WFDOWNLOADS_BROKEN_ACK_ALT}>"
             alt="<{$smarty.const._AM_WFDOWNLOADS_BROKEN_ACK_ALT}>"/> <{$smarty.const._AM_WFDOWNLOADS_BROKEN_ACKDESC}>
        <br/>
        <img src="<{xoModuleIcons16 1.png}>" title="<{$smarty.const._AM_WFDOWNLOADS_BROKEN_CONFIRM_ALT}>"
             alt="<{$smarty.const._AM_WFDOWNLOADS_BROKEN_CONFIRM_ALT}>"/> <{$smarty.const._AM_WFDOWNLOADS_BROKEN_CONFIRMDESC}>
    </p>
</fieldset>

<br/>

<fieldset>
    <legend style='font-weight: bold; color: #900;'><{$smarty.const._AM_WFDOWNLOADS_BROKEN_REPORTS}></legend>
    <{if ($reports_count == 0)}>
        <{$smarty.const._AM_WFDOWNLOADS_BROKEN_NOFILEMATCH}>
    <{else}>
        <table class='outer'>
            <tr>
                <th><{$smarty.const._AM_WFDOWNLOADS_BROKEN_ID}></th>
                <th><{$smarty.const._AM_WFDOWNLOADS_BROKEN_DATESUBMITTED}></th>
                <th><{$smarty.const._AM_WFDOWNLOADS_BROKEN_REPORTER}></th>
                <th><{$smarty.const._AM_WFDOWNLOADS_BROKEN_TITLE}></th>
                <th><{$smarty.const._AM_WFDOWNLOADS_BROKEN_FILESUBMITTER}></th>
                <th><{$smarty.const._AM_WFDOWNLOADS_MINDEX_ACTION}></th>
            </tr>
            <{foreach item=report from=$reports}>
                <tr class="<{cycle values='even, odd'}>">
                    <td><{$report.reportid}></td>
                    <td><{$report.formatted_date}></td>
                    <td>
                        <{if ($report.reporter_email) == ''}>
                            <{$report.reporter_uname}> (<{$report.ip}>)
                        <{else}>
                            <a href='mailto:<{$report.reporter_email}>'><{$report.reporter_uname}></a>
                            (<{$report.ip}>)
                        <{/if}>
                    </td>
                    <td>
                        <{if ($report.download_lid) == false}>
                            <{$smarty.const._AM_WFDOWNLOADS_BROKEN_DOWNLOAD_DONT_EXISTS}>
                        <{else}>
                            <a href='" . WFDOWNLOADS_URL . "/singlefile.php?cid=<{$report.download_cid}>&amp;lid=<{$report.download_lid}>' target='_blank'><{$report.download_title}></a>
                        <{/if}>
                    </td>
                    <td>
                        <{if ($report.submitter_email) == ''}>
                            <{$report.submitter_uname}>
                        <{else}>
                            <a href='mailto:<{$report.submitter_email}>'><{$report.submitter_uname}></a>
                        <{/if}>
                    </td>
                    <td align='center'>
                        <a href='?op=report.ignore&amp;lid=' alt='' title=''>
                            <img src="<{xoModuleIcons16 on.png}>" title="<{$smarty.const._AM_WFDOWNLOADS_BROKEN_IGNORE_ALT}>"
                                 alt="<{$smarty.const._AM_WFDOWNLOADS_BROKEN_IGNORE_ALT}>"/>
                        </a>
                        <a href='downloads.php?op=download.edit&amp;lid=<{$report.download_lid}>' alt='' title=''>
                            <img src="<{xoModuleIcons16 edit.png}>" title="<{$smarty.const._AM_WFDOWNLOADS_BROKEN_EDIT_ALT}>"
                                 alt="<{$smarty.const._AM_WFDOWNLOADS_BROKEN_EDIT_ALT}>"/>
                        </a>
                        <a href='?op=report.delete&amp;lid=<{$report.download_lid}>' alt='' title=''>
                            <img src="<{xoModuleIcons16 delete.png}>" title="<{$smarty.const._AM_WFDOWNLOADS_BROKEN_DELETE_ALT}>"
                                 alt="<{$smarty.const._AM_WFDOWNLOADS_BROKEN_DELETE_ALT}>"/>
                        </a>
                        <a href='?op=reports.update&amp;lid=<{$report.download_lid}>&amp;ack=<{$report.acknowledged}>'
                           alt='<{$smarty.const._AM_WFDOWNLOADS_BROKEN_ACK_ALT}>' title='<{$smarty.const._AM_WFDOWNLOADS_BROKEN_ACK_ALT}>'>
                            <{if ($report.acknowledged)}>
                                <img src="<{xoModuleIcons16 1.png}>"/>
                            <{else}>
                                <img src="<{xoModuleIcons16 0.png}>"/>
                            <{/if}>
                        </a>
                        <a href='?op=reports.update&amp;lid=<{$report.download_lid}>&amp;con=<{$report.confirmed}>'
                           alt='<{$smarty.const._AM_WFDOWNLOADS_BROKEN_CONFIRM_ALT}>' title='<{$smarty.const._AM_WFDOWNLOADS_BROKEN_CONFIRM_ALT}>'>
                            <{if ($report.confirmed)}>
                                <img src="<{xoModuleIcons16 1.png}>"/>
                            <{else}>
                                <img src="<{xoModuleIcons16 0.png}>"/>
                            <{/if}>
                        </a>
                    </td>
                </tr>
            <{/foreach}>
        </table>
        <{$reports_pagenav}>
    <{/if}>
</fieldset>

<br/>

<fieldset>
    <legend style='font-weight: bold;'><{$smarty.const._AM_WFDOWNLOADS_MOD_MODREQUESTSINFO}></legend>
    <p>
        <{$smarty.const._AM_WFDOWNLOADS_MOD_TOTMODREQUESTS}>:&nbsp;<{$modifications_count}>
    </p>

    <p>
        <img src="<{xoModuleIcons16 1.png}>" title="<{$smarty.const._AM_WFDOWNLOADS_MOD_APPROVE_ALT}>"
             alt="<{$smarty.const._AM_WFDOWNLOADS_MOD_APPROVE_ALT}>"/> <{$smarty.const._AM_WFDOWNLOADS_MOD_APPROVEDESC}>
        <br/>
        <img src="<{xoModuleIcons16 view.png}>" title="<{$smarty.const._AM_WFDOWNLOADS_MOD_VIEW_ALT}>"
             alt="<{$smarty.const._AM_WFDOWNLOADS_MOD_VIEW_ALT}>"/> <{$smarty.const._AM_WFDOWNLOADS_MOD_VIEWDESC}>
        <br/>
        <img src="<{xoModuleIcons16 delete.png}>" title="<{$smarty.const._AM_WFDOWNLOADS_MOD_IGNORE_ALT}>"
             alt="<{$smarty.const._AM_WFDOWNLOADS_MOD_IGNORE_ALT}>"/> <{$smarty.const._AM_WFDOWNLOADS_MOD_IGNOREDESC}>
    </p>
</fieldset>

<fieldset>
    <legend style='font-weight: bold; color: #900;'><{$smarty.const._AM_WFDOWNLOADS_MODIFICATIONS}></legend>
    <{if ($modifications_count == 0)}>
        <{$smarty.const._AM_WFDOWNLOADS_MOD_NOMODREQUEST}>
    <{else}>
        <table class='outer'>
            <tr>
                <th><{$smarty.const._AM_WFDOWNLOADS_MOD_MODID}></th>
                <th><{$smarty.const._AM_WFDOWNLOADS_MOD_MODTITLE}></th>
                <th><{$smarty.const._AM_WFDOWNLOADS_MOD_MODIFYSUBMIT}></th>
                <th><{$smarty.const._AM_WFDOWNLOADS_MOD_DATE}></th>
                <th><{$smarty.const._AM_WFDOWNLOADS_MINDEX_ACTION}></th>
            </tr>
            <{foreach item=modification from=$modifications}>
                <tr class="<{cycle values='even, odd'}>">
                    <td><{$modification.requestid}></td>
                    <td><{$modification.download.title}> -> <{$modification.title}></td>
                    <td><{$modification.submitter_uname}></td>
                    <td><{$modification.formatted_date}></td>
                    <td align='center'>
                        <a href='?op=modification.change&amp;requestid=<{$modification.requestid}>'>
                            <img
                                    src="<{xoModuleIcons16 1.png}>"
                                    title="<{$smarty.const._AM_WFDOWNLOADS_MOD_APPROVE_ALT}>"
                                    alt="<{$smarty.const._AM_WFDOWNLOADS_MOD_APPROVE_ALT}>"/>
                        </a>
                        <a href='?op=modification.show&amp;requestid=<{$modification.requestid}>'>
                            <img
                                    src="<{xoModuleIcons16 view.png}>"
                                    title="<{$smarty.const._AM_WFDOWNLOADS_MOD_VIEW_ALT}>"
                                    alt="<{$smarty.const._AM_WFDOWNLOADS_MOD_VIEW_ALT}>"/>
                        </a>
                        <a href='?op=modification.ignore&amp;requestid=<{$modification.requestid}>'>
                            <img
                                    src="<{xoModuleIcons16 delete.png}>"
                                    title="<{$smarty.const._AM_WFDOWNLOADS_MOD_IGNORE_ALT}>"
                                    alt="<{$smarty.const._AM_WFDOWNLOADS_MOD_IGNORE_ALT}>"/>
                        </a>
                    </td>
                </tr>
            <{/foreach}>
        </table>
        <{$modifications_pagenav}>
    <{/if}>
</fieldset>
