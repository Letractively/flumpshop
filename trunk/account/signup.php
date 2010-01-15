<?php
$page_title = "Signup";
require_once dirname(__FILE__)."/../header.php";
?>
<h1 class="content">Sign Up</h1>
<form action='createaccount.php' method="post" name="signupform" id="signupform" class="validateForm">
<fieldset class="ui-widget-content">
	<legend>Login Details</legend>
    <table>
    	<tr>
        	<td><label for="username">Username</label></td>
            <td><input type="text" name="username" id="username" class="required alphanumeric" minlength="5" maxlength="75" remote="./checkuname.php" title="Invalid" /></td>
        </tr>
        <tr>
        	<td><label for="pass1">Password</label></td>
            <td><input type="password" name="pass1" id="pass1" class="required" minlength="6" /></td>
        </tr>
        <tr>
        	<td><label for="pass2">Confirm Password</label></td>
            <td><input type="password" name="pass2" id="pass2" class="required" equalto="#pass1" minlength="6" /></td>
        </tr>
    <tr><td colspan="2"><h3 class="content">Contact Details</h3></td></tr>
    <?php
	if ($config->getNode('account','requireEmailValidation') && !$config->getNode('account','requireAllAtSignup')) {
		//Email validation required, but address optional
echo <<<EOT
	<tr>
		<td><label for="email">Email Address</label></td>
		<td><input type="text" name="email" id="email" class="required email" remote="./checkemail.php" title="Already in use" /></td>
	</tr>
	<tr>
		<td colspan="2"><a href='javascript:void(0);' onclick='$("#addrFields").toggle("blind");'>I want to enter my Delivery Address now</a></td>
	</tr>
	<tr>
		<td colspan="2">
			<table id="addrFields" class="ui-helper-hidden">
				<tr>
					<td><label for="name">Name</label></td>
					<td><input type="text" name="name" id="name" minlength="6" maxlength="75" /></td>
				</tr>
				<tr>
					<td><label for="address1">Address 1</label></td>
					<td><input type="text" name="address1" id="address1" maxlength="100" /></td>
				</tr>
				<tr>
					<td><label for="address2">Address 2</label></td>
					<td><input type="text" name="address2" id="address2" maxlength="100" /></td>
				</tr>
				<tr>
					<td><label for="address3">Address 3</label></td>
					<td><input type="text" name="address3" id="address3" maxlength="100" /></td>
				</tr>
				<tr>
					<td><label for="postcode">Post Code</label></td>
					<td><input type="text" name="postcode" id="postcode" maxlength="15" /></td>
				</tr>
				<tr>
					<td><label for="country">Country</label></td>
					<td><select name="country" id="country" style="width: 157px;"><option selected="selected"></option>
EOT;
					$countries = $dbConn->query("SELECT * FROM `country` ORDER BY name ASC");
					while ($country = $dbConn->fetch($countries)) {
						echo "<option value='".$country['iso']."'>".$country['name']."</option>";
					}
					echo <<<EOT
					</select></td>
				</tr>
			</table>
		</td>
	</tr>
EOT;
	$validatorRules = 'name:{required:"#addrFields:visible"},address1:{required:"#addrFields:visible"},address2:{required:"#addrFields:visible"},address3:{required:"#addrFields:visible"},postcode:{required:"#addrFields:visible"},country:{required:"#addrFields:visible"}';
	} elseif ($config->getNode('account','requireEmailValidation') && $config->getNode('account','requireAllAtSignup')) {
		//Both Email and Address required at signup
echo <<<EOT
	<tr>
		<td><label for="email">Email Address</label></td>
		<td><input type="text" name="email" id="email" class="required email" remote="./checkemail.php" title="Already in use" /></td>
	</tr>
	<tr>
		<td colspan="2"><h4 class="content">Delivery Address</h4></td>
	<tr>
		<td><label for="name">Name</label></td>
		<td><input type="text" name="name" id="name" minlength="6" maxlength="75" class="required" /></td>
	</tr>
	<tr>
		<td><label for="address1">Address 1</label></td>
		<td><input type="text" name="address1" id="address1" maxlength="100" class="required" /></td>
	</tr>
	<tr>
		<td><label for="address2">Address 2</label></td>
		<td><input type="text" name="address2" id="address2" maxlength="100" class="required" /></td>
	</tr>
	<tr>
		<td><label for="address3">Address 3</label></td>
		<td><input type="text" name="address3" id="address3" maxlength="100" class="required" /></td>
	</tr>
	<tr>
		<td><label for="postcode">Post Code</label></td>
		<td><input type="text" name="postcode" id="postcode" maxlength="15" class="required" /></td>
	</tr>
	<tr>
		<td><label for="country">Country</label></td>
		<td><select name="country" id="country" style="width: 157px;" class="required"><option selected="selected"></option>
EOT;
		$countries = $dbConn->query("SELECT * FROM `country` ORDER BY name ASC");
		while ($country = $dbConn->fetch($countries)) {
			echo "<option value='".$country['iso']."'>".$country['name']."</option>";
		}
		echo <<<EOT
		</select></td>
	</tr>
EOT;
	$validatorRules = "";
	} else {
		//No Required Personal Information
		echo <<<EOT
	<tr>
		<td colspan="2">If you want, you can enter you contact information now to save time when you are ready to buy.<br /><a href='javascript:void(0);' onclick='$("#addrFields").toggle("blind");'>I want to enter my Contact Details now</a></td>
	</tr>
	<tr>
		<td colspan="2">
			<table id="addrFields" class="ui-helper-hidden">
				<tr>
					<td><label for="email">Email Address</label></td>
					<td><input type="text" name="email" id="email" class="email" remote="./checkemail.php" title="Already in use" /></td>
				</tr>
				<tr>
					<td><label for="name">Name</label></td>
					<td><input type="text" name="name" id="name" minlength="6" maxlength="75" /></td>
				</tr>
				<tr>
					<td><label for="address1">Address 1</label></td>
					<td><input type="text" name="address1" id="address1" maxlength="100" /></td>
				</tr>
				<tr>
					<td><label for="address2">Address 2</label></td>
					<td><input type="text" name="address2" id="address2" maxlength="100" /></td>
				</tr>
				<tr>
					<td><label for="address3">Address 3</label></td>
					<td><input type="text" name="address3" id="address3" maxlength="100" /></td>
				</tr>
				<tr>
					<td><label for="postcode">Post Code</label></td>
					<td><input type="text" name="postcode" id="postcode" maxlength="15" /></td>
				</tr>
				<tr>
					<td><label for="country">Country</label></td>
					<td><select name="country" id="country" style="width: 157px;"><option selected="selected"></option>
EOT;
					$countries = $dbConn->query("SELECT * FROM `country` ORDER BY name ASC");
					while ($country = $dbConn->fetch($countries)) {
						echo "<option value='".$country['iso']."'>".$country['name']."</option>";
					}
					echo <<<EOT
					</select></td>
				</tr>
			</table>
		</td>
	</tr>
EOT;
	$validatorRules = "email:{required: '#addrFields:visible'},name:{required:'#addrFields:visible'},address1:{required:'#addrFields:visible'},address2:{required:'#addrFields:visible'},address3:{required:'#addrFields:visible'},postcode:{required:'#addrFields:visible'},country:{required:'#addrFields:visible'}";
	}
	?>
    <tr>
    	<td><label for="captcha">Please enter the characters in the image<br />
        	<img id="siimage" align="left" style="padding-right: 5px; border: 0" src="../captcha/securimage_show.php?sid=<?php echo md5(time()) ?>" /></label>
            <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0" width="19" height="19" id="SecurImage_as3" align="middle">
                    <param name="allowScriptAccess" value="sameDomain" />
                    <param name="allowFullScreen" value="false" />
                    <param name="movie" value="../captcha/securimage_play.swf?audio=../captcha/securimage_play.php&bgColor1=#777&bgColor2=#fff&iconColor=#000&roundedCorner=5" />
                    <param name="quality" value="high" />
                    <param name="bgcolor" value="#ffffff" />
                    <embed src="../captcha/securimage_play.swf?audio=../captcha/securimage_play.php&bgColor1=#777&bgColor2=#fff&iconColor=#000&roundedCorner=5" quality="high" bgcolor="#ffffff" width="19" height="19" name="SecurImage_as3" align="middle" allowScriptAccess="sameDomain" allowFullScreen="false" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
            </object>
    		<br />
            
            <!-- pass a session id to the query string of the script to prevent ie caching -->
            <a tabindex="-1" style="border-style: none" href="javascript:void(0);" title="Refresh Image" onClick="document.getElementById('siimage').src = '../captcha/securimage_show.php?sid=' + Math.random(); return false"><span class="ui-icon ui-icon-refresh"></a>
        </td>
        <td>
        <input type="text" minlength="6" maxlength="6" class="required" remote="../captcha/ajaxVerify.php" name="captcha" id="captcha" title="Incorrect Code" />
        </td>
    </tr>
    <tr>
        <td><label for='privacy'>I agree to the <a href='<?php echo $config->getNode('paths','root');?>/legal/privacy.php' target='_blank'>Privacy Policy</a></label></td>
        <td><input type="checkbox" name="privacy" id="privacy" class="ui-widget-content required" /></td>
    </tr>
    <tr>
        <td><div style="width: 250px;"><label for='contact'>Don't tell me about special offers or products that might interest me</label></div></td>
        <td><input type="checkbox" name="contact" id="contact" class="ui-widget-content" /></td>
    </tr>
    <tr>
        <td colspan="2"><input type="submit" value="Sign Up!" class="ui-widget-content" /></td>
    </tr>
</table>
</fieldset>
</form>
<script type="text/javascript">$('#signupform').validate({rules: {<?php echo $validatorRules;?>}});
</script>
<?php
require_once dirname(__FILE__)."/../footer.php";
?>