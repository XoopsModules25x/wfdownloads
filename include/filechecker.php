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

//defined('XOOPS_ROOT_PATH') || die('XOOPS root path not defined');

require_once dirname(dirname(dirname(__DIR__))) . '/include/cp_header.php';
$mydirname = basename(dirname(__DIR__));
xoops_loadLanguage('filechecker', $mydirname);

/**
 * Class FileChecker
 * check status of a directory
 */
class FileChecker
{
    /**
     * @param      $file_path
     * @param null $original_file_path
     * @param      $redirectFile
     *
     * @internal param $path
     * @internal param int $mode
     * @return bool|string
     */
    public static function getFileStatus($file_path, $original_file_path = null, $redirectFile)
    {
        global $pathIcon16;

        if (empty($file_path)) {
            return false;
        }
        if (is_null($redirectFile)) {
            $redirectFile = $_SERVER['PHP_SELF'];
        }
        if (is_null($original_file_path)) {
            if (self::fileExists($file_path)) {
                $path_status = "<img src='$pathIcon16/1.png' >";
                $path_status .= "$file_path (" . _FC_WFDOWNLOADS_AVAILABLE . ") ";
            } else {
                $path_status = "<img src='$pathIcon16/0.png' >";
                $path_status .= "$file_path (" . _FC_WFDOWNLOADS_NOTAVAILABLE . ") ";
            }
        } else {
            if (self::compareFiles($file_path, $original_file_path)) {
                $path_status = "<img src='$pathIcon16/1.png' >";
                $path_status .= "$file_path (" . _FC_WFDOWNLOADS_AVAILABLE . ") ";
            } else {
                $path_status = "<img src='$pathIcon16/0.png' >";
                $path_status .= "$file_path (" . _FC_WFDOWNLOADS_NOTAVAILABLE . ") ";
                $path_status .= "<form action='" . $_SERVER['PHP_SELF'] . "' method='post'>";
                $path_status .= "<input type='hidden' name='op' value='copyfile'>";
                $path_status .= "<input type='hidden' name='file_path' value='$file_path'>";
                $path_status .= "<input type='hidden' name='original_file_path' value='$original_file_path'>";
                $path_status .= "<input type='hidden' name='redirect' value='$redirectFile'>";
                $path_status .= "<button class='submit' onClick='this.form.submit();'>" . _FC_WFDOWNLOADS_CREATETHEFILE . "</button>";
                $path_status .= "</form>";
            }
        }

        return $path_status;
    }

    /**
     * @param   $source_path
     * @param   $destination_path
     *
     * @return bool
     */
    public static function copyFile($source_path, $destination_path)
    {
        $source_path      = str_replace('..', '', $source_path);
        $destination_path = str_replace('..', '', $destination_path);

        return @copy($source_path, $destination_path);
    }

    /**
     * @param   $file1_path
     * @param   $file2_path
     *
     * @return bool
     */
    public static function compareFiles($file1_path, $file2_path)
    {
        if (!self::fileExists($file1_path) || !self::fileExists($file2_path)) {
            return false;
        }
        if (filetype($file1_path) !== filetype($file2_path)) {
            return false;
        }
        if (filesize($file1_path) !== filesize($file2_path)) {
            return false;
        }
        $crc1 = strtoupper(dechex(crc32(file_get_contents($file1_path))));
        $crc2 = strtoupper(dechex(crc32(file_get_contents($file2_path))));
        if ($crc1 !== $crc2) {
            return false;
        }

        return true;
    }

    /**
     * @param   $file_path
     *
     * @return bool
     */
    public static function fileExists($file_path)
    {
        return is_file($file_path);
    }

    /**
     * @param     $target
     * @param int $mode
     *
     * @return bool
     */
    public static function setFilePermissions($target, $mode = 0777)
    {
        $target = str_replace('..', '', $target);

        return @chmod($target, (int) $mode);
    }
}

$op = (isset($_POST['op'])) ? $_POST['op'] : "";
switch ($op) {
    case 'copyfile':
        if (isset($_POST['original_file_path'])) {
            $original_file_path = $_POST['original_file_path'];
        }
        if (isset($_POST['file_path'])) {
            $file_path = $_POST['file_path'];
        }
        if (isset($_POST['redirect'])) {
            $redirect = $_POST['redirect'];
        }
        $msg = (FileChecker::copyFile($original_file_path, $file_path)) ? _FC_WFDOWNLOADS_FILECOPIED : _FC_WFDOWNLOADS_FILENOTCOPIED;
        redirect_header($redirect, 2, $msg . ': ' . $file_path);
        exit();
        break;
}
