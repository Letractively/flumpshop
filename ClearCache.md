# Introduction #

This feature required the [SecondTierAdmin](SecondTierAdmin.md) Password.

Flumpshop improves the efficiency and load times of the site frontend using a process called caching - where the information is generated the first time it is requested, then stored. This stored copy is what is sent the next time a user requests the information.

To enable the site to update, Flumpshop automatically clears cache elements 24h after they were created. However, if you have made a change to the site that you need to be made available immediately, it may be necessary to use this feature to force the Cache to be emptied.

Before you do this, it is recommended to try pushing Ctrl+F5 in your web browser to check it is Flumpshop that is caching the resources, not your web browser. After clearing the cache, the next page load of the Flumpshop storefront may be significantly slower whilst vital elements are regenerated.