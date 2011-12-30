<?php require_once dirname(__FILE__)."/../preload.php"; require_once dirname(__FILE__)."/../header.php";?>
  <h1 class="content">My Account</h1>
  <?php
  if (isset($_GET['id'])) {
	  if (isset($_SESSION['adminAuth']) && $_SESSION['adminAuth'] == true) {
		  $user = new User($_GET['id']);
		  echo "User: ".$user->getUname()."<br />";
		  if ($user->getCustomerExists()) {
				echo "<div class='ui-widget'><div class='ui-widget-header'>Default Contact Information</div>";
				echo "<div class='ui-widget-content'>".$user->customer->getName()."<br />";
				echo $user->customer->getAddress()."<hr />";
				echo $user->customer->getEmail()."</div></div>";
		  } else {
				echo "This user hasn't set any contact information.";
		  }
	  } else {
		  echo $config->getNode('messages','adminDenied');
	  }
  }
  elseif (!isset($_SESSION['login']['active']) or $_SESSION['login']['active'] == false) {
	  echo $config->getNode("messages","loginNeeded");
  } else {
	  $user = new User($_SESSION['login']['id']);
	  echo "Username: ".$user->getUname()."<br />";
	  if ($user->getCustomerExists()) {
			echo "<div class='ui-widget'><div class='ui-widget-header'>Default Contact Information</div>";
			if (!$user->customer->deliverySupported()) {
				echo "<div class='ui-state-error'><span class='ui-icon ui-icon-alert'></span>".$config->getNode('messages','countryNotSupported')."</div>";
			}
			echo "<div class='ui-widget-content'>".$user->customer->getName()."<br />";
			echo $user->customer->getAddress()."<hr />";
			echo $user->customer->getEmail()."</div></div>";
	  } else {
		  echo $config->getNode('messages','userNoCustomer');
		  ?>
          <form action="saveCustomer.php" name="newCustomerForm" id="newCustomerForm" method="post">
          	<fieldset>
            	<legend>Contact Information</legend>
                <table>
                	<tr>
                    	<td><label for="name">Full Name: </label></td>
                        <td><input type="text" name="name" id="name" class="ui-widget-content required" minlength="6" maxlength="75" /></td>
                        <td></td>
                    </tr>
                    <tr>
                    	<td><label for="address1">Address Line 1: </label></td>
                        <td><input type="text" name="address1" id="address1" class="ui-widget-content required" maxlength="100" /></td>
                        <td></td>
                    </tr>
                    <tr>
                    	<td><label for="address2">Address Line 2: </label></td>
                        <td><input type="text" name="address2" id="address2" class="ui-widget-content required" maxlength="100" /></td>
                        <td></td>
                    </tr>
                    <tr>
                    	<td><label for="address3">Address Line 3: </label></td>
                        <td><input type="text" name="address3" id="address3" class="ui-widget-content" maxlength="100" /></td>
                        <td></td>
                    </tr>
                    <tr>
                    	<td><label for="postcode">Post Code: </label></td>
                        <td><input type="text" name="postcode" id="postcode" class="ui-widget-content required" maxlength="15" /></td>
                        <td></td>
                    </tr>
                    <tr>
                    	<td><label for="country">Country: </label></td>
                        <td>
                        <select name="country" id="country" class="ui-widget-content required" style="width: 190px;">
                        	<option selected="selected"></option>
                            <?php
							$countries = $dbConn->query("SELECT * FROM `country` ORDER BY name ASC");
							while ($country = $dbConn->fetch($countries)) {
								echo "<option value='".$country['iso']."'>".$country['name']."</option>";
							}
							?>
                        </select>
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                    	<td><label for="email">Email: </label></td>
                        <td><input type="text" name="email" id="email" class="ui-widget-content required email" maxlength="75" /></td>
                        <td></td>
                    </tr>
                    <tr>
                    <td>&nbsp;</td>
                        <td style="text-align: right;"><input type="checkbox" name="terms" id="terms" class="ui-widget-content required" /></td>
                        <td><label for='terms' style="padding-right: 3em;">I agree to the <a href='<?php echo $config->getNode('paths','root');?>/legal/termsofuse.php' target='_blank'>Terms of Use</a></label></td>
                    </tr>
                    <tr>
                    	<td>&nbsp;</td>
                        <td style="text-align: right;"><input type="checkbox" name="privacy" id="privacy" class="ui-widget-content required" /></td>
                        <td><label for='privacy' style="padding-right: 3em;">I agree to the <a href='<?php echo $config->getNode('paths','root');?>/legal/privacy.php' target='_blank'>Privacy Policy</a></label></td>
                    </tr>
                    <tr>
                    	<td>&nbsp;</td>
                        <td style="text-align: right;"><input type="checkbox" name="contact" id="contact" class="ui-widget-content" /></td>
                        <td><label for='contact' style="padding-right: 3em;">I don't want to be contacted occasionally about special offers or products that might interest me</label></td>
                    </tr>
                    <tr>
                    	<td>&nbsp;</td>
                        <td><input type="submit" value="Save" class="ui-widget-content" /></td>
                    </tr>
                </table>
            </fieldset>
          </form>
          <script>
		  $('#newCustomerForm').validate({
										 errorClass: 'ui-state-error',
										 validClass: 'ui-widget-content',
										 errorPlacement: function(error, element) {
											 error.appendTo( element.parent("td").next("td") );
										 },
										 submitHandler: function(form) {
											jQuery(form).ajaxSubmit({target: '#newCustomerForm'});
										 }
										 });
		  </script>
          <?php
	  }
  }
  require_once dirname(__FILE__)."/../footer.php";
  ?>