ALTER TABLE `customers` ADD `archive` BOOL NOT NULL DEFAULT '0';
ALTER TABLE `customers` ADD `can_contact` BOOL NOT NULL DEFAULT '1';

CREATE TABLE `newsletters` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`title` VARCHAR( 100 ) NOT NULL ,
`body` TEXT NOT NULL
);