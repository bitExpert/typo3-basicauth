# Typo3 Basic Authentication

Adds basic authentication (htaccess) support for Typo3 frontend pages. 
This may be useful for staging or development environments.

## Disclaimer
The extension should not be used to protect high confidential data. 
Other extension, your own code or misconfiguration may disable the 
access protection on the hole website or some sub pages.

## Prerequisites

 - PHP >= 5.6
 - Typo3 >= 7.6.2
  
## Setup

### Composer

Run `composer require bitexpert/typo3-basic-authentication`

### Manual
 1. Download [zip file](https://typo3.org/extensions/repository/view/basicauth-1) and extract to typo3conf/ext folder
 1. Activate extension in Extension Manager
 1. (Only for composer mode) Add autoload entry to your composer.json file `"bitExpert\\Basicauth\\": "web/typo3conf/ext/basicauth/Classes"`
 
## Usage
 
The access protection is disabled by default. 
The activation is handled by a file named `BASIC_AUTH_ENABLED` in the `typo3conf` folder.

Due this file it is possible to enable/disable the protection due scripts, easily.

The protection can be toggled in the CMS administration `ADMIN TOOLS -> Basic Auth` menu.

All backend users (active and not deleted) are able to authenticate with their credentials.



