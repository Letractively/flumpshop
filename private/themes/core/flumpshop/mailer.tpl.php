<body bgcolor="#C8C8C8" link="#263137" vlink="#263137" style="min-width:600px;max-width:1000px;margin:auto">
	<font face="Verdana,Geneva,sans-serif">
		<table width="90%" border="0" style="margin:auto">
			<tr>
				<td bgcolor="#2A2A2A" height="54">
					<a style="color:#FFFFFF" href='<?php echo $config->getNode('paths','root');?>'>
						<h1 style="color:#FFFFFF">
								<font color="#FFFFFF" face="Rockwell,arial"><?php echo $config->getNode('messages','name');?></font>
						</h1>
					</a>
				</td>
			</tr>
			<tr bgcolor="#F8F6F6">
				<td>
					<p>Problems viewing the email? <a href="<?php echo $config->getNode('paths','root');?>/newsletters/?id=[[[mailing_id]]]">View it on our website</a></p>
					<br /><?php echo $mail_content;?><br />
					<p><font size="1">You have received this email after subscribing to our mailing list. If you do not wish to receive newsletters from us in future, <a href='<?php echo $config->getNode("paths","root");?>/account/unsubscribe.php'>Click here to unsubscribe</a>.</font></p>
				</td>
			</tr>
			<tr bgcolor="#303030">
				<td>
					<font size="1">
						<font color="#DDD"><?php echo $config->getNode('messages','footer');?></font>
						<a href='<?php echo $config->getNode('paths','root');?>/legal/privacy.php'><font color="#0DB3DB">Privacy Policy</font></a> &middot;
						<a href='<?php echo $config->getNode('paths','root');?>/legal/terms.php'><font color="#0DB3DB">Terms and Conditions</font></a> &middot;
						<a href='<?php echo $config->getNode('paths','root');?>/legal/disclaimer.php'><font color="#0DB3DB">Disclaimer</font></a>&nbsp;&nbsp;
					</font>
			</td>
		</tr>
	</font>
</body>