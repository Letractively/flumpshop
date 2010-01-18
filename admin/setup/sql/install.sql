CREATE TABLE `basket` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `obj` text,
  `lock` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

CREATE TABLE `bugs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `header` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `resolved` tinyint(1) NOT NULL DEFAULT '0',
  `assignedTo` varchar(5) NOT NULL DEFAULT 'None',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

CREATE TABLE `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(75) NOT NULL,
  `description` varchar(255) NOT NULL,
  `parent` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `parent` (`parent`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

CREATE TABLE `country` (
  `iso` char(2) NOT NULL,
  `name` varchar(80) NOT NULL,
  `supported` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`iso`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

CREATE TABLE `delivery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country` varchar(2) NOT NULL,
  `lowerbound` decimal(7,2) NOT NULL,
  `upperbound` decimal(7,2) NOT NULL,
  `price` decimal(6,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `country` (`country`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

CREATE TABLE `keys` (
  `id` int(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `action` text NOT NULL,
  `key` varchar(32) NOT NULL,
  `expiry` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expiryAction` text NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `login` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uname` varchar(75) NOT NULL,
  `password` varchar(32) NOT NULL,
  `customer` int(10) unsigned NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `can_contact` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `customer` (`customer`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

CREATE TABLE `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(250) NOT NULL DEFAULT 'Untitled',
  `body` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

CREATE TABLE `orders` (
  `id` int(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `basket` int(10) unsigned NOT NULL,
  `status` int(11) NOT NULL,
  `token` varchar(250) NOT NULL,
  `customer` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `basket` (`basket`),
  KEY `customer` (`customer`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `products` (
  `id` int(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT 'UNNAMED_PRODUCT',
  `price` decimal(9,2) NOT NULL DEFAULT '0.00',
  `stock` int(11) NOT NULL DEFAULT '0',
  `description` text NOT NULL,
  `category` int(11) NOT NULL,
  `reducedPrice` decimal(8,2) NOT NULL DEFAULT '0.00',
  `reducedValidFrom` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `reducedExpiry` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `weight` decimal(8,2) NOT NULL,
  `active` TINYINT(1) DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `category` (`category`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

CREATE TABLE `reserve` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `item` int(10) unsigned NOT NULL,
  `quantity` int(10) unsigned NOT NULL,
  `expire` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `item` (`item`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

CREATE TABLE `sessions` (
  `session_id` varchar(32) NOT NULL,
  `basket` int(10) unsigned NOT NULL,
  `active` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip_addr` int(12) unsigned DEFAULT '0',
  PRIMARY KEY (`session_id`),
  KEY `basket` (`basket`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `stats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(75) NOT NULL,
  `value` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`key`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

INSERT INTO `stats` (`key`,`value`) VALUES ('dbVer',1);

CREATE TABLE `techhelp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(250) NOT NULL DEFAULT 'Untitled',
  `body` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('AD', 'Andorra', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('AE', 'United Arab Emirates', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('AF', 'Afghanistan', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('AG', 'Antigua and Barbuda', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('AI', 'Anguilla', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('AL', 'Albania', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('AM', 'Armenia', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('AN', 'Netherlands Antilles', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('AO', 'Angola', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('AQ', 'Antarctica', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('AR', 'Argentina', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('AS', 'American Samoa', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('AT', 'Austria', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('AU', 'Australia', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('AW', 'Aruba', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('AZ', 'Azerbaijan', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('BA', 'Bosnia and Herzegovina', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('BB', 'Barbados', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('BD', 'Bangladesh', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('BE', 'Belgium', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('BF', 'Burkina Faso', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('BG', 'Bulgaria', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('BH', 'Bahrain', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('BI', 'Burundi', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('BJ', 'Benin', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('BM', 'Bermuda', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('BN', 'Brunei Darussalam', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('BO', 'Bolivia', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('BR', 'Brazil', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('BS', 'Bahamas', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('BT', 'Bhutan', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('BV', 'Bouvet Island', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('BW', 'Botswana', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('BY', 'Belarus', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('BZ', 'Belize', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('CA', 'Canada', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('CC', 'Cocos (Keeling) Islands', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('CD', 'Congo, the Democratic Republic of the', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('CF', 'Central African Republic', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('CG', 'Congo', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('CH', 'Switzerland', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('CI', 'Cote D''Ivoire', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('CK', 'Cook Islands', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('CL', 'Chile', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('CM', 'Cameroon', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('CN', 'China', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('CO', 'Colombia', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('CR', 'Costa Rica', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('CS', 'Serbia and Montenegro', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('CU', 'Cuba', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('CV', 'Cape Verde', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('CX', 'Christmas Island', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('CY', 'Cyprus', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('CZ', 'Czech Republic', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('DE', 'Germany', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('DJ', 'Djibouti', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('DK', 'Denmark', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('DM', 'Dominica', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('DO', 'Dominican Republic', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('DZ', 'Algeria', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('EC', 'Ecuador', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('EE', 'Estonia', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('EG', 'Egypt', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('EH', 'Western Sahara', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('ER', 'Eritrea', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('ES', 'Spain', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('ET', 'Ethiopia', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('FI', 'Finland', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('FJ', 'Fiji', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('FK', 'Falkland Islands (Malvinas)', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('FM', 'Micronesia, Federated States of', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('FO', 'Faroe Islands', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('FR', 'France', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('GA', 'Gabon', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('GB', 'United Kingdom', 1);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('GD', 'Grenada', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('GE', 'Georgia', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('GF', 'French Guiana', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('GH', 'Ghana', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('GI', 'Gibraltar', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('GL', 'Greenland', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('GM', 'Gambia', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('GN', 'Guinea', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('GP', 'Guadeloupe', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('GQ', 'Equatorial Guinea', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('GR', 'Greece', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('GS', 'South Georgia and the South Sandwich Islands', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('GT', 'Guatemala', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('GU', 'Guam', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('GW', 'Guinea-Bissau', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('GY', 'Guyana', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('HK', 'Hong Kong', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('HM', 'Heard Island and Mcdonald Islands', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('HN', 'Honduras', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('HR', 'Croatia', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('HT', 'Haiti', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('HU', 'Hungary', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('ID', 'Indonesia', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('IE', 'Ireland', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('IL', 'Israel', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('IN', 'India', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('IO', 'British Indian Ocean Territory', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('IQ', 'Iraq', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('IR', 'Iran, Islamic Republic of', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('IS', 'Iceland', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('IT', 'Italy', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('JM', 'Jamaica', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('JO', 'Jordan', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('JP', 'Japan', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('KE', 'Kenya', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('KG', 'Kyrgyzstan', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('KH', 'Cambodia', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('KI', 'Kiribati', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('KM', 'Comoros', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('KN', 'Saint Kitts and Nevis', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('KP', 'Korea, Democratic People''s Republic of', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('KR', 'Korea, Republic of', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('KW', 'Kuwait', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('KY', 'Cayman Islands', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('KZ', 'Kazakhstan', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('LA', 'Lao People''s Democratic Republic', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('LB', 'Lebanon', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('LC', 'Saint Lucia', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('LI', 'Liechtenstein', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('LK', 'Sri Lanka', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('LR', 'Liberia', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('LS', 'Lesotho', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('LT', 'Lithuania', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('LU', 'Luxembourg', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('LV', 'Latvia', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('LY', 'Libyan Arab Jamahiriya', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('MA', 'Morocco', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('MC', 'Monaco', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('MD', 'Moldova, Republic of', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('MG', 'Madagascar', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('MH', 'Marshall Islands', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('MK', 'Macedonia, the Former Yugoslav Republic of', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('ML', 'Mali', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('MM', 'Myanmar', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('MN', 'Mongolia', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('MO', 'Macao', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('MP', 'Northern Mariana Islands', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('MQ', 'Martinique', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('MR', 'Mauritania', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('MS', 'Montserrat', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('MT', 'Malta', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('MU', 'Mauritius', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('MV', 'Maldives', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('MW', 'Malawi', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('MX', 'Mexico', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('MY', 'Malaysia', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('MZ', 'Mozambique', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('NA', 'Namibia', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('NC', 'New Caledonia', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('NE', 'Niger', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('NF', 'Norfolk Island', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('NG', 'Nigeria', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('NI', 'Nicaragua', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('NL', 'Netherlands', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('NO', 'Norway', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('NP', 'Nepal', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('NR', 'Nauru', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('NU', 'Niue', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('NZ', 'New Zealand', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('OM', 'Oman', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('PA', 'Panama', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('PE', 'Peru', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('PF', 'French Polynesia', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('PG', 'Papua New Guinea', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('PH', 'Philippines', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('PK', 'Pakistan', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('PL', 'Poland', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('PM', 'Saint Pierre and Miquelon', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('PN', 'Pitcairn', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('PR', 'Puerto Rico', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('PS', 'Palestinian Territory, Occupied', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('PT', 'Portugal', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('PW', 'Palau', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('PY', 'Paraguay', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('QA', 'Qatar', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('RE', 'Reunion', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('RO', 'Romania', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('RU', 'Russian Federation', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('RW', 'Rwanda', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('SA', 'Saudi Arabia', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('SB', 'Solomon Islands', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('SC', 'Seychelles', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('SD', 'Sudan', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('SE', 'Sweden', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('SG', 'Singapore', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('SH', 'Saint Helena', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('SI', 'Slovenia', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('SJ', 'Svalbard and Jan Mayen', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('SK', 'Slovakia', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('SL', 'Sierra Leone', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('SM', 'San Marino', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('SN', 'Senegal', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('SO', 'Somalia', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('SR', 'Suriname', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('ST', 'Sao Tome and Principe', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('SV', 'El Salvador', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('SY', 'Syrian Arab Republic', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('SZ', 'Swaziland', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('TC', 'Turks and Caicos Islands', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('TD', 'Chad', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('TF', 'French Southern Territories', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('TG', 'Togo', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('TH', 'Thailand', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('TJ', 'Tajikistan', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('TK', 'Tokelau', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('TL', 'Timor-Leste', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('TM', 'Turkmenistan', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('TN', 'Tunisia', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('TO', 'Tonga', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('TR', 'Turkey', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('TT', 'Trinidad and Tobago', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('TV', 'Tuvalu', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('TW', 'Taiwan, Province of China', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('TZ', 'Tanzania, United Republic of', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('UA', 'Ukraine', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('UG', 'Uganda', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('UM', 'United States Minor Outlying Islands', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('US', 'United States', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('UY', 'Uruguay', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('UZ', 'Uzbekistan', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('VA', 'Holy See (Vatican City State)', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('VC', 'Saint Vincent and the Grenadines', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('VE', 'Venezuela', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('VG', 'Virgin Islands, British', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('VI', 'Virgin Islands, U.s.', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('VN', 'Viet Nam', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('VU', 'Vanuatu', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('WF', 'Wallis and Futuna', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('WS', 'Samoa', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('YE', 'Yemen', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('YT', 'Mayotte', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('ZA', 'South Africa', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('ZM', 'Zambia', 0);
INSERT INTO `country` (`iso`, `name`, `supported`) VALUES('ZW', 'Zimbabwe', 0);