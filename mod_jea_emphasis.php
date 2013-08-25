<?php
/**
 * @version     $Id$
 * @package     Joomla.Site
 * @subpackage  mod_jea_emphasiis
 * @copyright   Copyright (C) 2012 PHILIP Sylvain. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die();

require_once (dirname(__FILE__) . '/helper.php');

// Load component language
JFactory::getLanguage()->load('com_jea', JPATH_BASE.'/components/com_jea');

// Declare JEA helpers
JHtml::addIncludePath(JPATH_BASE.'/components/com_jea/helpers/html');

$rows = modJeaEmphasisHelper::getItems($params);

require(JModuleHelper::getLayoutPath('mod_jea_emphasis'));

