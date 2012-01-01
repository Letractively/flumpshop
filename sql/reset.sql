DELETE FROM cache;
DELETE FROM stats WHERE `key` != 'dbVer';
DELETE FROM product_popular_blacklist;