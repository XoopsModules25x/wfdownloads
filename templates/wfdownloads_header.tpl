<!-- Thank you for keeping this line in the template :-) //-->
<{*<div style="display: none;"><{$ref_smartfactory}></div>*}>
<!-- Thank you for keeping this line in the template :-) //-->


<{$wfdownloads_breadcrumb}>



<{if $catarray.imageheader|default:'' != ""}>
<br>
<div class="wfdownloads_head_catimageheader"><{$catarray.imageheader}></div>
<br>
<{/if}>

<{if $down.imageheader|default:'' != ''}>
<br>
<div class="wfdownloads_head_downimageheader"><{$down.imageheader}></div>
<br>
<{/if}>

<{if $imageheader|default:'' != ""}>
<br>
<div class="wfdownloads_head_imageheader"><{$imageheader}></div>
<br>
<{/if}>

<{if $catarray.indexheader|default:''}>
<div class="wfdownloads_head_catindexheader" align="<{$catarray.indexheaderalign}>"><p><{$catarray.indexheader}></p></div>
<br>
<{/if}>
<{if $catarray.letters|default:''}>
<div class="wfdownloads_head_catletters" align="center"><{$catarray.letters}></div>
<br>
<{/if}>
<{if $catarray.toolbar|default:null}>
<div class="wfdownloads_head_cattoolbar" align="center"><{$catarray.toolbar}></div>
<br>
<{/if}>
