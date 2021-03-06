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
 * Wfdownloads module
 *
 * @copyright       XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @package         wfdownload
 * @since           3.23
 * @author          Xoops Development Team
 */

use XoopsModules\Wfdownloads;

/**
 * Class WfsLists
 * @package XoopsModules\Wfdownloads
 */
class WfsLists
{
    public $value;
    public $selected;
    public $path = 'uploads';
    public $size;
    public $emptyselect;
    public $type;
    public $prefix;
    public $suffix;

    /**
     * $value:
     * Selection:
     * Path:
     * Size:
     * emptyselect:
     * $type: Filter which types of files should be returned
     *        Html
     *        Images
     *        files
     *        dir
     *
     * @param string $path
     * @param null   $value
     * @param string $selected
     * @param int    $size
     * @param int    $emptyselect
     * @param int    $type
     * @param string $prefix
     * @param string $suffix
     */
    public function __construct(
        $path = 'uploads',
        $value = null,
        $selected = '',
        $size = 1,
        $emptyselect = 0,
        $type = 0,
        $prefix = '',
        $suffix = ''
    ) {
        $this->value       = $value;
        $this->selection   = $selected;
        $this->path        = $path;
        $this->size        = (int)$size;
        $this->emptyselect = $emptyselect ? 0 : 1;
        $this->type        = $type;
    }

    /**
     * @param $this_array
     *
     * @return string
     */
    public function getarray($this_array)
    {
        $ret = "<select size='" . $this->size() . "' name='$this->value()'>";
        if ($this->emptyselect) {
            $ret .= "<option value='" . $this->value() . "'>----------------------</option>";
        }
        foreach ($this_array as $content) {
            $opt_selected = '';

            if ($content[0] == $this->isSelected()) {
                $opt_selected = 'selected';
            }
            $ret .= "<option value='" . $content . "' $opt_selected>" . $content . '</option>';
        }
        $ret .= '</select>';

        return $ret;
    }

    /**
     * Private to be called by other parts of the class
     *
     * @param $dirname
     *
     * @return array
     */
    public function getDirListAsArray($dirname)
    {
        $dirlist = [];
        if (\is_dir($dirname) && $handle = \opendir($dirname)) {
            while (false !== ($file = \readdir($handle))) {
                if (!\preg_match('/^[.]{1,2}$/', $file)) {
                    if ('cvs' !== mb_strtolower($file) && \is_dir($dirname . $file)) {
                        $dirlist[$file] = $file;
                    }
                }
            }
            \closedir($handle);

            \reset($dirlist);
        }

        return $dirlist;
    }

    /**
     * @param        $dirname
     * @param string $type
     * @param string $prefix
     * @param int    $noselection
     *
     * @return array
     */
    public static function getListTypeAsArray($dirname, $type = '', $prefix = '', $noselection = 1)
    {
        $filelist = [];
        switch (\trim($type)) {
            case 'images':
                $types = '[.gif|.jpg|.png]';
                if ($noselection) {
                    $filelist[''] = 'Show No Image';
                }
                break;
            case 'html':
                $types = '[.htm|.html|.xhtml|.php|.php3|.phtml|.txt|.tpl]';
                if ($noselection) {
                    $filelist[''] = 'No Selection';
                }
                break;
            default:
                $types = '';
                if ($noselection) {
                    $filelist[''] = 'No Selected File';
                }
                break;
        }

        if ('/' === mb_substr($dirname, -1)) {
            $dirname = mb_substr($dirname, 0, -1);
        }

        if (\is_dir($dirname) && $handle = \opendir($dirname)) {
            while (false !== ($file = \readdir($handle))) {
                if (!\preg_match('/^[.]{1,2}$/', $file) && \preg_match("/$types$/i", $file)
                    && \is_file($dirname . '/' . $file)) {
                    if ('blank.png' === mb_strtolower($file)) {
                        continue;
                    }
                    $file            = $prefix . $file;
                    $filelist[$file] = $file;
                }
            }
            \closedir($handle);
            \asort($filelist);
            \reset($filelist);
        }

        return $filelist;
    }

    public function value()
    {
        return $this->value;
    }

    public function isSelected()
    {
        return $this->selected;
    }

    /**
     * @return string
     */
    public function paths()
    {
        return $this->path;
    }

    /**
     * @return int
     */
    public function size()
    {
        return $this->size;
    }

    /**
     * @return int
     */
    public function emptyselect()
    {
        return $this->emptyselect;
    }

    /**
     * @return int
     */
    public function type()
    {
        return $this->type;
    }

    public function getPrefix()
    {
        return $this->prefix;
    }

    public function getSuffix()
    {
        return $this->suffix;
    }
}
