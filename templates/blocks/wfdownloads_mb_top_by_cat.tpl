<{foreach item=topcat from=$block.topcats}>
<{if $topcat.downloads}>
    <h3>
        <a href="<{$xoops_url}>/modules/<{$download.dirname}>/viewcat.php?cid=<{$download.cid}>">
            <{$topcat.title}>
        </a>
    </h3>
    <ul>
<{foreach item=download from=$topcat.downloads}>
        <li>
            <a href="<{$xoops_url}>/modules/<{$download.dirname}>/singlefile.php?cid=<{$download.cid}>&amp;lid=<{$download.id}>">
                <{$download.title}>
            </a>
            &nbsp;
            (<{$download.hits}>)
        </li>
<{/foreach}>
    </ul>
<{/if}>
<{/foreach}>
