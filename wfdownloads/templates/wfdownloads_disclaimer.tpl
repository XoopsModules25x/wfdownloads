<{include file='db:wfdownloads_header.tpl'}>

<h4><{$smarty.const._MD_WFDOWNLOADS_DISCLAIMERAGREEMENT}></h4>

<div>
<{if $download_disclaimer == true}>
    <{$download_disclaimer_content}>
<{elseif $submission_disclaimer == true}>
    <{$submission_disclaimer_content}>
<{/if}>
</div>

<br />

<{if $download_disclaimer == true}>
<form action="visit.php" method="post">
<{elseif $submission_disclaimer == true}>
<form action="submit.php" method="post">
<{/if}>
    <div align="center">
        <span style="font-weight: bold;"><{$smarty.const._MD_WFDOWNLOADS_DOYOUAGREE}></span>
        <br />
        <br />
        <input type='submit' class='formButton' value='<{$smarty.const._MD_WFDOWNLOADS_AGREE}>' alt='<{$smarty.const._MD_WFDOWNLOADS_AGREE}>' />
        <input type='hidden' name='agreed' value='1' />
        &nbsp;
        <input type='button' onclick='history.go(-1)' class='formButton' value='<{$smarty.const._CANCEL}>' alt='<{$smarty.const._CANCEL}>' />
        <input type='hidden' name='lid' value='<{$lid}>' />
        <input type='hidden' name='cid' value='<{$cid}>' />
    </div>
</form>
<br />

<{include file='db:wfdownloads_footer.tpl'}>
