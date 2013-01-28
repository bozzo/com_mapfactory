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
?>
<div id="Fullscreendiv" class="component_simplethu">
	<script>
		function fullscreen() {
			var myJqueryAlias = jQuery.noConflict();
			var fullscreenOn = (myJqueryAlias(document).fullScreen() ? false : true);
			myJqueryAlias("#Fullscreendiv").fullScreen(fullscreenOn);
			if (fullscreenOn)
			{
				myJqueryAlias("#MapFactoryMap").addClass("MapFactoryMapFullscreen").removeClass("MapFactoryMapSmallscreen");
				myJqueryAlias("#Fullscreen_button").text("Ecran normal");

				<?php
				if ($this->isGeoportail())
				{ 
					echo 'myJqueryAlias("#MapFactoryMap_OlMap").removeAttr("style");';
				}
				?>
			}
			else
			{
				myJqueryAlias("#MapFactoryMap").addClass("MapFactoryMapSmallscreen").removeClass("MapFactoryMapFullscreen");
				myJqueryAlias("#Fullscreen_button").text("Plein écran");
			}
		}
	</script>
	<h1>
		<?php echo $this->msg; ?>
	</h1>
	<a id="Fullscreen_button" onclick="fullscreen()">Plein écran</a>
	<div id="MapFactoryMap" class="MapFactoryMapSmallscreen"></div>
	<script>
        <?php echo $this->getMap(); ?>
    </script>
</div>
