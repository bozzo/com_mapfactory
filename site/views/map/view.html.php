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
//jimport('joomla.application.component.helper'); // Import component helper library

class MapFactoryViewMap extends JView {

	private $center_lon;
	private $center_lat;
	private $gpApiKey;
	private $mapKind;
	private $osmFile;
	private $osmTitle;
	private $isGeoportail;
	
	private static $MAP_KIND_OSM = "OSM";
	private static $MAP_KIND_GMAP = "GoogleMaps";
	private static $MAP_KIND_GEOP = "Geoportail";

	// Overwriting JView display method
	function display($tpl = null)
	{			
		//$input = JFactory::getApplication()->input;
		//$params = JComponentHelper::getParams(JReqest::getVar('option')); // Get parameter helper
		//$params->get('parameter_name'); // Get an individual parameter
		$app = JFactory::getApplication();
		$params = $app->getParams();

		// Assign data to the view
		$this->msg = $params->get('com_mapfactory_page_title');
		$this->center_lon = $params->get('com_mapfactory_center_lon');
		$this->center_lat = $params->get('com_mapfactory_center_lat');
		$this->gpApiKey = $params->get('com_mapfactory_gp_api_key');
		$this->mapKind = $params->get('com_mapfactory_map_kind');
		
		// track
		$this->osmFile = $params->get('com_mapfactory_track_osm_file');
		$this->osmTitle = $params->get('com_mapfactory_track_osm_title');		
		$this->osmWidth = $params->get('com_mapfactory_track_osm_width');		
		$this->osmOpacity = $params->get('com_mapfactory_track_osm_opacity');		
		$this->osmColor = $params->get('com_mapfactory_track_osm_color');	
		
		$doc =& JFactory::getDocument();
		$doc->addScript("http://code.jquery.com/jquery-latest.js");
		$doc->addScript("/templates/cavaliers/javascript/jquery.fullscreen-min.js");
		
		switch ($this->mapKind)
		{
			case self::$MAP_KIND_GMAP :
				$doc->addScript("http://maps.google.com/maps/api/js?v=3.2&sensor=false");
			case self::$MAP_KIND_OSM :
				$doc->addScript("http://www.openlayers.org/api/OpenLayers.js");		
				$this->isGeoportail = false;		
				break;
			case self::$MAP_KIND_GEOP:
				$doc->addScript("http://api.ign.fr/geoportail/api/js/latest/GeoportalExtended.js");	
				$this->isGeoportail = true;		
				break;
		}
			
		$style = "#MapFactoryMap { min-width: 100px; }";
		$style .= ".MapFactoryMapFullscreen { height: 100%; }";
		$style .= ".MapFactoryMapSmallscreen { height: 600px; }";
		$doc->addStyleDeclaration( $style );
		

		// Display the view
		parent::display($tpl);
	}

	function getGpxLayer($varName)
	{
		$map = "	var layer = new OpenLayers.Layer.Vector(\"Rallye 2011 10km\", {";
		$map .= "			strategies: [new OpenLayers.Strategy.Fixed()],";
		$map .= "			style: {strokeColor: \"yellow\", strokeWidth: 5, strokeOpacity: 0.5},";
		$map .= "			protocol: new OpenLayers.Protocol.HTTP({";
		$map .= "			url: \"media/com_mapfactory/sanstitre.osm\",";
		$map .= "			format: new OpenLayers.Format.GPX()";
		$map .= "		}),";
		$map .= "		projection: new OpenLayers.Projection(\"EPSG:4326\")";
		$map .= "	});";

		$map .= "	$varName.addLayers([layer]);";

		return $map;
	}

