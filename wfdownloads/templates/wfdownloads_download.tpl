<div>
    <span style="font-weight: bold;"><a href="singlefile.php?cid=<{$download.cid}>&amp;lid=<{$download.id}>" title="<{$smarty.const._MD_WFDOWNLOADS_VIEWDETAILS}>"><{$download.title}></a></span>&nbsp;<{$download.icons}>
    <{if ($download.isadmin == true or $download.issubmitter == true)}>
        <a href="submit.php?op=download.edit&amp;lid=<{$download.id}>"><img src="<{xoModuleIcons16 edit.png}>" title="<{$smarty.const._EDIT}>"
                                                                            alt="<{$smarty.const._EDIT}>"/></a>
    <{/if}>
    <{if ($download.isadmin == true)}>
        <a href="admin/downloads.php?op=download.delete&amp;lid=<{$download.id}>"><img src="<{xoModuleIcons16 delete.png}>"
                                                                                       title="<{$smarty.const._DELETE}>" alt="<{$smarty.const._DELETE}>"/></a>
    <{/if}>
</div>

<{if $show_screenshot == true}>
    <div>
        <{if $download.screenshot_full != ''}>
            <div>
                <a href="<{$xoops_url}>/<{$shots_dir}>/<{$download.screenshot_full}>" class="magnific_zoom" rel="<{$download.title}>">
                    <img src="<{$download.screenshot_thumb}>" alt="<{$download.title}>" title="<{$download.title}>" style='border: 1px solid black'/>
                </a>
            </div>
        <{/if}>
        <{if $download.screenshot_full2 != '' && $viewcat != true}>
            <div>
                <a href="<{$xoops_url}>/<{$shots_dir}>/<{$download.screenshot_full2}>" class="magnific_zoom" rel="<{$download.title}>">
                    <img src="<{$download.screenshot_thumb2}>" alt="<{$download.title}>" title="<{$download.title}>" style='border: 1px solid black'/>
                </a>
            </div>
        <{/if}>
        <{if $download.screenshot_full3 != '' && $viewcat != true}>
            <div>
                <a href="<{$xoops_url}>/<{$shots_dir}>/<{$download.screenshot_full3}>" class="magnific_zoom" rel="<{$download.title}>">
                    <img src="<{$download.screenshot_thumb3}>" alt="<{$download.title}>" title="<{$download.title}>" style='border: 1px solid black'/>
                </a>
            </div>
        <{/if}>
        <{if $download.screenshot_full4 != '' && $viewcat != true}>
            <div>
                <a href="<{$xoops_url}>/<{$shots_dir}>/<{$download.screenshot_full4}>" class="magnific_zoom" rel="<{$download.title}>">
                    <img src="<{$download.screenshot_thumb4}>" alt="<{$download.title}>" title="<{$download.title}>" style='border: 1px solid black'/>
                </a>
            </div>
        <{/if}>
    </div>
<{/if}>

<div title="<{$smarty.const._MD_WFDOWNLOADS_SUMMARY}>">
    <{$download.summary}>
</div>
<div>
    <a href="singlefile.php?cid=<{$download.cid}>&amp;lid=<{$download.id}>"><{$smarty.const._MD_WFDOWNLOADS_VIEWDETAILS}></a>
</div>

<div>
    <a href="visit.php?cid=<{$download.cid}>&amp;lid=<{$download.id}>">
        <img src="<{xoModuleIcons16 download.png}>" alt="<{$smarty.const._MD_WFDOWNLOADS_DOWNLOADNOW}>"
             title="<{$smarty.const._MD_WFDOWNLOADS_DOWNLOADNOW}>"/>
        <{$smarty.const._MD_WFDOWNLOADS_DOWNLOADNOW}>
    </a>&nbsp;&nbsp;
    <{if $download.use_mirrors == 1 && $download.mirrors_num >= 1}>
        <a href="mirror.php?op=list&amp;cid=<{$download.cid}>&amp;lid=<{$download.id}>">
            <img src="<{xoModuleIcons16 download.png}>" alt="<{$smarty.const._MD_WFDOWNLOADS_DOWNLOADMIRRORS}>"
                 title="<{$smarty.const._MD_WFDOWNLOADS_DOWNLOADMIRRORS}>"/>
            &nbsp;
            <{$smarty.const._MD_WFDOWNLOADS_DOWNLOADMIRRORS}>
        </a>
    <{/if}>
    <{if $download.use_mirrors != 1 && $download.mirror != ''}>
        <img src="<{xoModuleIcons16 download.png}>" alt="<{$smarty.const._MD_WFDOWNLOADS_DOWNLOADMIRRORS}>"
             title="<{$smarty.const._MD_WFDOWNLOADS_DOWNLOADMIRRORS}>"/>
        <{$download.mirror}>
    <{/if}>
</div>

<div>
    <span style="font-size: small;">
        <{$smarty.const._MD_WFDOWNLOADS_SUBMITTER}>:&nbsp;<{$download.submitter}>
        <br/>
        <{$lang_subdate}>:&nbsp;<{$download.updated}>
    </span>
</div>
