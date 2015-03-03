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
 * WfdownloadsMulticolumnsThemeForm Class
 *
 * @copyright   The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license     http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author      lucio <lucio.rota@gmail.com>
 * @package     Wfdownloads
 * @since       3.23
 * @version     $Id:$
 */

defined('XOOPS_ROOT_PATH') || die('XOOPS root path not defined');

xoops_load('XoopsForm');

/**
 * Form that will output formatted as a HTML table
 *
 * No styles and no JavaScript to check for required fields.
 */
class WfdownloadsMulticolumnsThemeForm extends XoopsForm
{
    /**
     * number of two-dimensional array (matrix) rows
     *
     * @var int
     */
    var $_rows = 0;

    /**
     * return number of rows
     *
     * @return int
     */
    public function getCountRows()
    {
        return $this->_rows;
    }

    /**
     * number of two-dimensional array (matrix) columns
     *
     * @var int
     */
    var $_columns = 0;

    /**
     * return number of columns
     *
     * @return int
     */
    public function getCountColumns()
    {
        return $this->_columns;
    }

    /**
     * two-dimensional array (matrix) of {@link XoopsFormElement} objects
     *
     * @var array
     */
    var $_elements = array();

    /**
     * mono-dimensional array of column titles
     *
     * @var array
     */
    var $_titles = array();

    /**
     * Add an element to the form
     *
     * @param object $formElement
     * @param bool   $required    is this a "required" element?
     * @param int    $row         two-dimensional array (matrix) row (0 first key)
     * @param int    $column      two-dimensional array (matrix) column (0 first key)
     *
     * @internal param $object $ &$formElement    reference to a {@link XoopsFormElement}
     */
    public function addElement($formElement, $required = false, $row = null, $column = null)
    {
        if (is_null($row)) {
            $row = $this->_rows;
        }
        if (is_null($column)) {
            $column = ($this->_columns == 0) ? $this->_columns : $this->_columns - 1;
        } // add new element as new row of the last column
        if (is_string($formElement)) {
            $this->_elements[$row][$column] = $formElement;
            if ($row >= $this->_rows) {
                $this->_rows = $row + 1;
            }
            if ($column >= $this->_columns) {
                $this->_columns = $column + 1;
            }
        } elseif (is_subclass_of($formElement, 'xoopsformelement')) {
            $this->_elements[$row][$column] = &$formElement;
            if ($row >= $this->_rows) {
                $this->_rows = $row + 1;
            }
            if ($column >= $this->_columns) {
                $this->_columns = $column + 1;
            }
            if (!$formElement->isContainer()) {
                if ($required) {
                    $formElement->_required = true;
                    $this->_required[]      = &$formElement;
                }
            } else {
                $required_elements = &$formElement->getRequired();
                $count             = count($required_elements);
                for ($i = 0; $i < $count; ++$i) {
                    $this->_required[] = &$required_elements[$i];
                }
            }
        }
    }

    /**
     * @param $elements
     */
    public function addRow($elements)
    {
        foreach ($elements as $key => $element) {
            $this->addElement($element, false, $this->_rows, $key);
        }
    }

    /**
     * @param $elements
     */
    public function addColumn($elements)
    {
        foreach ($elements as $key => $element) {
            $this->addElement($element, false, $key, $this->_columns);
        }
    }

    /**
     * @param $form
     */
    public function addForm($form)
    {
        foreach ($form->getElements() as $element) {
            $this->addElement($element, $element->isRequired(), $key, $this->_columns);
        }
    }

    /**
     * @param $titles
     */
    public function setTitles($titles)
    {
        if (is_array($titles)) {
            foreach ($titles as $key => $title) {
                $this->_titles[$key] = $title;
            }
        } else {
            $this->_title = $titles;
        }
    }

    /**
     * create HTML to output the form as a theme-enabled table with validation.
     *
     * YOU SHOULD AVOID TO USE THE FOLLOWING Nocolspan METHOD, IT WILL BE REMOVED
     *
     * To use the noColspan simply use the following example:
     *
     * $colspan = new XoopsFormDhtmlTextArea( '', 'key', $value, '100%', '100%' );
     * $colspan->setNocolspan();
     * $form->addElement( $colspan );
     *
     * @return string
     */
    function render()
    {
        $ele_name = $this->getName();
        $ret      = "";
        $ret .= "<form name='{$ele_name}' id='{$ele_name}' action='{$this->getAction()}' method='{$this->getMethod()}' onsubmit='return xoopsFormValidate_{$ele_name}();' {$this->getExtra()} >"
            . NWLINE;
        $ret .= "<table width='100%' class='outer' cellspacing='1'>" . NWLINE;
        $ret .= "<tr><th colspan='{$this->_columns}'>{$this->getTitle()}</th></tr>" . NWLINE;
        if (count($this->_titles) > 0) {
            $ret .= "<tr>";
            for ($column = 0; $column < $this->_columns; ++$column) {
                $ret .= "<th>";
                $ret .= (isset($this->_titles[$column])) ? "{$this->_titles[$column]}" : "&nbsp;";
                $ret .= "</th>" . NWLINE;
            }
            $ret .= "</tr>";
        }
        $hidden = '';
        $class  = 'even';
        for ($row = 0; $row < $this->_rows; ++$row) {
            $ret .= "<tr>";
            for ($column = 0; $column < $this->_columns; ++$column) {
                $ret .= "<td class='{$class}'>";
                if (isset($this->_elements[$row][$column])) {
                    $ele = $this->_elements[$row][$column];
                } else {
                    $ele = '&nbsp;';
                }
                if (!is_object($ele)) {
                    $ret .= $ele;
                } elseif (!$ele->isHidden()) {
                    if (!$ele->getNocolspan()) {
                        //$ret .= '<tr valign="top" align="left"><td class="head">';
                        if (($caption = $ele->getCaption()) != '') {
                            $ret .= "<div class='xoops-form-element-caption" . ($ele->isRequired() ? '-required' : '') . "'>";
                            $ret .= "<span class='caption-text'>{$caption}</span>";
                            $ret .= "<span class='caption-marker'>*</span>";
                            $ret .= "</div>";
                        }
                        if (($desc = $ele->getDescription()) != '') {
                            $ret .= "<div class='xoops-form-element-help'>{$desc}</div>";
                        }
                        //$ret .= '</td><td class="' . $class . '">';
                        $ret .= $ele->render();
                        //$ret .= '</td></tr>' . NWLINE;
                    } else {
                        //$ret .= '<tr valign="top" align="left"><td class="head" colspan="2">';
                        if (($caption = $ele->getCaption()) != '') {
                            $ret .= "<div class='xoops-form-element-caption" . ($ele->isRequired() ? '-required' : '') . "'>";
                            $ret .= "<span class='caption-text'>{$caption}</span>";
                            $ret .= "<span class='caption-marker'>*</span>";
                            $ret .= "</div>";
                        }
                        //$ret .= '</td></tr>' . NWLINE;
                        //$ret .= '<tr valign="top" align="left"><td class="' . $class . '" colspan="' . $this->_columns . '">';
                        $ret .= $ele->render();
                        //$ret .= '</td></tr>' . NWLINE;
                    }
                } else {
                    $hidden .= $ele->render();
                }
                $ret .= "</td>";
            }
            $ret .= "</tr>";
        }
        $ret .= "</table>" . NWLINE;
        $ret .= "{$hidden}" . NWLINE;
        $ret .= "</form>" . NWLINE;
        $ret .= $this->renderValidationJS(true);

        return $ret;
    }
}
