ALTER TABLE `products` ADD `SKU` VARCHAR(25) NULL DEFAULT NULL;
ALTER TABLE `products` ADD `cost` DECIMAL(9,2) NULL DEFAULT NULL;
ALTER TABLE `products` DROP `category`;
UPDATE `acp_login` SET `can_view_reports` = 1;