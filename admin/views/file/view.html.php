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

class MapFactoryViewFile extends JView {

	/**
	 * display method of Hello view
	 * @return void
	 */
	public function display($tpl = null)
	{
		// get the Data
		$form = $this->get('Form');
		$item = $this->get('Item');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
		// Assign the Data
		$this->form = $form;
		$this->item = $item;

		// Set the toolbar
		$this->addToolBar();

		// Display the template
		parent::display($tpl);
	}

	/**
	 * Setting the toolbar
	 */
	protected function addToolBar()
	{
		$input = JFactory::getApplication()->input;
		$input->set('hidemainmenu', true);
		$isNew = ($this->item->id == 0);
		JToolBarHelper::title($isNew ? JText::_('COM_MAPFACTORY_MANAGER_FILE_NEW') : JText::_('COM_MAPFACTORY_MANAGER_FILE_EDIT'));
		JToolBarHelper::save('file.save');
		JToolBarHelper::cancel('file.cancel', $isNew ? 'JTOOLBAR_CANCEL' : 'JTOOLBAR_CLOSE');
	}
}
?>
