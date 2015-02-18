<div>
    <span style="font-weight: bold;"><a href="singlefile.php?cid=<{$download.cid}>&amp;lid=<{$download.id}>" title="<{$smarty.const._MD_WFDOWNLOADS_VIEWDETAILS}>"><{$download.title}></a></span>&nbsp;<{$download.icons}>
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
</div>

<{if $show_screenshot == true}>
    <div>
        <{if $download.screenshots.0.filename != ''}>
            <div>
                <a href="<{$xoops_url}>/<{$shots_dir}>/<{$download.screenshots.0.filename}>" class="magnific_zoom" rel="<{$download.title}>">
                    <img src="<{$download.screenshots.0.thumb_url}>" alt="<{$download.title}>" title="<{$download.title}>" style='border: 1px solid black'/>
                </a>
            </div>
        <{/if}>
        <{if $download.screenshots.1.filename != '' && $viewcat != true}>
            <div>
                <a href="<{$xoops_url}>/<{$shots_dir}>/<{$download.screenshots.1.filename}>" class="magnific_zoom" rel="<{$download.title}>">
                    <img src="<{$download.screenshots.1.thumb_url}>" alt="<{$download.title}>" title="<{$download.title}>" style='border: 1px solid black'/>
                </a>
            </div>
        <{/if}>
        <{if $download.screenshots.2.filename != '' && $viewcat != true}>
            <div>
                <a href="<{$xoops_url}>/<{$shots_dir}>/<{$download.screenshots.2.filename}>" class="magnific_zoom" rel="<{$download.title}>">
                    <img src="<{$download.screenshots.2.thumb_url}>" alt="<{$download.title}>" title="<{$download.title}>" style='border: 1px solid black'/>
                </a>
            </div>
        <{/if}>
        <{if $download.screenshots.3.filename != '' && $viewcat != true}>
            <div>
                <a href="<{$xoops_url}>/<{$shots_dir}>/<{$download.screenshots.3.filename}>" class="magnific_zoom" rel="<{$download.title}>">
                    <img src="<{$download.screenshots.3.thumb_url}>" alt="<{$download.title}>" title="<{$download.title}>" style='border: 1px solid black'/>
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
