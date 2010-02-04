<?php
require_once dirname(__FILE__)."/../header.inc.php";
?><h1>About You</h1>
<p>These are the most important predefined text strings. If you've specified that you want to customise those, then you'll be given the opportunity to change these again later.</p>
<form action="../process/doAbout.php" method="post"><table>
<tr>
	<td><label for="name">Site Name</label></td>
    <td><input type="text" name="name" id="name" value="<?php echo $_SESSION['config']->getNode('messages','name');?>" /></td>
    <td><span class='iconbutton' onclick='$("#nameHelp").dialog("open");'></span></td>
</tr>
<tr>
	<td><label for="tagline">Tagline</label></td>
    <td><input type="text" name="tagline" id="tagline" value="<?php echo $_SESSION['config']->getNode('messages','tagline');?>" /></td>
    <td><span class='iconbutton' onclick='$("#taglineHelp").dialog("open");'></span></td>
</tr>
<tr>
	<td><label for="email">Email Address</label></td>
    <td><input type="text" name="email" id="email" value="<?php echo $_SESSION['config']->getNode('messages','email');?>" /></td>
    <td><span class='iconbutton' onclick='$("#emailHelp").dialog("open");'></span></td>
</tr>
<tr>
	<td><label for="address">Address</label></td>
    <td><textarea name="address" id="address"></textarea></td>
    <td><span class='iconbutton' onclick='$("#addressHelp").dialog("open");'></span></td>
</tr>
<tr>
	<td><label for="password">Administrator Password</label></td>
    <td><input type="password" name="password" id="password" /></td>
    <td><span class='iconbutton' onclick='$("#passwordHelp").dialog("open");'></span></td>
</tr>
<tr>
	<td><label for="password2">Confirm Administrator Password</label></td>
    <td><input type="password" name="password2" id="password2" /></td>
    <td><span class='iconbutton' onclick='$("#password2Help").dialog("open");'></span></td>
</tr>
</table>
<input type="submit" value="Continue" />
</form>
<div class="ui-helper-hidden helpDialog" id="nameHelp" title="Site Name">The name of this website, which is used in legal pages, and the header.</div>
<div class="ui-helper-hidden helpDialog" id="taglineHelp" title="Tagline">The tagline that appears below the Site Name.</div>
<div class="ui-helper-hidden helpDialog" id="emailHelp" title="Email Address">The email address anyone can use, anytime, to contact a person who maintains or is in some way connected to the site. Your site, not the Flumpshop system in general. We covered all bases there. HA!</div>
<div class="ui-helper-hidden helpDialog" id="addressHelp" title="Address">The address of your business, or the registered office of your company.</div>
<div class="ui-helper-hidden helpDialog" id="passwordHelp" title="Administrator Password">This is the password you will use to access the Admin CP at the end of this setup.</div>
<div class="ui-helper-hidden helpDialog" id="password2Help" title="Confirm Administrator Password">Type the password you entered into the box above. Why? Because we said so. And to make sure you didn't type it wrong the first time.</div>
<script>
document.logDirFocus = true;
</script><?php
require_once dirname(__FILE__)."/../footer.inc.php";?>