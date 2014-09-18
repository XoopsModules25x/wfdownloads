<form action="downloads.php" method="post" id="downloadsform">

<fieldset>
    <legend style='font-weight: bold; color: #900;'><{$smarty.const._AM_WFDOWNLOADS_MINDEX_PUBLISHEDDOWN}></legend>
    <{if ($published_downloads_count == 0)}>
        <{$smarty.const._AM_WFDOWNLOADS_MINDEX_NODOWNLOADSFOUND}>
    <{else}>
        <table class="outer">
            <tr>
                <th><{$smarty.const._AM_WFDOWNLOADS_MINDEX_ID}></th>
                <th><{$smarty.const._AM_WFDOWNLOADS_MINDEX_TITLE}></th>
                <th><{$smarty.const._AM_WFDOWNLOADS_FCATEGORY_TITLE}></th>
                <th><{$smarty.const._AM_WFDOWNLOADS_MINDEX_POSTER}></th>
                <th><{$smarty.const._AM_WFDOWNLOADS_MINDEX_SUBMITTED}></th>
                <th><{$smarty.const._AM_WFDOWNLOADS_MINDEX_ONLINESTATUS}></th>
                <th><{$smarty.const._AM_WFDOWNLOADS_MINDEX_PUBLISHED}></th>
                <th><{$smarty.const._AM_WFDOWNLOADS_MINDEX_LOG}></th>
                <th><{$smarty.const._AM_WFDOWNLOADS_MINDEX_ACTION}></th>
            </tr>

            <form id='form_filter' enctype='multipart/form-data' method='post' action='' name='form_filter'>
                <tr>
                    <td>&nbsp;</td>
                    <td>
                        <select id='filter_title_condition' title='<{$smarty.const._AM_WFDOWNLOADS_SEARCH}>' name='filter_title_condition' size='1'>
                            <option value='='<{if $filter_title_condition == '='}>selected='selected'<{/if}>><{$smarty.const._AM_WFDOWNLOADS_SEARCH_EQUAL}></option>
                            <option value='LIKE'<{if $filter_title_condition == 'LIKE'}>selected='selected'<{/if}>><{$smarty.const._AM_WFDOWNLOADS_SEARCH_CONTAINS}></option>
                        </select>
                        <input id='filter_title' type='text' value='<{$filter_title}>' maxlength='100' size='15' title='' name='filter_title'>
                    </td>
                    <td>
                        <select id='filter_category_title_condition' title='<{$smarty.const._AM_WFDOWNLOADS_SEARCH}>' name='filter_category_title_condition' size='1'>
                            <option value='='<{if $filter_category_title_condition =='='}>selected='selected'<{/if}>><{$smarty.const._AM_WFDOWNLOADS_SEARCH_EQUAL}></option>
                            <option value='LIKE' <{if $filter_category_title_condition =='LIKE'}>selected='selected'<{/if}>><{$smarty.const._AM_WFDOWNLOADS_SEARCH_CONTAINS}></option>
                        </select>
                        <input id='filter_category_title' type='text' value='<{$filter_category_title}>' maxlength='100' size='15' title='' name='filter_category_title'>
                    </td>
                    <td><{$filter_submitter_select}></td>
                    <td>
                        <{*
                        <select id='filter_date_condition' title='<{$smarty.const._AM_WFDOWNLOADS_SEARCH}>' name='filter_date_condition' size='1'>
                            <option value='='
                            <{if $filter_date_condition == '='}>selected='selected'<{/if}>><{$smarty.const._AM_WFDOWNLOADS_SEARCH_EQUAL}></option>
                            <option value='>'
                            <{if $filter_date_condition == '>'}>selected='selected'<{/if}>><{$smarty.const._AM_WFDOWNLOADS_SEARCH_GREATERTHAN}></option>
                            <option value='<'
                            <{if $filter_date_condition == '<'}>selected='selected'<{/if}>><{$smarty.const._AM_WFDOWNLOADS_SEARCH_LESSTHAN}></option>
                        </select>
                        <{$filter_date_select}>
                        *}>
                    </td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td align='center'>
                        <input id='submit' class='formButton' type='submit' title='<{$smarty.const._AM_WFDOWNLOADS_FILTER}>' value='<{$smarty.const._AM_WFDOWNLOADS_FILTER}>' name='submit'>
                    </td>
                </tr>
                <input id='op' type='hidden' value='downloads.filter' name='op'>
            </form>

            <{foreach item=download from=$published_downloads}>
                <tr class="<{cycle values='even, odd'}>">
                    <td align='center'><{$download.lid}></td>
                    <td>
                        <a href='../singlefile.php?cid=<{$download.cid}>&amp;lid=<{$download.lid}>'><{$download.title}></a>
                    </td>
                    <td>
                        <a href='../viewcat.php?cid=<{$download.cid}>'><{$download.category_title}></a>
                    </td>
                    <td><{$download.submitter_uname}></td>
                    <td><{$download.published_formatted}></td>
                    <td align='center'>
                        <{if $download.offline}>
                            <img src="<{xoModuleIcons16 0.png}>"/>
                        <{else}>
                            <img src="<{xoModuleIcons16 1.png}>"/>
                        <{/if}>
                    </td>
                    <td align='center'>
                        <{if $download.published}>
                            <img src="<{xoModuleIcons16 1.png}>"/>
                            <!--<{$download.published_formatted}>-->
                        <{else}>
                            <img src="<{xoModuleIcons16 0.png}>"/>
                        <{/if}>
                    </td>
                    <td>
                        <a href='?op=ip_logs.list&amp;lid=<{$download.lid}>' title="<{$smarty.const._AM_WFDOWNLOADS_IP_LOGS}>"><{$smarty.const._AM_WFDOWNLOADS_IP_LOGS}></a>
                    </td>
                    <td align='center'>
                        <a href='?op=download.add&amp;lid=<{$download.lid}>' title="<{$smarty.const._EDIT}>"><img src="<{xoModuleIcons16 edit.png}>"
                                                                                                                  title="<{$smarty.const._EDIT}>"
                                                                                                                  alt="<{$smarty.const._EDIT}>"/></a>
                        <a href='?op=download.delete&amp;lid=<{$download.lid}>' title="<{$smarty.const._DELETE}>"><img src="<{xoModuleIcons16 delete.png}>"
                                                                                                                       title="<{$smarty.const._DELETE}>"
                                                                                                                       alt="<{$smarty.const._DELETE}>"/></a>
                    </td>
                </tr>
            <{/foreach}>
        </table>
        <{$published_downloads_pagenav}>
    <{/if}>
