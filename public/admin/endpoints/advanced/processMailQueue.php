<?php

/**
 *  Process emails for x seconds
 *
 *  This file is part of Flumpshop.
 *
 *  Flumpshop is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  Flumpshop is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with Flumpshop.  If not, see <http://www.gnu.org/licenses/>.
 *
 *
 *  @Name processMailQueue.php
 *  @Version 1.0
 *  @author Lloyd Wallis <flump@flump.me>
 *  @copyright Copyright (c) 2009-2011, Lloyd Wallis
 *  @package Flumpshop
 */

require_once '../../../preload.php';

//Set the max time the script can run for
$t = (isset($_GET['time'])) ? intval($_GET['time']) : 30;
$startTime = time();
loadClass('Mail');
$mailer = new Mail();

while (time() - $t < $startTime) {
	//Send an email
	$result = $dbConn->query('SELECT mail_id, name, email, title, body
		FROM newsletter_queue,newsletters
		WHERE newsletter_queue.newsletter_id = newsletters.newsletter_id
		LIMIT 1');
	if ($dbConn->rows($result) === 1) {
		$row = $dbConn->fetch($result);
		//Delete the queue entry
		$dbConn->query('DELETE FROM newsletter_queue WHERE mail_id='.$row['mail_id'].' LIMIT 1');
		
		$mailer->send($row['name'], $row['email'], $row['title'], $row['body']);
		$_SESSION['messageCounter']++;
	} else {
		die('Flumpshop has processed all '.$_SESSION['messageCounter'].' emails in the newsletter queue.');
	}
}
echo 'Flumpshop has processed '.$_SESSION['messageCounter'].'/'.$_SESSION['messageTotal'].' emails in the newsletter queue';
?>
