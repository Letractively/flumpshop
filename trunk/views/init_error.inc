<?php
/**
 *  Outputs a Flumpshop-styler error page in the event of an unrecoverable error
 * when initialising resources, before the configuration is available.
 * 
 * Requires the following variables:
 * @var string $error_description A description of what has happened.
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
 *  @Name views/init_error.inc
 *  @Version 1.01
 *  @author Lloyd Wallis <flump5281@gmail.com>
 *  @copyright Copyright (c) 2009-2012, Lloyd Wallis
 *  @package Flumpshop
 */
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <style type="text/css">
      body {background-color: #1e2b5b; color: #FFF; font: Arial, Helvetica, sans-serif}
      .wrapper {width: 40%; margin: 10% auto auto auto}
      .header {font: "Trebuchet MS", Arial, Helvetica, sans-serif; font-size: 23px;text-align:center}
      .header2 {color: #c43131;}
      .title {background-color: #0e78ee; padding: 0 5px; line-height: 1.5; font-size: 17px;}
      .content {background-color: #e7e7e7; padding: 0 5px; color: #000; font-size: 14px}
      strong, a {font-weight: bold; color: #c43131}
      .error {color: #888; font-size: 12px}
    </style>
    <title>500 Internal Server Error</title></head><body>
    <div class="wrapper">
      <div class="header"><img src="/admin/images/logo.jpg" alt="Flumpshop Logo" />flump<span class='header2'>shop</span></div>
      <div class="title">500 Internal Server Error</div>
      <div class="content">
        Sorry, we encountered an error trying to process your request. Apologies for any inconvenience caused.<br />
        <ul>
          <li>If you were at the checkout and were entering payment details, please <strong>contact us</strong> as soon as possible to ensure that your order and payment have been received. Do not reload the page as you may be charged again.</li>
          <li>If you clicked on a link to get here, try <a href="javascript:history.go(0)">reloading the page</a> to see if that resolves the problem.</li>
          <li>If you are the website administrator, then the message at the bottom of this window provides more information about the error.</li>
        </ul>
        <div class="error">Error: <?=$error_description?></div>
      </div>
    </div>
  </body>
</html>