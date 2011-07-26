<?php
/**
 * This file is part of Joomla Estate Agency - Joomla! extension for real estate agency
 * 
 * @version     $Id$
 * @package		Jea.module.emphasis
 * @copyright	Copyright (C) 2008 PHILIP Sylvain. All rights reserved.
 * @license		GNU/GPL, see LICENSE.txt
 * Joomla Estate Agency is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses.
 * 
 */

defined('_JEXEC') or die('Restricted access');

require_once (dirname(__FILE__).DS.'helper.php');

$params->set('surface_measure', modJeaEmphasisHelper::getComponentParam('surface_measure'));

$application =& JFactory::getApplication();
// Register params to be retrieved into modJeaEmphasisHelper
$application->set('mod_jea_emphasis_params', $params);

$rows = modJeaEmphasisHelper::getList();

require(JModuleHelper::getLayoutPath('mod_jea_emphasis'));

