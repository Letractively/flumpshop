CREATE TABLE `cache` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nodeName` varchar(75) NOT NULL UNIQUE,
  `cache` longtext NOT NULL,
  PRIMARY KEY (`id`)
);