</fieldset>

<br/>

<fieldset>
    <legend style='font-weight: bold; color: #900;'><{$smarty.const._AM_WFDOWNLOADS_MINDEX_NEWDOWN}></legend>
    <{if ($new_downloads_count == 0)}>
        <{$smarty.const._AM_WFDOWNLOADS_MINDEX_NODOWNLOADSFOUND}>
    <{else}>
        <table class='outer'>
            <tr>
                <th><{$smarty.const._AM_WFDOWNLOADS_MINDEX_ID}></th>
                <th><{$smarty.const._AM_WFDOWNLOADS_MINDEX_TITLE}></th>
                <th><{$smarty.const._AM_WFDOWNLOADS_FCATEGORY_TITLE}></th>
                <th><{$smarty.const._AM_WFDOWNLOADS_MINDEX_POSTER}></th>
                <th><{$smarty.const._AM_WFDOWNLOADS_MINDEX_SUBMITTED}></th>
                <th><{$smarty.const._AM_WFDOWNLOADS_MINDEX_ACTION}></th>
            </tr>
            <{foreach item=new_download from=$new_downloads}>
                <tr class="<{cycle values='even, odd'}>">
                    <td><{$new_download.lid}></td>
                    <td><{$new_download.title}></td>
                    <td><{$new_download.category_title}></td>
                    <td><{$new_download.submitter_uname}></td>
                    <td><{$new_download.date_formatted}></td>
                    <td align='center'>
                        <a href='?op=newdownload.approve&amp;lid=<{$new_download.lid}>' title="<{$smarty.const._AM_WFDOWNLOADS_BAPPROVE}>"><img
                                    src="<{xoModuleIcons16 1.png}>" title="<{$smarty.const._AM_WFDOWNLOADS_BAPPROVE}>"
                                    alt="<{$smarty.const._AM_WFDOWNLOADS_BAPPROVE}>"/></a>
                        <a href='?op=download.edit&amp;lid=<{$new_download.lid}>' title="<{$smarty.const._EDIT}>"><img src="<{xoModuleIcons16 edit.png}>"
                                                                                                                       title="<{$smarty.const._EDIT}>"
                                                                                                                       alt="<{$smarty.const._EDIT}>"/></a>
                        <a href='?op=download.delete&amp;lid=<{$new_download.lid}>' title="<{$smarty.const._DELETE}>"><img
                                    src="<{xoModuleIcons16 delete.png}>" title="<{$smarty.const._DELETE}>" alt="<{$smarty.const._DELETE}>"/></a>
                    </td>
                </tr>
            <{/foreach}>
        </table>
        <{$newdownloads_pagenav}>
    <{/if}>
