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

// import joomla's filesystem classes
jimport('joomla.filesystem.folder');

/**
 * Script file of HelloWorld component
 */
class com_mapfactoryInstallerScript
{
	/**
	 * method to install the component
	 *
	 * @return void
	 */
	function install($parent)
	{
		// create a folder inside your images folder
		if(JFolder::create(JPATH_ROOT.DS.'images'.DS.'com_mapfactory'.DS.'osm')) {
			echo "OSM Folder created successfully";
		} else {
			echo "Unable to create OSM folder";
		}
		// create a folder inside your images folder
		if(JFolder::create(JPATH_ROOT.DS.'images'.DS.'com_mapfactory'.DS.'gpx')) {
			echo "GPX Folder created successfully";
		} else {
			echo "Unable to create GPX folder";
		}
		// $parent is the class calling this method
		//$parent->getParent()->setRedirectURL('index.php?option=com_helloworld');
	}

	/**
	 * method to uninstall the component
	 *
	 * @return void
	 */
	function uninstall($parent)
	{
		// $parent is the class calling this method
		//echo '<p>' . JText::_('COM_HELLOWORLD_UNINSTALL_TEXT') . '</p>';
	}

	/**
	 * method to update the component
	 *
	 * @return void
	 */
	function update($parent)
	{
		// create a folder inside your images folder
		if(JFolder::create(JPATH_ROOT.DS.'images'.DS.'com_mapfactory'.DS.'osm')) {
			echo "OSM Folder created successfully";
		} else {
			echo "Unable to create OSM folder";
		}
		// create a folder inside your images folder
		if(JFolder::create(JPATH_ROOT.DS.'images'.DS.'com_mapfactory'.DS.'gpx')) {
			echo "GPX Folder created successfully";
		} else {
			echo "Unable to create GPX folder";
		}
		// $parent is the class calling this method
		//echo '<p>' . JText::sprintf('COM_HELLOWORLD_UPDATE_TEXT', $parent->get('manifest')->version) . '</p>';
	}

	/**
	 * method to run before an install/update/uninstall method
	 *
	 * @return void
	 */
	function preflight($type, $parent)
	{
		// $parent is the class calling this method
		// $type is the type of change (install, update or discover_install)
		//echo '<p>' . JText::_('COM_HELLOWORLD_PREFLIGHT_' . $type . '_TEXT') . '</p>';
	}

	/**
	 * method to run after an install/update/uninstall method
	 *
	 * @return void
	 */
	function postflight($type, $parent)
	{
		// $parent is the class calling this method
		// $type is the type of change (install, update or discover_install)
		//echo '<p>' . JText::_('COM_HELLOWORLD_POSTFLIGHT_' . $type . '_TEXT') . '</p>';
	}
}

?>