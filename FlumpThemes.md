# Introduction #
Flumpshop is currently in the process of being altered to allow the use of a theme system similar to that of Drupal's phpTemplate engine. This provides much more flexibility to the current customisation system, allowing developers to completely structure the page outline to whatever suits their purpose.

Themes, like plug-ins, will have complete access to the Configuration and Database objects, but will also have many predefined variables that contain useful resources for the system.

# Basic Format #
The basic outline of the templating system is that, in addition to the current theme directory e.g. offlineDir/themes/flumpshop, which contains CSS and image files, there is the addition of another tpl folder, offlineDir/themes/core/flumpshop, which contains .tpl.php files, which are used to create the page layout.

Flumpshop, as far as it is aware in terms of actual page content, does not notice the difference on most pages of the site, with the content being stored to a variables instead of being directly output, by using output buffers.

Flumpshop, as a minimum, requires four tpl files in order to properly operate.

## header.tpl.php ##
This file is where the header information goes, that is, information that is largely the same on every page. It is here where the 

&lt;head&gt;

 tag, and preferably the top of the page and the navigation, will lie. There are multiple variables available for this file:
  * $meta\_tags
  * $title - for the 

&lt;title&gt;

 tag
  * $css\_links - contains all 

&lt;link&gt;

 tags for CSS stylesheets
  * $js\_links - contains all 

&lt;script&gt;

 tags for Javascript includes
  * $plugin\_includes - contains the output of all plugins header.inc.php files
  * $tab\_links - contains the quick access buttons: Home, About, Contact, Cart and Login
  * $navigation\_links - contains the main navigation bar, containing first and second level categories in nested lists. This variable remains available for use in the content and footer tpl files.

Beyond the above mentioned variables, you have access to $dbConn and $config, and can structure the document however you wish.

## footer.tpl.php ##
The opposite the the header, this is where all information that is the same on every page gets placed. Again, to allow maximum freedom, all you are expected to have (although not required) to include are the following variables:
  * $footer\_links - Contains legal and feedback links
  * $footer\_scripts - Contains any 

&lt;script&gt;

 includes that must be in the footer
  * $plugin\_includes - contains the output of all plugin's footer.inc.php files

Again, you have access to $dbConn and $config.

## content.tpl.php ##
This basic template file will contain the actual body of the page. All that is needed in this file is the content of the $page\_content variable, which contains the important parts of the page. In future, this may be expanded depending on PAGE\_TYPE.

## index.content.tpl.php ##
The home page uses a different variable structure for the content to other pages, and has the following variables for you to gain a better degree of flexibility over your home page:
  * $page\_title
  * $featured\_item\_1
  * $featured\_item\_2
  * $popular\_item\_1
  * $popular\_item\_2
  * $latest\_news
  * $quick\_tips

Of course, you have $dbConn and $config at your disposal as well.

# Advanced Theming #
From the above example, you may have noticed that the PAGE\_TYPE of the home page is index, and that index.content.tpl.php is used for the content of the index page. The same holds true for all other page types, if such a file exists, for further customisation of the site.

Even more so, product and categories can use the item.1.content.tpl.php to have a custom layout for item ID#1, letting you have a custom layout for specific products and categories.

# Possible Advanced Features #
Add drag/drop elements and widgets on the home page as well as removing/adding new ones

Import .fml theme files (Flump Magic Language) and store them in a library (offlineDir again)

# Current Restrictions #
For security, the Flumpshop image provider can only serve PNG and GIF files.