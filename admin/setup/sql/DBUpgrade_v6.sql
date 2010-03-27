SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

CREATE TABLE IF NOT EXISTS `category_feature` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(10) unsigned NOT NULL,
  `feature_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `compare_features` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `feature_name` varchar(50) NOT NULL DEFAULT 'Unnamed Attribute',
  `data_type` enum('number','string','date') NOT NULL,
  `default_value` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
)  ;

CREATE TABLE IF NOT EXISTS `feature_units` (
  `feature_id` int(10) unsigned NOT NULL,
  `multiple` int(11) NOT NULL,
  `unit` varchar(15) NOT NULL,
  PRIMARY KEY (`feature_id`,`multiple`)
) ;

CREATE TABLE IF NOT EXISTS `item_feature` (
  `item_id` int(10) unsigned NOT NULL,
  `feature_id` int(10) unsigned NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`item_id`,`feature_id`)
);
