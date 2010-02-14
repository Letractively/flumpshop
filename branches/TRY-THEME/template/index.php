<?php
require_once "../preload.php";
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="style.css" />
<title>Flumpshop Dynamic Theme Demo</title>
</head>
<body>
<div id="container">
    <div id="header">
        <h1 id="site_name">Flumpshop</h1>
        <h2 id="site_tagline">Branch: Try-theme</h2>
    </div><!--End Header-->
    <ul id="tabs">
        <li><a href="#">Home</a></li>
        <li><a href="#">About</a></li>
        <li><a href="#">Contact</a></li>
        <li><a href="#">Login</a></li>
        <li><a href="#">Cart</a></li>
        <li><a href="#">Admin</a></li>
    </ul><!-- End Tabs-->
    <div id="category_container">
        <ul id="categories">
            <li>
                <a class='category' href='#'>Category 1</a>
                <ul class="subcategory_container">
                    <li><a class="subcategory_link" href="#">Subcategory 1</a></li>
                    <li><a class="subcategory_link" href="#">Subcategory 2</a></li>
                    <li><a class="subcategory_link" href="#">Subcategory 3</a></li>
                    <li><a class="subcategory_link" href="#">Subcategory 4</a></li>
                    <li><a class="subcategory_link" href="#">Subcategory 5</a></li>
                </ul>
            </li>
            <li>
                <a class='category' href='#'>Category 2</a>
                <ul class="subcategory_container">
                    <li><a class="subcategory_link" href="#">Subcategory 1</a></li>
                    <li><a class="subcategory_link" href="#">Subcategory 2</a></li>
                    <li><a class="subcategory_link" href="#">Subcategory 3</a></li>
                    <li><a class="subcategory_link" href="#">Subcategory 4</a></li>
                    <li><a class="subcategory_link" href="#">Subcategory 5</a></li>
                </ul>
            </li>
            <li>
                <a class='category' href='#'>Category 3</a>
                <ul class="subcategory_container">
                    <li><a class="subcategory_link" href="#">Subcategory 1</a></li>
                    <li><a class="subcategory_link" href="#">Subcategory 2</a></li>
                    <li><a class="subcategory_link" href="#">Subcategory 3</a></li>
                    <li><a class="subcategory_link" href="#">Subcategory 4</a></li>
                    <li><a class="subcategory_link" href="#">Subcategory 5</a></li>
                </ul>
            </li>
            <li>
                <a class='category' href='#'>Category 4</a>
                <ul class="subcategory_container">
                    <li><a class="subcategory_link" href="#">Subcategory 1</a></li>
                    <li><a class="subcategory_link" href="#">Subcategory 2</a></li>
                    <li><a class="subcategory_link" href="#">Subcategory 3</a></li>
                    <li><a class="subcategory_link" href="#">Subcategory 4</a></li>
                    <li><a class="subcategory_link" href="#">Subcategory 5</a></li>
                </ul>
            </li>
            <li>
                <a class='category' href='#'>Category 5</a>
                <ul class="subcategory_container">
                    <li><a class="subcategory_link" href="#">Subcategory 1</a></li>
                    <li><a class="subcategory_link" href="#">Subcategory 2</a></li>
                    <li><a class="subcategory_link" href="#">Subcategory 3</a></li>
                    <li><a class="subcategory_link" href="#">Subcategory 4</a></li>
                    <li><a class="subcategory_link" href="#">Subcategory 5</a></li>
                </ul>
            </li>
        </ul>
    </div><!-- End Navigation-->
    <div id="page_text">
        <h3 id="page_title">Welcome to <?php echo $config->getNode('messages','name');?></h3>
        <p><?php echo $config->getNode("messages","homePage");?></p>
    </div><!-- End Page Text -->
    <div id="featured_items">
        <h4 id="featured_items_header"><?php echo $config->getNode("messages","featuredItemHeader");?></h4>
        <div class="featured_item" id="featured_item_1"><?php
            //Find Featured Items in DB
            $result = $dbConn->query("SELECT value FROM `stats` WHERE `key` LIKE 'featuredItem%'");
            //Load first item
            $row = $dbConn->fetch($result);
            $item = new Item($row['value']);
            echo $item->getDetails("INDEX");
        ?></div>
        <div class="featured_item" id="featured_item_2"><?php
			//Load second item
        	$row = $dbConn->fetch($result);
			$item = new Item($row['value']);
            echo $item->getDetails("INDEX");
		?></div>
    </div><!-- End Featured Items -->
    <div id="popular_items">
        <h4 id="popular_items_header">Most Popular Items</h4>
        <div class="popular_item" id="popular_item_1">
            <img src="../images/paypal.png" alt="Popular Item 1" />
            <h5>Popular Item</h5>
            <p>Popular Item Description</p>
        </div>
        <div class="popular_item" id="popular_item_2">
            <img src="../images/paypal.png" alt="Popular Item 2" />
            <h5>Popular Item</h5>
            <p>Popular Item Description</p>
        </div>
    </div><!-- End Popular Items -->
    <div id="latest_news">
        <h4>Latest News</h4>
        <p>Things are happening</p>
    </div><!-- End Latest News -->
    <div id="tech_tips">
        <h4>Technical Tips News</h4>
        <p>Things are happening</p>
    </div><!-- End Tech Tips -->
    <div id="footer">
    <p>Copyright &copy; 2009-2010 Flumpnet. All Rights Reserved.</p>
    </div>
</div><!--End Container-->
</body>
</html>
