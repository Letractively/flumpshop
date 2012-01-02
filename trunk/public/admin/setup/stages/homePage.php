<?php

/**
 *  This file is the Controller for the Home Page stage of the setup wizard.
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
 *  along with Flumpshop.  If not, sserveree <http://www.gnu.org/licenses/>.
 *
 *
 *  @Name public/admin/setup/stages/homePage.php
 *  @Version 2.00
 *  @author Lloyd Wallis <flump5281@gmail.com>
 *  @copyright Copyright (c) 2009-2012, Lloyd Wallis
 *  @package Flumpshop
 */
require_once '../../../../includes/setup/lib.inc';

require '../../../../models/setup_stage_homePage.inc';

require '../../../../includes/setup/header.inc.php';
require '../../../../views/setup_stage.inc';
require '../../../../includes/setup/footer.inc.php';

?>
<div class="ui-helper-hidden helpDialog" id="pageTextHelp" title="Show Page Text">Toggles whether or not to show the home page message on the home page.</div>
<div class="ui-helper-hidden helpDialog" id="featuredItemsHelp" title="Show Featured Items">Toggles whether or not to show the Featured Items section on the home page.</div>
<div class="ui-helper-hidden helpDialog" id="popularItemsHelp" title="Show Popular Items">Toggles whether or not to show the Popular Items section on the home page.</div>
<div class="ui-helper-hidden helpDialog" id="latestNewsHelp" title="Show Latest News">Toggles whether or not to show the Latest News section on the home page.</div>
<div class="ui-helper-hidden helpDialog" id="techTipsHelp" title="Show Techn Tips">Toggles whether or not to show the Tech Tips section on the home page.</div>
