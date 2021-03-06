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
	private $zoomLevel;
	private $gpApiKey;
	private $mapKind;
	private $type;
	//private $osmFile;
	private $osmTitle;
	private $isGeoportail;
	
	private static $MAP_KIND_OSM = "OSM";
	private static $MAP_KIND_GMAP = "GoogleMaps";
	private static $MAP_KIND_GEOP = "Geoportail";

	// Overwriting JView display method
	function display($tpl = null)
	{			
		$app = JFactory::getApplication();
		$params = $app->getParams();
		$jinput = $app->input;

		// Assign data to the view
		$this->msg = $params->get('com_mapfactory_page_title');
		$this->center_lon = $params->get('com_mapfactory_center_lon');
		$this->center_lat = $params->get('com_mapfactory_center_lat');
		$this->mapKind = $params->get('com_mapfactory_map_kind');
		$this->zoomLevel = $params->get('com_mapfactory_map_zoom_level');
		
		// Geoportail
		$this->gpApiKey = $params->get('com_mapfactory_gp_api_key');
		
		// osm
		$this->osmLayers = $params->get('com_mapfactory_osm_layers');
		
		// track
		$this->osmFile = $params->get('com_mapfactory_track_osm_file');
		$this->osmTitle = $params->get('com_mapfactory_track_osm_title');		
		$this->osmWidth = $params->get('com_mapfactory_track_osm_width');		
		$this->osmOpacity = $params->get('com_mapfactory_track_osm_opacity');		
		$this->osmColor = $params->get('com_mapfactory_track_osm_color');	

		$this->type = $jinput->get('type', 'none', 'STRING');
		if ($this->type != null && $this->type == "osm")
		{
			$this->mapKind=self::$MAP_KIND_OSM;
			if (! in_array("OSM",$this->osmLayers))
			{
				$this->osmLayers[]="OSM";
			}
		}
		elseif ($this->type != null && $this->type == "ign")
		{
			$this->mapKind=self::$MAP_KIND_GEOP;
		}
		
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

		$doc->addStyleSheet( "media/com_mapfactory/css/com_mapfactory_default.css" );		

		// Display the view
		parent::display($tpl);
	}
	
	function getOsmLayer($varName, $file, $cpt) {
		$map = "        $varName.addLayers([new OpenLayers.Layer.Vector(\"" . substr($file, 0, -4) . "\", {";
		$map .= "                       strategies: [new OpenLayers.Strategy.Fixed()],";
		$map .= "                       style: {strokeColor: \"" . $this->osmColor . "\", strokeWidth: " . $this->osmWidth . ", strokeOpacity: " . $this->osmOpacity . "},";
		$map .= "                       protocol: new OpenLayers.Protocol.HTTP({";
		$map .= "                       url: \"/images/gpx/" . $file . "\",";
		
		if (preg_match ( "/\.osm/", $file ))
		{
			$map .= "                       format: new OpenLayers.Format.OSM()";
		}
		elseif (preg_match ( "/\.gpx/", $file ))
		{
			$map .= "                       format: new OpenLayers.Format.GPX()";
		}
		$map .= "               }),";
		$map .= "               projection: new OpenLayers.Projection(\"EPSG:4326\")";
		$map .= "       })]);";
		
		return $map;
	}
	
	function getOsmLayerGP($varName, $file, $cpt) {
		if (preg_match ( "/\.osm/i", $file ))
		{
			$map = "$varName.getMap().addLayer(\"OSM\",";
		} 
		elseif (preg_match ( "/\.gpx/i", $file ))
		{
			$map = "$varName.getMap().addLayer(\"GPX\",";
		}
		
		$map .= "\"" . substr($file, 0, -4) . "\",";
		$map .= "\"/images/gpx/" . $file . "\",";
		$map .= "{";
		$map .= "       visibility:true,";
		$map .= "       styleMap:OpenLayers.StyleMap({";
		$map .= "               \"default\": new OpenLayers.Style(";
		$map .= "                       OpenLayers.Util.applyDefaults({";
		$map .= "                               fillColor: \"" . $this->osmColor . "\", strokeColor: \"" . $this->osmColor . "\", strokeWidth: " . $this->osmWidth . ", strokeOpacity: " . $this->osmOpacity;
		$map .= "                       },OpenLayers.Feature.Vector.style[\"default\"]))";
		$map .= "})});";
	
		
		/*new OpenLayers.StyleMap({
			"default": new OpenLayers.Style(
					OpenLayers.Util.applyDefaults({
				fillColor: "#FFFF00",
				fillOpacity: 0.75,
				strokeColor: "#FF9900",
				strokeWidth: 2,
				graphicZIndex: "${zIndex}",
				graphicName: "triangle",
				pointRadius: 8,
				//see context object below
				label:"${getName}",
				labelAlign: "rb",
				labelXOffset: -20,
				labelYOffset: -20,
				labelBackgroundColor: "#FFFF00",
				labelBorderColor: "black",
				labelBorderSize: "1px",
				fontColor: "black",
				fontWeight: "bold",
				fontSize: "12px",
				fontFamily: "Courier New, monospace"
		},OpenLayers.Feature.Vector.style["default"]),{
			/**
			 * The context object contains a function which is referenced in the symbolizer
			 * This function will be called with the feature as an argument when using the appropriate style("temporary")
			 *
			 * L'objet contexte contient une fonction appelée dans le symboliseur
			 * Cette fonction qui prend comme argument feature ,sera appelée lors de l'utilisation du style "temporary"
			 *//*
			context:{
				getName: function(f) {
					if (f.attributes['typeName']=='wpt') {
						return f.attributes['name'];
					}
					return '';
				}
			}
		}),
		"select": new OpenLayers.Style(
				OpenLayers.Util.applyDefaults({
			fillColor: "#FF9900",
			fillOpacity: 0.75,
			strokeColor: "#FFFF00",
			strokeWidth: 4,
			pointRadius: 12
		},OpenLayers.Feature.Vector.style["select"]))
		}),*/
		
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
		// API keys configuration variable set by <Geoportal.GeoRMHandler.getConfig>
		// variable contenant la configuration des clefs API remplie par <Geoportal.GeoRMHandler.getConfig>
		$map .= "		window.gGEOPORTALRIGHTSMANAGEMENT===undefined? {'apiKey':'" . $this->gpApiKey . "'} : gGEOPORTALRIGHTSMANAGEMENT)";
		$map .= "	);";
		
		$map .= "	if (!viewer) {";
		$map .= "		OpenLayers.Console.error(OpenLayers.i18n('new.instance.failed'));";
		$map .= "		return;";
		$map .= "	}";

		// ----- Layers
		$map .= "	viewer.addGeoportalLayers(['ORTHOIMAGERY.ORTHOPHOTOS','GEOGRAPHICALGRIDSYSTEMS.MAPS']);";

		$cpt=1;
        foreach ( $this->osmFile as $file )
        {
        	if (preg_match ( "/\.(osm)|(gpx)/i", $file ))
			{	
				$map .= $this->getOsmLayerGP ( "viewer", $file, $cpt );
				$cpt ++;
			}
		}
				
		// ----- Autres
		$map .= "	viewer.getMap().setCenterAtLonLat(" . $this->center_lat . "," . $this->center_lon . ", " . $this->zoomLevel . ");";
		
		$map .= "}";

		return $map;
	}

	function getOSMMap()
	{
		// TODO add configuration option to chooce which layer print
		$map = "function initMap() {";
		$map .= "	var options = {";
		$map .= "		controls: [";
		$map .= "			new OpenLayers.Control.Navigation(),";
		$map .= "			new OpenLayers.Control.PanZoomBar(),";
		$map .= "			new OpenLayers.Control.Attribution(),";
		$map .= "			new OpenLayers.Control.LayerSwitcher({'ascending':false})";
		$map .= "		]";
		$map .= "	};";

		$map .= "	map = new OpenLayers.Map(\"MapFactoryMap\",options);";
					
		if (in_array("OSM",$this->osmLayers)) 			$map .= $this->addOsmMapnik("map");
		if (in_array("Cycle",$this->osmLayers)) 		$map .= $this->addOsmCycle("map");
		if (in_array("Transport",$this->osmLayers)) 	$map .= $this->addOsmTransport("map");
		if (in_array("Landscape",$this->osmLayers)) 	$map .= $this->addOsmLandscape("map");
		
		if (in_array("GMap",$this->osmLayers)) 			$map .= $this->addGoogleMapStreets("map");
		if (in_array("GSatellite",$this->osmLayers)) 	$map .= $this->addGoogleMapSatellite("map");
		if (in_array("GPhysical",$this->osmLayers)) 	$map .= $this->addGoogleMapPhysical("map");
		if (in_array("GHybrid",$this->osmLayers)) 		$map .= $this->addGoogleMapHybrid("map");

	 	$cpt=1;
        foreach ( $this->osmFile as $file )
        {
			if (preg_match ( "/\.(osm)|(gpx)/i", $file ))
			{	
				$map .= $this->getOsmLayer ( "map", $file, $cpt );
				$cpt ++;
			}
		}
		
		$map .= "	map.setCenter(new OpenLayers.LonLat(" . $this->center_lat . "," . $this->center_lon . ")";
		$map .= "		.transform(";
		$map .= "		new OpenLayers.Projection(\"EPSG:4326\"),";
		$map .= "		new OpenLayers.Projection(\"EPSG:900913\")";
		$map .= "		), " . $this->zoomLevel;
		$map .= "	);";
    	//$map .= "	map.zoomToMaxExtent();";
		
		$map .= "}";

		return $map;
	}

	function addOsmMapnik($varName)
	{
		$map = "var mapnik = new OpenLayers.Layer.OSM();";
		$map .= "$varName.addLayer(mapnik);";

		return $map;
	}

	function addOsmCycle($varName)
	{
		$map = 'var layerCycleMap = new OpenLayers.Layer.OSM("OpenCycleMap", ["http://a.tile.opencyclemap.org/cycle/${z}/${x}/${y}.png",';
		$map .= '	"http://b.tile.opencyclemap.org/cycle/${z}/${x}/${y}.png",';
		$map .= '	"http://c.tile.opencyclemap.org/cycle/${z}/${x}/${y}.png"]);';
		$map .= "$varName.addLayer(layerCycleMap);";

		return $map;
	}

	function addOsmTransport($varName)
	{
		$map = 'var layerTransport = new OpenLayers.Layer.OSM("Transport", ["http://a.tile2.opencyclemap.org/transport/${z}/${x}/${y}.png",';
		$map .= '	"http://b.tile2.opencyclemap.org/transport/${z}/${x}/${y}.png",';
		$map .= '	"http://c.tile2.opencyclemap.org/transport/${z}/${x}/${y}.png"]);';
		$map .= "$varName.addLayer(layerTransport);";

		return $map;
	}

	function addOsmLandscape($varName)
	{
		$map = 'var layerLandscape = new OpenLayers.Layer.OSM("Landscape", ["http://a.tile3.opencyclemap.org/landscape/${z}/${x}/${y}.png",';
		$map .= '	"http://b.tile3.opencyclemap.org/landscape/${z}/${x}/${y}.png",';
		$map .= '	"http://c.tile3.opencyclemap.org/landscape/${z}/${x}/${y}.png"]);';
		$map .= "$varName.addLayer(layerLandscape);";

		return $map;
	}

	function addGoogleMapPhysical($varName)
	{
		$map = "var gphy = new OpenLayers.Layer.Google(\"Google Physical\",{type: google.maps.MapTypeId.TERRAIN});";
		$map .= "$varName.addLayer(gphy);";
	
		return $map;
	}

	function addGoogleMapStreets($varName)
	{
		$map = "var gmap = new OpenLayers.Layer.Google(\"Google Streets\",{numZoomLevels: 20});";
		$map .= "$varName.addLayer(gmap);";
	
		return $map;
	}

	function addGoogleMapHybrid($varName)
	{
		$map = "var ghyb = new OpenLayers.Layer.Google(\"Google Hybrid\",{type: google.maps.MapTypeId.HYBRID, numZoomLevels: 20});";
		$map .= "$varName.addLayer(ghyb);";
	
		return $map;
	}

	function addGoogleMapSatellite($varName)
	{
		$map = "var gsat = new OpenLayers.Layer.Google(\"Google Satellite\",{type: google.maps.MapTypeId.SATELLITE, numZoomLevels: 22});";
		$map .= "$varName.addLayer(gsat);";
	
		return $map;
	}

	function getMap()
	{		
		$map = "";
		switch ($this->mapKind)
		{
			case self::$MAP_KIND_GMAP :
			case self::$MAP_KIND_OSM :
				$map .= $this->getOsmMap();	
				$map .= "initMap();";		
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

	function getToogleIgnOsmButton()
	{		
		$map = "";
		switch ($this->mapKind)
		{
			case self::$MAP_KIND_GMAP :
			case self::$MAP_KIND_OSM :
				if ($this->gpApiKey != null && $this->gpApiKey != "")
				{
					$map = '<a id="IgnOsm_button" class="MapFactoryBouton" href="' . JRoute::_('index.php?view=map&type=ign') . '">Carte IGN</a>';
				}
				break;
			case self::$MAP_KIND_GEOP:
				$map = '<a id="IgnOsm_button" class="MapFactoryBouton" href="' . JRoute::_('index.php?view=map&type=osm') . '">Carte OSM</a>';
				break;
		}

		return $map;
	}
}
?>
