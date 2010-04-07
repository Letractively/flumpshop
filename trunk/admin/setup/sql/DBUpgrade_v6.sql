CREATE TABLE `category_feature` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(10) unsigned NOT NULL,
  `feature_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE `compare_features` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `feature_name` varchar(50) NOT NULL DEFAULT 'Unnamed Attribute',
  `data_type` enum('number','string','date') NOT NULL,
  `default_value` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE `feature_units` (
  `feature_id` int(10) unsigned NOT NULL,
  `multiple` int(11) NOT NULL,
  `unit` varchar(15) NOT NULL,
  PRIMARY KEY (`feature_id`,`multiple`)
);

CREATE TABLE `item_feature_date` (
  `item_id` int(10) unsigned NOT NULL,
  `feature_id` int(10) unsigned NOT NULL,
  `value` date NOT NULL,
  PRIMARY KEY (`item_id`,`feature_id`)
);

CREATE TABLE `item_feature_number` (
  `item_id` int(10) unsigned NOT NULL,
  `feature_id` int(10) unsigned NOT NULL,
  `value` bigint(20) NOT NULL,
  PRIMARY KEY (`item_id`,`feature_id`)
);

CREATE TABLE `item_feature_string` (
  `item_id` int(10) unsigned NOT NULL,
  `feature_id` int(10) unsigned NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`item_id`,`feature_id`)
);