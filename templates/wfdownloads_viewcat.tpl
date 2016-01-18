<{include file='db:wfdownloads_header.tpl'}>

<div>
    <{if $category_rssfeed_URL != ''}>
        <a href='<{$category_rssfeed_URL}>' title='<{$smarty.const._MD_WFDOWNLOADS_LEGENDTEXTCATRSS}>'>
            <img src='assets/images/icon/rss.gif' alt='<{$smarty.const._MD_WFDOWNLOADS_LEGENDTEXTCATRSS}>'
                 title='<{$smarty.const._MD_WFDOWNLOADS_LEGENDTEXTCATRSS}>'/>
        </a>
    <{/if}>
    <{if $category_title != ""}>
        <h1>
            <{$category_title}>
            <{if $isAdmin == true}>
                <a href="admin/categories.php?op=category.edit&amp;cid=<{$category_cid}>"><img src="<{xoModuleIcons16 edit.png}>"
                                                                                               title="<{$smarty.const._EDIT}>" alt="<{$smarty.const._EDIT}>"/></a>
                <a href="admin/categories.php?op=category.delete&amp;cid=<{$category_cid}>"><img src="<{xoModuleIcons16 delete.png}>"
                                                                                                 title="<{$smarty.const._DELETE}>"
                                                                                                 alt="<{$smarty.const._DELETE}>"/></a>
            <{/if}>
            <{if $category_allowed_upload == true}>
                <a href="submit.php?cid=<{$category_cid}>"><img src="<{xoModuleIcons16 add.png}>" title="<{$smarty.const._MD_WFDOWNLOADS_SUBMITDOWNLOAD}>"
                                                                alt="<{$smarty.const._MD_WFDOWNLOADS_SUBMITDOWNLOAD}>"/></a>
            <{/if}>
        </h1>
    <{/if}>
    <{if $category_image_URL}>
        <img src="<{$category_image_URL}>" alt="<{$category_title}>" title="<{$category_title}>"/>
        <br/>
    <{/if}>
    <{$category_description}>
</div>

<br/>

<{if $subcategories}>
    <div>
        <h3><{$smarty.const._MD_WFDOWNLOADS_SUBCATEGORIESLISTING}></h3>
        <br/>
        <!-- Start category loop -->
        <{foreach item=subcategory from=$subcategories}>
            <div>
                <div>
                    <a href="viewcat.php?cid=<{$subcategory.cid}>" style="font-weight: bold;"><{$subcategory.title}></a>&nbsp;(<{$subcategory.downloads_count}>)
                    <{if $isAdmin == true}>
                        <a href="admin/categories.php?op=category.edit&amp;cid=<{$subcategory.cid}>"><img src="<{xoModuleIcons16 edit.png}>"
                                                                                                          title="<{$smarty.const._EDIT}>"
                                                                                                          alt="<{$smarty.const._EDIT}>"/></a>
                        <a href="admin/categories.php?op=category.delete&amp;cid=<{$subcategory.cid}>"><img src="<{xoModuleIcons16 delete.png}>"
                                                                                                            title="<{$smarty.const._DELETE}>"
                                                                                                            alt="<{$smarty.const._DELETE}>"/></a>
                    <{/if}>
                    <{if $subcategory.allowed_upload == true}>
                        <a href="submit.php?cid=<{$subcategory.cid}>"><img src="<{xoModuleIcons16 add.png}>"
                                                                           title="<{$smarty.const._MD_WFDOWNLOADS_SUBMITDOWNLOAD}>"
                                                                           alt="<{$smarty.const._MD_WFDOWNLOADS_SUBMITDOWNLOAD}>"/></a>
                    <{/if}>
                </div>
                <{if $subcategory.image_URL != ''}><img src="<{$subcategory.image_URL}>" alt="<{$subcategory.title}>" title="<{$subcategory.title}>"/><br/><{/if}>
                <div title="<{$smarty.const._MD_WFDOWNLOADS_CSUMMARY}>">
                    <{$subcategory.summary}>
                </div>
            </div>
            <br/>
        <{/foreach}>
    </div>
<{/if}>

<div class="wfdownloads_view_catpath">
    <{$category_path}>
</div>

