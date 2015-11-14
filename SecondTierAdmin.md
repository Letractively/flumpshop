# Introduction #

When you first set up your Flumpshop instance, you define an administrator password. Additionally, at the end of the installation, you are also provided with an admin password to log in with using the inituser account. This may seem a little strange, but this is because Flumpshop operates a two-tier authentication system.


# Details #

When a user logs in to /admin using the username and password created for them, they are given access to the basic sections of the Admin CP that they have been granted permission to administer. To control these accounts, as well as to provide an additional level of security, many Flumpshop ACP features required you to log in with a second password, after the first account. This second password is the one defined in the [Setup Wizard](SetupWizard.md), and is stored (encrypted, of course) in the [Configuration Object](ConfigurationObject.md). This means it cannot be hacked using any SQL exploits, as this is not stored in the database, and means some operations can still be completed in the event of a database failure.

This second tier password is used when you want to access any pages in the Advanced or Plugins sections of the Admin CP, as well as when you want to re-run the setup wizard. Using the config-defined password for the setup wizard ensure that the system can still be modified in the event that it can no longer access the database.