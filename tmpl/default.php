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

JHTML::stylesheet('mod_jea_emphasis.css', 'modules/mod_jea_emphasis/');
$charset = strtoupper(JFactory::getDocument()->getCharset());
?>

<?php foreach ($rows as $k => $row) : $url = modJeaEmphasisHelper::getPropertyRoute($row) ?>
<dl class="mod-jea-emphasis<?php echo $params->get('moduleclass_sfx') ?> <?php echo $params->get('display_mode', 'vertical')?>">
  <dt>
    <a href="<?php echo $url ?>" title="<?php echo JText::_('COM_JEA_DETAIL') ?>">
    <?php 
    if (empty($row->title)) {
        echo JText::sprintf('COM_JEA_PROPERTY_TYPE_IN_TOWN', 
                             htmlspecialchars($row->type, ENT_COMPAT, $charset), 
                             htmlspecialchars($row->town, ENT_COMPAT, $charset));
    } else {
        echo htmlspecialchars($row->title, ENT_COMPAT, $charset);
    } ?>
    </a>
  </dt>

  <?php if ($params->get('show_details', 1)): ?>
  <dd>
    <?php if ( $params->get('show_thumbnails', 1) && $imgUrl = modJeaEmphasisHelper::getItemImg($row)) : ?>
    <a href="<?php echo $url ?>" title="<?php echo JText::_('COM_JEA_DETAIL') ?>" class="image"><img src="<?php echo $imgUrl ?>"alt="<?php echo JText::_('COM_JEA_DETAIL') ?>" /></a>
    <?php endif ?>
  
    <?php if ($row->slogan): ?>
    <span class="slogan"><?php echo htmlspecialchars($row->slogan, ENT_COMPAT, $charset) ?></span>
    <?php endif ?>

    <?php echo $row->transaction_type == 'RENTING' ? JText::_('COM_JEA_FIELD_PRICE_RENT_LABEL') :  JText::_('COM_JEA_FIELD_PRICE_LABEL') ?> : 
    <strong> <?php echo JHtml::_('utility.formatPrice', (float) $row->price , JText::_('COM_JEA_CONSULT_US') ) ?> </strong>
    <?php if ($row->transaction_type == 'RENTING' && (float)$row->price != 0.0) echo JText::_('COM_JEA_PRICE_PER_FREQUENCY_'. $row->rate_frequency) ?>

    <?php if (!empty($row->living_space)): ?>
    <br /><?php echo  JText::_('COM_JEA_FIELD_LIVING_SPACE_LABEL') ?> : <strong>
    <?php echo JHtml::_('utility.formatSurface', (float) $row->living_space , '-' ) ?>
    </strong>
    <?php endif ?>

    <?php if (!empty($row->land_space)): ?>
    <br /><?php echo  JText::_('COM_JEA_FIELD_LAND_SPACE_LABEL') ?> : <strong>
    <?php echo JHtml::_('utility.formatSurface', (float) $row->land_space , '-' ) ?>
    </strong>
    <?php endif ?>

    <?php if (!empty($row->amenities)) : ?>
    <br /> <strong><?php echo JText::_('COM_JEA_AMENITIES') ?> : </strong>
    <?php echo JHtml::_('amenities.bindList', $row->amenities) ?>
    <?php endif ?>

    <br />
    <a href="<?php echo $url ?>" title="<?php echo JText::_('COM_JEA_DETAIL') ?>"><?php echo JText::_('COM_JEA_DETAIL') ?></a>
  </dd>
  <?php endif ?>
</dl>
<?php endforeach ?>
