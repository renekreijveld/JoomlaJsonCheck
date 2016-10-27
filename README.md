# Joomla JSON Check Plugin

Checks Joomla 'params' and 'rules' fields in your Joomla database for invalid JSON data.

This plugin sis based on the excellent work of Robert Went: https://github.com/robwent/joomla-json-db-check

## Why?

Joomla 3.6.3 improved validation of JSON data stored in the database (Usually as params for extensions). Unfortunately, this means that after updating, sites with invalid data can can become inaccessible.

The usual error message shown is:

> 0 - Error decoding JSON data: Syntax error

## How To Use

Simply install the plugin with the Joomla extension installer. Use the Install from URL option.

Then use this URL:

