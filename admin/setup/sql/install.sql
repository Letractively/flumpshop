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

INSERT INTO `country` (`iso`, `name`, `supported`) VALUES
('AD', 'Andorra', 0),
('AE', 'United Arab Emirates', 0),
('AF', 'Afghanistan', 0),
('AG', 'Antigua and Barbuda', 0),
('AI', 'Anguilla', 0),
('AL', 'Albania', 0),
('AM', 'Armenia', 0),
('AN', 'Netherlands Antilles', 0),
('AO', 'Angola', 0),
('AQ', 'Antarctica', 0),
('AR', 'Argentina', 0),
('AS', 'American Samoa', 0),
('AT', 'Austria', 0),
('AU', 'Australia', 0),
('AW', 'Aruba', 0),
('AZ', 'Azerbaijan', 0),
('BA', 'Bosnia and Herzegovina', 0),
('BB', 'Barbados', 0),
('BD', 'Bangladesh', 0),
('BE', 'Belgium', 0),
('BF', 'Burkina Faso', 0),
('BG', 'Bulgaria', 0),
('BH', 'Bahrain', 0),
('BI', 'Burundi', 0),
('BJ', 'Benin', 0),
('BM', 'Bermuda', 0),
('BN', 'Brunei Darussalam', 0),
('BO', 'Bolivia', 0),
('BR', 'Brazil', 0),
('BS', 'Bahamas', 0),
('BT', 'Bhutan', 0),
('BV', 'Bouvet Island', 0),
('BW', 'Botswana', 0),
('BY', 'Belarus', 0),
('BZ', 'Belize', 0),
('CA', 'Canada', 0),
('CC', 'Cocos (Keeling) Islands', 0),
('CD', 'Congo, the Democratic Republic of the', 0),
('CF', 'Central African Republic', 0),
('CG', 'Congo', 0),
('CH', 'Switzerland', 0),
('CI', 'Cote D''Ivoire', 0),
('CK', 'Cook Islands', 0),
('CL', 'Chile', 0),
('CM', 'Cameroon', 0),
('CN', 'China', 0),
('CO', 'Colombia', 0),
('CR', 'Costa Rica', 0),
('CS', 'Serbia and Montenegro', 0),
('CU', 'Cuba', 0),
('CV', 'Cape Verde', 0),
('CX', 'Christmas Island', 0),
('CY', 'Cyprus', 0),
('CZ', 'Czech Republic', 0),
('DE', 'Germany', 0),
('DJ', 'Djibouti', 0),
('DK', 'Denmark', 0),
('DM', 'Dominica', 0),
('DO', 'Dominican Republic', 0),
('DZ', 'Algeria', 0),
('EC', 'Ecuador', 0),
('EE', 'Estonia', 0),
('EG', 'Egypt', 0),
('EH', 'Western Sahara', 0),
('ER', 'Eritrea', 0),
('ES', 'Spain', 0),
('ET', 'Ethiopia', 0),
('FI', 'Finland', 0),
('FJ', 'Fiji', 0),
('FK', 'Falkland Islands (Malvinas)', 0),
('FM', 'Micronesia, Federated States of', 0),
('FO', 'Faroe Islands', 0),
('FR', 'France', 0),
('GA', 'Gabon', 0),
('GB', 'United Kingdom', 0),
('GD', 'Grenada', 0),
('GE', 'Georgia', 0),
('GF', 'French Guiana', 0),
('GH', 'Ghana', 0),
('GI', 'Gibraltar', 0),
('GL', 'Greenland', 0),
('GM', 'Gambia', 0),
('GN', 'Guinea', 0),
('GP', 'Guadeloupe', 0),
('GQ', 'Equatorial Guinea', 0),
('GR', 'Greece', 0),
('GS', 'South Georgia and the South Sandwich Islands', 0),
('GT', 'Guatemala', 0),
('GU', 'Guam', 0),
('GW', 'Guinea-Bissau', 0),
('GY', 'Guyana', 0),
('HK', 'Hong Kong', 0),
('HM', 'Heard Island and Mcdonald Islands', 0),
('HN', 'Honduras', 0),
('HR', 'Croatia', 0),
('HT', 'Haiti', 0),
('HU', 'Hungary', 0),
('ID', 'Indonesia', 0),
('IE', 'Ireland', 0),
('IL', 'Israel', 0),
('IN', 'India', 0),
('IO', 'British Indian Ocean Territory', 0),
('IQ', 'Iraq', 0),
('IR', 'Iran, Islamic Republic of', 0),
('IS', 'Iceland', 0),
('IT', 'Italy', 0),
('JM', 'Jamaica', 0),
('JO', 'Jordan', 0),
('JP', 'Japan', 0),
('KE', 'Kenya', 0),
('KG', 'Kyrgyzstan', 0),
('KH', 'Cambodia', 0),
('KI', 'Kiribati', 0),
('KM', 'Comoros', 0),
('KN', 'Saint Kitts and Nevis', 0),
('KP', 'Korea, Democratic People''s Republic of', 0),
('KR', 'Korea, Republic of', 0),
('KW', 'Kuwait', 0),
('KY', 'Cayman Islands', 0),
('KZ', 'Kazakhstan', 0),
('LA', 'Lao People''s Democratic Republic', 0),
('LB', 'Lebanon', 0),
('LC', 'Saint Lucia', 0),
('LI', 'Liechtenstein', 0),
('LK', 'Sri Lanka', 0),
('LR', 'Liberia', 0),
('LS', 'Lesotho', 0),
('LT', 'Lithuania', 0),
('LU', 'Luxembourg', 0),
('LV', 'Latvia', 0),
('LY', 'Libyan Arab Jamahiriya', 0),
('MA', 'Morocco', 0),
('MC', 'Monaco', 0),
('MD', 'Moldova, Republic of', 0),
('MG', 'Madagascar', 0),
('MH', 'Marshall Islands', 0),
('MK', 'Macedonia, the Former Yugoslav Republic of', 0),
('ML', 'Mali', 0),
('MM', 'Myanmar', 0),
('MN', 'Mongolia', 0),
('MO', 'Macao', 0),
('MP', 'Northern Mariana Islands', 0),
('MQ', 'Martinique', 0),
('MR', 'Mauritania', 0),
('MS', 'Montserrat', 0),
('MT', 'Malta', 0),
('MU', 'Mauritius', 0),
('MV', 'Maldives', 0),
('MW', 'Malawi', 0),
('MX', 'Mexico', 0),
('MY', 'Malaysia', 0),
('MZ', 'Mozambique', 0),
('NA', 'Namibia', 0),
('NC', 'New Caledonia', 0),
('NE', 'Niger', 0),
('NF', 'Norfolk Island', 0),
('NG', 'Nigeria', 0),
('NI', 'Nicaragua', 0),
('NL', 'Netherlands', 0),
('NO', 'Norway', 0),
('NP', 'Nepal', 0),
('NR', 'Nauru', 0),
('NU', 'Niue', 0),
('NZ', 'New Zealand', 0),
('OM', 'Oman', 0),
('PA', 'Panama', 0),
('PE', 'Peru', 0),
('PF', 'French Polynesia', 0),
('PG', 'Papua New Guinea', 0),
('PH', 'Philippines', 0),
('PK', 'Pakistan', 0),
('PL', 'Poland', 0),
('PM', 'Saint Pierre and Miquelon', 0),
('PN', 'Pitcairn', 0),
('PR', 'Puerto Rico', 0),
('PS', 'Palestinian Territory, Occupied', 0),
('PT', 'Portugal', 0),
('PW', 'Palau', 0),
('PY', 'Paraguay', 0),
('QA', 'Qatar', 0),
('RE', 'Reunion', 0),
('RO', 'Romania', 0),
('RU', 'Russian Federation', 0),
('RW', 'Rwanda', 0),
('SA', 'Saudi Arabia', 0),
('SB', 'Solomon Islands', 0),
('SC', 'Seychelles', 0),
('SD', 'Sudan', 0),
('SE', 'Sweden', 0),
('SG', 'Singapore', 0),
('SH', 'Saint Helena', 0),
('SI', 'Slovenia', 0),
('SJ', 'Svalbard and Jan Mayen', 0),
('SK', 'Slovakia', 0),
('SL', 'Sierra Leone', 0),
('SM', 'San Marino', 0),
('SN', 'Senegal', 0),
('SO', 'Somalia', 0),
('SR', 'Suriname', 0),
('ST', 'Sao Tome and Principe', 0),
('SV', 'El Salvador', 0),
('SY', 'Syrian Arab Republic', 0),
('SZ', 'Swaziland', 0),
('TC', 'Turks and Caicos Islands', 0),
('TD', 'Chad', 0),
('TF', 'French Southern Territories', 0),
('TG', 'Togo', 0),
('TH', 'Thailand', 0),
('TJ', 'Tajikistan', 0),
('TK', 'Tokelau', 0),
('TL', 'Timor-Leste', 0),
('TM', 'Turkmenistan', 0),
('TN', 'Tunisia', 0),
('TO', 'Tonga', 0),
('TR', 'Turkey', 0),
('TT', 'Trinidad and Tobago', 0),
('TV', 'Tuvalu', 0),
('TW', 'Taiwan, Province of China', 0),
('TZ', 'Tanzania, United Republic of', 0),
('UA', 'Ukraine', 0),
('UG', 'Uganda', 0),
('UM', 'United States Minor Outlying Islands', 0),
('US', 'United States', 0),
('UY', 'Uruguay', 0),
('UZ', 'Uzbekistan', 0),
('VA', 'Holy See (Vatican City State)', 0),
('VC', 'Saint Vincent and the Grenadines', 0),
('VE', 'Venezuela', 0),
('VG', 'Virgin Islands, British', 0),
('VI', 'Virgin Islands, U.s.', 0),
('VN', 'Viet Nam', 0),
('VU', 'Vanuatu', 0),
('WF', 'Wallis and Futuna', 0),
('WS', 'Samoa', 0),
('YE', 'Yemen', 0),
('YT', 'Mayotte', 0),
('ZA', 'South Africa', 0),
('ZM', 'Zambia', 0),
('ZW', 'Zimbabwe', 0);