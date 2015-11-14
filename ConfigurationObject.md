# Introduction #

The Configuration Object stores all the vital information for each page to load. It stores everything from the directory the site is in to static page content.


# Details #

The Configuration Object is serialized in a conf.txt file in the [offlineDir](offlineDir.md) and is loaded at runtime for each page. The system knows where to find the file because of the path stored in the conf.txt in the [root](root.md) of the site.

The object contains an md5-encoded string of the Administrator Password, as well as plain text login details for the database server and the SMTP server. It is for this reason that it must not be kept in a publicly accessible location.

# Editing the Configuration Object #
Most of the changes should be made automatically when necessary, or can be edited using safe, [Flumpnet Robot](FlumpnetRobot.md)-validated forms.

For those that can't, you can access the Configuration Object directly by using the [Configuration Manager](ConfigurationManager.md) in the [Advanced](Advanced.md) menu of the [Admin CP](AdminCP.md)