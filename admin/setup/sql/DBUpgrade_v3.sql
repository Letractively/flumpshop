CREATE TABLE `item_category` (
`id` INT(11) unsigned NOT NULL AUTO_INCREMENT,
`itemid` INT(11),
`catid` INT(11),
PRIMARY KEY (`id`)
);

INSERT INTO `item_category` (itemid, catid)
SELECT id, category FROM products;

ALTER TABLE `products`
DROP COLUMN `category`;