CREATE TABLE  `acp_login` (
`id` INT(11) NOT NULL AUTO_INCREMENT ,
`uname` VARCHAR( 8 ) NOT NULL ,
`pass` VARCHAR( 32 ) NOT NULL ,
`last_login` TIMESTAMP NOT NULL ,
`last_tier2_login` TIMESTAMP NOT NULL ,
`can_add_products` BOOL NOT NULL DEFAULT  '0',
`can_edit_products` BOOL NOT NULL DEFAULT  '0',
`can_delete_products` BOOL NOT NULL DEFAULT  '0',
`can_add_categories` BOOL NOT NULL DEFAULT  '0',
`can_edit_categories` BOOL NOT NULL DEFAULT  '0',
`can_delete_categories` BOOL NOT NULL DEFAULT  '0',
`can_edit_pages` BOOL NOT NULL DEFAULT  '0',
`can_edit_delivery_rates` BOOL NOT NULL DEFAULT  '0',
`can_post_news` BOOL NOT NULL DEFAULT  '0',
`can_add_customers` BOOL NOT NULL DEFAULT  '0',
`can_contact_customers` BOOL NOT NULL DEFAULT  '0',
`can_view_customers` BOOL NOT NULL DEFAULT  '0',
`can_view_orders` BOOL NOT NULL DEFAULT  '0',
`can_edit_orders` BOOL NOT NULL DEFAULT  '0',
`can_view_reports` BOOL NOT NULL DEFAULT  '0',
`pass_expires` TIMESTAMP NOT NULL,
PRIMARY KEY (`id`)
);