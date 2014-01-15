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

class modJeaEmphasisHelper
{


    public static function getItems(&$params)
    {
        $orderby = $params->get('order_by', 'created');
        $selection = $params->get('selection', 'featured');
        $limit = (int) $params->get('number_items', 5);

        $db     = JFactory::getDbo();
        $query  = $db->getQuery(true);

        $query->select('p.*');
        $query->from('#__jea_properties AS p');

        // Join properties types
        $query->select('t.value AS `type`');
        $query->join('LEFT', '#__jea_types AS t ON t.id = p.type_id');

        // Join departments
        $query->select('d.value AS department');
        $query->join('LEFT', '#__jea_departments AS d ON d.id = p.department_id');

        // Join towns
        $query->select('town.value AS town');
        $query->join('LEFT', '#__jea_towns AS town ON town.id = p.town_id');

        // Join slogans
        $query->select('s.value AS slogan');
        $query->join('LEFT', '#__jea_slogans AS s ON s.id = p.slogan_id');

        $query->where('p.published=1');
        $query->where('p.language in ('.$db->quote(JFactory::getLanguage()->getTag()).','.$db->quote('*').')');

        // Filter by access level
        $user = JFactory::getUser();
        $groups = implode(',', $user->getAuthorisedViewLevels());
        $query->where('p.access IN ('.$groups.')');

        // Filter by start and end dates.
        $nullDate = $db->Quote($db->getNullDate());
        $nowDate  = $db->Quote(JFactory::getDate()->toSql());

        $query->where('(p.publish_up = '.$nullDate.' OR p.publish_up <= '.$nowDate.')');
        $query->where('(p.publish_down = '.$nullDate.' OR p.publish_down >= '.$nowDate.')');

        switch($selection){
            case 'featured':
                $query->where('p.featured=1');
                break;
            case 'latest':
                $orderby = 'created';
                break;
            case 'random':
                $orderby = 'random';
                break;
        }

        switch($orderby){
            case 'created':
                $query->order('p.created DESC');
                break;
            case 'ordering':
                $query->order('p.ordering ASC');
                break;
            case 'price':
                $query->order('p.price ASC');
                break;
            case 'hits':
                $query->order('p.hits DESC');
                break;
            case 'random':
                $query->order('RAND()');
                break;
        }

        $db->setQuery($query, 0, $limit);
        return $db->loadObjectList();
    }

    public static function getPropertyRoute(&$row)
    {
        static $menuItems;

        if ($menuItems === null){
            $menuItems = array(
                'both'    => 0,
                'renting' => 0,
                'selling' => 0
            );
            $app  = JFactory::getApplication();
            $menu = $app->getMenu();
            $items = $menu->getItems('component', 'com_jea');
            $lang = JFactory::getLanguage()->getTag();
            $user = JFactory::getUser();
            $viewLevels = $user->getAuthorisedViewLevels();

            foreach ($items as $item) {
                if (!in_array($item->access, $viewLevels)) {
                    continue;
                }
                $layout = isset($item->query['layout']) ? $item->query['layout'] : 'default';
                $view = isset($item->query['view']) ? $item->query['view'] : '';

                if ($view == 'properties' && ($item->language == '*' || $item->language == $lang)) {

                    if ($layout == 'search' || $layout == 'searchmap') {
                        if (empty($menuItems['both'])) {
                            $menuItems['both'] = $item->id;
                        }
                    }

                    if ($layout == 'default') {

                        $type = $item->params->get('filter_transaction_type');

                        if ($type == 'SELLING' && empty($menuItems['selling'])) {

                            $menuItems['selling'] = $item->id;

                        } elseif ($type == 'RENTING' && empty($menuItems['renting'])) {

                            $menuItems['renting'] = $item->id;

                        } elseif (empty($menuItems['both'])) {

                            $menuItems['both'] = $item->id;
                        }
                    }
                }
            }
        }


        $slug = $row->alias ? ($row->id . ':' . $row->alias) : $row->id;

        $url = 'index.php?option=com_jea&view=property&id=' . $slug;

        if (!empty($menuItems['selling']) && $row->transaction_type == 'SELLING') {
            $url .= '&Itemid=' . $menuItems['selling'];
        } elseif (!empty($menuItems['renting']) && $row->transaction_type == 'RENTING') {
            $url .= '&Itemid=' . $menuItems['renting'];
        } elseif (!empty($menuItems['both'])) {
            $url .= '&Itemid=' . $menuItems['both'];
        }

        return JRoute::_($url);
    }

    public static function getItemImg(&$row)
    {
        $images = json_decode($row->images);
        $image  = null;

        if (!empty($images) && is_array($images)) {

            $image = array_shift($images);
            $imagePath = JPATH_ROOT . '/images/com_jea';

            if (file_exists($imagePath . '/thumb-min/'.$row->id.'-'.$image->name)) {
                // If the thumbnail already exists, display it directly
                $baseURL = JURI::root(true);
                return $baseURL.'/images/com_jea/thumb-min/'.$row->id.'-'.$image->name;

            } elseif (file_exists($imagePath . '/images/' . $row->id.'/'.$image->name)) {
                // If the thumbnail doesn't exist, generate it and output it on the fly
                $url = 'index.php?option=com_jea&task=thumbnail.create&size=min&id='
                . $row->id .'&image='.$image->name;

                return JRoute::_($url);
            }
        }

        return '';
    }


}