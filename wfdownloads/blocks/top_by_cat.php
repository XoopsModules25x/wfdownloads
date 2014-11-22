<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
/**
 * Wfdownloads module
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         wfdownload
 * @since           3.23
 * @author          Xoops Development Team
 * @version         svn:$id$
 */

/**
 * Function: b_mydownloads_top_by_cat_show
 * Input   : $options[0] = date for the most recent downloads
 *                     hits for the most popular downloads
 *            $block['content'] = The optional above content
 *            $options[1]   = How many downloads are displayes
 * Output  : Returns the most recent or most popular downloads
 */
defined('XOOPS_ROOT_PATH') || die('XOOPS root path not defined');
include_once dirname(__DIR__) . '/include/common.php';
/**
 * @param $options
 *
 * @return array
 */
function wfdownloads_top_by_cat_show($options)
{
    $wfdownloads = WfdownloadsWfdownloads::getInstance();

    $gperm_handler = xoops_gethandler('groupperm');
    $groups = is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getGroups() : array(0 => XOOPS_GROUP_ANONYMOUS);
    $allowedDownCategoriesIds = $gperm_handler->getItemIds('WFDownCatPerm', $groups, $wfdownloads->getModule()->mid());

    $block = array();

    // get downloads
    $criteria = new CriteriaCompo();
    $criteria->add(new Criteria('cid', '(' . implode(',', $allowedDownCategoriesIds) . ')', 'IN'));
    $criteria->add(new Criteria('offline', false));
    $criteria->setSort('date');
    $criteria->setOrder('DESC');
    $criteria->setLimit($options[1]);
    $downloadObjs = $wfdownloads->getHandler('download')->getObjects($criteria);

    foreach ($downloadObjs as $downloadObj) {
        $download = $downloadObj->toArray();
        if (!in_array((int) $download['cid'], $allowedDownCategoriesIds)) {
            continue;
        }
        $download['title'] = xoops_substr($download['title'], 0, ($options[2] - 1));
        $download['id'] = (int) $download['lid'];
        if ($options[0] == 'published') {
            $download['date'] = formatTimestamp($download['published'], $wfdownloads->getConfig('dateformat'));
        } else {
            $download['date'] = formatTimestamp($download['date'], $wfdownloads->getConfig('dateformat'));
        }
        $download['dirname'] = $wfdownloads->getModule()->dirname();
        $block['downloads'][] = $download;
    }

    $categoriesTopParentByCid = $wfdownloads->getHandler('category')->getAllSubcatsTopParentCid();

    foreach ($wfdownloads->getHandler('category')->topCategories as $cid) {
        $block['topcats'][$cid]['title']  = $wfdownloads->getHandler('category')->allCategories[$cid]->getVar('title');
        $block['topcats'][$cid]['cid'] = $cid;
        $block['topcats'][$cid]['imgurl'] = $wfdownloads->getHandler('category')->allCategories[$cid]->getVar('imgurl');
    }

    foreach ($block['downloads'] as $value) {
        $block['topcats'][$categoriesTopParentByCid[$value['cid']]]['downloads'][] = $value;
    }

    return $block;
}

/**
 * @param $options
 *
 * @return string
 */
function wfdownloads_top_by_cat_edit($options)
{
    $form = "" . _MB_WFDOWNLOADS_DISP . "&nbsp;";
    $form .= "<input type='hidden' name='options[]' value='" . ($options[0] == 'published') ? 'published' : 'hits' . "' />";
    $form .= "<input type='text' name='options[]' value='" . $options[1] . "' />&nbsp;" . _MB_WFDOWNLOADS_FILES . "";
    $form .= "<br />";
    $form .= "" . _MB_WFDOWNLOADS_CHARS . "&nbsp;<input type='text' name='options[]' value='" . $options[2] . "' />&nbsp;" . _MB_WFDOWNLOADS_LENGTH . "";

    return $form;
}
