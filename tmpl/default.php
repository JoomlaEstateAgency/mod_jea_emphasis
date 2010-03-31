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

// no direct access
defined('_JEXEC') or die('Restricted access');

JHTML::stylesheet('mod_jea_emphasis.css', 'modules/mod_jea_emphasis/');
$document=& JFactory::getDocument();
$charset = strtoupper($document->getCharset());
?>

<?php foreach ($rows as $k => $row) :?>
	<dl class="jea_mod_emphasis_item" >
		<dt class="jea_mod_emphasis_title" >
			<a href="<?php echo modJeaEmphasisHelper::getComponentUrl( $row->id ) ?>" title="<?php echo JText::_('Show detail') ?>" > 
			<strong> 
			<?php echo ucfirst( JText::sprintf('PROPERTY TYPE IN TOWN', htmlentities($row->type, ENT_COMPAT, $charset), htmlentities($row->town, ENT_COMPAT, $charset) ) ) ?>
			</strong> 
			( <?php echo JText::_('Ref' ) . ' : ' . $row->ref ?> )
			</a>
		</dt>
	
		<?php if ( $params->get('display_thumbnails', 0) && $imgUrl = modJeaEmphasisHelper::getItemImg( $row->id ) ) : ?>
		<dt class="image">
		    <a href="<?php echo modJeaEmphasisHelper::getComponentUrl( $row->id ) ?>" title="<?php echo JText::_('Detail') ?>">
		      <img src="<?php echo $imgUrl ?>" alt="<?php echo JText::_('Detail') ?>" />
			</a>
		</dt>
		<?php endif ?>

<?php if ($params->get('display_details', 0)) : ?>	
		<dd>
		<?php if ($row->slogan): ?> 
		<span class="slogan" >
			<strong><?php echo htmlentities($row->slogan) ?></strong><br />
		</span>
		<?php endif ?>
	
		<?php echo $row->is_renting ? JText::_('Renting price') :  JText::_('Selling price') ?> : 
		<strong> <?php echo modJeaEmphasisHelper::formatPrice( floatval($row->price) , JText::_('Consult us') ) ?></strong>
		<br />
		
		<?php 
		if ($row->living_space) {
		    echo  JText::_('Living space') . ' : <strong>' . $row->living_space . ' ' 
		    	  . $params->get('surface_measure') . '</strong>' .PHP_EOL ;
		}?>
		<br />

		<?php
		if ($row->land_space) {
		    echo  JText::_('Land space') . ' : <strong>' . $row->land_space  .' '
		          . $params->get('surface_measure'). '</strong>' .PHP_EOL ;
		}		
		?>
		
		<?php if ( $row->advantages ) : ?>
		    <br /><strong><?php echo JText::_('Advantages') ?> : </strong>
		    <?php echo modJeaEmphasisHelper::getAdvantages( $row->advantages )?>
		<?php endif ?>
		
		<br />
		<a href="<?php echo modJeaEmphasisHelper::getComponentUrl( $row->id ) ?>" title="<?php echo JText::_('Show detail') ?>"> 
		<?php echo JText::_('Detail') ?> </a>
		</dd>
<?php endif ?>
		<dd class="clr"></dd>
	
	</dl>
<?php endforeach ?>