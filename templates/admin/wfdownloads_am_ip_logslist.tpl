<input type='button' value='<{$smarty.const._AM_WFDOWNLOADS_BACK}>' onclick='history.go(-1)'>
<fieldset>
    <legend style='font-weight: bold; color: #900;'><{$smarty.const._AM_WFDOWNLOADS_IP_LOGS}></legend>
<{if ($ip_logs_count == 0)}>
    <{$smarty.const._AM_WFDOWNLOADS_EMPTY_LOG}>
<{else}>
    <h2><{$download.log_title}></h2>
    <table class="outer">
        <tr>
            <th><{$smarty.const._AM_WFDOWNLOADS_IP_ADDRESS}></th>
            <th><{$smarty.const._AM_WFDOWNLOADS_DATE}></th>
            <th><{$smarty.const._AM_WFDOWNLOADS_USER}></th>
        </tr>
    <{foreach item=ip_log from=$ip_logs}>
        <tr class="<{cycle values='even, odd'}>">
            <td><{$ip_log.ip_address}></td>
            <td align='center'><{$ip_log.date_formatted}></td>
            <td align='center'><a href='../../../userinfo.php?uid=<{$ip_log.uid}>'><{$ip_log.uname}></a></td>
        </tr>
    <{/foreach}>
    </table>
<{/if}>
</fieldset>
<input type='button' value='<{$smarty.const._AM_WFDOWNLOADS_BACK}>' onclick='history.go(-1)'>