<br/>

<{if $downloads}>
    <div>
        <h3><{$smarty.const._MD_WFDOWNLOADS_DOWNLOADSLISTING}></h3>
        <{if $show_links == true}>
            <style type="text/css">
                .button_green {
                    -moz-box-shadow: inset 0 1px 0 0 #d9fbbe;
                    -webkit-box-shadow: inset 0 1px 0 0 #d9fbbe;
                    box-shadow: inset 0 1px 0 0 #d9fbbe;
                    background: -webkit-gradient(linear, left top, left bottom, color-stop(0.05, #d9fbbe), color-stop(1, #d9fbbe));
                    background: -moz-linear-gradient(,center top, #a5cc52 5%, #d9fbbe 100%);
                    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#d9fbbe', endColorstr='#b8e356');
                    background-color: #d9fbbe;
                    -webkit-border-radius: 2px;
                    -moz-border-radius: 2px;
                    border-radius: 2px;
                    text-indent: 0;
                    border: 1px solid #83c41a;
                    display: inline-block;
                    color: inherit;
                    font-family: inherit;
                    font-size: 12px;
                    font-weight: bold;
                    font-style: normal;
                    height: 20px;
                    line-height: 20px;
                    width: auto;
                    min-width: 10px;
                    text-decoration: none;
                    text-align: center;
                    text-shadow: 1px 1px 0 #d9fbbe;
                    margin: 2px 0;
                    padding: 0 4px;
                }

                .button_green:hover {
                    background: -webkit-gradient(linear, left top, left bottom, color-stop(0.05, #b8e356), color-stop(1, #a5cc52));
                    background: -moz-linear-gradient(,center top, #b8e356 5%, #a5cc52 100%);
                    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#b8e356', endColorstr='#a5cc52');
                    background-color: #86ae47;
                }

                .button_green:active {
                    position: relative;
                    top: 1px;
                }

                .button_grey {
                    background: -webkit-gradient(linear, left top, left bottom, color-stop(0.05, #ededed), color-stop(1, #dfdfdf));
                    background: -moz-linear-gradient(,center top, #ededed 5%, #dfdfdf 100%);
                    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ededed', endColorstr='#dfdfdf');
                    background-color: #ededed;
                    -webkit-border-radius: 2px;
                    -moz-border-radius: 2px;
                    border-radius: 2px;
                    text-indent: 0;
                    border: 1px solid #dcdcdc;
                    display: inline-block;
                    color: inherit;
                    font-family: inherit;
                    font-size: 12px;
                    font-weight: bold;
                    font-style: normal;
                    height: 20px;
                    line-height: 20px;
                    width: auto;
                    min-width: 10px;
                    text-decoration: none;
                    text-align: center;
                    text-shadow: 1px 1px 0 #ffffff;
                    margin: 2px 0;
                    padding: 0 4px;
                }

                .button_grey:hover {
                    background: -webkit-gradient(linear, left top, left bottom, color-stop(0.05, #dfdfdf), color-stop(1, #ededed));
                    background: -moz-linear-gradient(,center top, #dfdfdf 5%, #ededed 100%);
                    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#dfdfdf', endColorstr='#ededed');
                    background-color: #dfdfdf;
                }

                .button_grey:active {
                    position: relative;
                    top: 1px;
                }
            </style>
            <div>
                <span style="font-size: small; font-weight: bold;"><span title="<{$smarty.const._MD_WFDOWNLOADS_SORTDOWNLOADSBY}>"><{$lang_cursortedby}></span></span>

                <div>
                    <{if ($orderby == "titleA")}>
                        <span class='button_green' title="<{$smarty.const._MD_WFDOWNLOADS_TITLEATOZ}>"><{$smarty.const._MD_WFDOWNLOADS_TITLE}>&nbsp;<img
                                    src="<{xoModuleIcons16 up.gif}>" alt="&uarr;"></span>
                    <{else}>
                        <a class='button_grey' href="viewcat.php?cid=<{$category_cid}>&amp;orderby=titleA" title="<{$smarty.const._MD_WFDOWNLOADS_TITLEATOZ}>"><{$smarty.const._MD_WFDOWNLOADS_TITLE}>
                            &nbsp;<img
                                    src="<{xoModuleIcons16 up.gif}>" alt="&uarr;"></a>
                    <{/if}>
                    <{if ($orderby == "titleD")}>
                        <span class='button_green' title="<{$smarty.const._MD_WFDOWNLOADS_TITLEZTOA}>"><{$smarty.const._MD_WFDOWNLOADS_TITLE}>&nbsp;<img
                                    src="<{xoModuleIcons16 down.gif}>" alt="&darr;"></span>
                    <{else}>
                        <a class='button_grey' href="viewcat.php?cid=<{$category_cid}>&amp;orderby=titleD" title="<{$smarty.const._MD_WFDOWNLOADS_TITLEZTOA}>"><{$smarty.const._MD_WFDOWNLOADS_TITLE}>
                            &nbsp;<img
                                    src="<{xoModuleIcons16 down.gif}>" alt="&darr;"></a>
                    <{/if}>
                    |
                    <{if ($orderby == "dateA")}>
                        <span class='button_green' title="<{$smarty.const._MD_WFDOWNLOADS_DATEOLD}>"><{$smarty.const._MD_WFDOWNLOADS_DATE}>&nbsp;<img
                                    src="<{xoModuleIcons16 up.gif}>" alt="&uarr;"></span>
                    <{else}>
                        <a class='button_grey' href="viewcat.php?cid=<{$category_cid}>&amp;orderby=dateA" title="<{$smarty.const._MD_WFDOWNLOADS_DATEOLD}>"><{$smarty.const._MD_WFDOWNLOADS_DATE}>&nbsp;<img
                                    src="<{xoModuleIcons16 up.gif}>" alt="&uarr;"></a>
                    <{/if}>
                    <{if ($orderby == "dateD")}>
                        <span class='button_green' title="<{$smarty.const._MD_WFDOWNLOADS_DATENEW}>"><{$smarty.const._MD_WFDOWNLOADS_DATE}>&nbsp;<img
                                    src="<{xoModuleIcons16 down.gif}>" alt="&darr;"></span>
                    <{else}>
                        <a class='button_grey' href="viewcat.php?cid=<{$category_cid}>&amp;orderby=dateD" title="<{$smarty.const._MD_WFDOWNLOADS_DATENEW}>"><{$smarty.const._MD_WFDOWNLOADS_DATE}>&nbsp;<img
                                    src="<{xoModuleIcons16 down.gif}>" alt="&darr;"></a>
                    <{/if}>
                    |
                    <{if $use_ratings == true}>
                        <{if ($orderby == "ratingA")}>
                            <span class='button_green' title="<{$smarty.const._MD_WFDOWNLOADS_RATINGLTOH}>"><{$smarty.const._MD_WFDOWNLOADS_RATING}>&nbsp;<img
                                        src="<{xoModuleIcons16 up.gif}>" alt="&uarr;"></span>
                        <{else}>
                            <a class='button_grey' href="viewcat.php?cid=<{$category_cid}>&amp;orderby=ratingA" title="<{$smarty.const._MD_WFDOWNLOADS_RATINGLTOH}>"><{$smarty.const._MD_WFDOWNLOADS_RATING}>
                                &nbsp;<img
                                        src="<{xoModuleIcons16 up.gif}>" alt="&uarr;"></a>
                        <{/if}>
                        <{if ($orderby == "ratingD")}>
                            <span class='button_green' title="<{$smarty.const._MD_WFDOWNLOADS_RATINGHTOL}>"><{$smarty.const._MD_WFDOWNLOADS_RATING}>&nbsp;<img
                                        src="<{xoModuleIcons16 down.gif}>" alt="&darr;"></span>
                        <{else}>
                            <a class='button_grey' href="viewcat.php?cid=<{$category_cid}>&amp;orderby=ratingD" title="<{$smarty.const._MD_WFDOWNLOADS_RATINGHTOL}>"><{$smarty.const._MD_WFDOWNLOADS_RATING}>
                                &nbsp;<img
                                        src="<{xoModuleIcons16 down.gif}>" alt="&darr;"></a>
                        <{/if}>
                        |
                    <{/if}>
                    <{if ($orderby == "hitsA")}>
                        <span class='button_green' title="<{$smarty.const._MD_WFDOWNLOADS_POPULARITYLTOM}>"><{$smarty.const._MD_WFDOWNLOADS_POPULARITY}>&nbsp;<img
                                    src="<{xoModuleIcons16 up.gif}>" alt="&uarr;"></span>
                    <{else}>
                        <a class='button_grey' href="viewcat.php?cid=<{$category_cid}>&amp;orderby=hitsA"
                           title="<{$smarty.const._MD_WFDOWNLOADS_POPULARITYLTOM}>"><{$smarty.const._MD_WFDOWNLOADS_POPULARITY}>&nbsp;<img
                                    src="<{xoModuleIcons16 up.gif}>" alt="&uarr;"></a>
                    <{/if}>
                    <{if ($orderby == "hitsD")}>
                        <span class='button_green' title="<{$smarty.const._MD_WFDOWNLOADS_POPULARITYMTOL}>"><{$smarty.const._MD_WFDOWNLOADS_POPULARITY}>&nbsp;<img
                                    src="<{xoModuleIcons16 down.gif}>" alt="&darr;"></span>
                    <{else}>
                        <a class='button_grey' href="viewcat.php?cid=<{$category_cid}>&amp;orderby=hitsD"
                           title="<{$smarty.const._MD_WFDOWNLOADS_POPULARITYMTOL}>"><{$smarty.const._MD_WFDOWNLOADS_POPULARITY}>&nbsp;<img
                                    src="<{xoModuleIcons16 down.gif}>" alt="&darr;"></a>
                    <{/if}>
                    |
                    <{if ($orderby == "sizeA")}>
                        <span class='button_green' title="<{$smarty.const._MD_WFDOWNLOADS_SIZELTOH}>"><{$smarty.const._MD_WFDOWNLOADS_SIZE}>&nbsp;<img
                                    src="<{xoModuleIcons16 up.gif}>" alt="&uarr;"></span>
                    <{else}>
                        <a class='button_grey' href="viewcat.php?cid=<{$category_cid}>&amp;orderby=sizeA"
                           title="<{$smarty.const._MD_WFDOWNLOADS_SIZELTOH}>"><{$smarty.const._MD_WFDOWNLOADS_SIZE}>&nbsp;<img
                                    src="<{xoModuleIcons16 up.gif}>" alt="&uarr;"></a>
                    <{/if}>
                    <{if ($orderby == "sizeD")}>
                        <span class='button_green' title="<{$smarty.const._MD_WFDOWNLOADS_SIZEHTOL}>"><{$smarty.const._MD_WFDOWNLOADS_SIZE}>&nbsp;<img
                                    src="<{xoModuleIcons16 down.gif}>" alt="&darr;"></span>
                    <{else}>
                        <a class='button_grey' href="viewcat.php?cid=<{$category_cid}>&amp;orderby=sizeD"
                           title="<{$smarty.const._MD_WFDOWNLOADS_SIZEHTOL}>"><{$smarty.const._MD_WFDOWNLOADS_SIZE}>&nbsp;<img
                                    src="<{xoModuleIcons16 down.gif}>" alt="&darr;"></a>
                    <{/if}>
                </div>
            </div>
        <{/if}>
        <br/>
        <{$pagenav}>
        <!-- Start link loop -->
        <{foreach item=download from=$downloads}>
            <div>
                <{include file="db:wfdownloads_download.tpl" download=$download}>
            </div>
            <br/>
        <{/foreach}>
        <!-- End link loop -->
        <div style="clear:both"></div>
        <{$pagenav}>
    </div>
<{/if}>

<{if $category_rssfeed_URL != ""}>
    <a href='<{$category_rssfeed_URL}>' title='<{$smarty.const._MD_WFDOWNLOADS_LEGENDTEXTCATRSS}>'>
        <img src='assets/images/icon/rss.gif' alt='<{$smarty.const._MD_WFDOWNLOADS_LEGENDTEXTCATRSS}>'
             title='<{$smarty.const._MD_WFDOWNLOADS_LEGENDTEXTCATRSS}>'/>
    </a>
<{/if}>

<{include file='db:wfdownloads_footer.tpl'}>
