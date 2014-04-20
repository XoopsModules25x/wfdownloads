<{include file='db:wfdownloads_header.tpl'}>

<h4 title="<{$smarty.const._MD_WFDOWNLOADS_TITLE}>"><{$down_arr.title}></h4>
<div title="<{$smarty.const._MD_WFDOWNLOADS_DESCRIPTION}>">
    <{$down_arr.description}>
</div>

<br />

<div><{$lang_review_found}></div>

<br />

<table class="wfdownloads_reviews_reviewlist" cellspacing="0">
    <tr>
        <th><{$smarty.const._MD_WFDOWNLOADS_REVIEWER}></th>
        <th><{$smarty.const._CO_WFDOWNLOADS_DATE}></th>
        <th><{$smarty.const._MD_WFDOWNLOADS_RATEDRESOURCE}></th>
        <th><{$smarty.const._CO_WFDOWNLOADS_REVIEW}></th>
    </tr>
<!-- Start review loop -->
<{foreach item=review from=$down_review}>
    <tr>
        <td><{$review.submitter}></td>
        <td><{$review.date}></td>
        <td><img src="<{$xoops_url}>/modules/<{$smarty.const._WFDOWNLOADS_DIRNAME}>/assets/images/icon/<{$review.rated_img}>" alt="" title="" /></td>
        <td>
            <span class="wfdownloads_reviews_revlisttitle">"<{$review.title}>"</span>
            <br />
            <span class="wfdownloads_reviews_revlistrev"><{$review.review}></span>
        </td>
    </tr>
<{/foreach}>
<!-- End review loop -->
</table>
<{if $navbar.navbar }>
<div>
    <span style="font-weight: bold;"><{$smarty.const._MD_WFDOWNLOADS_PAGES}></span>: <{$navbar.navbar}>
</div>
<{/if}>

<br />

<a href="<{$xoops_url}>/modules/wfdownloads/review.php?cid=<{$down_arr.cid}>&amp;lid=<{$down_arr.lid}>"><{$smarty.const._MD_WFDOWNLOADS_ADDREVIEW}></a>

<{include file='db:wfdownloads_footer.tpl'}>
