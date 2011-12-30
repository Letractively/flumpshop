<?php
/**
 *  Outputs a login form for the ACP
 * 
 * Requires the following variables:
 * @var string $login_action The page to submit the login form
 * @var string $login_message The message that is displayed to users
 * @var Array $login_fields An array of arrays with the following keys:
 * label: A user-friendly description of the field
 * type: The value to put in the type parameter of the input element
 * id: The name and id values of the input element
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
 *  @Name views/admin_login.inc
 *  @Version 1.01
 *  @author Lloyd Wallis <flump5281@gmail.com>
 *  @copyright Copyright (c) 2009-2012, Lloyd Wallis
 *  @package Flumpshop
 */
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <style type="text/css">
      body {background-color: #1e2b5b; color: #FFF; font: Arial, Helvetica, sans-serif;}
      form {width: 300px; margin: 150px auto 0 auto;}
      .header {font: "Trebuchet MS", Arial, Helvetica, sans-serif; font-size: 23px;}
      .header2 {color: #c43131;}
      .title {background-color: #0e78ee; padding: 0 5px; line-height: 1.5; font-size: 17px;}
      .content {background-color: #e7e7e7; padding: 0 5px; color: #000; font-size: 12px;}
      .content label {color: #1e2b5b;}
      table td {text-align: right;}
      input {border: 1px solid #1e2b5b; color: #1e2b5b; width: 200px;}
      input.submit {width: auto; position: relative; left: 220px; border: 3px outset #1e2b5b; background: #FFF; color: #1e2b5b; font-size: 14px; font-weight: bold;}
    </style>
    <title>Flumpshop Login</title></head><body>
    <form action="<?= $login_action ?>" method="post">
      <div class="header"><img src="/admin/images/logo.jpg" alt="Flumpshop Logo" />flump<span class='header2'>shop</span></div>
      <div class="title">please login...</div>
      <div class="content">
        <?= $login_message ?>
        <table>
          <?php
          foreach ($login_fields as $field) {
            ?>
            <tr>
              <td><label for="<?= $field['id'] ?>"><?= $field['label'] ?></label></td>
              <td><input type="<?= $field['type'] ?>" name="<?= $field['id'] ?>" id="<?= $field['id'] ?>" /></td>
            </tr>
            <?php
          }
          ?>
        </table>
        <input type="submit" class="submit" value="Login" />
      </div>
    </form>
  </body>
</html>