</fieldset>

<br/>

<fieldset>
    <legend style='font-weight: bold; color: #900;'><{$smarty.const._AM_WFDOWNLOADS_MINDEX_AUTOPUBLISHEDDOWN}></legend>
    <br/>
    <{if ($autopublished_downloads_count == 0)}>
        <{$smarty.const._AM_WFDOWNLOADS_MINDEX_NODOWNLOADSFOUND}>
    <{else}>
        <table class="outer">
            <tr>
                <th><{$smarty.const._AM_WFDOWNLOADS_MINDEX_ID}></th>
                <th><{$smarty.const._AM_WFDOWNLOADS_MINDEX_TITLE}></th>
                <th><{$smarty.const._AM_WFDOWNLOADS_FCATEGORY_TITLE}></th>
                <th><{$smarty.const._AM_WFDOWNLOADS_MINDEX_POSTER}></th>
                <th><{$smarty.const._AM_WFDOWNLOADS_MINDEX_SUBMITTED}></th>
                <th><{$smarty.const._AM_WFDOWNLOADS_MINDEX_ONLINESTATUS}></th>
                <th><{$smarty.const._AM_WFDOWNLOADS_MINDEX_PUBLISHED}></th>
                <th><{$smarty.const._AM_WFDOWNLOADS_MINDEX_LOG}></th>
                <th><{$smarty.const._AM_WFDOWNLOADS_MINDEX_ACTION}></th>
            </tr>
            <{foreach item=download from=$autopublished_downloads}>
                <tr class="<{cycle values='even, odd'}>">
                    <td><{$download.lid}></td>
                    <td><a href='../singlefile.php?cid=<{$download.cid}>&amp;lid=<{$download.lid}>'><{$download.title}></a></td>
                    <td><{$download.category_title}></td>
                    <td><{$download.submitter_uname}></td>
                    <td><{$download.published_formatted}></td>
                    <td align='center'>
                        <{if $download.offline}>
                            <img src="<{xoModuleIcons16 0.png}>"/>
                        <{else}>
                            <img src="<{xoModuleIcons16 1.png}>"/>
                        <{/if}>
                    </td>
                    <td align='center'>
                        <{if $download.published}>
                            <img src="<{xoModuleIcons16 1.png}>"/>
                            <!--<{$download.published_formatted}>-->
                        <{else}>
                            <img src="<{xoModuleIcons16 0.png}>"/>
                        <{/if}>
                    </td>
                    <td><a href='ip_logs.php?lid=<{$download.lid}>'><{$smarty.const._AM_WFDOWNLOADS_IP_LOGS}></a></td>
                    <td align='center'>
                        <a href='?op=download.add&amp;lid=<{$download.lid}>' title="<{$smarty.const._EDIT}>"><img src="<{xoModuleIcons16 edit.png}>"
                                                                                                                  title="<{$smarty.const._EDIT}>"
                                                                                                                  alt="<{$smarty.const._EDIT}>"/></a>
                        <a href='?op=download.delete&amp;lid=<{$download.lid}>' title="<{$smarty.const._DELETE}>"><img src="<{xoModuleIcons16 delete.png}>"
                                                                                                                       title="<{$smarty.const._DELETE}>"
                                                                                                                       alt="<{$smarty.const._DELETE}>"/></a>
                    </td>
                </tr>
            <{/foreach}>
        </table>
        <{$autopublished_downloads_pagenav}>
    <{/if}>
</fieldset>

<br/>

