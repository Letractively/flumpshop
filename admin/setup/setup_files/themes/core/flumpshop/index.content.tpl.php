<tr>
	<td>
		<table id="content_container" class="ui-corner-top">
			<tr>
				<td colspan="2"><h2 id="page_title"><?php echo $page_title?></h2></td>
			</tr>
			<tr>
				<td colspan="2" id="page_text"><?php echo $config->getNode("messages","homePage")?></td>
			</tr>
			<tr>
				<td colspan="2"><h4><?php echo $config->getNode("messages","featuredItemHeader")?></h4></td>
			</tr>
			<tr id="featured_items">
				<td class="featured_item"><?php echo $featured_item_1?></td>
				<td class="featured_item"><?php echo $featured_item_2?></td>
			</tr>
			<tr>
				<td colspan="2"><h4><?php echo $config->getNode("messages","popularItemHeader")?></h4></td>
			</tr>
			<tr id="popular_items">
				<td class="popular_item"><?php echo $popular_item_1?></td>
				<td class="popular_item"><?php echo $popular_item_2?></td>
			</tr>
			<tr>
				<td colspan="2"><h4><?php echo $config->getNode("messages","latestNewsHeader")?></h4></td>
			</tr>
			<tr id="latest_news">
				<td><?php echo $latest_news?></td>
			</tr>
			<tr><td style="height:15px"></td></tr>
			<tr>
				<td colspan="2"><h4><?php echo $config->getNode("messages","technicalHeader")?></h4></td>
			</tr>
			<tr id="quick_tips">
				<td><?php echo $quick_tips?></td>
			</tr>
		</table>
	</td>
</tr>