	function getOsmLayer($varName)
	{
		$map = "	$varName.addLayers([new OpenLayers.Layer.Vector(\"" . $this->osmTitle . "\", {";
		$map .= "			strategies: [new OpenLayers.Strategy.Fixed()],";
		$map .= "			style: {strokeColor: \"" . $this->osmColor . "\", strokeWidth: " . $this->osmWidth . ", strokeOpacity: " . $this->osmOpacity . "},";
		$map .= "			protocol: new OpenLayers.Protocol.HTTP({";
		$map .= "			url: \"media/com_mapfactory/" . $this->osmFile . "\",";
		$map .= "			format: new OpenLayers.Format.OSM()";
		$map .= "		}),";
		$map .= "		projection: new OpenLayers.Projection(\"EPSG:4326\")";
		$map .= "	})]);";

		//$map .= "	$varName.addLayers([layer]);";

		return $map;
	}

	function getGeoportailMap()
	{
		$map = "function initGeoportailMap() {";

		$map .= "	var options= {";
		$map .= "		mode:'normal',";
		$map .= "		territory:'FXX'";
		//$map = "		proxy:'assets/proxy.php?url='";
		$map .= "	};";

		$map .= "	viewer= new Geoportal.Viewer.Default('MapFactoryMap', OpenLayers.Util.extend(";
		$map .= "		options,";
		//$map .= "		// API keys configuration variable set by <Geoportal.GeoRMHandler.getConfig>";
		//$map .= "		// variable contenant la configuration des clefs API remplie par <Geoportal.GeoRMHandler.getConfig>";
		$map .= "		window.gGEOPORTALRIGHTSMANAGEMENT===undefined? {'apiKey':'" . $this->gpApiKey . "'} : gGEOPORTALRIGHTSMANAGEMENT)";
		$map .= "	);";
		
		$map .= "	if (!viewer) {";
		$map .= "		OpenLayers.Console.error(OpenLayers.i18n('new.instance.failed'));";
		$map .= "		return;";
		$map .= "	}";

		//$map .= "	// ----- Layers";
		$map .= "	viewer.addGeoportalLayers(['ORTHOIMAGERY.ORTHOPHOTOS','GEOGRAPHICALGRIDSYSTEMS.MAPS']);";

		//$map .= "	// ----- Autres";
		$map .= "	viewer.getMap().setCenterAtLonLat(" . $this->center_lat . "," . $this->center_lon . ");";
		
		$map .= "}";

		return $map;
	}

	function getOsmMap()
	{
		// TODO add configuration option to chooce which layer print
		$map = "function initOsmMap() {";
		$map .= "	var options = {";
		$map .= "		controls: [";
		$map .= "			new OpenLayers.Control.Navigation(),";
		$map .= "			new OpenLayers.Control.PanZoomBar(),";
		$map .= "			new OpenLayers.Control.Attribution(),";
		$map .= "			new OpenLayers.Control.LayerSwitcher({'ascending':false})";
		$map .= "		]";
		$map .= "	};";

		$map .= "	map = new OpenLayers.Map(\"MapFactoryMap\",options);";
		$map .= "	var mapnik = new OpenLayers.Layer.OSM();";

		$map .= '	var layerCycleMap = new OpenLayers.Layer.OSM("OpenCycleMap", ["http://a.tile.opencyclemap.org/cycle/${z}/${x}/${y}.png",';
		$map .= '		"http://b.tile.opencyclemap.org/cycle/${z}/${x}/${y}.png",';
		$map .= '		"http://c.tile.opencyclemap.org/cycle/${z}/${x}/${y}.png"]);';

		$map .= '	var layerTransport = new OpenLayers.Layer.OSM("Transport", ["http://a.tile2.opencyclemap.org/transport/${z}/${x}/${y}.png",';
		$map .= '		"http://b.tile2.opencyclemap.org/transport/${z}/${x}/${y}.png",';
		$map .= '		"http://c.tile2.opencyclemap.org/transport/${z}/${x}/${y}.png"]);';

		$map .= '	var layerLandscape = new OpenLayers.Layer.OSM("Landscape", ["http://a.tile3.opencyclemap.org/landscape/${z}/${x}/${y}.png",';
		$map .= '		"http://b.tile3.opencyclemap.org/landscape/${z}/${x}/${y}.png",';
		$map .= '		"http://c.tile3.opencyclemap.org/landscape/${z}/${x}/${y}.png"]);';

		$map .= "	map.addLayer(layerCycleMap);";
		$map .= "	map.addLayer(mapnik);";
		$map .= "	map.addLayer(layerLandscape);";
		$map .= "	map.addLayer(layerTransport);";

		if ($this->osmFile != '-1')
		{
			$map .= $this->getOsmLayer("map");
		}
		
		$map .= "	map.setCenter(new OpenLayers.LonLat(" . $this->center_lat . "," . $this->center_lon . ")";
		$map .= "		.transform(";
		$map .= "		new OpenLayers.Projection(\"EPSG:4326\"),";
		$map .= "		new OpenLayers.Projection(\"EPSG:900913\")";
		$map .= "		), 15";
		$map .= "	);";
		
		$map .= "}";

		return $map;
	}