<fieldset>
    <legend style='font-weight: bold; color: #900;'><{$smarty.const._AM_WFDOWNLOADS_MINDEX_EXPIREDDOWN}></legend>
    <br/>
    <{if ($expired_downloads_count == 0)}>
        <{$smarty.const._AM_WFDOWNLOADS_MINDEX_NODOWNLOADSFOUND}>
    <{else}>
        <table class="outer">
            <tr>
                <th><{$smarty.const._AM_WFDOWNLOADS_MINDEX_ID}></th>
                <th><{$smarty.const._AM_WFDOWNLOADS_MINDEX_TITLE}></th>
                <th><{$smarty.const._AM_WFDOWNLOADS_FCATEGORY_TITLE}></th>
                <th><{$smarty.const._AM_WFDOWNLOADS_MINDEX_POSTER}></th>
                <th><{$smarty.const._AM_WFDOWNLOADS_MINDEX_SUBMITTED}></th>
                <th><{$smarty.const._AM_WFDOWNLOADS_MINDEX_ONLINESTATUS}></th>
                <th><{$smarty.const._AM_WFDOWNLOADS_MINDEX_PUBLISHED}></th>
                <th><{$smarty.const._AM_WFDOWNLOADS_MINDEX_LOG}></th>
                <th><{$smarty.const._AM_WFDOWNLOADS_MINDEX_ACTION}></th>
            </tr>
            <{foreach item=download from=$expired_downloads}>
                <tr class="<{cycle values='even, odd'}>">
                    <td><{$download.lid}></td>
                    <td><a href='../singlefile.php?cid=<{$download.cid}>&amp;lid=<{$download.lid}>'><{$download.title}></a></td>
                    <td><{$download.category_title}></td>
                    <td><{$download.submitter_uname}></td>
                    <td><{$download.published_formatted}></td>
                    <td align='center'>
                        <{if $download.offline}>
                            <img src="<{xoModuleIcons16 0.png}>"/>
                        <{else}>
                            <img src="<{xoModuleIcons16 1.png}>"/>
                        <{/if}>
                    </td>
                    <td align='center'>
                        <{if $download.published}>
                            <img src="<{xoModuleIcons16 1.png}>"/>
                            <!--<{$download.published_formatted}>-->
                        <{else}>
                            <img src="<{xoModuleIcons16 0.png}>"/>
                        <{/if}>
                    </td>
                    <td><a href='ip_logs.php?lid=<{$download.lid}>'><{$smarty.const._AM_WFDOWNLOADS_IP_LOGS}></a></td>
                    <td align='center'>
                        <a href='?op=download.add&amp;lid=<{$download.lid}>' title="<{$smarty.const._EDIT}>"><img src="<{xoModuleIcons16 edit.png}>"
                                                                                                                  title="<{$smarty.const._EDIT}>"
                                                                                                                  alt="<{$smarty.const._EDIT}>"/></a>
                        <a href='?op=download.delete&amp;lid=<{$download.lid}>' title="<{$smarty.const._DELETE}>"><img src="<{xoModuleIcons16 delete.png}>"
                                                                                                                       title="<{$smarty.const._DELETE}>"
                                                                                                                       alt="<{$smarty.const._DELETE}>"/></a>
                    </td>
                </tr>
            <{/foreach}>
        </table>
        <{$expired_downloads_pagenav}>
    <{/if}>
</fieldset>

<br/>

