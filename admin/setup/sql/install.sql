CREATE TABLE  `acp_login` (
`id` INT(11) NOT NULL AUTO_INCREMENT,
`uname` VARCHAR( 8 ) NOT NULL,
`pass` VARCHAR( 32 ) NOT NULL,
`last_login` TIMESTAMP NOT NULL,
`last_tier2_login` TIMESTAMP NOT NULL,
`can_add_products` BOOL NOT NULL DEFAULT '0',
`can_edit_products` BOOL NOT NULL DEFAULT '0',
`can_delete_products` BOOL NOT NULL DEFAULT '0',
`can_add_categories` BOOL NOT NULL DEFAULT '0',
`can_edit_categories` BOOL NOT NULL DEFAULT '0',
`can_delete_categories` BOOL NOT NULL DEFAULT '0',
`can_edit_pages` BOOL NOT NULL DEFAULT '0',
`can_edit_delivery_rates` BOOL NOT NULL DEFAULT '0',
`can_post_news` BOOL NOT NULL DEFAULT '0',
`can_add_customers` BOOL NOT NULL DEFAULT '0',
`can_contact_customers` BOOL NOT NULL DEFAULT '0',
`can_view_customers` BOOL NOT NULL DEFAULT '0',
`can_view_orders` BOOL NOT NULL DEFAULT '0',
`can_create_orders` BOOL NOT NULL DEFAULT '0',
`can_edit_orders` BOOL NOT NULL DEFAULT '0',
`can_assign_orders` BOOL NOT NULL DEFAULT '0',
`can_view_reports` BOOL NOT NULL DEFAULT '0',
`can_add_features` BOOL NOT NULL DEFAULT '0',
`can_edit_features` BOOL NOT NULL DEFAULT '0',
`can_delete_features` BOOL NOT NULL DEFAULT '0',
`pass_expires` TIMESTAMP NOT NULL,
PRIMARY KEY (`id`)
);

CREATE TABLE `basket` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `locked` BOOL NOT NULL DEFAULT '0',
  `total` decimal(10,2) NOT NULL DEFAULT '0',
  `delivery` decimal(10,2) NOT NULL DEFAULT '0',
  `vat` BOOL NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
);

CREATE TABLE `basket_items` (
	`item_id` int(11) unsigned NOT NULL,
	`basket_id` int(11) unsigned NOT NULL,
	`quantity` int(11) unsigned NOT NULL,
	`sold_at` decimal(10,2) unsigned NOT NULL DEFAULT '0',
	PRIMARY KEY (`item_id`, `basket_id`)
);

CREATE TABLE `basket_keys` (
  `basket_id` int(10) unsigned NOT NULL,
  `key_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`basket_id`,`key_id`)
);

CREATE TABLE `bugs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `header` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `resolved` tinyint(1) NOT NULL DEFAULT '0',
  `assignedTo` varchar(5) NOT NULL DEFAULT 'None',
  PRIMARY KEY (`id`)
);

CREATE TABLE `cache` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nodeName` varchar(75) NOT NULL UNIQUE,
  `cache` longtext NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(75) NOT NULL,
  `description` text NOT NULL,
  `parent` int(11) NOT NULL DEFAULT '0',
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `parent` (`parent`)
);

CREATE TABLE `category_feature` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(10) unsigned NOT NULL,
  `feature_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE `compare_features` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `feature_name` varchar(50) NOT NULL DEFAULT 'Unnamed Attribute',
  `data_type` varchar(6) NOT NULL,
  `default_value` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE `country` (
  `iso` char(2) NOT NULL,
  `name` varchar(80) NOT NULL,
  `supported` tinyint(1) NOT NULL DEFAULT '0',
  `currency` CHAR(3) DEFAULT 'EUR',
  PRIMARY KEY (`iso`)
);

