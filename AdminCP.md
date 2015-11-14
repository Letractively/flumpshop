# Introduction #

**The Admin CP has been completely redesigned, however this page has been kept for reference.**

The Admin CP is the first port of call for changing anything on the site. It lets you do anything, from creating a new product or category to viewing orders and changing the content of static pages. It can be accessed by navigation to the /admin directory within the main directory of the shop.

The Admin CP currently uses a single authentication method - an Administrator Password stored in the [Configuration Object](ConfigurationObject.md). This is set up as part of the [Setup Wizard](SetupWizard.md), and cannot currently be changed within the system.


# Using the Admin CP #
After logging in, the site will load a menu on the left of the page, which allows you to select a task. Note: You may have to refresh the page to see this - [See Issue 1](http://code.google.com/p/flumpshop/issues/detail?id=1) for details.

## Top Level Menus ##
  * [Create Object](CreateObject.md) - Create a new object for the site
  * [Edit Object](EditObject.md) - Edit an existing object or static page
  * [View Orders](ViewOrders.md) - View and update orders
  * [Delivery Settings](DeliverySettings.md) - Configure delivery options
  * [Waves](Waves.md) - Development
  * [Advanced](Advanced.md) - Access advanced settings and direct [Configuration Object](ConfigurationObject.md) editing

Close is not a menu, but simply closes the current Admin module.