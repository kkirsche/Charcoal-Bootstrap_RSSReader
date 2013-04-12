### Version 0.1.1
* AJAX Bug fix - SimplePie Autoloader was not being loaded correctly.
* Added "Loading feed" and "Error!" dialogues to better communicate to the user what is happening.

**Known Bugs:**
* Some feeds are not read correctly, thus causing a blank screen (as AJAX is getting the XHR header back, but with no data)