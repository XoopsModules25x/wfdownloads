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
 * WfdownloadsObjectTree
 *
 * @copyright   The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license     http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author      lucio <lucio.rota@gmail.com>
 * @package     Wfdownloads
 * @since       3.23
 * @version     $Id:$
 */

defined('XOOPS_ROOT_PATH') || die('XOOPS root path not defined');

include_once XOOPS_ROOT_PATH . '/class/tree.php';

/**
 * Form element that ...
 */
class WfdownloadsObjectTree extends XoopsObjectTree
{
    /**
     * Make options for a select box from
     *
     * @param string $fieldName    Name of the member variable from the node objects that should be used as the title for the options.
     * @param int    $key          ID of the object to display as the root of select options
     * @param string $optionsArray (reference to a string when called from outside) Result from previous recursions
     * @param string $prefix_orig  String to indent items at deeper levels
     * @param string $prefix_curr  String to indent the current item
     *
     * @return string
    @access private
     */
    function _makeSelBoxOptionsArray($fieldName, $key, &$optionsArray, $prefix_orig, $prefix_curr = '')
    {
        if ($key > 0) {
            $value                = $this->_tree[$key]['obj']->getVar($this->_myId);
            $optionsArray[$value] = $prefix_curr . $this->_tree[$key]['obj']->getVar($fieldName);
            $prefix_curr .= $prefix_orig;
        }
        if (isset($this->_tree[$key]['child']) && !empty($this->_tree[$key]['child'])) {
            foreach ($this->_tree[$key]['child'] as $childkey) {
                $this->_makeSelBoxOptionsArray($fieldName, $childkey, $optionsArray, $prefix_orig, $prefix_curr);
            }
        }

        return $optionsArray;
    }

    /**
     * Make a select box with options from the tree
     *
     * @param string  $fieldName      Name of the member variable from the node objects that should be used as the title for the options.
     * @param string  $prefix         String to indent deeper levels
     * @param bool    $addEmptyOption Set TRUE to add an empty option with value "0" at the top of the hierarchy
     * @param integer $key            ID of the object to display as the root of select options
     *
     * @return array $optionsArray   Associative array of value->name pairs, useful for {@link XoopsFormSelect}->addOptionArray method
     */
    function makeSelBoxOptionsArray($fieldName, $prefix = '-', $addEmptyOption = false, $key = 0)
    {
        $optionsArray = array();
        if ($addEmptyOption) {
            $optionsArray[0] = '';
        }

        return $this->_makeSelBoxOptionsArray($fieldName, $key, $optionsArray, $prefix);
    }

}
