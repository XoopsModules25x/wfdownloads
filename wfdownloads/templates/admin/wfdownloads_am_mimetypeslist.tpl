<fieldset>
    <legend style='font-weight: bold; color: #900;'><{$smarty.const._AM_WFDOWNLOADS_MIME_ADMINF}></legend>
    <{$smarty.const._AM_WFDOWNLOADS_MIME_ADMINFINFO}>
    <br/>
    <{if ($allowAdminMimetypes|count)}>
    <{foreach item=allowAdminMimetype from=$allowAdminMimetypes}>
    <{$allowAdminMimetype}> |
    <{/foreach}>
    <{else}>
    <{$smarty.const._AM_WFDOWNLOADS_MIME_NOMIMEINFO}>
    <{/if}>
</fieldset>

<br/>

<fieldset>
    <legend style='font-weight: bold; color: #900;'><{$smarty.const._AM_WFDOWNLOADS_MIME_USERF}></legend>
    <{$smarty.const._AM_WFDOWNLOADS_MIME_USERFINFO}>
    <br/>
    <{if ($allowUserMimetypes|count)}>
    <{foreach item=allowUserMimetype from=$allowUserMimetypes}>
    <{$allowUserMimetype}> |
    <{/foreach}>
    <{else}>
    <{$smarty.const._AM_WFDOWNLOADS_MIME_NOMIMEINFO}>
    <{/if}>
</fieldset>

<br/>

<fieldset>
    <legend style='font-weight: bold; color: #900;'><{$smarty.const._AM_WFDOWNLOADS_MIME_MIMETYPES_LIST}></legend>
    <{if ($mimetypes_count == 0)}>
    <{$smarty.const._AM_WFDOWNLOADS_MIME_NOMIMETYPES}>
    <{else}>
    <table class='outer'>
        <tr>
            <th><{$smarty.const._AM_WFDOWNLOADS_MIME_ID}></th>
            <th><{$smarty.const._AM_WFDOWNLOADS_MIME_NAME}></th>
            <th><{$smarty.const._AM_WFDOWNLOADS_MIME_EXT}></th>
            <th><{$smarty.const._AM_WFDOWNLOADS_MIME_ADMIN}></th>
            <th><{$smarty.const._AM_WFDOWNLOADS_MIME_USER}></th>
            <th><{$smarty.const._AM_WFDOWNLOADS_MINDEX_ACTION}></th>
        </tr>
        <{foreach item=mimetype from=$mimetypes}>
        <tr class="<{cycle values='even, odd'}>">
            <td class='head'><{$mimetype.mime_id}></td>
            <td class='even'><{$mimetype.mime_name}></td>
            <td class='even' align='center'>.<{$mimetype.mime_ext}></td>
            <td class='even' align='center' align='center'>
                <a href='?op=mimetype.update&amp;admin=1&amp;mime_id=<{$mimetype.mime_id}>&amp;start=<{$start}>'>
                    <{if ($mimetype.mime_admin == 1)}>
                    <img src="<{xoModuleIcons16 1.png}>"/>
                    <{else}>
                    <img src="<{xoModuleIcons16 0.png}>"/>
                    <{/if}>
                </a>
            </td>
            <td class='even' align='center'>
                <a href='?op=mimetype.update&amp;user=1&amp;mime_id=<{$mimetype.mime_id}>&amp;start=<{$start}>'>
                    <{if ($mimetype.mime_user == 1)}>
                    <img src="<{xoModuleIcons16 1.png}>"/>
                    <{else}>
                    <img src="<{xoModuleIcons16 0.png}>"/>
                    <{/if}>
                </a>
            </td>
            <td class='even' align='center'>
                <a href='?op=mimetype.edit&amp;mime_id=<{$mimetype.mime_id}>'><img src="<{xoModuleIcons16 edit.png}>" title="<{$smarty.const._EDIT}>"
                                                                                   alt="<{$smarty.const._EDIT}>"/></a>
                <a href='?op=mimetype.delete&amp;mime_id=<{$mimetype.mime_id}>'><img src="<{xoModuleIcons16 delete.png}>"
                                                                                     title="<{$smarty.const._DELETE}>"
                                                                                     alt="<{$smarty.const._DELETE}>"/></a>
            </td>
        </tr>
        <{/foreach}>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td align='center'>
                <a href='?op=mimetypes.update&amp;admin=1&amp;type_all=1'><img src="<{xoModuleIcons16 1.png}>"/></a>
                <a href='?op=mimetypes.update&amp;admin=1&amp;type_all=0'><img src="<{xoModuleIcons16 0.png}>"/></a>
            </td>
            <td align='center'>
                <a href='?op=mimetypes.update&amp;user=1&amp;type_all=1'><img src="<{xoModuleIcons16 1.png}>"/></a>
                <a href='?op=mimetypes.update&amp;user=1&amp;type_all=0'><img src="<{xoModuleIcons16 0.png}>"/></a>
            </td>
            <td></td>
        </tr>
    </table>
    <{$mimetypes_pagenav}>
    <{/if}>
</fieldset>
