<?php
defined ( '_JEXEC' ) or die ();

jimport ( 'joomla.application.categories' );

/**
 * Build the route for the com_mapfactory component
 *
 * @param
 *        	array	An array of URL arguments
 * @return array URL arguments to use to assemble the subsequent URL.
 * @since 1.5
 *       
 */
function MapFactoryBuildRoute(&$query) {
	$segments = array ();
	
	// get a menu item based on Itemid or currently active
	$app = JFactory::getApplication ();
	$menu = $app->getMenu ();
	
	if (isset ( $query ['view'] )) {
		$view = $query ['view'];
		if ($view != 'map') {
			$segments [] = $view;
		}
		unset ( $query ['view'] );
	}
	if (isset ( $query ['type'] )) {
		$segments [] = $query ['type'];
		unset ( $query ['type'] );
	}
	return $segments;
}

/**
 * Parse the segments of a URL.
 *
 * @param
 *        	array	The segments of the URL to parse.
 *        	
 * @return array URL attributes to be used by the application.
 * @since 1.5
 */
function MapFactoryParseRoute($segments) {
	$vars = array ();
	
	// Get the active menu item.
	$app = JFactory::getApplication ();
	$menu = $app->getMenu ();
	$item = $menu->getActive ();
	
	// Count route segments
	$count = count ( $segments );
	
	switch ($count) {
		case 0 :
			$vars ['view'] = 'map';
			break;
		case 1 :
			switch ($segments [0]) {
				case 'ign' :
				case 'osm' :
					$vars ['view'] = 'map';
					$vars ['type'] = $segments [0];
					break;
				default :
					$vars ['view'] = $segments [0];
					break;
			}
			break;
		case 2 :
		default :
			$vars ['view'] = $segments [0];
			$vars ['type'] = $segments [1];
			break;
	}
	return $vars;
}
?>