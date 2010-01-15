<?php
class Mail {
	var $connection;
	
	function Mail() {
		global $config;
		debug_message("Connecting to SMTP Server...");
		$this->connection = fsockopen($config->getNode("smtp","host"),$config->getNode("smtp","port"));
		$response = fgets($this->connection,4096);
		if (empty($response)) {
			trigger_error("Could not connect to SMTP Server.",E_USER_ERROR);
		} else {
			debug_message("Response from SMTP Server: ".$response);
			//Connection Established. Login.
			$this->sendMsg("EHLO ".$_SERVER['HTTP_HOST']);
			$this->sendMsg("AUTH LOGIN");
			$this->sendMsg(base64_encode($config->getNode("smtp","uname")));
			$this->sendMsg(base64_encode($config->getNode("smtp","password")));
			debug_message("Connected to SMTP Server",true);
		}
	}
	
	function send($toName, $toAddr, $subject, $body) {
		global $config;
		$subject = $this->stripString($subject);
		$this->sendMsg("MAIL FROM: ".$config->getNode("smtp","email"));
		$this->sendMsg("RCPT TO: $toName <$toAddr>");
		$this->sendMsg("DATA");
		$this->sendMsg("To: {$toName} <{$toAddr}>\r\nFrom: ".$this->stripString($config->getNode('messages','name'))." <".$config->getNode('smtp','email').">\r\nSubject: {$subject}\r\nMIME-Version: 1.0\r\nContent-Type: text/html; charset=UTF-8\r\n\r\n{$body}\r\n.");
	}
	
	function stripString($str) {
		return preg_replace("/[^a-zA-Z0-9\s]/","",html_entity_decode($str));
	}
	
	function __destruct() {
		$this->sendMsg("QUIT");
		fclose($this->connection);
	}
	
	function sendMsg($msg) {
		debug_message("Message sent to SMTP Server: ".htmlentities($msg));
		fputs($this->connection,$msg."\r\n");
		$response = fread($this->connection,8192);
		debug_message("Response from SMTP Server: ".$response);
		return $response;
	}
}
?>