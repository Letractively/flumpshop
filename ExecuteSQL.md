# Introduction #
**This feature is for advanced users only. Not only could you break Flumpshop or delete data unintentionally, you could also break the server the system is running on if you're not careful. Don't touch it unless you completely understand what you are doing, i.e. are a Flumpshop developer**

This feature requires the [SecondTierAdmin](SecondTierAdmin.md) Password.

Flumpshop is designed with the idea of being able to control everything simply and easily. However, sometimes there is something you want to do that Flumpshop might not have a button for. So, it provides the next best thing - a button that will let you tell it what you want it to do.

This feature runs directly on the Flumpshop's revolutionary [Database Abstraction Layer](DatabaseAbstractionLayer.md), allowing you to write MySQL that will be executed _almost_ directly on the Flumpshop backend database engine. However, it has the unique advantage of also running through the Database Object's query parser, which adapts this query into SQLite if that is the backend system, the same parser that is used by Flumpshop itself. Remember to be careful though, and refer to a database structure diagram when making adjustments. If possible, it might be better to use a GUI such as phpMyAdmin or SQLite Browser.