<{php}>
    /** add JQuery */
    global $xoTheme;
    $xoTheme->addScript("browse.php?Frameworks/jquery/jquery.js");
    // magnific
    $xoTheme->addScript("" . $xoops_url . "/modules/wfdownloads/assets/js/magnific/jquery.magnific-popup.min.js");
    $xoTheme->addStylesheet("" . $xoops_url . "/modules/wfdownloads/assets/js/magnific/magnific-popup.css");
    $this->assign('xoops_module_header', $xoTheme->renderMetas(null, true));
<{/php}>

<{include file='db:wfdownloads_header.tpl'}>

<div>
        <span style="font-weight: bold;"><{$smarty.const._MD_WFDOWNLOADS_TOTALNEWDOWNLOADS}>:</span>
        <br />
        <{$smarty.const._MD_WFDOWNLOADS_LASTWEEK}>: <{$allweekdownloads}>
        &nbsp;|&nbsp;
        <{$smarty.const._MD_WFDOWNLOADS_LAST30DAYS}>: <{$allmonthdownloads}>
        <br />
        <{$smarty.const._MD_WFDOWNLOADS_SHOW}>:&nbsp;
        <a href="<{$xoops_url}>/modules/wfdownloads/newlist.php?newdownloadshowdays=7"><{$smarty.const._MD_WFDOWNLOADS_1WEEK}></a>
        &nbsp;|&nbsp;
        <a href="<{$xoops_url}>/modules/wfdownloads/newlist.php?newdownloadshowdays=14"><{$smarty.const._MD_WFDOWNLOADS_2WEEKS}></a>
        &nbsp;|&nbsp;
        <a href="<{$xoops_url}>/modules/wfdownloads/newlist.php?newdownloadshowdays=30"><{$smarty.const._MD_WFDOWNLOADS_30DAYS}></a>
    <div class="wfdownloads_newlist_totallast">
        <{$smarty.const._MD_WFDOWNLOADS_DTOTALFORLAST}> <{$newdownloadshowdays}> <{$smarty.const._MD_WFDOWNLOADS_DAYS}>
    </div>
</div>
<br />

<!-- Start link loop -->
<{foreach item=download from=$file}>
<{include file='db:wfdownloads_download.tpl'}>
<br />
<{/foreach}>
<!-- End link loop -->

<{include file='db:wfdownloads_footer.tpl'}>

<script type="text/javascript">
    $('.magnific_zoom').magnificPopup({
        type               : 'image',
        image              : {
            cursor     : 'mfp-zoom-out-cur',
            titleSrc   : "title",
            verticalFit: true,
            tError     : '<{$smarty.const._MD_WFDOWNLOADS_MAGNIFIC_image_tError}>' // Error message
        },
        iframe             : {
            patterns: {
                youtube : {
                    index: 'youtube.com/',
                    id   : 'v=',
                    src  : '//www.youtube.com/embed/%id%?autoplay=1'
                }, vimeo: {
                    index: 'vimeo.com/',
                    id   : '/',
                    src  : '//player.vimeo.com/video/%id%?autoplay=1'
                }, gmaps: {
                    index: '//maps.google.',
                    src  : '%id%&output=embed'
                }
            }
        },
        preloader          : true,
        showCloseBtn       : true,
        closeBtnInside     : false,
        closeOnContentClick: true,
        closeOnBgClick     : true,
        enableEscapeKey    : true,
        modal              : false,
        alignTop           : false,
        mainClass          : 'mfp-img-mobile mfp-fade',
        zoom               : {
            enabled : true,
            duration: 300,
            easing  : 'ease-in-out'
        },
        removalDelay       : 200
    });
</script>
