<?php
/**
 *  This view outputs a simple page that referrs the client to the next
 * controller needed.
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
 *  @Name views/setup_stage_process_referrer.inc
 *  @Version 1.00
 *  @author Lloyd Wallis <flump5281@gmail.com>
 *  @copyright Copyright (c) 2009-2012, Lloyd Wallis
 *  @package Flumpshop
 */
require dirname(__FILE__) . '/../includes/setup/header.inc.php';
?><h1>Processing...</h1>
<p>The Flumpnet Robot is busy processing the information you have just given him. He'll ask you a few more questions when he's caught up.</p>
<script type="text/javascript">
  newurl = '../?frame=leftFrame&p=<?=$navigation_stage?>';
  if (parent.leftFrame.window.location.href != newurl)
  parent.leftFrame.window.location = newurl;
  window.location = '../stages/<?=$next_stage?>';
</script>
<?php
require dirname(__FILE__) . '/../includes/setup/footer.inc.php';