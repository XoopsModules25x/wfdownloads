<{include file='db:wfdownloads_header.tpl'}>

<{if count($categories) gt 0}>
<div>
    <h3><{$smarty.const._MD_WFDOWNLOADS_MAINLISTING}></h3>
    <br />
    <!-- Start category loop -->
<{foreach item=category from=$categories}>
    <div>
        <div>
            <a href="viewcat.php?cid=<{$category.id}>" style="font-weight: bold;"><{$category.title}></a>&nbsp;(<{$category.downloads_count}>)
        <{if $isAdmin == true}>
            <a href="admin/categories.php?op=category.edit&amp;cid=<{$category.cid}>"><img src="<{xoModuleIcons16 edit.png}>"
                                                                                           title="<{$smarty.const._EDIT}>"
                                                                                           alt="<{$smarty.const._EDIT}>"/></a>
            <a href="admin/categories.php?op=category.delete&amp;cid=<{$category.cid}>"><img src="<{xoModuleIcons16 delete.png}>"
                                                                                             title="<{$smarty.const._DELETE}>"
                                                                                             alt="<{$smarty.const._DELETE}>"/></a>
        <{/if}>
        <{if $category.allowed_upload == true}>
            <a href="submit.php?cid=<{$category.cid}>"><img src="<{xoModuleIcons16 add.png}>" title="<{$smarty.const._MD_WFDOWNLOADS_SUBMITDOWNLOAD}>"
                                                            alt="<{$smarty.const._MD_WFDOWNLOADS_SUBMITDOWNLOAD}>"/></a>
        <{/if}>
        </div>
        <img src="<{$category.image_URL}>" alt="<{$category.alttext}>" title="<{$category.alttext}>"/><br />
    <{if ($category.days) == ''}>
        <!-- No downloads -->
    <{else}>
        <!--Last upload <{$category.days}> days ago-->
    <{/if}>
        <div title="<{$smarty.const._MD_WFDOWNLOADS_CSUMMARY}>">
            <{$category.summary}>
        </div>
    <{if $category.subcategories}>
        <div>
        <{foreach item=subcategory from=$category.subcategories}>
            <div>
                <span style="font-size: small;"><a href="viewcat.php?cid=<{$subcategory.cid}>"><{$subcategory.title}></a></span>
            </div>
        <{/foreach}>
        </div>
    <{/if}>
    </div>
    <br />
<{/foreach}>
    <!-- End category loop -->
</div>

<br />

<div>
    <span style="font-size: small;"><{$lang_thereare}></span>
</div>

<br />

<div>
    <span style="font-size: small;">
    <ul>
        <li><img src="assets/images/icon/download.gif" alt="" title=""/>&nbsp;<{$smarty.const._MD_WFDOWNLOADS_NO_FILES}></li>
        <li><img src="assets/images/icon/download1.gif" alt="" title=""/>&nbsp;<{$smarty.const._MD_WFDOWNLOADS_LEGENDTEXTNEW}></li>
        <li><img src="assets/images/icon/download2.gif" alt="" title=""/>&nbsp;<{$smarty.const._MD_WFDOWNLOADS_LEGENDTEXTNEWTHREE}></li>
        <li><img src="assets/images/icon/download3.gif" alt="" title=""/>&nbsp;<{$smarty.const._MD_WFDOWNLOADS_LEGENDTEXTTHISWEEK}></li>
        <li><img src="assets/images/icon/download4.gif" alt="" title=""/>&nbsp;<{$smarty.const._MD_WFDOWNLOADS_LEGENDTEXTNEWLAST}></li>
    </ul>
    </span>
</div>
<{/if}>

<{if $full_rssfeed_URL != ''}>
<a href='<{$full_rssfeed_URL}>' title='<{$smarty.const._MD_WFDOWNLOADS_LEGENDTEXTRSS}>'>
    <img src='assets/images/icon/rss.gif' alt='<{$smarty.const._MD_WFDOWNLOADS_LEGENDTEXTRSS}>' title='<{$smarty.const._MD_WFDOWNLOADS_LEGENDTEXTRSS}>'/>
</a>
<{/if}>

<br />

<div align="<{$catarray.indexfooteralign}>">
    <{$catarray.indexfooter}>
</div>

<br />

<{include file='db:wfdownloads_footer.tpl'}>
