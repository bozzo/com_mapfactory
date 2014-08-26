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

// no direct access
defined( '_JEXEC' ) or die( 'Acces interdit' );

jimport( 'joomla.application.component.model' );

class MapFactoryModelMap extends JModel {

	function __construct() {
		parent::__construct();
		$mainframe = JFactory::getApplication();

		// recupere les parametres de pagination de la session utilisateur
		$limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', 20, 'int');
		$limitstart = JRequest::getVar('limitstart', 0, '', 'int');

		// si limit a changé, on ajuste la nouvelle limitstart pour notre liste
		$limitstart = ($limit != 0 ? (floor($limitstart/$limit)*$limit) : 0);
		// on mémorise les nouvelles valeurs dans la session utilisateur
		$this->setState('limitstart', $limitstart);
		$this->setState('limit', $limit);
	}

	/**
	 * Methode pour construire la requete SQL
	 * @return string de la requete SQL
	 */
	function _buildQuery() {
		$query = null;
		$query = "SELECT id, title, modified, hits FROM #__content WHERE state=1";				
		if (JDEBUG) echo "<br />DEBUG : query SQL=".$query;

		return $query;
	}

	/**
	 * Ma Methode pour recuperer mes données
	 * @return object with data
	 */
	function &getDataContents() {
		$this->_data = null;

		// construction de la requete SQL
		$query = $this->_buildQuery();
		// recuperation des données selon la page 'Pagination' ou l'on se trouve
		if ($query) $this->_data = $this->_getList( $query, $this->getState('limitstart'), $this->getState('limit') );

		return $this->_data;
	}

// ------------Code communs aux modeles pour gerer la pagination------------------------
	
	function getTotal() {
		if (empty($this->_total)) {
			$query = $this->_buildQuery();
			// calcul le nombre d'enregistrement
			$this->_total = $this->_getListCount($query);
		}
		return $this->_total;
	}

	function getPagination() {
		// initialise les données de pagination si besoin
		if (empty($this->_pagination)) {
			jimport('joomla.html.pagination');
			$this->_pagination = new JPagination(
				$this->getTotal(), $this->getState('limitstart'), $this->getState('limit')
			);
		}
		return $this->_pagination;
	}
}
?>
