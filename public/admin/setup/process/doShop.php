<?php
/**
 *  This file is the Controller that process the input from the shop stage
 * of the setup wizard. It may re-call the paths Controller if its sanity checks
 * deem it necessary.
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
 *  @Name public/admin/setup/process/doShop.php
 *  @Version 2.00
 *  @author Lloyd Wallis <flump5281@gmail.com>
 *  @copyright Copyright (c) 2009-2012, Lloyd Wallis
 *  @package Flumpshop
 */
require_once '../../../../includes/setup/lib.inc';

$tree = 'shop';
$navigation_stage = '3.1';
$next_stage = getNextStage(5);
$falsify_keys = array('enabled', 'vat', 'shopMode', 'sendFeedback', 'debug');

require '../../../../models/setup_stage_process_generic.inc';
require '../../../../views/setup_stage_process_referrer.inc';