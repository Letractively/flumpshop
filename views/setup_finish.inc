<?php
/**
 *  This view is the penultimate stage of the setup wizard. It outputs a short
 * page and a button to commit the setup's configuration.
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
 *  @Name views/setup_finish.inc
 *  @Version 1.01
 *  @author Lloyd Wallis <flump5281@gmail.com>
 *  @copyright Copyright (c) 2009-2012, Lloyd Wallis
 *  @package Flumpshop
 */
?><h1>Finish</h1>
<p>Well, we're reaching the end of our time together. Depending on what you selected, we may barely know each other, or it may seem like we have known each other forever.</p>
<p>Friendships aside, click the big button that says "Save" (yes, that one), to save all the things you've told me, and to start up your new site. Alternatively, it is possible to press F5 in your browser to make it like we never met.</p>
<p>Make your decision wisely, for I have all the time in the world. However, I should point out that from this point on I'm paid by the hour, and am also already late for my daughters xylophone recital. So come on, make a decision already. NOW. Come on, I haven't got all day.</p>
<form action="../process/doFinish.php">
<input type="submit" value="Save" />
</form>
<script type="text/javascript">
  parent.leftFrame.window.location = '../?frame=leftFrame&p=4.1';
</script>