<{include file='db:wfdownloads_header0.tpl'}>

<!--
<{*<div>*}>
<{*<{if $cat_rssfeed_link !== ""}>*}>
<{*<{$cat_rssfeed_link}>*}>
<{*<{/if}>*}>
<{*<{$description}></div><br>*}>
-->

<{if $subcategories}>
<fieldset><legend class="subcategories"><{$smarty.const._MD_WFDOWNLOADS_SUBCATLISTING}></legend>
<div style="padding: 2px;">
<div align= "left" style="margin-left: 5px; padding: 0px;">
 <table width="540">
  <tr>
   <{foreach item=subcat from=$subcategories}>
    <td width="298" height="30"><a href="viewcat.php?cid=<{$subcat.id}>"><{$subcat.title}></a>&nbsp;<!-- (<{$subcat.totallinks}>) --><br>
<!-- removed below due to issues with subcats display when deeper than 2 levels
        <{*<{if $subcat.infercategories}>*}>
            <{*<{foreach item=subsubcat from=$subcat.infercategories}>*}>
                <{*<{$subsubcat.title}>*}>
            <{*<{/foreach}>*}>
        <{*<{/if}>*}>
-->
    </td>
     <{if $subcat.count % 2 == 0}>
      </tr><tr>
     <{/if}>
   <{/foreach}>
   </tr>
 </table>
</div></fieldset>
<br>
<{/if}>

<!--
<{*<div><b><{$category_path}></b></div><br>*}>

<{*<{if $show_links === true}> *}>
<{*<div align="center"><small>*}>
<{*<b><{$smarty.const._MD_WFDOWNLOADS_SORTBY}></b>&nbsp;<{$smarty.const._MD_WFDOWNLOADS_TITLE}> (*}>
<{*<a href="viewcat.php?cid=<{$category_id}>&amp;orderby=titleA">*}>
<{*<img src="images/up.gif" align="middle" alt=""></a>*}>
<{*<a href="viewcat.php?cid=<{$category_id}>&amp;orderby=titleD">*}>
<{*<img src="images/down.gif" align="middle" alt=""></a>*}>
<{*)*}>
<{*&nbsp;*}>
<{*<{$smarty.const._MD_WFDOWNLOADS_DATE}> (*}>
<{*<a href="viewcat.php?cid=<{$category_id}>&amp;orderby=dateA">*}>
<{*<img src="images/up.gif" align="middle" alt=""></a>*}>
<{*<a href="viewcat.php?cid=<{$category_id}>&amp;orderby=dateD">*}>
<{*<img src="images/down.gif" align="middle" alt=""></a>*}>
<{*)*}>
<{*&nbsp;*}>
<{*<{$smarty.const._MD_WFDOWNLOADS_RATING}> (*}>
<{*<a href="viewcat.php?cid=<{$category_id}>&amp;orderby=ratingA">*}>
<{*<img src="images/up.gif" align="middle" alt=""></a>*}>
<{*<a href="viewcat.php?cid=<{$category_id}>&amp;orderby=ratingD">*}>
<{*<img src="images/down.gif" align="middle" alt=""></a>*}>
<{*)*}>
<{*&nbsp;*}>
<{*<{$smarty.const._MD_WFDOWNLOADS_POPULARITY}> (*}>
<{*<a href="viewcat.php?cid=<{$category_id}>&amp;orderby=hitsA">*}>
<{*<img src="images/up.gif" align="middle" alt="">*}>
<{*</a><a href="viewcat.php?cid=<{$category_id}>&amp;orderby=hitsD">*}>
<{*<img src="images/down.gif" align="middle" alt=""></a>*}>
<{*)*}>
<{*<br>*}>
<{*<b><{$lang_cursortedby}></b>*}>
<{*</small></div>*}>
<{*<br>*}>
<{*<{/if}>*}>
-->

<{*<{if $page_nav === true}>*}>
<{*<div><{$smarty.const._MD_WFDOWNLOADS_PAGES}>: <{$pagenav}></div><br>*}>
<{*<{/if}>*}>

<table width="100%" cellspacing="0" cellpadding="10" border="0">
 <tr>
  <td width="100%">
   <!-- Start link loop -->
   <{section name=i loop=$file}>
     <{include file="db:wfdownloads_download0.tpl" down=$file[i]}>
   <{/section}>
   <!-- End link loop -->
  </td>
 </tr>
</table>

<{*<{if $page_nav === true}>*}>
<{*<div align="right"><{$smarty.const._MD_WFDOWNLOADS_PAGES}>: <{$pagenav}></div>*}>
<{*<{/if}>*}>

<{include file='db:wfdownloads_footer.tpl'}>
