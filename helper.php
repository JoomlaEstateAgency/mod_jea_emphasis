<?php
/**
 * This file is part of Joomla Estate Agency - Joomla! extension for real estate agency
 *
 * @version		0.1 2008-03-30
 * @package		Jea.module.emphasis
 * @copyright	Copyright (C) 2008 PHILIP Sylvain. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla Estate Agency is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses.
 *
 */
defined('_JEXEC') or die('Restricted access');

class modJeaEmphasisHelper
{

	function getComponentParam($param, $default='')
	{
		static $instance;

		if ( !is_object($instance) ) {
			jimport('joomla.application.component.helper');
				
			$instance =& JComponentHelper::getParams('com_jea');
			
			//Sets a default values if not already assigned
			
			// fix bug #10973] Warning: cannot yet handle MBCS in html_entity_decode()!
			if( (int) PHP_VERSION < 5){	    	
		    	$surface_measure = html_entity_decode( 'm&sup2;', ENT_COMPAT, 'UTF-8' );
		    	$currency_symbol = html_entity_decode( '&euro;', ENT_COMPAT, 'UTF-8' ) ;
		    	$thousands_separator = html_entity_decode( '&nbsp;', ENT_COMPAT, 'UTF-8' );
		    } else {
		    	$surface_measure = utf8_encode(html_entity_decode( 'm&sup2;', ENT_COMPAT, 'ISO-8859-15' ));
		    	$currency_symbol = utf8_encode(html_entity_decode( '&euro;', ENT_COMPAT, 'ISO-8859-15' )) ;
		    	$thousands_separator = utf8_encode(html_entity_decode( '&nbsp;', ENT_COMPAT, 'ISO-8859-15' ));
		    }
			
			$instance->def('surface_measure', $surface_measure);
			$instance->def('currency_symbol', $currency_symbol);
			$instance->def('thousands_separator', $thousands_separator);
			$instance->def('decimals_separator', ',');
			$instance->def('symbol_place', 1);
		}

		return $instance->get($param, $default) ;
	}

	function getList($params)
	{
		$orderby = $params->get('order_by', '');

		$fields = 'tp.id, tp.ref, tp.is_renting ,tp.price AS price, tp.living_space, tp.land_space, tp.advantages, '
		        .  'tp.ordering AS ordering, td.value AS `department`, ts.value AS `slogan`, tt.value AS `type`, '
		        .  'tto.value AS `town`, tp.date_insert AS date_insert' ;

		$select = 'SELECT ' . $fields .' FROM #__jea_properties AS tp'. PHP_EOL 
		        . 'LEFT JOIN #__jea_departments AS td ON td.id = tp.department_id'. PHP_EOL
		        . 'LEFT JOIN #__jea_slogans AS ts ON ts.id = tp.slogan_id'. PHP_EOL
		        . 'LEFT JOIN #__jea_types AS tt ON tt.id = tp.type_id'. PHP_EOL
		        . 'LEFT JOIN #__jea_towns AS tto ON tto.id = tp.town_id'. PHP_EOL ;
		        
		$sql = $select .' WHERE tp.emphasis=1 AND tp.published=1 ORDER BY '. $orderby ;

		$db =& JFactory::getDBO();
		$db->setQuery($sql, 0, $params->get('number_to_display') );
		$rows = $db->loadObjectList();
		
		if ( $db->getErrorNum() ) {
			JError::raiseWarning( 200, $db->getErrorMsg() );
		}

		return $rows;
	}
	
	function getComponentUrl ( $id=0 )
	{
		$url = 'index.php?option=com_jea&view=default&Itemid=' . JRequest::getInt('Itemid', 0 ) ;
	  
		if ( $id ) {
			$url .= '&id=' . intval( $id ) ;
		}
	  
		return JRoute::_( $url );
	}
	
	function getItemImg( $id=0 )
	{
		if ( is_file( JPATH_ROOT.DS.'components'.DS.'com_jea'.DS.'upload'.DS.'properties'.DS.$id.DS.'min.jpg' ) ){
			
			return JURI::root().'components/com_jea/upload/properties/'.$id.'/min.jpg' ;
		}
		
		return false;
	}


	function formatPrice ( $price , $default="" )
	{
		if ( !empty($price) ) {
				
			//decode charset before using number_format
			$charset = 'UTF-8';
				
			$decimal_separator = modJeaEmphasisHelper::getComponentParam('decimals_separator' , ',');
			$thousands_separator = modJeaEmphasisHelper::getComponentParam('thousands_separator', ' ');
			$currency_symbol = modJeaEmphasisHelper::getComponentParam('currency_symbol', '&euro;');
			$symbol_place = modJeaEmphasisHelper::getComponentParam('symbol_place', 1);
				
			jimport('joomla.utilities.string');
			$decimal_separator   = JString::transcode( $decimal_separator , $charset, 'ISO-8859-1' );
			$thousands_separator = JString::transcode( $thousands_separator , $charset, 'ISO-8859-1' );

			$price = number_format( $price, 0, $decimal_separator, $thousands_separator ) ;

			//re-encode
			$price = JString::transcode( $price, 'ISO-8859-1', $charset );

			//is currency symbol before or after price?
			if ( $symbol_place == 1 ) {
					
				return htmlentities( $price .' '. $currency_symbol, ENT_COMPAT, $charset );

			} else {
					
				return htmlentities( $currency_symbol .' '. $price, ENT_COMPAT, $charset );
			}

		} else {

			return $default ;
		}
	}
	
	function getAdvantages( $advantages=""  )
	{
	  
		if ( !empty($advantages) ) {
			$advantages = explode( '-' , $advantages );
		}
		
		$sql = "SELECT `id`,`value` FROM #__jea_advantages" ;

		$db = & JFactory::getDBO();
		$db->setQuery($sql);
		$rows = $db->loadObjectList();
		
		$temp = array();

		foreach ( $rows as $k=> $row ) {
			
			if ( in_array($row->id, $advantages) ) {	
				$temp[] =  $row->value;
			}
		}
		
		return implode(',', $temp);
	}


}