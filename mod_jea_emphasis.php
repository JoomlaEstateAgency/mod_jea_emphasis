<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_jea_emphasis
 * @copyright   Copyright (C) 2008 - 2020 PHILIP Sylvain. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

 use Joomla\Registry\Registry;
 use Joomla\CMS\HTML\HTMLHelper;
 use Joomla\CMS\Helper\ModuleHelper;
 use Joomla\CMS\Application\SiteApplication;

defined('_JEXEC') or die;

assert($params instanceof Registry);
assert($app instanceof SiteApplication);

require_once dirname(__FILE__) . '/helper.php';

// Load component language
$app->getLanguage()->load('com_jea', JPATH_BASE.'/components/com_jea');

// Declare JEA helpers
HTMLHelper::addIncludePath(JPATH_BASE.'/components/com_jea/helpers/html');

$rows = modJeaEmphasisHelper::getItems($params);

require ModuleHelper::getLayoutPath('mod_jea_emphasis', $params->get('layout', 'default'));

