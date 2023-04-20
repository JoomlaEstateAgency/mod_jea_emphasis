<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_jea_emphasis
 * @copyright   Copyright (C) 2008 - 2020 PHILIP Sylvain. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Joomla\CMS\Language\Text;
use Joomla\Registry\Registry;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Application\SiteApplication;

defined('_JEXEC') or die;

assert($params instanceof Registry);
assert($app instanceof SiteApplication);

/**
 * @var $rows stdClass[] The list of properties
 */

HTMLHelper::stylesheet('mod_jea_emphasis/mod_jea_emphasis.css', ['relative' => true]);
$charset = strtoupper($app->getDocument()->getCharset());

?>

<div class="mod-jea-emphasis-container">
<?php foreach ($rows as $row) : $url = modJeaEmphasisHelper::getPropertyRoute($row) ?>
	<dl class="mod-jea-emphasis<?php echo $params->get('moduleclass_sfx') ?> <?php echo $params->get('display_mode', 'vertical')?>">
		<dt>
			<a href="<?php echo $url ?>" title="<?php echo Text::_('COM_JEA_DETAIL') ?>">
			<?php
			echo empty($row->title) ?
			Text::sprintf(
				'COM_JEA_PROPERTY_TYPE_IN_TOWN',
				htmlspecialchars($row->type, ENT_COMPAT, $charset),
				htmlspecialchars($row->town, ENT_COMPAT, $charset)
			)
			:
			htmlspecialchars($row->title, ENT_COMPAT, $charset);
			?>
			</a>
		</dt>
		<?php if ($params->get('show_details', 1)): ?>
		<dd>
			<?php if ( $params->get('show_thumbnails', 1) && $imgUrl = modJeaEmphasisHelper::getItemImg($row)) : ?>
			<a href="<?php echo $url ?>" title="<?php echo Text::_('COM_JEA_DETAIL') ?>" class="image">
				<img src="<?php echo $imgUrl ?>" alt="<?php echo Text::_('COM_JEA_DETAIL') ?>" />
			</a>
			<?php endif ?>

			<?php if ($row->slogan): ?>
			<span class="slogan"><?php echo htmlspecialchars($row->slogan, ENT_COMPAT, $charset) ?></span>
			<?php endif ?>

			<?php echo $row->transaction_type == 'RENTING' ? Text::_('COM_JEA_FIELD_PRICE_RENT_LABEL') :  Text::_('COM_JEA_FIELD_PRICE_LABEL') ?> :
			<strong><?php echo HTMLHelper::_('utility.formatPrice', (float) $row->price , Text::_('COM_JEA_CONSULT_US') ) ?></strong>
			<?php if ($row->transaction_type == 'RENTING' && (float) $row->price != 0.0)
				echo Text::_('COM_JEA_PRICE_PER_FREQUENCY_'. $row->rate_frequency)
			?>
			<?php if (!empty($row->living_space)): ?>
			<br /><?php echo  Text::_('COM_JEA_FIELD_LIVING_SPACE_LABEL') ?> :
			<strong><?php echo HTMLHelper::_('utility.formatSurface', (float) $row->living_space , '-' ) ?></strong>
			<?php endif ?>

			<?php if (!empty($row->land_space)): ?>
			<br /><?php echo  Text::_('COM_JEA_FIELD_LAND_SPACE_LABEL') ?> :
			<strong><?php echo HTMLHelper::_('utility.formatSurface', (float) $row->land_space , '-' ) ?></strong>
			<?php endif ?>

			<?php if (!empty($row->amenities)) : ?>
			<br /><strong><?php echo Text::_('COM_JEA_AMENITIES') ?> :</strong>
			<?php echo HTMLHelper::_('amenities.bindList', $row->amenities) ?>
			<?php endif ?>

			<br /><a href="<?php echo $url ?>" title="<?php echo Text::_('COM_JEA_DETAIL') ?>"><?php echo Text::_('COM_JEA_DETAIL') ?></a>
		</dd>
		<?php endif ?>
	</dl>
<?php endforeach ?>
</div>
