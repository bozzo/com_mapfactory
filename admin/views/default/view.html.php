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

defined('_JEXEC') or die('Acces interdit');
jimport( 'joomla.application.component.view');

class MapFactoryViewDefault extends JView {
	
	// Overwriting JView display method
	function display($tpl = null)
	{		
		// Set the toolbar
		$this->addToolBar();
		
		// Display the view
		parent::display($tpl);
	}
	
	/**
	 * Setting the toolbar
	 */
	protected function addToolBar()
	{
		JToolBarHelper::title(JText::_('COM_MAPFACTORY_DEFAULT_TITLE'));
		JToolBarHelper::media_manager('com_mapfactory/osm',JText::_('COM_MAPFACTORY_TOOLBAR_UPLOAD_BUTTON'));
	}
}
?>
