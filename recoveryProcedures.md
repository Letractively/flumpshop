# Introduction #

**Please note that the contents of this document are provisional and subject to change**

Although every precaution is always made to ensure that the Flumpshop platform does not fail, the system is not perfect, and additionally, there are other issues that could cause a system failure. It is important that preventive steps are taken, and that in the event of a failure additional restore procedures for your needs are always in place.


# Prevention #

## XML Backups ##
By default, the Flumpshop platform is configured to create an XML backup of two of the primary data stores, conf.txt and the MySQL/SQLite database, every 24 hours in the offlineDir/backups directory. These backups are never deleted, so a snapshot of this information is always available. It is strongly recommended, however, that a copy of these backup files is kept on another system, preferably offsite.

## Image Backups ##
Although the vast majority of information is stored in the XML backup file, this file does not include the images that are uploaded to the site, stored in the offlineDir/images directory. An operator should regularly backup the contents of this file as well, in order to prevent data loss in the event of a hard disk failure.

In addition, any custom plugins and themes should also be backed up for the same reason. Official ones can easily be obtained again, so these are not vital.

# Recovery #

In the event of a system failure, this is the recommended recovery procedure for the Flumpshop platform. Please note the process assumes that the physical machine is running, with an operating system and web server successfully configured.

## To Recover Normally ##

  1. Ensure you have access to a recent backup of all data, preferably prior to the failure, on your system
  1. If necessary, delete/rename the old Flumpshop rootDir and offlineDir to remove any potential faults
  1. Download and extract the latest version of Flumpshop to your web server
  1. Follow the regular setupWizard process to configure the system
  1. Login to the ACP using the inituser account
  1. Optional: In Advanced->Configuration Manager, set in Main Settings, uncheck the Site Enabled box, and in Predefined Text String, change the Site Disabled message to a friendly notice to show visitors to your system whilst the recovery is completed
  1. If the new system has a different offlineDir, or any other potentially breaking differences to anything configured in the setup wizard, manually edit the Backup XML file to reflect these changes
  1. Ensure that you have a login for the both the main ACP Login and the Tier 2 Login that work in the XML file, otherwise importing it will result in you being locked out of the system
  1. Go to Advanced->Import, and browse for and upload the backup XML file
  1. Be patient whilst the system imports the data
    * Please note the import system does not detect a timeout, and if the PHP or Web Server configuration forces it to abort, the process must be started again, therefore you must manually increase the maximum allowed execution time
  1. Review the resulting web page to ensure that there were no issues, which will have to be manually resolved if they occur. Please note if you disabled the site, it will now be reactivated again
  1. Reload the ACP, and relog with the restored login details
  1. At this point, you should now be able to transfer any files backed up from the offlineDir. It is highly recommended that you do **NOT** copy the conf.txt file, as this may break the system again
  1. Install any themes/plugins that you require. Since they should store information in conf.txt they generally do not need reconfiguring
  1. Browse the site frontend and ACP to ensure that all features are once again working functionally

# Known Flaws #

The platform is still in development, and has the following known issues

  * The import feature currently does not restore conf.txt data
  * The import feature currently cannot filter what information is restored
  * The import feature cannot detect nor work around an execution timeout