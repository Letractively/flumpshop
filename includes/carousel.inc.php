<?php
//Creates a Carousel of random products using the JS JCarousel plugin

//LIMITATION: Only once per page

require_once dirname(__FILE__)."/../preload.php";
?>
<ul id='carousel' class='jcarousel jcarousel-skin-tango'>
  	<?php 
		//Selects up to ten random products from the database, and creates an image linking to them
		$result = $dbConn->query("SELECT id FROM `products` WHERE active=1 ORDER BY RAND() LIMIT 10");
		
		//Load Item
		while ($row = $dbConn->fetch($result)) {
			$item = new Item($row['id']);
			echo "<li><a href='{$item->getURL()}'>";
			echo "<img src='item/imageProvider.php?id={$item->getID()}' width='75' height='75' alt='{$item->getName()}' title='{$item->getName()}' />";
			echo "</a></li>";
		}
	?>
</ul>
<?php
if ($dbConn->rows($result) != 0) {//Don't activate if there's no products
?>
	<script type="text/javascript">
	function carousel_initCallback(carousel)
	{
		// Disable autoscrolling if the user clicks the prev or next button.
		carousel.buttonNext.bind('click', function() {
			carousel.startAuto(0);
		});
	 
		carousel.buttonPrev.bind('click', function() {
			carousel.startAuto(0);
		});
	 
		// Pause autoscrolling if the user moves with the cursor over the clip.
		carousel.clip.hover(function() {
			carousel.stopAuto();
		}, function() {
			carousel.startAuto();
		});
	};
	 
	$('#carousel').jcarousel({
		auto: 2,
		animation: 'slow',
		scroll: 1,
		vertical: true,
		wrap: 'last',
		initCallback: carousel_initCallback
	});
	</script>
<?php
}
?>