<fieldset>
    <legend style='font-weight: bold; color: #900;'><{$smarty.const._AM_WFDOWNLOADS_MINDEX_OFFLINEDOWN}></legend>
    <br/>
    <{if ($offline_downloads_count == 0)}>
        <{$smarty.const._AM_WFDOWNLOADS_MINDEX_NODOWNLOADSFOUND}>
    <{else}>
        <table class="outer">
            <tr>
                <th><{$smarty.const._AM_WFDOWNLOADS_MINDEX_ID}></th>
                <th><{$smarty.const._AM_WFDOWNLOADS_MINDEX_TITLE}></th>
                <th><{$smarty.const._AM_WFDOWNLOADS_FCATEGORY_TITLE}></th>
                <th><{$smarty.const._AM_WFDOWNLOADS_MINDEX_POSTER}></th>
                <th><{$smarty.const._AM_WFDOWNLOADS_MINDEX_SUBMITTED}></th>
                <th><{$smarty.const._AM_WFDOWNLOADS_MINDEX_ONLINESTATUS}></th>
                <th><{$smarty.const._AM_WFDOWNLOADS_MINDEX_PUBLISHED}></th>
                <th><{$smarty.const._AM_WFDOWNLOADS_MINDEX_LOG}></th>
                <th><{$smarty.const._AM_WFDOWNLOADS_MINDEX_ACTION}></th>
            </tr>
            <{foreach item=download from=$offline_downloads}>
                <tr class="<{cycle values='even, odd'}>">
                    <td><{$download.lid}></td>
                    <td><a href='../singlefile.php?cid=<{$download.cid}>&amp;lid=<{$download.lid}>'><{$download.title}></a></td>
                    <td><{$download.category_title}></td>
                    <td><{$download.submitter_uname}></td>
                    <td><{$download.published_formatted}></td>
                    <td align='center'>
                        <{if $download.offline}>
                            <img src="<{xoModuleIcons16 0.png}>"/>
                        <{else}>
                            <img src="<{xoModuleIcons16 1.png}>"/>
                        <{/if}>
                    </td>
                    <td align='center'>
                        <{if $download.published}>
                            <img src="<{xoModuleIcons16 1.png}>"/>
                            <!--<{$download.published_formatted}>-->
                        <{else}>
                            <img src="<{xoModuleIcons16 0.png}>"/>
                        <{/if}>
                    </td>
                    <td><a href='ip_logs.php?lid=<{$download.lid}>'><{$smarty.const._AM_WFDOWNLOADS_IP_LOGS}></a></td>
                    <td align='center'>
                        <a href='?op=download.add&amp;lid=<{$download.lid}>' title="<{$smarty.const._EDIT}>"><img src="<{xoModuleIcons16 edit.png}>"
                                                                                                                  title="<{$smarty.const._EDIT}>"
                                                                                                                  alt="<{$smarty.const._EDIT}>"/></a>
                        <a href='?op=download.delete&amp;lid=<{$download.lid}>' title="<{$smarty.const._DELETE}>"><img src="<{xoModuleIcons16 delete.png}>"
                                                                                                                       title="<{$smarty.const._DELETE}>"
                                                                                                                       alt="<{$smarty.const._DELETE}>"/></a>
                    </td>
                </tr>
            <{/foreach}>
        </table>
        <{$offline_downloads_pagenav}>
    <{/if}>
</fieldset>

<br/>

<fieldset>
    <legend style='font-weight: bold; color: #900;'><{$smarty.const._AM_WFDOWNLOADS_MINDEX_BATCHFILES}></legend>
    <br/>
    <{$smarty.const._AM_WFDOWNLOADS_MINDEX_BATCHPATH}>: <{$batch_path}>
    <br/>
    <{if ($batch_files_count == 0)}>
    <{$smarty.const._AM_WFDOWNLOADS_MINDEX_NOBATCHFILESFOUND}>
    <{else}>
    <table class="outer">
        <tr>
            <th><{$smarty.const._AM_WFDOWNLOADS_MINDEX_ID}></th>
            <th><{$smarty.const._AM_WFDOWNLOADS_BATCHFILE_FILENAME}></th>
            <th><{$smarty.const._AM_WFDOWNLOADS_BATCHFILE_FILESIZE}></th>
            <th>.<{$smarty.const._AM_WFDOWNLOADS_BATCHFILE_EXTENSION}> - <{$smarty.const._AM_WFDOWNLOADS_BATCHFILE_MIMETYPE}></th>
            <th><{$smarty.const._AM_WFDOWNLOADS_MINDEX_ACTION}></th>
        </tr>
        <{foreach item=batch_file from=$batch_files}>
        <tr class="<{cycle values='even, odd'}>">
            <td><{$batch_file.id}></td>
            <td><{$batch_file.filename}></td>
            <td><{$batch_file.size}></td>
            <td>.<{$batch_file.extension}> - <{$batch_file.mimetype}></td>
            <td align='center'>
                <a href='?op=batchfile.add&amp;batchid=<{$batch_file.id}>' title="<{$smarty.const._ADD}>"><img src="<{xoModuleIcons16 add.png}>"
                                                                                                          title="<{$smarty.const._ADD}>"
                                                                                                          alt="<{$smarty.const._ADD}>"/></a>
                <a href='?op=batchfile.delete&amp;batchid=<{$batch_file.id}>' title="<{$smarty.const._DELETE}>"><img src="<{xoModuleIcons16 delete.png}>"
                                                                                                               title="<{$smarty.const._DELETE}>"
                                                                                                               alt="<{$smarty.const._DELETE}>"/></a>
            </td>
        </tr>
        <{/foreach}>
    </table>
    <{/if}>
</fieldset>

</form>
