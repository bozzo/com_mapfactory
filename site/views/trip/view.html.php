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
 
 jimport( 'joomla.application.component.view');

class MapFactoryViewTrip extends JView {

	private $center_lon;
	private $center_lat;
	
	// Overwriting JView display method
	function display($tpl = null) 
	{
	        $doc =& JFactory::getDocument();
			//$doc->addScript("http://www.openlayers.org/api/OpenLayers.js");
			$doc->addScript("http://code.jquery.com/jquery-latest.js");
			$doc->addScript("http://api.ign.fr/geoportail/api/js/latest/GeoportalExtended.js");
			
			$style = "#MapFactoryMap { height: 600px; min-width: 100px; }";
			$doc->addStyleDeclaration( $style );
			
			$input = JFactory::getApplication()->input;
			$this->center_lon = $input->get('center_lon');
			$this->center_lat = $input->get('center_lat');
			
	        // Assign data to the view
	        $this->msg = 'Liste des circuits';
	 
	        // Display the view
	        parent::display($tpl);
	}
	
	function getMap($lat,$lon)
	{
		// FIXME get it from admin panel
		$map = "var APIkey= 'ej0gmxfdbpt4fe4z59fgdsor';";
        
        $map .= "function initMap() {";
        	 
        //$map .= "	// ----- Traduction";
        //$map .= "	//translate();";
        
        //$map .= "	// ----- Options";
        
        $map .= "	var options= {";
        $map .= "		mode:'normal',";
        $map .= "		territory:'FXX'";
        //$map = "		proxy:'assets/proxy.php?url='";
        $map .= "	};";
        
        $map .= "	viewer= new Geoportal.Viewer.Default('MapFactoryMap', OpenLayers.Util.extend(";
        $map .= "		options,";
       //$map .= "		// API keys configuration variable set by <Geoportal.GeoRMHandler.getConfig>";
       //$map .= "		// variable contenant la configuration des clefs API remplie par <Geoportal.GeoRMHandler.getConfig>";
        $map .= "		window.gGEOPORTALRIGHTSMANAGEMENT===undefined? {'apiKey':APIkey} : gGEOPORTALRIGHTSMANAGEMENT)";
        $map .= "	);";
        $map .= "	if (!viewer) {";
        //$map .= "		// problem ...";
        $map .= "		OpenLayers.Console.error(OpenLayers.i18n('new.instance.failed'));";
        $map .= "		return;";
        $map .= "	}";
        	 
        //$map .= "	// ----- Layers";
        $map .= "	viewer.addGeoportalLayers(['ORTHOIMAGERY.ORTHOPHOTOS','GEOGRAPHICALGRIDSYSTEMS.MAPS']);";
        
        //$map .= "	// ----- Autres";
        $map .= "	viewer.getMap().setCenterAtLonLat(" . $this->center_lat . "," . $this->center_lon . ");";
        
        //$map .= '	// OpenStreetMap tiled layer :';
    	$map .= '	var osmarender= new OpenLayers.Layer.OSM(';
        $map .= '		"OpenStreetMap (Mapnik)",';
        $map .= '		"http://tile.openstreetmap.org/${z}/${x}/${y}.png",';
        $map .= '		{';
        $map .= '    		projection: new OpenLayers.Projection("EPSG:900913"),';
        $map .= '    		units: "m",';
        $map .= '    		numZoomLevels: 18,';
        $map .= '    		maxResolution: 156543.0339,';
        $map .= '    		maxExtent: new OpenLayers.Bounds(-20037508, -20037508, 20037508, 20037508),';
        $map .= '   		visibility: false,';
        $map .= '   		originators:[{';
        $map .= '           	logo:"osm",';
        $map .= '           	pictureUrl:"http://wiki.openstreetmap.org/Wiki.png",';
        $map .= '           	url:"http://wiki.openstreetmap.org/wiki/WikiProject_France"';
        $map .= '       	}]';
        $map .= '		});';
    	$map .= '	viewer.getMap().addLayers([osmarender]);';
    			
    	$map .= '	var layerCycleMap = new OpenLayers.Layer.OSM("OpenCycleMap", ["http://a.tile.opencyclemap.org/cycle/${z}/${x}/${y}.png",';
        $map .= '			"http://b.tile.opencyclemap.org/cycle/${z}/${x}/${y}.png",';
        $map .= '			"http://c.tile.opencyclemap.org/cycle/${z}/${x}/${y}.png"]);';
        $map .= "	viewer.getMap().addLayer([layerCycleMap]);";
        $map .= "}";
        
        $map .= "Geoportal.GeoRMHandler.getConfig([APIkey],null,null, {onContractsComplete: initMap});";
        
        //$map .= "map = new OpenLayers.Map(\"MapFactoryMap\",options);";
        /*$map .= "var mapnik = new OpenLayers.Layer.OSM();";
        $map .= "var gphy = new OpenLayers.Layer.Google(\"Google Physical\",{type: google.maps.MapTypeId.TERRAIN});";
        $map .= "var gmap = new OpenLayers.Layer.Google(\"Google Streets\",{numZoomLevels: 20});";
        $map .= "var ghyb = new OpenLayers.Layer.Google(\"Google Hybrid\",{type: google.maps.MapTypeId.HYBRID, numZoomLevels: 20});";
        $map .= "var gsat = new OpenLayers.Layer.Google(\"Google Satellite\",{type: google.maps.MapTypeId.SATELLITE, numZoomLevels: 22});";
        $map .= "var opencyclemap = new OpenLayers.Layer.OSM.CycleMap(\"Open Cycle Map\");";
        
        $map .= "map.addLayer(gmap);";
        $map .= "map.addLayer(ghyb);";
        $map .= "map.addLayer(gsat);";
        $map .= "map.addLayer(gphy);";
        $map .= "map.addLayer(opencyclemap);";
        $map .= "map.addLayer(mapnik);";*/
        
        /*$map .= "layerMapnik = new OpenLayers.Layer.OSM.Mapnik(\"Mapnik\");";
        $map .= "layerMapnik.setOpacity(0.4);";
        $map .= "map.addLayer(layerMapnik);";*/
        /*
        $map .= 'var layerCycleMap = new OpenLayers.Layer.OSM("OpenCycleMap", ["http://a.tile.opencyclemap.org/cycle/${z}/${x}/${y}.png",';
        $map .= '"http://b.tile.opencyclemap.org/cycle/${z}/${x}/${y}.png",';
        $map .= '"http://c.tile.opencyclemap.org/cycle/${z}/${x}/${y}.png"]);';
        $map .= "map.addLayer(layerCycleMap);";  
        
        $map .= 'var layerTransport = new OpenLayers.Layer.OSM("Transport", ["http://a.tile2.opencyclemap.org/transport/${z}/${x}/${y}.png",';
        $map .= '"http://b.tile2.opencyclemap.org/transport/${z}/${x}/${y}.png",';
        $map .= '"http://c.tile2.opencyclemap.org/transport/${z}/${x}/${y}.png"]);';
        $map .= "map.addLayer(layerTransport);";  

        $map .= 'var layerLandscape = new OpenLayers.Layer.OSM("Landscape", ["http://a.tile3.opencyclemap.org/landscape/${z}/${x}/${y}.png",';
        $map .= '"http://b.tile3.opencyclemap.org/landscape/${z}/${x}/${y}.png",';
        $map .= '"http://c.tile3.opencyclemap.org/landscape/${z}/${x}/${y}.png"]);';
        $map .= "map.addLayer(layerLandscape);";
        
        /*$map .= "layerCycleMap = new OpenLayers.Layer.OSM.CycleMap(\"CycleMap\");";
        $map .= "layerCycleMap.setOpacity(0.4);";
        $map .= "map.addLayer(layerCycleMap);";        */
        
       /* $map .= "var layer = new OpenLayers.Layer.Vector(\"Rallye 2011 10km\", {";
	        $map .= "strategies: [new OpenLayers.Strategy.Fixed()],";
	        $map .= "style: {strokeColor: \"red\", strokeWidth: 5, strokeOpacity: 0.5},";
	        $map .= "protocol: new OpenLayers.Protocol.HTTP({";
		        $map .= "url: \"/media/com_mapfactory/sanstitre.osm\",";
		        $map .= "format: new OpenLayers.Format.OSM()";
	        $map .= "}),";
	        $map .= "projection: new OpenLayers.Projection(\"EPSG:4326\")";
        $map .= "});";
        
        $map .= "map.addLayers([layer]);";
        
        $map .= "map.setCenter(new OpenLayers.LonLat(" . $this->center_lat . "," . $this->center_lon . ")";
       		$map .= ".transform(";
        		$map .= "new OpenLayers.Projection(\"EPSG:4326\"),";
        		$map .= "new OpenLayers.Projection(\"EPSG:900913\")";
        	$map .= "), 15";
        $map .= ");";*/
		
		return $map;
	}
}
?>