	function getGoogleMap()
	{
		// TODO add configuration option to chooce which layer print
		$map = "function initGoogleMap() {";
		$map .= "	var options = {";
		$map .= "		controls: [";
		$map .= "			new OpenLayers.Control.Navigation(),";
		$map .= "			new OpenLayers.Control.PanZoomBar(),";
		$map .= "			new OpenLayers.Control.Attribution(),";
		$map .= "			new OpenLayers.Control.LayerSwitcher({'ascending':false})";
		$map .= "		]";
		$map .= "	};";

		$map .= "	map = new OpenLayers.Map(\"MapFactoryMap\",options);";
		
		$map .= "	var gphy = new OpenLayers.Layer.Google(\"Google Physical\",{type: google.maps.MapTypeId.TERRAIN});";
		$map .= "	var gmap = new OpenLayers.Layer.Google(\"Google Streets\",{numZoomLevels: 20});";
		$map .= "	var ghyb = new OpenLayers.Layer.Google(\"Google Hybrid\",{type: google.maps.MapTypeId.HYBRID, numZoomLevels: 20});";
		$map .= "	var gsat = new OpenLayers.Layer.Google(\"Google Satellite\",{type: google.maps.MapTypeId.SATELLITE, numZoomLevels: 22});";

		$map .= "	map.addLayer(gmap);";
		$map .= "	map.addLayer(ghyb);";
		$map .= "	map.addLayer(gsat);";
		$map .= "	map.addLayer(gphy);";

		if ($this->osmFile != '-1')
		{
			$map .= $this->getOsmLayer("map");
		}
		
		$map .= "	map.setCenter(new OpenLayers.LonLat(" . $this->center_lat . "," . $this->center_lon . ")";
		$map .= "		.transform(";
		$map .= "		new OpenLayers.Projection(\"EPSG:4326\"),";
		$map .= "		new OpenLayers.Projection(\"EPSG:900913\")";
		$map .= "		), 15";
		$map .= "	);";
		
		$map .= "}";

		return $map;
	}

	function getMap()
	{		
		$map = "";
		switch ($this->mapKind)
		{
			case self::$MAP_KIND_GMAP :
				$map .= $this->getGoogleMap();
				$map .= "initGoogleMap();";	
				break;
			case self::$MAP_KIND_OSM :
				$map .= $this->getOsmMap();	
				$map .= "initOsmMap();";		
				break;
			case self::$MAP_KIND_GEOP:
				$map .= $this->getGeoportailMap();
				$map .= "Geoportal.GeoRMHandler.getConfig(['" . $this->gpApiKey . "'],null,null, {onContractsComplete: initGeoportailMap});";	
				break;
		}		

		return $map;
	}

	function isGeoportail()
	{			
		return $this->isGeoportail;
	}

	function addFullScreenButton()
	{		
		$map = "$('#OpenLayers.Control.LayerSwitcher_5_layersDiv').append('<div class=\"baseLbl\" id=\"Fullscreen_button\" style=\"\">Fullscreen</div>');";
		$map .= "$('#Fullscreen_button').toggle($(document).fullScreen() != null);";
		$map .= "$('#Fullscreen_button').bind('onclick', function() {";
		$map .= "	$('#Fullscreen_button').fullScreen(($(document).fullScreen() ? 'false' : 'true'));";
		$map .= "});";

		return $map;
	}
}
?>
