<?php
//Creates a Carousel of random products using the JS JCarousel plugin

require_once dirname(__FILE__)."/../preload.php";

//Use incremental ID to allow multiple carousels per page
if (!$config->isNode('temp','carouselid')) $config->setNode('temp','carouselid',0);
?><div id='carousel<?php echo $config->getNode("temp","carouselid");?>' class="jMyCarousel" style="width: 150px;">
<ul>
  	<?php 
		//Selects up to ten random products from the database, and creates an image linking to them
		$result = $dbConn->query("SELECT id FROM `products` WHERE active=1 ORDER BY RAND() LIMIT 0, ".$config->getNode('widget_carousel','images'));
		
		$size = $config->getNode("widget_carousel", "imageScale");
		//Load Item
		while ($row = $dbConn->fetch($result)) {
			$item = new Item($row['id']);
			echo "<li><a href='".$item->getURL()."'>";
			echo "<center><img src='item/imageProvider.php?id=".$item->getID()."' alt='".$item->getName()."' title='".$item->getName()."' style='width: ".(75*$size)."px; height: ".(75*$size)."px' /></center>";
			echo "</a></li>";
		}
	?>
</ul></div>
<?php
if ($dbConn->rows($result) != 0) {//Don't activate if there's no products
?>
	<script type="text/javascript">
	$(function() {
		$("#carousel<?php echo $config->getNode("temp","carouselid");?>").jMyCarousel({
			visible: '<?php echo $config->getNode('widget_carousel','indexHeight');?>px',
			auto : true, 
			vertical : true,
			speed : 2500,
			circular: true
		});
	});
	</script>
<?php
}
$config->setNode("temp","carouselid",$config->getNode("temp","carouselid")+1); //Increment the Carousel ID
?>