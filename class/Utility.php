<?php

namespace XoopsModules\Wfdownloads;

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * WfdownloadsUtil Class
 *
 * @copyright   XOOPS Project (https://xoops.org)
 * @license     http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author      XOOPS Development Team
 * @package     Wfdownloads
 * @since       1.03
 */

use Xmf\Request;
use XoopsModules\Wfdownloads;
use XoopsModules\Wfdownloads\Common;
use XoopsModules\Wfdownloads\Constants;

/**
 * Class Utility
 */
class Utility extends Common\SysUtility
{
    //--------------- Custom module methods -----------------------------
    /**
     * @param     $categoryObj
     * @param int $level
     */
    public static function displayCategory(Wfdownloads\Category $categoryObj, $level = 0)
    {
        /** @var \XoopsModules\Wfdownloads\Helper $helper */
        $helper = Helper::getInstance();

        $description = $categoryObj->description();
        if (!XOOPS_USE_MULTIBYTES) {
            if (mb_strlen($description) >= 100) {
                $description = mb_substr($description, 0, 100 - 1) . '...';
            }
        }
        $modify = "<a href='category.php?op=mod&amp;categoryid=" . $categoryObj->categoryid() . '&amp;parentid=' . $categoryObj->parentid() . "'><img src='" . PUBLISHER_URL . "/assets/images/links/edit.gif' title='" . \_AM_PUBLISHER_EDITCOL . "' alt='" . \_AM_PUBLISHER_EDITCOL . "'></a>";
        $delete = "<a href='category.php?op=del&amp;categoryid=" . $categoryObj->categoryid() . "'><img src='" . PUBLISHER_URL . "/assets/images/links/delete.png' title='" . \_AM_PUBLISHER_DELETECOL . "' alt='" . \_AM_PUBLISHER_DELETECOL . "'></a>";

        $spaces = '';
        for ($j = 0; $j < $level; ++$j) {
            $spaces .= '&nbsp;&nbsp;&nbsp;';
        }

        echo '<tr>';
        echo "<td class='even' align='center'>" . $categoryObj->categoryid() . '</td>';
        echo "<td class='even' align='left'>" . $spaces . "<a href='" . PUBLISHER_URL . '/category.php?categoryid=' . $categoryObj->categoryid() . "'><img src='" . PUBLISHER_URL . "/assets/images/links/subcat.gif' alt=''>&nbsp;" . $categoryObj->name() . '</a></td>';
        echo "<td class='even' align='center'>" . $categoryObj->weight() . '</td>';
        echo "<td class='even' align='center'> $modify $delete </td>";
        echo '</tr>';
        $subCategoriesObj = $helper->getHandler('Category')->getCategories(0, 0, $categoryObj->categoryid());
        if (\count($subCategoriesObj) > 0) {
            ++$level;
            foreach ($subCategoriesObj as $key => $thiscat) {
                self::displayCategory($thiscat, $level);
            }
            unset($key, $thiscat);
        }
        //        unset($categoryObj);
    }

    /**
     * @param bool $showmenu
     * @param int  $categoryId
     * @param int  $nbSubCats
     * @param null $categoryObj
     */
    public static function editCategory($showmenu = false, $categoryId = 0, $nbSubCats = 4, $categoryObj = null)
    {
        /** @var \XoopsModules\Wfdownloads\Helper $helper */
        $helper = Helper::getInstance();

        // if there is a parameter, and the id exists, retrieve data: we're editing a category
        if (0 != $categoryId) {
            // Creating the category object for the selected category
            $categoryObj = $helper->getHandler('Category')->get($categoryId);
            if ($categoryObj->notLoaded()) {
                \redirect_header('category.php', 1, \_AM_PUBLISHER_NOCOLTOEDIT);
                //            exit();
            }
        } else {
            if (!$categoryObj) {
                $categoryObj = $helper->getHandler('Category')->create();
            }
        }

        if (0 != $categoryId) {
            echo "<br>\n";
            publisherOpenCollapsableBar('edittable', 'edittableicon', \_AM_PUBLISHER_EDITCOL, \_AM_PUBLISHER_CATEGORY_EDIT_INFO);
        } else {
            publisherOpenCollapsableBar('createtable', 'createtableicon', \_AM_PUBLISHER_CATEGORY_CREATE, \_AM_PUBLISHER_CATEGORY_CREATE_INFO);
        }

        $sform = $categoryObj->getForm($nbSubCats);
        $sform->display();

        if (!$categoryId) {
            publisherCloseCollapsableBar('createtable', 'createtableicon');
        } else {
            publisherCloseCollapsableBar('edittable', 'edittableicon');
        }

        //Added by fx2024
        if ($categoryId) {
            $selCat = $categoryId;

            publisherOpenCollapsableBar('subcatstable', 'subcatsicon', \_AM_PUBLISHER_SUBCAT_CAT, \_AM_PUBLISHER_SUBCAT_CAT_DSC);
            // Get the total number of sub-categories
            $categoriesObj = $helper->getHandler('Category')->get($selCat);
            $totalsubs     = $helper->getHandler('Category')->getCategoriesCount($selCat);
            // creating the categories objects that are published
            $subcatsObj    = $helper->getHandler('Category')->getCategories(0, 0, $categoriesObj->categoryid());
            $totalSCOnPage = \count($subcatsObj);
            echo "<table width='100%' cellspacing=1 cellpadding=3 border=0 class = outer>";
            echo '<tr>';
            echo "<td width='60' class='bg3' align='left'><strong>" . \_AM_PUBLISHER_CATID . '</strong></td>';
            echo "<td width='20%' class='bg3' align='left'><strong>" . \_AM_PUBLISHER_CATCOLNAME . '</strong></td>';
            echo "<td class='bg3' align='left'><strong>" . \_AM_PUBLISHER_SUBDESCRIPT . '</strong></td>';
            echo "<td width='60' class='bg3' align='right'><strong>" . \_AM_PUBLISHER_ACTION . '</strong></td>';
            echo '</tr>';
            if ($totalsubs > 0) {
                foreach ($subcatsObj as $subcat) {
                    $modify = "<a href='category.php?op=mod&amp;categoryid=" . $subcat->categoryid() . "'><img src='" . XOOPS_URL . '/modules/' . $helper->getModule()->dirname() . "/assets/images/links/edit.gif' title='" . \_AM_PUBLISHER_MODIFY . "' alt='" . \_AM_PUBLISHER_MODIFY . "'></a>";
                    $delete = "<a href='category.php?op=del&amp;categoryid=" . $subcat->categoryid() . "'><img src='" . XOOPS_URL . '/modules/' . $helper->getModule()->dirname() . "/assets/images/links/delete.png' title='" . \_AM_PUBLISHER_DELETE . "' alt='" . \_AM_PUBLISHER_DELETE . "'></a>";
                    echo '<tr>';
                    echo "<td class='head' align='left'>" . $subcat->categoryid() . '</td>';
                    echo "<td class='even' align='left'><a href='" . XOOPS_URL . '/modules/' . $helper->getModule()->dirname() . '/category.php?categoryid=' . $subcat->categoryid() . '&amp;parentid=' . $subcat->parentid() . "'>" . $subcat->name() . '</a></td>';
                    echo "<td class='even' align='left'>" . $subcat->description() . '</td>';
                    echo "<td class='even' align='right'> {$modify} {$delete} </td>";
                    echo '</tr>';
                }
                //                unset($subcat);
            } else {
                echo '<tr>';
                echo "<td class='head' align='center' colspan= '7'>" . \_AM_PUBLISHER_NOSUBCAT . '</td>';
                echo '</tr>';
            }
            echo "</table>\n";
            echo "<br>\n";
            publisherCloseCollapsableBar('subcatstable', 'subcatsicon');

            publisherOpenCollapsableBar('bottomtable', 'bottomtableicon', \_AM_PUBLISHER_CAT_ITEMS, \_AM_PUBLISHER_CAT_ITEMS_DSC);
            $startitem = Request::getInt('startitem');
            // Get the total number of published ITEMS
            $totalitems = $helper->getHandler('Item')->getItemsCount($selCat, [Wfdownloads\Constants::PUBLISHER_STATUS_PUBLISHED]);
            // creating the items objects that are published
            $itemsObj         = $helper->getHandler('Item')->getAllPublished($helper->getConfig('idxcat_perpage'), $startitem, $selCat);
            $totalitemsOnPage = \count($itemsObj);
            $allcats          = $helper->getHandler('Category')->getObjects(null, true);
            echo "<table width='100%' cellspacing=1 cellpadding=3 border=0 class = outer>";
            echo '<tr>';
            echo "<td width='40' class='bg3' align='center'><strong>" . \_AM_PUBLISHER_ITEMID . '</strong></td>';
            echo "<td width='20%' class='bg3' align='left'><strong>" . \_AM_PUBLISHER_ITEMCOLNAME . '</strong></td>';
            echo "<td class='bg3' align='left'><strong>" . \_AM_PUBLISHER_ITEMDESC . '</strong></td>';
            echo "<td width='90' class='bg3' align='center'><strong>" . \_AM_PUBLISHER_CREATED . '</strong></td>';
            echo "<td width='60' class='bg3' align='center'><strong>" . \_AM_PUBLISHER_ACTION . '</strong></td>';
            echo '</tr>';
            if ($totalitems > 0) {
                for ($i = 0; $i < $totalitemsOnPage; ++$i) {
                    $categoryObj = $allcats[$itemsObj[$i]->categoryid()];
                    $modify      = "<a href='item.php?op=mod&amp;itemid=" . $itemsObj[$i]->itemid() . "'><img src='" . XOOPS_URL . '/modules/' . $helper->getModule()->dirname() . "/assets/images/links/edit.gif' title='" . \_AM_PUBLISHER_EDITITEM . "' alt='" . \_AM_PUBLISHER_EDITITEM . "'></a>";
                    $delete      = "<a href='item.php?op=del&amp;itemid="
                                   . $itemsObj[$i]->itemid()
                                   . "'><img src='"
                                   . XOOPS_URL
                                   . '/modules/'
                                   . $helper->getModule()->dirname()
                                   . "/assets/images/links/delete.png' title='"
                                   . \_AM_PUBLISHER_DELETEITEM
                                   . "' alt='"
                                   . \_AM_PUBLISHER_DELETEITEM
                                   . "'></a>";
                    echo '<tr>';
                    echo "<td class='head' align='center'>" . $itemsObj[$i]->itemid() . '</td>';
                    echo "<td class='even' align='left'>" . $categoryObj->name() . '</td>';
                    echo "<td class='even' align='left'>" . $itemsObj[$i]->getitemLink() . '</td>';
                    echo "<td class='even' align='center'>" . $itemsObj[$i]->getDatesub('s') . '</td>';
                    echo "<td class='even' align='center'> $modify $delete </td>";
                    echo '</tr>';
                }
            } else {
                $itemid = -1;
                echo '<tr>';
                echo "<td class='head' align='center' colspan= '7'>" . \_AM_PUBLISHER_NOITEMS . '</td>';
                echo '</tr>';
            }
            echo "</table>\n";
            echo "<br>\n";
            $parentid         = Request::getInt('parentid', 0, 'GET');
            $pagenavExtraArgs = "op=mod&categoryid=$selCat&parentid=$parentid";
            \xoops_load('XoopsPageNav');
            $pagenav = new \XoopsPageNav($totalitems, $helper->getConfig('idxcat_perpage'), $startitem, 'startitem', $pagenavExtraArgs);
            echo '<div style="text-align:right;">' . $pagenav->renderNav() . '</div>';
            echo "<input type='button' name='button' onclick=\"location='item.php?op=mod&categoryid=" . $selCat . "'\" value='" . \_AM_PUBLISHER_CREATEITEM . "'>&nbsp;&nbsp;";
            echo '</div>';
        }
        //end of fx2024 code
    }

    // ====================== START ===================================

    /**
     * Standard functions
     *
     * @param mixed $bytes
     * @param mixed $precision
     */

    /**
     * This function transforms a numerical size (like 2048) to a letteral size (like 2MB)
     *
     * @param int $bytes numerical size
     * @param int $precision
     *
     * @return string letteral size
     **/
    public static function bytesToSize1000($bytes, $precision = 2)
    {
        // human readable format -- powers of 1000
        $unit = ['b', 'kb', 'mb', 'gb', 'tb', 'pb', 'eb'];

        return @\round($bytes / \pow(1000, $i = \floor(\log($bytes, 1000))), $precision) . ' ' . $unit[(int)$i];
    }

    /**
     * @param     $bytes
     * @param int $precision
     *
     * @return string
     */
    public static function bytesToSize1024($bytes, $precision = 2)
    {
        // human readable format -- powers of 1024
        $unit = ['B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB'];

        $i = floor(log($bytes, 1024));

        return @round($bytes / (pow(1024, $i)), $precision) . ' ' . $unit[(int)$i];



    }

    /**
     * This function transforms the php.ini notation for numbers (like '2M') to an integer (2*1024*1024 in this case)
     *
     * @param string $size letteral size
     *
     * @return int numerical size
     **/
    public static function sizeToBytes1024($size)
    {
        $l   = mb_substr($size, -1);
        $ret = mb_substr($size, 0, -1);
        switch (mb_strtoupper($l)) {
            case 'P':
            case 'p':
                $ret *= 1024;
                break;
            case 'T':
            case 't':
                $ret *= 1024;
                break;
            case 'G':
            case 'g':
                $ret *= 1024;
                break;
            case 'M':
            case 'm':
                $ret *= 1024;
                break;
            case 'K':
            case 'k':
                $ret *= 1024;
                break;
        }

        return $ret;
    }

    /**
     * Filesystem functions
     *
     * @param mixed $path
     * @param mixed $level
     */

    /**
     * This function will read the full structure of a directory.
     * It's recursive because it doesn't stop with the one directory,
     * it just keeps going through all of the directories in the folder you specify.
     *
     * @param string $path path to the directory to make
     * @param int    $level
     *
     * @return array
     */
    public static function getDir($path = '.', $level = 0)
    {
        $ret    = [];
        $ignore = ['cgi-bin', '.', '..'];
        // Directories to ignore when listing output. Many hosts will deny PHP access to the cgi-bin.
        $dirHandler = @\opendir($path);
        // Open the directory to the handle $dirHandler
        while (false !== ($file = \readdir($dirHandler))) {
            // Loop through the directory
            if (!\in_array($file, $ignore)) {
                // Check that this file is not to be ignored
                $spaces = \str_repeat('&nbsp;', $level * 4);
                // Just to add spacing to the list, to better show the directory tree.
                if (\is_dir("$path/$file")) {
                    // Its a directory, so we need to keep reading down...
                    $ret[] = "<strong>{$spaces} {$file}</strong>";
                    $ret   = \array_merge($ret, self::getDir($path . \DIRECTORY_SEPARATOR . $file, $level + 1));
                    // Re-call this same function but on a new directory.
                    // this is what makes function recursive.
                } else {
                    $ret[] = "{$spaces} {$file}";
                    // Just print out the filename
                }
            }
        }
        \closedir($dirHandler);

        // close the directory handle
        return $ret;
    }

    /**
     * Create a new directory that contains the file index.html
     *
     * @param string $dir          path to the directory to make
     * @param int    $perm         mode
     * @param bool   $create_index if true create index.html
     *
     * @return bool Returns true on success or false on failure
     * @throws \Exception
     * @throws \RuntimeException
     */
    public static function makeDir($dir, $perm = 0777, $create_index = true)
    {
        if (!\mkdir($dir, $perm) && !\is_dir($dir)) {
            throw new \RuntimeException('The directory ' . $dir . ' could not be created.');
        }
        if ($create_index) {
            if (false !== ($fileHandler = @\fopen($dir . '/index.html', 'wb'))) {
                \fwrite($fileHandler, '<script>history.go(-1);</script>');
            }
            if (false === @\fclose($fileHandler)) {
                throw new \RuntimeException('The file ' . $fileHandler . ' could not be created.');
            }
        }

        return true;

        return null;
    }

    /**
     * @param string $path
     *
     * @return array
     */
    public static function getFiles($path = '.')
    {
        $files = [];
        $dir   = \opendir($path);
        while (false !== ($file = \readdir($dir))) {
            if (\is_file($path . $file)) {
                if ('.' !== $file && '..' !== $file && 'blank.png' !== $file && 'index.html' !== $file) {
                    $files[] = $file;
                }
            }
        }

        return $files;
    }

    /**
     * Copy a file
     *
     * @param string $source      is the original directory
     * @param string $destination is the destination directory
     *
     * @return bool Returns true on success or false on failure
     */
    /*
    public static function copyFile($source, $destination)
    {
        // Simple copy for a file
        if (is_file($source)) {
            return copy($source, $destination);
        } else {
            return false;
        }
    }
    */

    /**
     * Copy a directory and its contents
     *
     * @param string $source      is the original directory
     * @param string $destination is the destination directory
     *
     * @return bool Returns true on success or false on failure
     * @throws \Exception
     * @throws \RuntimeException
     */
    public static function copyDir($source, $destination)
    {
        if (!$dirHandler = \opendir($source)) {
            return false;
        }
        if (\mkdir($destination) || \is_dir($destination)) {
            throw new \RuntimeException('The directory ' . $destination . ' could not be created.');
        }
        while (false !== ($file = \readdir($dirHandler))) {
            if (('.' !== $file) && ('..' !== $file)) {
                if (\is_dir("{$source}/{$file}")) {
                    if (!self::copyDir("{$source}/{$file}", "{$destination}/{$file}")) {
                        return false;
                    }
                } else {
                    if (!\copy("{$source}/{$file}", "{$destination}/{$file}")) {
                        return false;
                    }
                }
            }
        }
        \closedir($dirHandler);

        return true;
    }

    /**
     * Delete a file
     *
     * @param string $path is the file absolute path
     *
     * @return bool Returns true on success or false on failure
     */
    public static function delFile($path)
    {
        if (\is_file($path)) {
            @\chmod($path, 0777);

            return @\unlink($path);
        }

        return false;
    }

    /**
     * Delete a empty/not empty directory
     *
     * @param string $dir          path to the directory to delete
     * @param bool   $if_not_empty if false it delete directory only if false
     *
     * @return bool Returns true on success or false on failure
     */
    public static function delDir($dir, $if_not_empty = true)
    {
        if (!\file_exists($dir)) {
            return true;
        }
        if (true === $if_not_empty) {
            if (!\is_dir($dir)) {
                return \unlink($dir);
            }
            foreach (\scandir($dir, \SCANDIR_SORT_NONE) as $item) {
                if ('.' === $item || '..' === $item) {
                    continue;
                }
                if (!self::delDir("{$dir}/{$item}")) {
                    return false;
                }
            }
        }
        // NOP

        return \rmdir($dir);
    }

    /**
     * Module functions
     *
     * @param mixed $dirname
     */

    /**
     * Check if a module exist and return module verision
     *
     * @param string $dirname
     *
     * @return boolean, integer   false if module not installed or not active, module version if installed
     *
     * @access  public
     * @author  luciorota
     */
    public static function checkModule($dirname)
    {
        if (!\xoops_isActiveModule($dirname)) {
            return false;
        }
        /** @var \XoopsModuleHandler $moduleHandler */
        $moduleHandler = \xoops_getHandler('module');
        $module        = $moduleHandler->getByDirname($dirname);

        return $module->getVar('version');
    }

    /**
     * Recursively sort categories by level and weight
     *
     * @param int $pid
     * @param int $level
     *
     * @return array array of arrays: 'pid', 'cid', 'level', 'category' as array
     *
     * @access  public
     * @author  luciorota
     */
    public static function sortCategories($pid = 0, $level = 0)
    {
        /** @var \XoopsModules\Wfdownloads\Helper $helper */
        $helper = Helper::getInstance();

        $sorted   = [];
        $criteria = new \CriteriaCompo();
        $criteria->add(new \Criteria('pid', $pid));
        $criteria->setSort('weight');
        $criteria->setOrder('ASC');
        $subCategoryObjs = $helper->getHandler('Category')->getObjects($criteria);
        if (\count($subCategoryObjs) > 0) {
            ++$level;
            foreach ($subCategoryObjs as $subCategoryObj) {
                $pid      = $subCategoryObj->getVar('pid');
                $cid      = $subCategoryObj->getVar('cid');
                $sorted[] = ['pid' => $pid, 'cid' => $cid, 'level' => $level, 'category' => $subCategoryObj->toArray()];
                if (false !== ($subSorted = self::sortCategories($cid, $level))) {
                    $sorted = \array_merge($sorted, $subSorted);
                }
            }
        }

        return $sorted;
    }

    /**
     * Create download by letter choice bar/menu
     * updated starting from this idea https://xoops.org/modules/news/article.php?storyid=6497
     *
     * @return string html
     *
     * @access  public
     * @author  luciorota
     */
    public static function lettersChoice()
    {
        /** @var \XoopsModules\Wfdownloads\Helper $helper */
        $helper = Helper::getInstance();

        $criteria = $helper->getHandler('Download')->getActiveCriteria();
        $criteria->setGroupby('UPPER(LEFT(title,1))');
        $countsByLetters = $helper->getHandler('Download')->getCounts($criteria);
        // Fill alphabet array
        $alphabet      = \getLocalAlphabet();
        $alphabetArray = [];
        foreach ($alphabet as $letter) {
            $letter_array = [];
            if (isset($countsByLetters[$letter])) {
                $letter_array['letter'] = $letter;
                $letter_array['count']  = $countsByLetters[$letter];
                $letter_array['url']    = XOOPS_URL . "/modules/{$helper->getModule()->dirname()}/viewcat.php?letter={$letter}";
            } else {
                $letter_array['letter'] = $letter;
                $letter_array['count']  = 0;
                $letter_array['url']    = '';
            }
            $alphabetArray[$letter] = $letter_array;
            unset($letter_array);
        }
        // Render output
        if (!isset($GLOBALS['xoTheme']) || !\is_object($GLOBALS['xoTheme'])) {
            require_once $GLOBALS['xoops']->path('/class/theme.php');
            $GLOBALS['xoTheme'] = new \xos_opal_Theme();
        }
        require_once $GLOBALS['xoops']->path('class/template.php');
        $letterschoiceTpl          = new \XoopsTpl();
        $letterschoiceTpl->caching = false; // Disable cache
        $letterschoiceTpl->assign('alphabet', $alphabetArray);
        $html = $letterschoiceTpl->fetch("db:{$helper->getModule()->dirname()}_co_letterschoice.tpl");
        unset($letterschoiceTpl);

        return $html;
    }

    /**
     * Checks if a user is admin of Wfdownloads
     *
     * @return bool
     */
    public static function userIsAdmin()
    {
        /** @var \XoopsModules\Wfdownloads\Helper $helper */
        $helper = Helper::getInstance();

        static $wfdownloads_isAdmin;
        if (null !== $wfdownloads_isAdmin) {
            return $wfdownloads_isAdmin;
        }
        $wfdownloads_isAdmin = (!\is_object($GLOBALS['xoopsUser'])) ? false : $GLOBALS['xoopsUser']->isAdmin($helper->getModule()->getVar('mid'));

        return $wfdownloads_isAdmin;
    }

    public static function getCpHeader()
    {
        \xoops_cp_header();
    }

    /**
     * @param bool $withLink
     *
     * @return string
     */
    public static function moduleHome($withLink = true)
    {
        /** @var \XoopsModules\Wfdownloads\Helper $helper */
        $helper = Helper::getInstance();

        $wfdownloadsModuleName = $helper->getModule()->getVar('name');
        if (!$withLink) {
            return $wfdownloadsModuleName;
        }

        return '<a href="' . WFDOWNLOADS_URL . '/">{$wfdownloadsModuleName}</a>';
    }

    /**
     * Detemines if a table exists in the current db
     *
     * @param string $table the table name (without XOOPS prefix)
     *
     * @return bool True if table exists, false if not
     *
     * @access public
     * @author xhelp development team
     */
    public static function tableExists($table)
    {
        $bRetVal = false;
        //Verifies that a MySQL table exists
        $GLOBALS['xoopsDB'] = \XoopsDatabaseFactory::getDatabaseConnection();
        $realName           = $GLOBALS['xoopsDB']->prefix($table);

        $sql = 'SHOW TABLES FROM ' . XOOPS_DB_NAME;
        $ret = $GLOBALS['xoopsDB']->queryF($sql);
        while (list($m_table) = $GLOBALS['xoopsDB']->fetchRow($ret)) {
            if ($m_table == $realName) {
                $bRetVal = true;
                break;
            }
        }
        $GLOBALS['xoopsDB']->freeRecordSet($ret);

        return $bRetVal;
    }

    /**
     * Gets a value from a key in the xhelp_meta table
     *
     * @param string $key
     *
     * @return string $value
     *
     * @access public
     * @author xhelp development team
     */
    public static function getMeta($key)
    {
        $GLOBALS['xoopsDB'] = \XoopsDatabaseFactory::getDatabaseConnection();
        $sql                = \sprintf('SELECT metavalue FROM `%s` WHERE metakey=%s', $GLOBALS['xoopsDB']->prefix('wfdownloads_meta'), $GLOBALS['xoopsDB']->quoteString($key));
        $ret                = $GLOBALS['xoopsDB']->query($sql);
        if (!$ret) {
            $value = false;
        } else {
            [$value] = $GLOBALS['xoopsDB']->fetchRow($ret);
        }

        return $value;
    }

    /**
     * Sets a value for a key in the xhelp_meta table
     *
     * @param string $key
     * @param string $value
     *
     * @return bool true if success, false if failure
     *
     * @access public
     * @author xhelp development team
     */
    public static function setMeta($key, $value)
    {
        $GLOBALS['xoopsDB'] = \XoopsDatabaseFactory::getDatabaseConnection();
        if (false !== ($ret = self::getMeta($key))) {
            $sql = \sprintf('UPDATE `%s` SET metavalue = %s WHERE metakey = %s', $GLOBALS['xoopsDB']->prefix('wfdownloads_meta'), $GLOBALS['xoopsDB']->quoteString($value), $GLOBALS['xoopsDB']->quoteString($key));
        } else {
            $sql = \sprintf('INSERT INTO `%s` (metakey, metavalue) VALUES (%s, %s)', $GLOBALS['xoopsDB']->prefix('wfdownloads_meta'), $GLOBALS['xoopsDB']->quoteString($key), $GLOBALS['xoopsDB']->quoteString($value));
        }
        $ret = $GLOBALS['xoopsDB']->queryF($sql);
        if (!$ret) {
            return false;
        }

        return true;
    }

    /**
     * @param           $name
     * @param           $value
     * @param int|float $time
     */
    public static function setCookieVar($name, $value, $time = 0)
    {
        if (0 == $time) {
            $time = \time() + 3600 * 24 * 365;
            //$time = '';
        }
        setcookie($name, $value, $time, '/', \ini_get('session.cookie_domain'), \ini_get('session.cookie_secure'), \ini_get('session.cookie_httponly'));
    }

    /**
     * @param        $name
     * @param string $default
     *
     * @return string
     */
    public static function getCookieVar($name, $default = '')
    {
        if (isset($_COOKIE[$name]) && ($_COOKIE[$name] > '')) {
            return $_COOKIE[$name];
        }

        return $default;
    }

    /**
     * @return array
     */
    public static function getCurrentUrls()
    {
        $http        = (false === mb_strpos(XOOPS_URL, 'https://')) ? 'http://' : 'https://';
        $phpSelf     = $_SERVER['SCRIPT_NAME'];
        $httpHost    = $_SERVER['HTTP_HOST'];
        $queryString = $_SERVER['QUERY_STRING'];

        if ('' !== $queryString) {
            $queryString = '?' . $queryString;
        }
        $currentURL          = $http . $httpHost . $phpSelf . $queryString;
        $urls                = [];
        $urls['http']        = $http;
        $urls['httphost']    = $httpHost;
        $urls['phpself']     = $phpSelf;
        $urls['querystring'] = $queryString;
        $urls['full']        = $currentURL;

        return $urls;
    }

    /**
     * @return mixed
     */
    public static function getCurrentPage()
    {
        $urls = self::getCurrentUrls();

        return $urls['full'];
    }

    /**
     * @param array $errors
     *
     * @return string
     */
    public static function formatErrors($errors = [])
    {
        $ret = '';
        //mb    foreach ($errors as $key => $value) {
        foreach ($errors as $value) {
            $ret .= "<br> - {$value}";
        }

        return $ret;
    }

    // TODO : The SEO feature is not fully implemented in the module...

    /**
     * @param        $op
     * @param        $id
     * @param string $title
     *
     * @return string
     */
    public static function generateSeoUrl($op, $id, $title = '')
    {
        if (\defined('SEO_ENABLED')) {
            if (SEO_ENABLED === 'rewrite') {
                // generate SEO url using htaccess
                return XOOPS_URL . "/wfdownloads.${op}.${id}/" . self::getSeoTitle($title);
            } elseif (SEO_ENABLED === 'path-info') {
                // generate SEO url using path-info
                return XOOPS_URL . "/modules/wfdownloads/seo.php/${op}.${id}/" . self::getSeoTitle($title);
            }
            exit('Unknown SEO method.');
        }
        // generate classic url
        switch ($op) {
            case 'category':
                return XOOPS_URL . "/modules/wfdownloads/${op}.php?categoryid=${id}";
            case 'item':
            case 'print':
                return XOOPS_URL . "/modules/wfdownloads/${op}.php?itemid=${id}";
            default:
                exit('Unknown SEO operation.');
        }
    }

    /**
     * @param string $title
     * @return string
     */
    public static function getSeoTitle($title = '')
    {
        $words = \preg_split('/[^0-9a-z.]+/', mb_strtolower($title), -1, \PREG_SPLIT_NO_EMPTY);
        if (\count($words) > 0) {
            return \implode($words, '-') . '.html';
        }

        return '';
    }

    /**
     * save_Permissions()
     *
     * @param $groups
     * @param $id
     * @param $permName
     *
     * @return bool
     */
    public static function savePermissions($groups, $id, $permName)
    {
        /** @var \XoopsModules\Wfdownloads\Helper $helper */
        $helper = Helper::getInstance();

        $id               = (int)$id;
        $result           = true;
        $mid              = $helper->getModule()->mid();
        $grouppermHandler = \xoops_getHandler('groupperm');
        // First, if the permissions are already there, delete them
        $grouppermHandler->deleteByModule($mid, $permName, $id);
        // Save the new permissions
        if (\is_array($groups)) {
            foreach ($groups as $group_id) {
                $grouppermHandler->addRight($permName, $id, $group_id, $mid);
            }
        }

        return $result;
    }

    /**
     * toolbar()
     *
     * @return string
     */
    public static function toolbar()
    {
        /** @var \XoopsModules\Wfdownloads\Helper $helper */
        $helper = Helper::getInstance();

        $isSubmissionAllowed = false;
        if (\is_object($GLOBALS['xoopsUser'])
            && (\_WFDOWNLOADS_SUBMISSIONS_DOWNLOAD == $helper->getConfig('submissions')
                || \_WFDOWNLOADS_SUBMISSIONS_BOTH == $helper->getConfig('submissions'))) {
            $groups = $GLOBALS['xoopsUser']->getGroups();
            if (\count(\array_intersect($helper->getConfig('submitarts'), $groups)) > 0) {
                $isSubmissionAllowed = true;
            }
        } elseif (!\is_object($GLOBALS['xoopsUser']) && (\_WFDOWNLOADS_ANONPOST_DOWNLOAD == $helper->getConfig('anonpost') || \_WFDOWNLOADS_ANONPOST_BOTH == $helper->getConfig('anonpost'))) {
            $isSubmissionAllowed = true;
        }
        $toolbar = '[ ';
        if (true === $isSubmissionAllowed) {
            $category_suffix = Request::getInt('cid', '', 'GET'); //Added by Lankford
            $toolbar         .= "<a href='submit.php{$category_suffix}'>" . \_MD_WFDOWNLOADS_SUBMITDOWNLOAD . '</a> | ';
        }
        $toolbar .= "<a href='newlist.php'>" . \_MD_WFDOWNLOADS_LATESTLIST . '</a>';
        $toolbar .= ' | ';
        $toolbar .= "<a href='topten.php?list=hit'>" . \_MD_WFDOWNLOADS_POPULARITY . '</a>';
        if ($helper->getConfig('enable_ratings')) {
            $toolbar .= ' | ';
            $toolbar .= "<a href='topten.php?list=rate'>" . \_MD_WFDOWNLOADS_TOPRATED . '</a>';
        }
        $toolbar .= ' ]';

        return $toolbar;
    }

    /**
     * displayicons()
     *
     * @param         $time
     * @param int     $status
     * @param int     $counter
     *
     * @return string
     */
    public static function displayIcons($time, $status = \_WFDOWNLOADS_STATUS_WAITING, $counter = 0)
    {
        /** @var \XoopsModules\Wfdownloads\Helper $helper */
        $helper = Helper::getInstance();

        $new     = '';
        $pop     = '';
        $newdate = (\time() - (86400 * $helper->getConfig('daysnew')));
        $popdate = (\time() - (86400 * $helper->getConfig('daysupdated')));

        if (\_WFDOWNLOADS_DISPLAYICONS_NO != $helper->getConfig('displayicons')) {
            if ($newdate < $time) {
                if ((int)$status > \_WFDOWNLOADS_STATUS_APPROVED) {
                    if (\_WFDOWNLOADS_DISPLAYICONS_ICON == $helper->getConfig('displayicons')) {
                        $new = '&nbsp;<img src=' . XOOPS_URL . '/modules/' . WFDOWNLOADS_DIRNAME . "/assets/images/icon/update.gif alt='' align ='absmiddle'>";
                    }
                    if (\_WFDOWNLOADS_DISPLAYICONS_TEXT == $helper->getConfig('displayicons')) {
                        $new = '<i>' . \_WFDOWNLOADS_MD_UPDATED . '</i>';
                    }
                } else {
                    if (\_WFDOWNLOADS_DISPLAYICONS_ICON == $helper->getConfig('displayicons')) {
                        $new = '&nbsp;<img src=' . XOOPS_URL . '/modules/' . WFDOWNLOADS_DIRNAME . "/assets/images/icon/newred.gif alt='' align ='absmiddle'>";
                    }
                    if (\_WFDOWNLOADS_DISPLAYICONS_TEXT == $helper->getConfig('displayicons')) {
                        $new = '<i>' . \_WFDOWNLOADS_MD_NEW . '</i>';
                    }
                }
            }
            if ($popdate < $time) {
                if ($counter >= $helper->getConfig('popular')) {
                    if (\_WFDOWNLOADS_DISPLAYICONS_ICON == $helper->getConfig('displayicons')) {
                        $pop = '&nbsp;<img src =' . XOOPS_URL . '/modules/' . WFDOWNLOADS_DIRNAME . "/assets/images/icon/pop.gif alt='' align ='absmiddle'>";
                    }
                    if (\_WFDOWNLOADS_DISPLAYICONS_TEXT == $helper->getConfig('displayicons')) {
                        $pop = '<i>' . \_WFDOWNLOADS_MD_POPULAR . '</i>';
                    }
                }
            }
        }
        $icons = "{$new} {$pop}";

        return $icons;
    }

    //if (!function_exists('convertorderbyin')) {
    // Reusable Link Sorting Functions

    /**
     * convertorderbyin()
     *
     * @param   $orderby
     *
     * @return string
     */
    public static function convertorderbyin($orderby)
    {
        switch (\trim($orderby)) {
            case 'titleA':
                $orderby = 'title ASC';
                break;
            case 'titleD':
                $orderby = 'title DESC';
                break;
            case 'dateA':
                $orderby = 'published ASC';
                break;
            case 'dateD':
                $orderby = 'published DESC';
                break;
            case 'hitsA':
                $orderby = 'hits ASC';
                break;
            case 'hitsD':
                $orderby = 'hits DESC';
                break;
            case 'ratingA':
                $orderby = 'rating ASC';
                break;
            case 'ratingD':
                $orderby = 'rating DESC';
                break;
            case 'sizeD':
                $orderby = 'size DESC';
                break;
            case 'sizeA':
                $orderby = 'size ASC';
                break;
            default:
                $orderby = 'published DESC';
                break;
        }

        return $orderby;
    }

    //}

    //if (!function_exists('convertorderbytrans')) {

    /**
     * @param $orderby
     *
     * @return string
     */
    public static function convertorderbytrans($orderby)
    {
        if ('title ASC' === $orderby) {
            $orderbyTrans = \_MD_WFDOWNLOADS_TITLEATOZ;
        }
        if ('title DESC' === $orderby) {
            $orderbyTrans = \_MD_WFDOWNLOADS_TITLEZTOA;
        }
        if ('published ASC' === $orderby) {
            $orderbyTrans = \_MD_WFDOWNLOADS_DATEOLD;
        }
        if ('published DESC' === $orderby) {
            $orderbyTrans = \_MD_WFDOWNLOADS_DATENEW;
        }
        if ('hits ASC' === $orderby) {
            $orderbyTrans = \_MD_WFDOWNLOADS_POPULARITYLTOM;
        }
        if ('hits DESC' === $orderby) {
            $orderbyTrans = \_MD_WFDOWNLOADS_POPULARITYMTOL;
        }
        if ('rating ASC' === $orderby) {
            $orderbyTrans = \_MD_WFDOWNLOADS_RATINGLTOH;
        }
        if ('rating DESC' === $orderby) {
            $orderbyTrans = \_MD_WFDOWNLOADS_RATINGHTOL;
        }
        if ('size ASC' === $orderby) {
            $orderbyTrans = \_MD_WFDOWNLOADS_SIZELTOH;
        }
        if ('size DESC' === $orderby) {
            $orderbyTrans = \_MD_WFDOWNLOADS_SIZEHTOL;
        }

        return $orderbyTrans;
    }

    //}

    //if (!function_exists('convertorderbyout')) {

    /**
     * @param $orderby
     *
     * @return string
     */
    public static function convertorderbyout($orderby)
    {
        if ('title ASC' === $orderby) {
            $orderby = 'titleA';
        }
        if ('title DESC' === $orderby) {
            $orderby = 'titleD';
        }
        if ('published ASC' === $orderby) {
            $orderby = 'dateA';
        }
        if ('published DESC' === $orderby) {
            $orderby = 'dateD';
        }
        if ('hits ASC' === $orderby) {
            $orderby = 'hitsA';
        }
        if ('hits DESC' === $orderby) {
            $orderby = 'hitsD';
        }
        if ('rating ASC' === $orderby) {
            $orderby = 'ratingA';
        }
        if ('rating DESC' === $orderby) {
            $orderby = 'ratingD';
        }
        if ('size ASC' === $orderby) {
            $orderby = 'sizeA';
        }
        if ('size DESC' === $orderby) {
            $orderby = 'sizeD';
        }

        return $orderby;
    }

    //}

    /**
     * updaterating()
     *
     * @param   $lid
     */
    public static function updateRating($lid)
    {
        /** @var \XoopsModules\Wfdownloads\Helper $helper */
        $helper = Helper::getInstance();

        $ratingObjs    = $helper->getHandler('Rating')->getObjects(new \Criteria('lid', (int)$lid));
        $ratings_count = \count($ratingObjs);
        $totalRating   = 0;
        foreach ($ratingObjs as $ratingObj) {
            $totalRating += $ratingObj->getVar('rating');
        }
        $averageRating = $totalRating / $ratings_count;
        $averageRating = \number_format($averageRating, 4);
        $downloadObj   = $helper->getHandler('Download')->get($lid);
        $downloadObj->setVar('rating', $averageRating);
        $downloadObj->setVar('votes', $ratings_count);
        $helper->getHandler('Download')->insert($downloadObj);
    }

    /**
     * categoriesCount()
     *
     * @return int
     */
    public static function categoriesCount()
    {
        $grouppermHandler = \xoops_getHandler('groupperm');
        /** @var \XoopsModules\Wfdownloads\Helper $helper */
        $helper                   = Helper::getInstance();
        $groups                   = \is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getGroups() : [0 => XOOPS_GROUP_ANONYMOUS];
        $allowedDownCategoriesIds = $grouppermHandler->getItemIds('WFDownCatPerm', $groups, $helper->getModule()->mid());

        return \count($allowedDownCategoriesIds);
    }

    /**
     * getTotalDownloads()
     *
     * @param int|array $cids (array of integer)
     *
     * @return bool number of items in items table that are associated with a given table $table id
     */
    public static function getTotalDownloads($cids = 0)
    {
        /** @var \XoopsModules\Wfdownloads\Helper $helper */
        $helper = Helper::getInstance();

        $criteria = new \CriteriaCompo(new \Criteria('offline', false));
        $criteria->add(new \Criteria('published', 0, '>'));
        $criteria->add(new \Criteria('published', \time(), '<='));
        $expiredCriteria = new \CriteriaCompo(new \Criteria('expired', 0));
        $expiredCriteria->add(new \Criteria('expired', \time(), '>='), 'OR');
        $criteria->add($expiredCriteria);
        if ($cids && \is_array($cids)) {
            $criteria->add(new \Criteria('cid', '(' . \implode(',', $cids) . ')', 'IN'));
        } elseif ($cids > 0) {
            $criteria->add(new \Criteria('cid', (int)$cids));
        } else {
            return false;
        }
        $criteria->setGroupBy('cid');
        $info['published'] = $helper->getHandler('Download')->getMaxPublishdate($criteria);
        $info['count']     = $helper->getHandler('Download')->getCount($criteria);

        return $info;
    }

    /**
     * @return string
     */
    public static function headerImage()
    {
        /** @var \XoopsModules\Wfdownloads\Helper $helper */
        $helper = Helper::getInstance();

        $image  = '';
        $result = $GLOBALS['xoopsDB']->query('SELECT indeximage, indexheading FROM ' . $GLOBALS['xoopsDB']->prefix('wfdownloads_indexpage') . ' ');
        [$indexImage, $indexHeading] = $GLOBALS['xoopsDB']->fetchrow($result);
        if (!empty($indeximage)) {
            $image = self::displayImage($indexImage, 'index.php', $helper->getConfig('mainimagedir'), $indexHeading);
        }

        return $image;
    }

    /**
     * @param string $image
     * @param string $href
     * @param string $imgSource
     * @param string $altText
     *
     * @return string
     */
    public static function displayImage($image = '', $href = '', $imgSource = '', $altText = '')
    {
        /** @var \XoopsModules\Wfdownloads\Helper $helper */
        $helper = Helper::getInstance();

        $showImage = '';

        // Check to see if link is given
        if ($href) {
            $showImage = "<a href='{$href}'>";
        }
        // checks to see if the file is valid else displays default blank image
        if (!\is_dir(XOOPS_ROOT_PATH . "/{$imgSource}/{$image}") && \is_dir(XOOPS_ROOT_PATH . "/{$imgSource}/{$image}")) {
            $showImage .= "<img src='" . XOOPS_URL . "/{$imgSource}/{$image}' border='0' alt='{$altText}'>";
        } else {
            if ($GLOBALS['xoopsUser'] && $GLOBALS['xoopsUser']->isAdmin($helper->getModule()->mid())) {
                $showImage .= "<img src='" . XOOPS_URL . '/modules/' . WFDOWNLOADS_DIRNAME . "/assets/images/brokenimg.png' alt='" . \_MD_WFDOWNLOADS_ISADMINNOTICE . "'>";
            } else {
                $showImage .= "<img src='" . XOOPS_URL . '/modules/' . WFDOWNLOADS_DIRNAME . "/assets/images/blank.png' alt='{$altText}'>";
            }
        }
        if ($href) {
            $showImage .= '</a>';
        }
        \clearstatcache();

        return $showImage;
    }

    /**
     * createThumb()
     *
     * @param          $imgName
     * @param          $imgPath
     * @param          $imgSavePath
     * @param int      $width
     * @param int      $height
     * @param int      $quality
     * @param bool|int $update
     * @param int      $aspect
     *
     * @return string
     */
    public static function createThumb($imgName, $imgPath, $imgSavePath, $width = 100, $height = 100, $quality = 100, $update = false, $aspect = 1)
    {
        /** @var \XoopsModules\Wfdownloads\Helper $helper */
        $helper = Helper::getInstance();

        // Paths
        if (false === $helper->getConfig('usethumbs')) {
            $imageURL = XOOPS_URL . "/{$imgPath}/{$imgName}";

            return $imageURL;
        }
        $imagePath = XOOPS_ROOT_PATH . "/{$imgPath}/{$imgName}";
        $saveFile  = "{$imgPath}/{$imgSavePath}/{$width}x{$height}_{$imgName}";
        $savePath  = XOOPS_ROOT_PATH . '/' . $saveFile;
        // Return the image if no update and image exists
        if (false === $update && \file_exists($savePath)) {
            return XOOPS_URL . '/' . $saveFile;
        }
        // Original image info
        [$origWidth, $origHeight, $type, $attr] = \getimagesize($imagePath, $info);
        switch ($type) {
            case 1:
                # GIF image
                if (\function_exists('imagecreatefromgif')) {
                    $img = @\imagecreatefromgif($imagePath);
                } else {
                    $img = @\imagecreatefrompng($imagePath);
                }
                break;
            case 2:
                # JPEG image
                $img = @\imagecreatefromjpeg($imagePath);
                break;
            case 3:
                # PNG image
                $img = @\imagecreatefrompng($imagePath);
                break;
            default:
                return $imagePath;
                break;
        }
        if (!empty($img)) {
            // Get original image size and scale ratio
            $scale = $origWidth / $origHeight;
            if ($width / $height > $scale) {
                $width = $height * $scale;
            } else {
                $height = $width / $scale;
            }
            // Create a new temporary image
            if (\function_exists('imagecreatetruecolor')) {
                $tempImg = \imagecreatetruecolor($width, $height);
            } else {
                $tempImg = \imagecreate($width, $height);
            }
            // Copy and resize old image into new image
            \imagecopyresampled($tempImg, $img, 0, 0, 0, 0, $width, $height, $origWidth, $origHeight);
            \imagedestroy($img);
            \flush();
            $img = $tempImg;
        }
        switch ($type) {
            case 1:
            default:
                # GIF image
                if (\function_exists('imagegif')) {
                    \imagegif($img, $savePath);
                } else {
                    \imagepng($img, $savePath);
                }
                break;
            case 2:
                # JPEG image
                \imagejpeg($img, $savePath, $quality);
                break;
            case 3:
                # PNG image
                \imagepng($img, $savePath);
                break;
        }
        \imagedestroy($img);
        \flush();

        return XOOPS_URL . '/' . $saveFile;
    }

    /**
     * isNewImage()
     *
     * @param int $published date
     *
     * @return array 'image', 'alttext', 'days'  number of days between $published and now
     **/
    public static function isNewImage($published)
    {
        if ($published <= 0) {
            $indicator['image']   = 'assets/images/icon/download.gif';
            $indicator['alttext'] = \_MD_WFDOWNLOADS_NO_FILES;
            $indicator['days']    = null;
        } else {
            $days              = (int)((\time() - $published) / 86400); // number of days between $published and now
            $indicator['days'] = $days;
            switch ($days) {
                case 0:
                    // today
                    $indicator['image']   = 'assets/images/icon/download1.gif';
                    $indicator['alttext'] = \_MD_WFDOWNLOADS_TODAY;
                    break;
                case 1:
                case 2:
                    // less than 3 days
                    $indicator['image']   = 'assets/images/icon/download2.gif';
                    $indicator['alttext'] = \_MD_WFDOWNLOADS_THREE;
                    break;
                case 3:
                case 4:
                case 5:
                case 6:
                    // less than 7 days
                    $indicator['image']   = 'assets/images/icon/download3.gif';
                    $indicator['alttext'] = \_MD_WFDOWNLOADS_NEWTHIS;
                    break;
                case 7:
                default:
                    // more than a week
                    $indicator['image']   = 'assets/images/icon/download4.gif';
                    $indicator['alttext'] = \_MD_WFDOWNLOADS_NEWLAST;
                    break;
            }
        }

        return $indicator;
    }

    // GetDownloadTime()
    // This function is used to show some different download times
    // BCMATH-Support in PHP needed!
    // (c)02.04.04 by St@neCold, stonecold@csgui.de, http://www.csgui.de

    /**
     * @param int $size
     * @param int $gmodem
     * @param int $gisdn
     * @param int $gdsl
     * @param int $gslan
     * @param int $gflan
     *
     * @return string
     */
    public static function getDownloadTime($size = 0, $gmodem = 1, $gisdn = 1, $gdsl = 1, $gslan = 0, $gflan = 0)
    {
        $aflag  = [];
        $amtime = [];
        $artime = [];
        $ahtime = [];
        $asout  = [];
        $aflag  = [$gmodem, $gisdn, $gdsl, $gslan, $gflan];
        $amtime = [$size / 6300 / 60, $size / 7200 / 60, $size / 86400 / 60, $size / 1125000 / 60, $size / 11250000 / 60];
        $amname = ['Modem(56k)', 'ISDN(64k)', 'DSL(768k)', 'LAN(10M)', 'LAN(100M'];
        for ($i = 0; $i < 5; ++$i) {
            $artime[$i] = ($amtime[$i] % 60);
        }
        for ($i = 0; $i < 5; ++$i) {
            $ahtime[$i] = \sprintf(' %2.0f', $amtime[$i] / 60);
        }
        if ($size <= 0) {
            $dltime = 'N/A';
        } else {
            for ($i = 0; $i < 5; ++$i) {
                if (!$aflag[$i]) {
                    $asout[$i] = '';
                } else {
                    if (($amtime[$i] * 60) < 1) {
                        $asout[$i] = \sprintf(' : %4.2fs', $amtime[$i] * 60);
                    } else {
                        if ($amtime[$i] < 1) {
                            $asout[$i] = \sprintf(' : %2.0fs', \round($amtime[$i] * 60));
                        } else {
                            if (0 == $ahtime[$i]) {
                                $asout[$i] = \sprintf(' : %5.1fmin', $amtime[$i]);
                            } else {
                                $asout[$i] = \sprintf(' : %2.0fh%2.0fmin', $ahtime[$i], $artime[$i]);
                            }
                        }
                    }
                    $asout[$i] = '<b>' . $amname[$i] . '</b>' . $asout[$i];
                    if ($i < 4) {
                        $asout[$i] .= ' | ';
                    }
                }
            }
            $dltime = '';
            for ($i = 0; $i < 5; ++$i) {
                $dltime .= $asout[$i];
            }
        }

        return $dltime;
    }

    /**
     * @param $haystack
     * @param $needle
     *
     * @return string
     */
    public static function strrrchr($haystack, $needle)
    {
        return mb_substr($haystack, 0, mb_strpos($haystack, $needle) + 1);
    }

    /**
     * @param      $fileName
     * @param bool $isAdmin
     *
     * @return array
     */
    public static function allowedMimetypes($fileName, $isAdmin = true)
    {
        /** @var \XoopsModules\Wfdownloads\Helper $helper */
        $helper = Helper::getInstance();

        $ext      = \ltrim(mb_strrchr($fileName, '.'), '.');
        $criteria = new \CriteriaCompo(new \Criteria('mime_ext', mb_strtolower($ext)));
        if (true === $isAdmin) {
            $criteria->add(new \Criteria('mime_admin', true));
        } else {
            $criteria->add(new \Criteria('mime_user', true));
        }
        if (false !== ($mimetypeObjs = $helper->getHandler('Mimetype')->getObjects($criteria))) {
            $mimetypeObj = $mimetypeObjs[0];
            $ret         = \explode(' ', $mimetypeObj->getVar('mime_types'));
        } else {
            $ret = [];
        }

        return $ret;
    }

    /**
     * @param $size_str
     *
     * @return int
     */
    public static function returnBytes($size_str)
    {
        switch (mb_substr($size_str, -1)) {
            case 'M':
            case 'm':
                return (int)$size_str * 1048576;
            case 'K':
            case 'k':
                return (int)$size_str * 1024;
            case 'G':
            case 'g':
                return (int)$size_str * 1073741824;
            default:
                return $size_str;
        }
    }

    /**
     * uploading()
     *
     * @param string $filename
     * @param string $uploadDirectory
     * @param array  $allowedMimetypes
     * @param string $redirectURL
     * @param int    $num
     * @param bool   $redirect
     * @param bool   $isAdmin
     * @param bool   $onlyImages
     *
     * @return array
     **/
    public static function uploading(
        $filename,
        $uploadDirectory = 'uploads',
        $allowedMimetypes = [],
        $redirectURL = 'index.php',
        $num = 0,
        $redirect = false,
        $isAdmin = true,
        $onlyImages = false
    ) {
        /** @var \XoopsModules\Wfdownloads\Helper $helper */
        $helper = Helper::getInstance();
        $file   = [];
        if (empty($allowedMimetypes)) {
            $allowedMimetypes = self::allowedMimetypes($_FILES['userfile']['name'], $isAdmin);
        }
        if (empty($allowedMimetypes)) {
            $errors = 'MIME type not allowed';
            \redirect_header($redirectURL, 4, $errors);
        }
        $uploadDirectory .= '/';
        $file_name       = $_FILES['userfile']['name'];
        //Admin can upload files of any size
        if (self::userIsAdmin()) {
            $maxFileSize = self::returnBytes(\ini_get('upload_max_filesize'));
        } else {
            $maxFileSize = $helper->getConfig('maxfilesize');
        }
        $maxImageWidth  = $helper->getConfig('maximgwidth');
        $maxImageHeight = $helper->getConfig('maximgheight');
        // TODO: use Xoops XoopsMediaUploader class
        if ($onlyImages) {
            //            require_once XOOPS_ROOT_PATH . '/modules/wfdownloads/class/img_uploader.php';
            //xoops_load('XoopsMediaUploader');
            $uploader = new Wfdownloads\MediaImgUploader($uploadDirectory, $allowedMimetypes, $maxFileSize, $maxImageWidth, $maxImageHeight);
        } else {
            require_once XOOPS_ROOT_PATH . '/class/uploader.php';
            //xoops_load('XoopsMediaUploader');
            $uploader = new \XoopsMediaUploader($uploadDirectory, $allowedMimetypes, $maxFileSize, $maxImageWidth, $maxImageHeight);
        }
        //    $uploader->noAdminSizeCheck(1);
        if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {
            if (!$uploader->upload()) {
                $errors = $uploader->getErrors();
                \unlink($uploadDirectory . $uploader->savedFileName);
                \redirect_header($redirectURL, 4, $errors);
            } else {
                if ($redirect) {
                    \redirect_header($redirectURL, 4, \_AM_WFDOWNLOADS_UPLOADFILE);
                } else {
                    if (\is_file($uploader->savedDestination)) {
                        //                    $file['url'] = XOOPS_URL . '/' . $uploadDirectory . '/';
                        $file['filename'] = mb_strtolower($uploader->savedFileName);
                        $file['filetype'] = $_FILES['userfile']['type'];
                        $file['size']     = \filesize($uploadDirectory . mb_strtolower($uploader->savedFileName));
                    }

                    return $file;
                }
            }
        } else {
            $errors = $uploader->getErrors();
            \unlink($uploadDirectory . $uploader->savedFileName);
            \redirect_header($redirectURL, 4, $errors);
        }

        return null;
    }

    /**
     * @param      $filePath
     * @param bool $isBinary
     * @param bool $retBytes
     *
     * @return bool|int|mixed
     */
    public static function download($filePath, $isBinary = true, $retBytes = true)
    {
        // how many bytes per chunk
        //$chunkSize = 1 * (1024 * 1024);
        $chunkSize    = 8 * (1024 * 1024); //8MB (highest possible fread length)
        $buffer       = '';
        $bytesCounter = 0;

        if (true === $isBinary) {
            $handler = \fopen($filePath, 'rb');
        } else {
            $handler = \fopen($filePath, 'rb');
        }
        if (false === $handler) {
            return false;
        }
        while (!\feof($handler)) {
            $buffer = \fread($handler, $chunkSize);
            echo $buffer;
            \ob_flush();
            \flush();
            if ($retBytes) {
                $bytesCounter += mb_strlen($buffer);
            }
        }
        $status = \fclose($handler);
        if ($retBytes && $status) {
            return $bytesCounter; // return num. bytes delivered like readfile() does.
        }

        return $status;
    }

    // IN PROGRESS
    // IN PROGRESS
    // IN PROGRESS

    /**
     * @param $filePath
     * @param $fileMimetype
     * @author     Jack Mason
     * @website    volunteer @ http://www.osipage.com, web access application and bookmarking tool.
     * @copyright  Free script, use anywhere as you like, no attribution required
     * @created    2014
     *             The script is capable of downloading really large files in PHP. Files greater than 2GB may fail in 32-bit windows or similar system.
     *             All incorrect headers have been removed and no nonsense code remains in this script. Should work well.
     *             The best and most recommended way to download files with PHP is using xsendfile, learn
     *             more here: https://tn123.org/mod_xsendfile/
     *
     */
    public static function largeDownload($filePath, $fileMimetype)
    {
        /* You may need these ini settings too */
        \set_time_limit(0);
        \ini_set('memory_limit', '512M');
        if (!empty($filePath)) {
            $fileInfo            = \pathinfo($filePath);
            $fileName            = $fileInfo['basename'];
            $fileExtension       = $fileInfo['extension'];
            $default_contentType = 'application/octet-stream';
            // to find and use specific content type, check out this IANA page : http://www.iana.org/assignments/media-types/media-types.xhtml
            $contentType = $default_contentType;
            if (false !== ($fileMimetype = !'')) {
                $contentType = $fileMimetype;
            }
            if (\is_file($filePath)) {
                $size   = \filesize($filePath);
                $offset = 0;
                $length = $size;
                //HEADERS FOR PARTIAL DOWNLOAD FACILITY BEGINS
                if (Request::hasVar('HTTP_RANGE', 'SERVER')) {
                    \preg_match('/bytes=(\d+)-(\d+)?/', $_SERVER['HTTP_RANGE'], $matches);
                    $offset  = (int)$matches[1];
                    $length  = (int)$matches[2] - $offset;
                    $fhandle = \fopen($filePath, 'rb');
                    \fseek($fhandle, $offset); // seek to the requested offset, this is 0 if it's not a partial content request
                    $data = \fread($fhandle, $length);
                    \fclose($fhandle);
                    \header('HTTP/1.1 206 Partial Content');
                    \header('Content-Range: bytes ' . $offset . '-' . ($offset + $length) . '/' . $size);
                }//HEADERS FOR PARTIAL DOWNLOAD FACILITY BEGINS
                //USUAL HEADERS FOR DOWNLOAD
                \header('Content-Disposition: attachment;filename=' . $fileName);
                \header('Content-Type: ' . $contentType);
                \header('Accept-Ranges: bytes');
                \header('Pragma: public');
                \header('Expires: -1');
                \header('Cache-Control: no-cache');
                \header('Cache-Control: public, must-revalidate, post-check=0, pre-check=0');
                \header('Content-Length: ' . \filesize($filePath));
                $chunksize = 8 * (1024 * 1024); //8MB (highest possible fread length)
                if ($size > $chunksize) {
                    $handle = \fopen($_FILES['file']['tmp_name'], 'rb');
                    $buffer = '';
                    while (!\feof($handle) && (\CONNECTION_NORMAL === \connection_status())) {
                        $buffer = \fread($handle, $chunksize);
                        print $buffer;
                        \ob_flush();
                        \flush();
                    }
                    if (\CONNECTION_NORMAL !== \connection_status()) {
                        //TODO traslation
                        echo 'Connection aborted';
                    }
                    \fclose($handle);
                } else {
                    \ob_clean();
                    \flush();
                    \readfile($filePath);
                }
            } else {
                //TODO traslation
                echo 'File does not exist!';
            }
        } else {
            //TODO traslation
            echo 'There is no file to download!';
        }
    }

    /**
     * @param $selectedForumId
     *
     * @return int
     */
    public static function getForum($selectedForumId)
    {
        $selectedForumId = (int)$selectedForumId;
        echo "<select name='forumid'>";
        echo "<option value='0'>----------------------</option>";
        $result = $GLOBALS['xoopsDB']->query('SELECT forum_name, forum_id FROM ' . $GLOBALS['xoopsDB']->prefix('bb_forums') . ' ORDER BY forum_id');
        while (list($forumName, $forumId) = $GLOBALS['xoopsDB']->fetchRow($result)) {
            $optionSelected = '';
            if ($forumId == $selectedForumId) {
                $optionSelected = "selected='selected'";
            }
            echo "<option value='{$forumId}' {$optionSelected}>{$forumName}</option>";
        }
        echo '</select></div>';

        return $selectedForumId;
    }

    /**
     * @param $serverURL
     *
     * @return bool
     */
    public static function mirrorOnline($serverURL)
    {
        $fp = @\fsockopen($serverURL, 80, $errno, $errstr, 5);
        if (!$fp) {
            $isOnline = false;
        } else {
            $isOnline = true;
            \fclose($fp);
        }

        return $isOnline;
    }

    /**
     * truncateHtml can truncate a string up to a number of characters while preserving whole words and HTML tags
     * www.gsdesign.ro/blog/cut-html-string-without-breaking-the-tags
     * www.cakephp.org
     *
     * @param string $text         String to truncate.
     * @param int    $length       Length of returned string, including ellipsis.
     * @param string $ending       Ending to be appended to the trimmed string.
     * @param bool   $exact        If false, $text will not be cut mid-word
     * @param bool   $considerHtml If true, HTML tags would be handled correctly
     *
     * @return string Trimmed string.
     */
    public static function truncateHtml($text, $length = 100, $ending = '...', $exact = false, $considerHtml = true)
    {
        if ($considerHtml) {
            // if the plain text is shorter than the maximum length, return the whole text
            if (mb_strlen(\preg_replace('/<.*?' . '>/', '', $text)) <= $length) {
                return $text;
            }
            // splits all html-tags to scanable lines
            \preg_match_all('/(<.+?' . '>)?([^<>]*)/s', $text, $lines, \PREG_SET_ORDER);
            $total_length = mb_strlen($ending);
            $open_tags    = [];
            $truncate     = '';
            foreach ($lines as $line_matchings) {
                // if there is any html-tag in this line, handle it and add it (uncounted) to the output
                if (!empty($line_matchings[1])) {
                    // if it's an "empty element" with or without xhtml-conform closing slash
                    if (\preg_match('/^<(\s*.+?\/\s*|\s*(img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param)(\s.+?)?)>$/is', $line_matchings[1])) {
                        // do nothing
                        // if tag is a closing tag
                    } elseif (\preg_match('/^<\s*\/([^\s]+?)\s*>$/s', $line_matchings[1], $tag_matchings)) {
                        // delete tag from $open_tags list
                        $pos = \array_search($tag_matchings[1], $open_tags, true);
                        if (false !== $pos) {
                            unset($open_tags[$pos]);
                        }
                        // if tag is an opening tag
                    } elseif (\preg_match('/^<\s*([^\s>!]+).*?' . '>$/s', $line_matchings[1], $tag_matchings)) {
                        // add tag to the beginning of $open_tags list
                        \array_unshift($open_tags, mb_strtolower($tag_matchings[1]));
                    }
                    // add html-tag to $truncate'd text
                    $truncate .= $line_matchings[1];
                }
                // calculate the length of the plain text part of the line; handle entities as one character
                $content_length = mb_strlen(\preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', ' ', $line_matchings[2]));
                if ($total_length + $content_length > $length) {
                    // the number of characters which are left
                    $left            = $length - $total_length;
                    $entities_length = 0;
                    // search for html entities
                    if (\preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', $line_matchings[2], $entities, \PREG_OFFSET_CAPTURE)) {
                        // calculate the real length of all entities in the legal range
                        foreach ($entities[0] as $entity) {
                            if ($left >= $entity[1] + 1 - $entities_length) {
                                $left--;
                                $entities_length += mb_strlen($entity[0]);
                            } else {
                                // no more characters left
                                break;
                            }
                        }
                    }
                    $truncate .= mb_substr($line_matchings[2], 0, $left + $entities_length);
                    // maximum lenght is reached, so get off the loop
                    break;
                }
                $truncate     .= $line_matchings[2];
                $total_length += $content_length;

                // if the maximum length is reached, get off the loop
                if ($total_length >= $length) {
                    break;
                }
            }
        } else {
            if (mb_strlen($text) <= $length) {
                return $text;
            }
            $truncate = mb_substr($text, 0, $length - mb_strlen($ending));
        }
        // if the words shouldn't be cut in the middle...
        if (!$exact) {
            // ...search the last occurance of a space...
            $spacepos = mb_strrpos($truncate, ' ');
            if (null !== $spacepos) {
                // ...and cut the text in this position
                $truncate = mb_substr($truncate, 0, $spacepos);
            }
        }
        // add the defined ending to the text
        $truncate .= $ending;
        if ($considerHtml) {
            // close all unclosed html-tags
            foreach ($open_tags as $tag) {
                $truncate .= '</' . $tag . '>';
            }
        }

        return $truncate;
    }

    // Swish-e support EXPERIMENTAL

    /**
     * php4swish-e 1.1, a web search interface for the swish-e search engine.
     * swish-e is a popular open-source search engine that runs on many platforms.
     * More information on swish-e is available at swish-e.org.
     * This code has been thoroughly tested and is ready for production
     * on any UNIX or Linux server that has the swish-e search engine installed.
     * You are free to modify this code as you see fit.
     * You must specify the path of swish-e ($swish) and
     * the location of the search index file ($swisheIndexFilePath).
     * You will also need to change the default index file served if it is not index.php.
     * If you want the meta description information to display completely,
     * be sure the <meta description... information is on *one* line for each web page.
     * If you wish to allow external search forms to call this script, be sure to set the
     * form's action attribute to whatever you name this file.
     * Suggestions for enhancements are welcome.
     */
    public static function checkSwishe()
    {
        /** @var \XoopsModules\Wfdownloads\Helper $helper */
        $helper = Helper::getInstance();

        // Get the location of the document repository (the index files are located in the root)
        $swisheDocPath = $helper->getConfig('uploaddir');
        // Get the location of the SWISH-E executable
        $swisheExePath = $helper->getConfig('swishe_exe_path');
        // check if _binfilter.sh exists
        if (!\is_file("{$swisheDocPath}/_binfilter.sh")) {
            return false;
        }
        // check if swish-e.conf exists
        if (!\is_file("{$swisheDocPath}/swish-e.conf")) {
            return false;
        }
        // check if swish-e.exe exists
        if (!\is_file("{$swisheExePath}/swish-e.exe")) {
            return false;
        }

        return true;
    }

    public static function swishe_config()
    {
        /** @var \XoopsModules\Wfdownloads\Helper $helper */
        $helper = Helper::getInstance();

        // Get the location of the document repository (the index files are located in the root)
        $swisheDocPath = $helper->getConfig('uploaddir');
        // Create _binfilter.sh
        $file = "{$swisheDocPath}/_binfilter.sh";
        $fp   = \fopen($file, 'wb') || exit("<BR><BR>Unable to open $file");
        \fwrite($fp, "strings \"\$1\" - 2>/dev/null\n");
        \fclose($fp);
        \chmod($file, 0755);
        unset($fp);
        // Create swish-e.conf
        $file = "{$swisheDocPath}/swish-e.conf";
        $fp   = \fopen($file, 'wb') || exit("<BR><BR>Unable to open {$file}");
        // IndexDir [directories or files|URL|external program]
        // IndexDir defines the source of the documents for Swish-e. Swish-e currently supports three file access methods: File system, HTTP (also called spidering), and prog for reading files from an external program.
        \fwrite($fp, "IndexDir {$swisheDocPath}/\n");
        // IndexFile *path*
        // Index file specifies the location of the generated index file. If not specified, Swish-e will create the file index.swish-e in the current directory.
        \fwrite($fp, "IndexFile {$swisheDocPath}/index.swish-e\n");
        // TruncateDocSize *number of characters*
        // TruncateDocSize limits the size of a document while indexing documents and/or using filters. This config directive truncates the numbers of read bytes of a document to the specified size. This means: if a document is larger, read only the specified numbers of bytes of the document.
        //fwrite($fp, "TruncateDocSize 100000\n");
        // IndexReport [0|1|2|3]
        // This is how detailed you want reporting while indexing. You can specify numbers 0 to 3. 0 is totally silent, 3 is the most verbose. The default is 1.
        \fwrite($fp, "IndexReport 1\n");
        // IndexContents [TXT|HTML|XML|TXT2|HTML2|XML2|TXT*|HTML*|XML*] *file extensions*
        // The IndexContents directive assigns one of Swish-e's document parsers to a document, based on the its extension. Swish-e currently knows how to parse TXT, HTML, and XML documents.
        \fwrite($fp, "IndexContents TXT* .dat\n");
        // FileFilter *suffix* "filter-prog" ["filter-options"]
        // This maps file suffix (extension) to a filter program. If filter-prog starts with a directory delimiter (absolute path), Swish-e doesn't use the FilterDir settings, but uses the given filter-prog path directly.
        //fwrite($fp, "FileFilter .dat \"{$swisheDocPath}/_binfilter.sh\" \"'%p'\"\n");
        // IndexOnly *list of file suffixes*
        // This directive specifies the allowable file suffixes (extensions) while indexing. The default is to index all files specified in IndexDir.
        \fwrite($fp, "IndexOnly .dat\n");
        // MinWordLimit *integer*
        // Set the minimum length of an word. Shorter words will not be indexed. The default is 1 (as defined in src/config.h).
        \fwrite($fp, "MinWordLimit 3\n");
        \fclose($fp);
        \chmod($file, 0755);
    }

    /**
     * @param $swisheQueryWords
     *
     * @return array|bool
     */
    public static function searchSwishe($swisheQueryWords)
    {
        /**
         * @param $str
         * @param $num_chars
         * @return string
         */
        function strright($str, $num_chars)
        {
            $str_length = mb_strlen($str);

            return mb_substr($str, $str_length - $num_chars, $str_length);
        }

        /** @var \XoopsModules\Wfdownloads\Helper $helper */
        $helper = Helper::getInstance();

        $ret = false;
        // IN PROGRESS
        // IN PROGRESS
        // IN PROGRESS
        $swisheQueryWords = \stripslashes($swisheQueryWords);
        if (mb_strlen($swisheQueryWords) > 2) {
            //print "<BR>SEARCH!";
            // Get the first word in $swisheQueryWords and use it for the $summary_query.
            // IN PROGRESS
            // IN PROGRESS
            // IN PROGRESS
            $summary_query   = \str_replace('"', ' ', $swisheQueryWords);
            $summary_query   = \trim($summary_query);
            $summary_query_e = \explode(' ', $summary_query);
            $summary_query   = \trim($summary_query_e[0]);
            $summary_query   = \rtrim($summary_query, '*');

            //print "<BR>SQ:  ".$summary_query;

            // Get the location of the document repository (the index files are located in the root)
            $swisheDocPath        = $helper->getConfig('uploaddir');
            $swisheDocPath_strlen = mb_strlen($helper->getConfig('swishe_doc_path'));

            // Get the location of the SWISH-E executable
            $swisheExePath = $helper->getConfig('swishe_exe_path');

            // Get search query
            $swisheQueryWords    = \escapeshellcmd($swisheQueryWords); // escape potentially malicious shell commands
            $swisheQueryWords    = \stripslashes($swisheQueryWords); // remove backslashes from search query
            $swisheQueryWords    = \preg_replace('#("|\')#', '', $swisheQueryWords); // remove quotes from search query
            $swisheCommand       = "{$swisheExePath}/swish-e"; // path of swish-e command
            $swisheIndexFilePath = "{$swisheDocPath}/index.swish-e"; // path of swish-e index file
            $swisheSearchParams  = ''; // Additional search parameters
            $swisheSearchParams  .= '-H1'; // -H1 : print standard result header (default).
            if (0 != $helper->getConfig('swishe_search_limit')) {
                $swisheSearchParams .= " -m{$helper->getConfig('swishe_search_limit')}"; // -m *number* (max results)
            }

            // Opens a pipe to swish-e
            $swishePipeHandler = \popen("{$swisheCommand} -w {$swisheQueryWords} -f {$swisheIndexFilePath} {$swisheSearchParams}", 'r');
            if (!$swishePipeHandler) {
                exit('The search request generated an error...Please try again.');
            }
            \trigger_error("{$swisheCommand} -w {$swisheQueryWords} -f {$swisheIndexFilePath} {$swisheSearchParams}");

            $line_cnt = 1;
            // loop through each line of the pipe result (i.e. swish-e output) to find hit number
            while (false !== ($nline = @\fgets($swishePipeHandler, 1024))) {
                if (4 == $line_cnt) {
                    $num_line = $nline;
                    break; // grab the 4th line, which contains the number of hits returned
                }
                ++$line_cnt;
            }

            // strip out all but the number of hits
            //$num_results = preg_replace('/# Number of hits: /', '', $num_line);

            //$table_header_flag = false;
            //$disp_nff_flag = true;

            $ret = [];
            while (false !== ($line = @\fgets($swishePipeHandler, 4096))) {
                // loop through each line of the pipe result (i.e. swish-e output)
                if (\preg_match("/^(\d+)\s+(\S+)\s+\"(.*)\"\s+(\d+)/", $line)) {
                    // Skip commented-out lines and the last line
                    $line    = \explode('"', $line); // split the string into an array by quotation marks
                    $line[1] = \preg_replace('/[[:blank:]]/', '%%', $line[1]); // replace every space with %% for the phrase in quotation marks
                    $line    = \implode('"', $line); // collapse the array into a string
                    $line    = \preg_replace('/[[:blank:]]/', "\t", $line); // replace every space with a tab

                    [$relevance, $result_url, $result_title, $file_size] = \explode("\t", $line); // split the line into an array by tabs; assign variable names to each column
                    $relevance          /= 10; // format relevance as a percentage for search results
                    $full_path_and_file = $result_url;
                    $result_url         = \trim(mb_substr($result_url, $swisheDocPath_strlen - 1, mb_strlen($result_url)));
                    $file_path          = strright($result_url, mb_strlen($result_url) - 2);
                    //                $file_path2 =       substr($result_url, (strlen($result_url) - (strlen($result_url) - 2)),strlen($result_url));
                    $ret[] = [
                        'relevance'    => $relevance,
                        'result_url'   => $result_url,
                        'result_title' => $result_title,
                        'file_size'    => $file_size,
                        'file_path'    => $file_path,
                    ];
                }
            }
            // close the shell pipe
            \pclose($swishePipeHandler);
        }

        return $ret;
    }

    // Swish-e support EXPERIMENTAL

    // ===========================================================
}
