<?php
/**
 *
 * This file is part of com_mapfactory.
 *
 * com_mapfactory is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * com_mapfactory is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with com_mapfactory.  If not, see <http://www.gnu.org/licenses/>.
 *
 * Created on 12 sept. 2012
 * By bozzo
 *
 **/
 
defined('_JEXEC') or die('Accès interdit');
jimport('joomla.application.component.controlleradmin');

class MapFactoryControllerFiles extends JControllerAdmin
{
        /**
         * Proxy for getModel.
         * @since       2.5
         */
        public function getModel($name = 'File', $prefix = 'MapFactoryModel') 
        {
                $model = parent::getModel($name, $prefix, array('ignore_request' => true));
                return $model;
        }
}
?>