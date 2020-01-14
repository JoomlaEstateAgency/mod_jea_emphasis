<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_jea_emphasis
 * @copyright   Copyright (C) 2008 - 2020 PHILIP Sylvain. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

require_once dirname(__FILE__) . '/helper.php';

// Load component language
JFactory::getLanguage()->load('com_jea', JPATH_BASE.'/components/com_jea');

// Declare JEA helpers
JHtml::addIncludePath(JPATH_BASE.'/components/com_jea/helpers/html');

$rows = modJeaEmphasisHelper::getItems($params);

require JModuleHelper::getLayoutPath('mod_jea_emphasis', $params->get('layout', 'default'));

