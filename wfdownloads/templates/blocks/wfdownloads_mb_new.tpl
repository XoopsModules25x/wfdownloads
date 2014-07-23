<ul>
<{foreach item=download from=$block.downloads}>
    <li>
        <{$download.date}>
        &nbsp;
        <a href="<{$xoops_url}>/modules/<{$download.dirname}>/singlefile.php?cid=<{$download.cid}>&amp;lid=<{$download.id}>">
            <{$download.title}>
        </a>
    </li>
<{/foreach}>
</ul>
