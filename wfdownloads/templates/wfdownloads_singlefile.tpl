<{php}>
    /** add JQuery */
    global $xoTheme;
    $xoTheme->addScript("browse.php?Frameworks/jquery/jquery.js");
    $xoTheme->addScript("" . $xoops_url . "/modules/wfdownloads/assets/js/mediaelement/build/mediaelement-and-player.min.js");
    $xoTheme->addStylesheet("" . $xoops_url . "/modules/wfdownloads/assets/js/mediaelement/build/mediaelementplayer.min.css");
    $this->assign('xoops_module_header', $xoTheme->renderMetas(null, true));
<{/php}>

<{include file='db:wfdownloads_header.tpl'}>

<h1><{$category_title}></h1>
<img src="<{$category_image}>" alt="<{$category_title}>" title="<{$category_title}>"/>

<div>
    <h3>
        <{$download.title}>&nbsp;<{$download.icons}>
        <{if ($download.isadmin == true) }>
            <a href="admin/downloads.php?op=download.edit&amp;lid=<{$download.id}>">
                <img src="<{xoModuleIcons16 edit.png}>"
                    title="<{$smarty.const._EDIT}>"
                    alt="<{$smarty.const._EDIT}>"/>
            </a>
            <a href="admin/downloads.php?op=download.delete&amp;lid=<{$download.id}>">
                <img src="<{xoModuleIcons16 delete.png}>"
                    title="<{$smarty.const._DELETE}>"
                    alt="<{$smarty.const._DELETE}>"/>
            </a>
        <{elseif ($download.issubmitter == true && $download.has_custom_fields == false)}>
            <a href="submit.php?op=download.edit&amp;lid=<{$download.id}>">
                <img src="<{xoModuleIcons16 edit.png}>"
                    title="<{$smarty.const._EDIT}>"
                    alt="<{$smarty.const._EDIT}>"/>
            </a>
        <{/if}>
    </h3>

    <div style="float:right; width:35%">
        <span style="font-size: small;">
            <div style="margin-left: 10px; margin-right: 10px; padding: 4px; background-color:#e6e6e6; border-color:#999999;" class="outer">
                <div><span style="font-weight: bold;"><{$smarty.const._MD_WFDOWNLOADS_SUBMITTER}>:</span>&nbsp;<{$download.submitter}></div>
                <{if $download.publisher != ''}>
                    <div><span style="font-weight: bold;"><{$smarty.const._MD_WFDOWNLOADS_PUBLISHER}>:</span>&nbsp;<{$download.publisher}></div>
                <{/if}>
                <div><span style="font-weight: bold;"><{$lang_subdate}>:</span>&nbsp;<{$download.updated}></div>
                <br>
                <{if $download.version != 0}>
                    <div><span style="font-weight: bold;"><{$smarty.const._MD_WFDOWNLOADS_VERSION}>:</span>&nbsp;<{$download.version}></div>
                <{/if}>
                <div><span style="font-weight: bold;"><{$smarty.const._MD_WFDOWNLOADS_VERSIONTYPES}>:</span>&nbsp;<{$download.versiontypes}></div>
                <div><span style="font-weight: bold;"><{$smarty.const._MD_WFDOWNLOADS_DOWNLOADHITS}>:</span>&nbsp;<{$download.hits}></div>
                <br>

                <div><span style="font-weight: bold;"><{$smarty.const._MD_WFDOWNLOADS_FILESIZE}>:</span>&nbsp;<{$download.size}></div>
                <div><span style="font-weight: bold;"><{$smarty.const._MD_WFDOWNLOADS_UPLOAD_FILETYPE}>:</span>&nbsp;<{$download.filetype}></div>
                <span style="font-weight: bold;"><{$smarty.const._MD_WFDOWNLOADS_DOWNTIMES}></span>

                <div style="margin-left: 4px;">
                    <{$download.downtime}>
                </div>
                <{if $download.homepage != ''}>
                    <div><span style="font-weight: bold;"><{$smarty.const._MD_WFDOWNLOADS_HOMEPAGE}>:</span>&nbsp;<{$download.homepage}></div>
                <{/if}>
            </div>

            <br>

            <{if @in_array($download.filetype, array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/x-png','image/jpeg'))}>
                <div style="margin-left: 10px; margin-right: 10px; padding: 4px; background-color:#e6e6e6; border-color:#999999;" class="outer">
                    <span style="font-weight: bold;"><{$smarty.const._MD_WFDOWNLOADS_PREVIEW}></span>
                    <br>
                    <img style="width:100%; height:auto;" src="<{$file_url}>"/>
                </div>
                <br>

                                <{elseif @in_array($download.filetype, array('audio/mpeg', 'audio/mp3', 'audio/ogg'))}>

                <div style="margin-left: 10px; margin-right: 10px; padding: 4px; background-color:#e6e6e6; border-color:#999999;" class="outer">
                    <span style="font-weight: bold;"><{$smarty.const._MD_WFDOWNLOADS_PREVIEW}></span>
                    <audio id="preview-player" src="<{$file_url}>" type="<{$download.filetype}>" controls="controls" style="width:100%">
                    </audio>
                    <script>
                        $('audio#preview-player').mediaelementplayer();
                    </script>
                </div>
                <br>

                                <{elseif @in_array($download.filetype, array('application/pdf'))}>

                <div style="margin-left: 10px; margin-right: 10px; padding: 4px; background-color:#e6e6e6; border-color:#999999;" class="outer">
                    <span style="font-weight: bold;"><{$smarty.const._MD_WFDOWNLOADS_PREVIEW}></span>
                    <br>
                    <iframe style="width:100%; height: auto;" src="<{$file_url}>">
                        <p>Your browser does not support iframes.</p>
                    </iframe>
                </div>
                <br>

                                <{elseif @in_array($download.filetype, array('video/flv', 'application/octet-stream', 'video/x-flv', 'video/mp4', 'video/ogg', 'video/x-m4v'))}>

                <div style="margin-left: 10px; margin-right: 10px; padding: 4px; background-color:#e6e6e6; border-color:#999999;" class="outer">
                    <span style="font-weight: bold;"><{$smarty.const._MD_WFDOWNLOADS_PREVIEW}></span>
                    <video id="preview-player" src="<{$file_url}>" type="<{$download.filetype}>" controls="controls" style="width:100%;height:160px;">
                    </video>
                    <script>
                        $('video#preview-player').mediaelementplayer({
                            // if the <video width> is not specified, this is the default
                            defaultVideoWidth : 240,
                            // if the <video height> is not specified, this is the default
                            defaultVideoHeight: 135,
                            // enables Flash and Silverlight to resize to content size
                            enableAutosize    : false
                        });
                    </script>
                    <div style="clear:both"></div>
                </div>
                <br>
            <{/if}>

            <{if $download.use_ratings == 1}>
                <div style="margin-left: 10px; margin-right: 10px; padding: 4px; background-color:#e6e6e6; border-color:#999999;" class="outer">
                    <div>
                        <span style="font-weight: bold;"><{$smarty.const._MD_WFDOWNLOADS_RATINGC}></span>&nbsp;<img src="assets/images/icon/<{$download.rateimg}>"
                                                                                                                    alt="<{$download.average_rating|string_format:'%.2f'}>"
                                                                                                                    title="<{$download.average_rating|string_format:'%.2f'}>"
                                                                                                                    align="middle"/>&nbsp;(<{$download.votes}>)
                    </div>
                </div>
                <br>
            <{/if}>
            <{if $download.use_reviews == 1}>
                <div style="margin-left: 10px; margin-right: 10px; padding: 4px; background-color:#e6e6e6; border-color:#999999;" class="outer">
                    <div>
                        <span style="font-weight: bold;"><{$smarty.const._MD_WFDOWNLOADS_REVIEWS}></span>&nbsp;<img src="assets/images/icon/<{$download.review_rateimg}>"
                                                                                                                    alt="<{$download.review_average_rating|string_format:'%.2f'}>"
                                                                                                                    title="<{$download.review_average_rating|string_format:'%.2f'}>"/>&nbsp;(<{$download.reviews_num}>
                        )
                    </div>
                </div>
                <br>
            <{/if}>
            <{if $download.use_mirrors == 1}>
                <div style="margin-left: 10px; margin-right: 10px; padding: 4px; background-color:#e6e6e6; border-color:#999999;" class="outer">
                    <div><span style="font-weight: bold;"><{$smarty.const._MD_WFDOWNLOADS_MIRROR_AVAILABLE}></span>&nbsp;<{$download.mirrors_num}></div>
                </div>
                <br>
            <{/if}>
            <{if !$custom_form}>
                <div style="margin-left: 10px; margin-right: 10px; padding: 4px; background-color:#e6e6e6; border-color:#999999;" class="outer">
                    <span style="font-weight: bold;"><{$smarty.const._MD_WFDOWNLOADS_PRICE}>:</span>&nbsp;<{$download.price}>
                    <br>
                    <span style="font-weight: bold;"><{$smarty.const._MD_WFDOWNLOADS_SUPPORTEDPLAT}>:</span>&nbsp;<{$download.platform}>
                    <br>
                    <span style="font-weight: bold;"><{$smarty.const._MD_WFDOWNLOADS_DOWNLICENSE}>:</span>&nbsp;<{$download.license}>
                    <br>
                    <span style="font-weight: bold;"><{$smarty.const._MD_WFDOWNLOADS_LIMITS}>:</span>&nbsp;<{$download.limitations}>
                </div>
            <{/if}>
        </span>
    </div>

    <div>
        <div>
            <a href="visit.php?cid=<{$download.cid}>&amp;lid=<{$download.id}>">
                <img src="<{xoModuleIcons16 download.png}>" alt="<{$smarty.const._MD_WFDOWNLOADS_DOWNLOADNOW}>"
                     title="<{$smarty.const._MD_WFDOWNLOADS_DOWNLOADNOW}>"/>
                &nbsp;
                <{$smarty.const._MD_WFDOWNLOADS_DOWNLOADNOW}>
            </a>
            &nbsp;&nbsp;
            <{if $download.use_mirrors == true && $download.mirrors_num >= 1}>
                <a href="mirror.php?op=mirrors.list&amp;cid=<{$download.cid}>&amp;lid=<{$download.id}>">
                    <img src="<{xoModuleIcons16 download.png}>" alt="<{$smarty.const._MD_WFDOWNLOADS_DOWNLOADMIRRORS}>"
                         title="<{$smarty.const._MD_WFDOWNLOADS_DOWNLOADMIRRORS}>"/>
                    &nbsp;
                    <{$smarty.const._MD_WFDOWNLOADS_DOWNLOADMIRRORS}>
                </a>
            <{/if}>
            <{if $download.use_mirrors != true && $download.mirror != ''}>
                <img src="<{xoModuleIcons16 download.png}>" alt="<{$download.mirror}>" title="<{$download.mirror}>"/>
                &nbsp;
                <{$download.mirror}>
            <{/if}>
        </div>
        <{if $download.forumid > 0}>
            <a href="<{$xoops_url}>/modules/newbb/viewforum.php?forum=<{$download.forumid}>">
                <img src="assets/images/icon/forum.gif" alt="<{$smarty.const._MD_WFDOWNLOADS_INFORUM}>" title="<{$smarty.const._MD_WFDOWNLOADS_INFORUM}>"/>
                &nbsp;
                <{$smarty.const._MD_WFDOWNLOADS_INFORUM}>
            </a>
        <{/if}>
    </div>

    <div title="<{$smarty.const._MD_WFDOWNLOADS_DESCRIPTION}>">
        <{$download.description}>
    </div>

    <br>

    <{if $show_screenshot == true}>
    <{foreach key=key item=screenshot from=$download.screenshots}>
    <{if $screenshot.filename}>
        <div style="margin-left: 10px; margin-right: 10px; padding: 4px;">
            <span style="font-weight: bold;"><{$key+1}></span>
            <div><a href="<{$xoops_url}>/<{$shots_dir}>/<{$screenshot.filename}>" class="magnific_zoom" rel="<{$download.title}>">
                    <img src="<{$screenshot.thumb_url}>" alt="<{$smarty.const._MD_WFDOWNLOADS_SCREENSHOTCLICK}>"
                         title="<{$smarty.const._MD_WFDOWNLOADS_SCREENSHOTCLICK}>" style="border: 1px solid black"/></a>
            </div>
            <div>
                <a href="<{$screenshot.thumb_url}>" rel="external"><{$lang_screenshot_click}></a>
            </div>
        </div>
    <{/if}>
    <{/foreach}>
        <br>
    <{/if}>

    <{if $download.features != ''}>
        <div style="font-weight: bold;"><{$smarty.const._MD_WFDOWNLOADS_FEATURES}></div>
        <div>
            <ul>
                <{foreach item=features from=$download.features}>
                    <li><{$features}></li>
                <{/foreach}>
            </ul>
        </div>
        <br>
    <{/if}>
    <{if $download.requirements != ''}>
        <div style="font-weight: bold;"><{$smarty.const._MD_WFDOWNLOADS_REQUIREMENTS}></div>
        <div>
            <ul>
                <{foreach item=requirements from=$download.requirements}>
                    <li><{$requirements}></li>
                <{/foreach}>
            </ul>
        </div>
        <br>
    <{/if}>
    <{if $download.history != ''}>
        <div>
            <span style="font-weight: bold;"><{$smarty.const._MD_WFDOWNLOADS_HISTORY}></span>
            <br>
            <{$download.history}>
        </div>
        <br>
    <{/if}>

    <div style="clear:both"></div>
</div>

<{* Formulize module support (2006/05/04) jpc - start *}>
<{if $custom_form}>
<{foreach item=custom_field from=$custom_fields}>
    <div style="margin-left: 0; margin-right: 10px; padding: 4px; background-color:#e6e6e6; border-color:#999999;" class="outer">
    <span style="font-weight: bold;"><{$custom_field.caption}></span>:
    <br>
    <{foreach item=value from=$custom_field.values name=valueloop}>
        <{$value}>
        <{if !$smarty.foreach.valueloop.last}>
            <br>
        <{/if}>
    <{/foreach}>
    </div>
    <br>
<{/foreach}>
<{else}>
    <!-- no custom form -->
<{/if}>
<{* Formulize module support (2006/05/04) jpc - end *}>

<div>
    <span style="font-size: small;">
    <{if $download.use_mirrors == 1 && $download.add_mirror == 1}>
        <a href="mirror.php?cid=<{$download.cid}>&amp;lid=<{$download.id}>"><{$smarty.const._MD_WFDOWNLOADS_ADDMIRROR}></a>
        &nbsp;|&nbsp;
    <{/if}>
    <{if $download.use_reviews == 1}>
        <a href="review.php?cid=<{$download.cid}>&amp;lid=<{$download.id}>"><{$smarty.const._MD_WFDOWNLOADS_REVIEWTHISFILE}></a>
        &nbsp;|&nbsp;
    <{/if}>
    <{if $download.use_ratings == 1}>
        <a href="ratefile.php?cid=<{$download.cid}>&amp;lid=<{$download.id}>"><{$smarty.const._MD_WFDOWNLOADS_RATETHISFILE}></a>
        &nbsp;|&nbsp;
    <{/if}>
    <{if $download.useradminlink == true}>
        <a href="submit.php?cid=<{$download.cid}>&amp;lid=<{$download.id}>"><{$smarty.const._MD_WFDOWNLOADS_MODIFY}></a>
        &nbsp;|&nbsp;
    <{/if}>
    <{if $download.use_brokenreports == 1}>
        <a href="brokenfile.php?cid=<{$download.cid}>&amp;lid=<{$download.id}>"><{$smarty.const._MD_WFDOWNLOADS_REPORTBROKEN}></a>
        &nbsp;|&nbsp;
    <{/if}>
    <a target="_top" href="mailto:?subject=<{$download.mail_subject}>&amp;body=<{$download.mail_body}>"><{$smarty.const._MD_WFDOWNLOADS_TELLAFRIEND}></a>
    <{if $com_rule <> 0}>
        &nbsp;|&nbsp;
        <a href="#comments"><{$smarty.const._COMMENTS}> (<{$download.comments}>)</a>
    <{/if}>
    </span>
</div>


<br>


<{if $download.use_reviews == 1}>
    <div style="font-weight: bold;"><{$smarty.const._MD_WFDOWNLOADS_USERREVIEWSTITLE}></div>
    <div style="padding: 3px; margin:3px;">
    <{if ($review_amount > 0)}>
        <a href="review.php?op=reviews.list&amp;cid=<{$download.cid}>&amp;lid=<{$download.id}>"><{$smarty.const._MD_WFDOWNLOADS_USERREVIEWS|replace:'%s':$download.title}></a>
    <{else}>
        <a href="review.php?op=review.add&amp;cid=<{$download.cid}>&amp;lid=<{$download.id}>"><{$smarty.const._MD_WFDOWNLOADS_NOUSERREVIEWS|replace:'%s':$download.title}></a>
    <{/if}>
    </div>
    <br>
<{/if}>


<{if $download.use_mirrors == 1 && $download.mirrors_num >= 1}>
    <div style="font-weight: bold;"><{$smarty.const._MD_WFDOWNLOADS_USERMIRRORSTITLE}></div>
    <div style="padding: 3px; margin:3px;">
    <{if ($mirror_amount > 0)}>
        <a href="<{$xoops_url}>/modules/wfdownloads/mirror.php?op=mirrors.list&amp;cid=<{$download.cid}>&amp;lid=<{$download.id}>"><{$smarty.const._MD_WFDOWNLOADS_USERMIRRORS|replace:'%s':$download.title}></a>
    <{else}>
        <a href="<{$xoops_url}>/modules/wfdownloads/mirror.php?op=mirror.add&amp;cid=<{$download.cid}>&amp;lid=<{$download.id}>"><{$smarty.const._MD_WFDOWNLOADS_NOUSERMIRRORS|replace:'%s':$download.title}></a>
    <{/if}>
    </div>
    <br>
<{/if}>


<div>
    <span style="font-weight: bold;"><{$smarty.const._MD_WFDOWNLOADS_OTHERBYUID}> <{$download.submitter}></span>
    <ul>
    <{foreach item=download_by_user from=$downloads_by_user}>
        <li>
            <a href="<{$xoops_url}>/modules/wfdownloads/singlefile.php?cid=<{$download_by_user.cid}>&amp;lid=<{$download_by_user.lid}>">
                <{$download_by_user.title}>
            </a>
            (<{$download_by_user.published}>)
        </li>
    <{/foreach}>
    </ul>
</div>

<br>

<div><{$lang_copyright}></div>
<br>

<{include file='db:wfdownloads_footer.tpl'}>
