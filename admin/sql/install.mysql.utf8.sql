
DROP TABLE IF EXISTS `#__mapfactory_files`;
 
CREATE TABLE `#__mapfactory_files` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `filename` VARCHAR(50) NOT NULL,
   PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;
 
INSERT INTO `#__mapfactory_files` (`filename`) VALUES
        ('Rallye2012.osm'),
        ('Rallye2011.osm');