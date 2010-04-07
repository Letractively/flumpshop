<?php
require_once dirname(__FILE__)."/../header.inc.php";
require_once "../../../includes/vars.inc.php";
$dbConn = db_factory();

function randStr() {
	$chars = array("a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z",1,2,3,4,5,6,7,8,9,0,"A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z","@","=","+","-","_","&","*","%","$");
	$str = "";
	for ($i=0;$i<10;$i++) {
		$num = rand(0,70);
		$str .= $chars[$num];
	}
	return $str;
}

$adminPass = randStr();

//Create default user account
$dbConn->query("INSERT INTO `acp_login` (uname,pass,pass_expires,can_add_products,can_edit_products,can_delete_products,can_add_categories,can_edit_categories,can_delete_categories,can_edit_pages,can_edit_delivery_rates,can_post_news,can_add_customers,can_contact_customers,can_view_customers,can_view_orders,can_edit_orders,can_view_reports) VALUES ('inituser','".md5(sha1($adminPass))."',
 '".$dbConn->time(time()+3600*12)/*12 HOURS!*/."',1,1,1,1,1,1,1,1,1,1,1,1,1,1,1)");
?><h1>Goodbye, and hello!</h1>
<p>Hello, and welome to your new Flumpshop installation. My name is the Flumpnet robot, and I'm getting a strange feeling I've met you before. Anyway, the Flumpshop site is now live and raring to go, and I'm gonna give you a few helpful tips to get you started.</p>
<ul>
	<li><strong><big>Flumpshop has created a temporary login.</big></strong> - To set up user accounts, the login <strong>inituser</strong>, with password <strong><?php echo $adminPass;?></strong> has been created for the Admin CP. This account will be deleted in 12 hours, and if you haven't created your own account before then, you will be locked out.</li>
	<li><strong>Flumpshop is clever.</strong> With me watching your every step, and daily backups by default, you can be assured that whatever you do, there is always a way to get yourself back on your feet.</li>
    <li><strong>Flumpshop is psychic.</strong> Flumpshop can tell when there's an update available for the base system, and gives you information about you, and can even download and apply the update as soon as you authorise it, so you always know you'll have access to the latest updates.</li>
    <li><strong>Flumpshop is simple.</strong> Flumpshop was built with complete simplicity in mind. In the Admin CP, no changes are ever more than three clicks away, and with a clever and intuitive UI, you know you can find what you're looking for.</li>
    <li><strong>Flumpshop is comprehensive.</strong> We hate it as much as you do when you have to change the source code or spend hours traipsing through forums to change a simple thing. Throughout the development process, whenever someone said "Wouldn't it look better if...", we didn't change it. Instead, we made a button fr you to change it, so you know that anything you're unhappy with, you know you can fix it in moments</li>
    <li><strong>Flumpshop is evolving.</strong> Flumpshop is entirely open source, and you can post problems, get sample packs, documentation and many other resources at any time by clicking on over to <a href='http://flumpshop.googlecode.com' target="_blank">flumpshop.googlecode.com</a>. This means that even if there is something you can't change by pushing a button, you can tell us to add that button.</li>
    <li><strong>Flumpshop is more than a shop.</strong> Flumpshop can be used for whatever you want. Everything is customisable, so if you wanted, it can even be used as a CMS, and completely disable all shop-related features.</li>
    <li><strong>Flumpshop is extendable.</strong> And this isn't a poorly written spam email about bedroom performance. You can get extensions from the community, or even write your own.</li>
</ul>
<h2>What next?</h2>
<ul>
	<li><a href="../../" target="_top">Click here</a> to go to your Admin CP and begin adding information to your site</li>
    <li><a href="../../../" target="_top">Click here</a> to go to the frontend of your site and see it in its empty glory</li>
    <li><a href="http://flumpshop.googlecode.com" target="_blank">Click here</a> to go to the Flumpshop development site</li>
    <li><a href="http://www.theflump.com" target="_blank">Click here</a> to go to the Flumpnet website</li>
</ul><?php
require_once dirname(__FILE__)."/../footer.inc.php";
?>