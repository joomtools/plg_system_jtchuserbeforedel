# Joomla! System - Plugin - JT - Change user before delete
[![Joomla 3](https://img.shields.io/badge/Joomla™-3.10-darkgreen?logo=joomla&logoColor=c2c9d6&style=for-the-badge)](https://downloads.joomla.org/cms) [![Joomla 4](https://img.shields.io/badge/Joomla™-4.x-darkgreen?logo=joomla&logoColor=c2c9d6&style=for-the-badge)](https://downloads.joomla.org/cms)  
![PHP5.6](https://img.shields.io/badge/PHP-5.6-darkgreen?logo=php&style=for-the-badge) ![PHP7.x](https://img.shields.io/badge/PHP-7.x-darkgreen?logo=php&style=for-the-badge) ![PHP8.0](https://img.shields.io/badge/PHP-8.0-darkgreen?logo=php&style=for-the-badge) ![PHP8.1](https://img.shields.io/badge/PHP-8.1-darkgreen?logo=php&style=for-the-badge)

### [Downloads](https://github.com/joomtools/plg_system_jtchuserbeforedel/releases)

If you are an extension developer and your extension also stores the user ID in the database and should also be corrected when a user is deleted, you are welcome to make a pull request.

**The thing to keep in mind is:**
- The user ID must be an integer in a separate column of your table.
- Each row should have a unique ID.


Add a file with a unique name in one of the following paths:
- `/src/plugins/system/jtchuserbeforedel/src/Extension/onlyJoomla4`
- `/src/plugins/system/jtchuserbeforedel/src/Extension/onlyJoomla3`
- `/src/plugins/system/jtchuserbeforedel/src/Extension/all`

The class must implement my interface 'JtChUserBeforeDelInterface'.
Just take one of the existing extensions in `/src/plugins/system/jtchuserbeforedel/src/Extension/all` as a template.

**Please test the functionality before you make a pull request, since I don`t know your extension/database table I can only check if the source code fits but not the function.**

Thanks.