CREATE TABLE `customers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(75) NOT NULL,
  `address1` varchar(100) NOT NULL,
  `address2` varchar(100) NOT NULL,
  `address3` varchar(100) NOT NULL,
  `postcode` varchar(15) NOT NULL,
  `country` varchar(2) NOT NULL,
  `email` varchar(75) NOT NULL,
  `paypalid` varchar(128) NOT NULL,
  `archive` BOOL NOT NULL DEFAULT '0',
  `can_contact` BOOL NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
);

CREATE TABLE `delivery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country` varchar(2) NOT NULL,
  `lowerbound` decimal(7,2) NOT NULL,
  `upperbound` decimal(7,2) NOT NULL,
  `price` decimal(6,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `country` (`country`)
);

CREATE TABLE `feature_units` (
  `feature_id` int(10) unsigned NOT NULL,
  `multiple` int(11) NOT NULL,
  `unit` varchar(15) NOT NULL,
  PRIMARY KEY (`feature_id`,`multiple`)
);

CREATE TABLE `item_category` (
`id` INT(11) unsigned NOT NULL AUTO_INCREMENT,
`itemid` INT(11) unsigned,
`catid` INT(11) unsigned,
PRIMARY KEY (`id`)
);

CREATE TABLE `item_delivery` (
`id` INT(11) unsigned NOT NULL AUTO_INCREMENT,
`itemid` INT(11) unsigned,
`countryid` INT(11) unsigned,
`price` DECIMAL(9,2),
PRIMARY KEY (`id`)
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

CREATE TABLE `keys` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(32) NOT NULL,
  `expiry` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
);

CREATE TABLE `keys_action` (
	`key_id` int(10) unsigned NOT NULL,
	`action` VARCHAR(100),
	PRIMARY KEY (`key_id`,`action`)
);

CREATE TABLE `keys_expiry` (
	`key_id` int(10) unsigned NOT NULL,
	`action` VARCHAR(100),
	PRIMARY KEY (`key_id`,`action`)
);

CREATE TABLE `login` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uname` varchar(75) NOT NULL,
  `password` varchar(32) NOT NULL,
  `customer` int(10) unsigned NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `can_contact` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `customer` (`customer`)
);

CREATE TABLE `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(250) NOT NULL DEFAULT 'Untitled',
  `body` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `poster` int(11) unsigned,
  PRIMARY KEY (`id`)
);

CREATE TABLE `orders` (
  `id` int(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `basket` int(10) unsigned NOT NULL,
  `status` int(11) NOT NULL,
  `token` varchar(250),
  `billing` int(10) unsigned NOT NULL,
  `shipping` int(11) unsigned NOT NULL,
  `assignedTo` int(10) unsigned,
  PRIMARY KEY (`id`)
);

CREATE TABLE `products` (
  `id` int(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT 'UNNAMED_PRODUCT',
  `price` decimal(9,2) NOT NULL DEFAULT '0.00',
  `stock` int(11) NOT NULL DEFAULT '0',
  `description` longtext NOT NULL,
  `reducedPrice` decimal(9,2) NOT NULL DEFAULT '0.00',
  `reducedValidFrom` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `reducedExpiry` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `weight` decimal(9,2) NOT NULL,
  `active` TINYINT(1) DEFAULT 1,
  `SKU` VARCHAR(25) DEFAULT NULL,
  `cost` DECIMAL(9,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE `reserve` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `basketID` int(11) unsigned NOT NULL DEFAULT 0,
  `item` int(11) unsigned NOT NULL,
  `quantity` int(11) unsigned NOT NULL,
  `expire` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
);

CREATE TABLE `sessions` (
  `session_id` varchar(32) NOT NULL,
  `basket` int(10) unsigned NOT NULL,
  `active` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip_addr` int(12) DEFAULT '0',
  PRIMARY KEY (`session_id`),
  KEY `basket` (`basket`)
);

CREATE TABLE `stats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(75) NOT NULL,
  `value` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`key`)
);

INSERT INTO `stats` (`key`,`value`) VALUES ('dbVer',1);

CREATE TABLE `techhelp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(250) NOT NULL DEFAULT 'Untitled',
  `body` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `poster` int(11) unsigned,
  PRIMARY KEY (`id`)
);

INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('AD', 'Andorra', 0, 'EUR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('AE', 'United Arab Emirates', 0, 'AED');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('AF', 'Afghanistan', 0, 'AFN');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('AG', 'Antigua and Barbuda', 0, 'XCD');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('AI', 'Anguilla', 0, 'XCD');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('AL', 'Albania', 0, 'ALL');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('AM', 'Armenia', 0, 'AMD');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('AN', 'Netherlands Antilles', 0, 'ANG');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('AO', 'Angola', 0, 'AOA');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('AQ', 'Antarctica', 0, 'EUR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('AR', 'Argentina', 0, 'ARS');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('AS', 'American Samoa', 0, 'USD');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('AT', 'Austria', 0, 'EUR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('AU', 'Australia', 0, 'AUD');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('AW', 'Aruba', 0, 'AWG');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('AZ', 'Azerbaijan', 0, 'AZN');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('BA', 'Bosnia and Herzegovina', 0, 'BAM');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('BB', 'Barbados', 0, 'BBD');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('BD', 'Bangladesh', 0, 'BDT');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('BE', 'Belgium', 0, 'EUR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('BF', 'Burkina Faso', 0, 'XOF');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('BG', 'Bulgaria', 0, 'BGN');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('BH', 'Bahrain', 0, 'BHD');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('BI', 'Burundi', 0, 'BIF');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('BJ', 'Benin', 0, 'XOF');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('BM', 'Bermuda', 0, 'BMD');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('BN', 'Brunei Darussalam', 0, 'BND');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('BO', 'Bolivia', 0, 'BOB');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('BR', 'Brazil', 0, 'BRL');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('BS', 'Bahamas', 0, 'BSD');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('BT', 'Bhutan', 0, 'BTN');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('BV', 'Bouvet Island', 0, 'NOK');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('BW', 'Botswana', 0, 'BWP');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('BY', 'Belarus', 0, 'BYR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('BZ', 'Belize', 0, 'BZD');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('CA', 'Canada', 0, 'CAD');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('CC', 'Cocos (Keeling) Islands', 0, 'AUD');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('CD', 'Congo, the Democratic Republic of the', 0, 'CDF');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('CF', 'Central African Republic', 0, 'XAF');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('CG', 'Congo', 0, 'XAF');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('CH', 'Switzerland', 0, 'CHF');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('CI', 'Cote D''Ivoire', 0, 'XOF');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('CK', 'Cook Islands', 0, 'NZD');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('CL', 'Chile', 0, 'CLP');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('CM', 'Cameroon', 0, 'XAF');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('CN', 'China', 0, 'CNY');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('CO', 'Colombia', 0, 'COP');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('CR', 'Costa Rica', 0, 'CRC');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('CS', 'Serbia and Montenegro', 0, 'EUR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('CU', 'Cuba', 0, 'CUP');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('CV', 'Cape Verde', 0, 'CVE');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('CX', 'Christmas Island', 0, 'AUD');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('CY', 'Cyprus', 0, 'EUR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('CZ', 'Czech Republic', 0, 'CZK');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('DE', 'Germany', 0, 'EUR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('DJ', 'Djibouti', 0, 'DJF');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('DK', 'Denmark', 0, 'DKK');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('DM', 'Dominica', 0, 'XCD');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('DO', 'Dominican Republic', 0, 'DOP');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('DZ', 'Algeria', 0, 'DZD');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('EC', 'Ecuador', 0, 'USD');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('EE', 'Estonia', 0, 'EEK');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('EG', 'Egypt', 0, 'EGP');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('EH', 'Western Sahara', 0, 'MAD');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('ER', 'Eritrea', 0, 'ERN');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('ES', 'Spain', 0, 'EUR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('ET', 'Ethiopia', 0, 'ETB');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('FI', 'Finland', 0, 'EUR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('FJ', 'Fiji', 0, 'FJD');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('FK', 'Falkland Islands (Malvinas)', 0, 'FKP');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('FM', 'Micronesia, Federated States of', 0, 'USD');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('FO', 'Faroe Islands', 0, 'DKK');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('FR', 'France', 0, 'EUR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('GA', 'Gabon', 0, 'XAF');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('GB', 'United Kingdom', 1, 'GBP');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('GD', 'Grenada', 0, 'XCD');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('GE', 'Georgia', 0, 'GEL');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('GF', 'French Guiana', 0, 'EUR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('GH', 'Ghana', 0, 'GHS');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('GI', 'Gibraltar', 0, 'GIP');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('GL', 'Greenland', 0, 'DKK');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('GM', 'Gambia', 0, 'GMD');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('GN', 'Guinea', 0, 'GNF');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('GP', 'Guadeloupe', 0, 'EUR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('GQ', 'Equatorial Guinea', 0, 'XAF');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('GR', 'Greece', 0, 'EUR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('GS', 'South Georgia and the South Sandwich Islands', 0, 'GBP');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('GT', 'Guatemala', 0, 'GTQ');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('GU', 'Guam', 0, 'USD');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('GW', 'Guinea-Bissau', 0, 'XOF');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('GY', 'Guyana', 0, 'GYD');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('HK', 'Hong Kong', 0, 'HKD');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('HM', 'Heard Island and Mcdonald Islands', 0, 'AUD');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('HN', 'Honduras', 0, 'HNL');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('HR', 'Croatia', 0, 'HRK');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('HT', 'Haiti', 0, 'HTG');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('HU', 'Hungary', 0, 'HUF');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('ID', 'Indonesia', 0, 'IDR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('IE', 'Ireland', 0, 'EUR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('IL', 'Israel', 0, 'ILS');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('IN', 'India', 0, 'INR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('IO', 'British Indian Ocean Territory', 0, 'USD');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('IQ', 'Iraq', 0, 'IQD');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('IR', 'Iran, Islamic Republic of', 0, 'IRR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('IS', 'Iceland', 0, 'ISK');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('IT', 'Italy', 0, 'EUR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('JM', 'Jamaica', 0, 'JMD');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('JO', 'Jordan', 0, 'JOD');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('JP', 'Japan', 0, 'JPY');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('KE', 'Kenya', 0, 'KES');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('KG', 'Kyrgyzstan', 0, 'KGS');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('KH', 'Cambodia', 0, 'KHR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('KI', 'Kiribati', 0, 'AUD');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('KM', 'Comoros', 0, 'KMF');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('KN', 'Saint Kitts and Nevis', 0, 'XCD');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('KP', 'Korea, Democratic People''s Republic of', 0, 'KPW');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('KR', 'Korea, Republic of', 0, 'KRW');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('KW', 'Kuwait', 0, 'KWD');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('KY', 'Cayman Islands', 0, 'KYD');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('KZ', 'Kazakhstan', 0, 'KZT');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('LA', 'Lao People''s Democratic Republic', 0, 'LAK');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('LB', 'Lebanon', 0, 'LBP');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('LC', 'Saint Lucia', 0, 'XCD');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('LI', 'Liechtenstein', 0, 'CHF');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('LK', 'Sri Lanka', 0, 'LKR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('LR', 'Liberia', 0, 'LRD');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('LS', 'Lesotho', 0, 'LSL');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('LT', 'Lithuania', 0, 'LTL');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('LU', 'Luxembourg', 0, 'EUR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('LV', 'Latvia', 0, 'LVL');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('LY', 'Libyan Arab Jamahiriya', 0, 'LYD');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('MA', 'Morocco', 0, 'MAD');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('MC', 'Monaco', 0, 'EUR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('MD', 'Moldova, Republic of', 0, 'MDL');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('MG', 'Madagascar', 0, 'MGA');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('MH', 'Marshall Islands', 0, 'USD');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('MK', 'Macedonia, the Former Yugoslav Republic of', 0, 'MKD');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('ML', 'Mali', 0, 'XOF');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('MM', 'Myanmar', 0, 'MMK');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('MN', 'Mongolia', 0, 'MNT');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('MO', 'Macau', 0, 'MOP');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('MP', 'Northern Mariana Islands', 0, 'USD');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('MQ', 'Martinique', 0, 'EUR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('MR', 'Mauritania', 0, 'MRO');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('MS', 'Montserrat', 0, 'XCD');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('MT', 'Malta', 0, 'EUR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('MU', 'Mauritius', 0, 'MUR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('MV', 'Maldives', 0, 'MVR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('MW', 'Malawi', 0, 'MWK');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('MX', 'Mexico', 0, 'MXN');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('MY', 'Malaysia', 0, 'MYR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('MZ', 'Mozambique', 0, 'MZN');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('NA', 'Namibia', 0, 'NAD');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('NC', 'New Caledonia', 0, 'XPF');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('NE', 'Niger', 0, 'XOF');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('NF', 'Norfolk Island', 0, 'AUD');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('NG', 'Nigeria', 0, 'NGN');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('NI', 'Nicaragua', 0, 'NIO');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('NL', 'Netherlands', 0, 'EUR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('NO', 'Norway', 0, 'NOK');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('NP', 'Nepal', 0, 'NPR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('NR', 'Nauru', 0, 'AUD');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('NU', 'Niue', 0, 'NZD');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('NZ', 'New Zealand', 0, 'NZD');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('OM', 'Oman', 0, 'OMR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('PA', 'Panama', 0, 'PAB');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('PE', 'Peru', 0, 'PEN');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('PF', 'French Polynesia', 0, 'XPF');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('PG', 'Papua New Guinea', 0, 'PGK');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('PH', 'Philippines', 0, 'PHP');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('PK', 'Pakistan', 0, 'PKR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('PL', 'Poland', 0, 'PLN');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('PM', 'Saint Pierre and Miquelon', 0, 'EUR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('PN', 'Pitcairn', 0, 'EUR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('PR', 'Puerto Rico', 0, 'NZD');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('PT', 'Portugal', 0, 'EUR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('PW', 'Palau', 0, 'USD');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('PY', 'Paraguay', 0, 'PYG');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('QA', 'Qatar', 0, 'QAR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('RE', 'Reunion', 0, 'EUR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('RO', 'Romania', 0, 'RON');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('RU', 'Russian Federation', 0, 'RUB');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('RW', 'Rwanda', 0, 'RWF');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('SA', 'Saudi Arabia', 0, 'SAR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('SB', 'Solomon Islands', 0, 'SBD');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('SC', 'Seychelles', 0, 'EUR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('SD', 'Sudan', 0, 'EUR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('SE', 'Sweden', 0, 'EUR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('SG', 'Singapore', 0, 'EUR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('SH', 'Saint Helena', 0, 'EUR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('SI', 'Slovenia', 0, 'EUR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('SJ', 'Svalbard and Jan Mayen', 0, 'EUR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('SK', 'Slovakia', 0, 'EUR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('SL', 'Sierra Leone', 0, 'EUR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('SM', 'San Marino', 0, 'EUR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('SN', 'Senegal', 0, 'EUR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('SO', 'Somalia', 0, 'EUR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('SR', 'Suriname', 0, 'EUR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('ST', 'Sao Tome and Principe', 0, 'EUR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('SV', 'El Salvador', 0, 'EUR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('SY', 'Syrian Arab Republic', 0, 'EUR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('SZ', 'Swaziland', 0, 'EUR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('TC', 'Turks and Caicos Islands', 0, 'EUR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('TD', 'Chad', 0, 'EUR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('TF', 'French Southern Territories', 0, 'EUR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('TG', 'Togo', 0, 'EUR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('TH', 'Thailand', 0, 'EUR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('TJ', 'Tajikistan', 0, 'EUR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('TK', 'Tokelau', 0, 'EUR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('TL', 'Timor-Leste', 0, 'EUR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('TM', 'Turkmenistan', 0, 'EUR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('TN', 'Tunisia', 0, 'EUR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('TO', 'Tonga', 0, 'EUR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('TR', 'Turkey', 0, 'EUR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('TT', 'Trinidad and Tobago', 0, 'EUR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('TV', 'Tuvalu', 0, 'EUR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('TW', 'Taiwan, Province of China', 0, 'EUR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('TZ', 'Tanzania, United Republic of', 0, 'EUR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('UA', 'Ukraine', 0, 'EUR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('UG', 'Uganda', 0, 'EUR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('UM', 'United States Minor Outlying Islands', 0, 'EUR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('US', 'United States', 0, 'EUR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('UY', 'Uruguay', 0, 'EUR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('UZ', 'Uzbekistan', 0, 'EUR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('VA', 'Holy See (Vatican City State)', 0, 'EUR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('VC', 'Saint Vincent and the Grenadines', 0, 'EUR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('VE', 'Venezuela', 0, 'EUR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('VG', 'Virgin Islands, British', 0, 'EUR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('VI', 'Virgin Islands, U.s.', 0, 'EUR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('VN', 'Viet Nam', 0, 'EUR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('VU', 'Vanuatu', 0, 'EUR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('WF', 'Wallis and Futuna', 0, 'EUR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('WS', 'Samoa', 0, 'EUR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('YE', 'Yemen', 0, 'EUR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('YT', 'Mayotte', 0, 'EUR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('ZA', 'South Africa', 0, 'EUR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('ZM', 'Zambia', 0, 'EUR');
INSERT INTO `country` (`iso`, `name`, `supported`, `currency`) VALUES('ZW', 'Zimbabwe', 0, 'EUR');