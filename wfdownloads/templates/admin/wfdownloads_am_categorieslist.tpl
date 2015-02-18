<form action="categories.php" method="post" id="categoriesform">
    <fieldset>
        <legend style='font-weight: bold; color: #900;'><{$smarty.const._AM_WFDOWNLOADS_FCATEGORY_CATEGORIES_LIST}></legend>
        <table class="outer">
            <tr>
                <th><{$smarty.const._AM_WFDOWNLOADS_FCATEGORY_ID}></th>
                <th><{$smarty.const._AM_WFDOWNLOADS_FCATEGORY_TITLE}></th>
                <th><{$smarty.const._AM_WFDOWNLOADS_FCATEGORY_WEIGHT}></th>
                <th><{$smarty.const._AM_WFDOWNLOADS_FCATEGORY_DESCRIPTION}></th>
                <th><{$smarty.const._AM_WFDOWNLOADS_ACTION}></th>
            </tr>
            <{foreach item=sorted_category from=$sorted_categories}>
                <tr class="<{cycle values='even, odd'}>">
                    <td><{$sorted_category.category.cid}></td>
                    <td>
                        <{section name=indent loop=$sorted_category.level-1 step=1}>-<{/section}>
                        <a href='../viewcat.php?cid=<{$sorted_category.category.cid}>'><{$sorted_category.category.title}></a>
                    </td>
                    <td>
                        <label for="new_weights[<{$sorted_category.category.cid}>]">Category:</label>
                        <input type="text" name="new_weights[<{$sorted_category.category.cid}>]" id="new_weights[<{$sorted_category.category.cid}>]" size="11" maxlength="11"
                               value="<{$sorted_category.category.weight}>"/>
                    </td>
                    <td><{$sorted_category.category.description}></td>
                    <td align='center'>
                        <a href="?op=category.edit&amp;cid=<{$sorted_category.category.cid}>" title="<{$smarty.const._EDIT}>"><img src="<{xoModuleIcons16 edit.png}>" title="<{$smarty.const._EDIT}>" alt="<{$smarty.const._EDIT}>"/></a>
                        <a href="?op=category.delete&amp;cid=<{$sorted_category.category.cid}>" title="<{$smarty.const._DELETE}>"><img src="<{xoModuleIcons16 delete.png}>" title="<{$smarty.const._DELETE}>" alt="<{$smarty.const._DELETE}>"/></a>
                        <a href="?op=category.move&amp;cid=<{$sorted_category.category.cid}>" title="<{$smarty.const._AM_WFDOWNLOADS_BMOVE}>"><img src="<{xoModuleIcons16 forward.png}>" title="<{$smarty.const._AM_WFDOWNLOADS_BMOVE}>" alt="<{$smarty.const._AM_WFDOWNLOADS_BMOVE}>"/></a>
                    </td>
                </tr>
            <{/foreach}>
            <tr>
                <td colspan="1">&nbsp;</td>
                <td>
                    <{$token}>
                    <input type="hidden" name="op" value="categories.reorder"/>
                    <input type="submit" name="submit" value="<{$smarty.const._AM_WFDOWNLOADS_BUTTON_CATEGORIES_REORDER}>"/>
                </td>
                <td colspan="2">&nbsp;</td>
            </tr>
        </table>
    </fieldset>
</form>
