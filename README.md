# Easy_MultipleDomains - Module for Magento 1.4+

[![Build Status](https://travis-ci.org/easycom-xmedias/Easy_MultipleDomains.svg?branch=master)](https://travis-ci.org/emilient/Easy_MultipleDomains)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/easycom-xmedias/Easy_MultipleDomains/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/emilient/Easy_MultipleDomains/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/easycom-xmedias/Easy_MultipleDomains/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/emilient/Easy_MultipleDomains/?branch=master)
[![Dependency Status](https://www.versioneye.com/user/projects/54ff26e54a10643473000042/badge.svg?style=flat)](https://www.versioneye.com/user/projects/54ff26e54a10643473000042)

[![Latest Stable Version](https://poser.pugx.org/easycom/magento-multipledomains/v/stable.svg)](https://packagist.org/packages/easycom/magento-multipledomains) 
[![Total Downloads](https://poser.pugx.org/easycom/magento-multipledomains/downloads.svg)](https://packagist.org/packages/easycom/magento-multipledomains) 
[![Latest Unstable Version](https://poser.pugx.org/easycom/magento-multipledomains/v/unstable.svg)](https://packagist.org/packages/easycom/magento-multipledomains) 
[![License](https://poser.pugx.org/easycom/magento-multipledomains/license.svg)](http://opensource.org/licenses/MIT)

## What is this ?

This is a module for [Magento](http://magento.com/) that helps to handle multiple domains names in a single installation and don't care of store things.
By default, Magento will build all links inside generated html with the default base url. This could be disable by using {{base_url}} tag as value of "Base URL" config parameter but it's not recommanded.

With this module, you could : 
 - Create an many domains as you need from System/Configuration admin section
 - Configure Package and Template used by each domain
 - Disable secure navigation domain by domain : this is usefull if you add a domain which is not handled by your SSL certificate. (For example, an iPad don't ask you if you want to accept a self-signed certificate)

This module could help you in this cases : 
 - You have multiples servers, like a Varnish frontend and one or more magento instance behind it; and your are tired to have to bypass varnish every time you have to verify if something is due to cached situation or you want to address specifically one of your servers.
 In this situation, you could define a domain per server, setup the list of these domains in the module and Magento will respond to each domain as it do for the default one.

 - You have to make a specific design for a tierce project like a WebApp and the buying process integrated in the app is in fact the one of your website, so you make a design to handle specific behaviors. Then you just have to define a domain used by the app and the module take the relay.
 No need to setup a store for it, which could be complex if you are in multilingual context and consume a lot of resource in reindexation for example, for nothing.

## How to install it

### Use modman
Install [modman](https://github.com/colinmollenhour/modman), go to your source repository then clone the module :
```
modman init {path_to_sources} # if init not already done
modman clone https://github.com/emilient/Easy_MultipleDomains.git
```

### Use composer
Add those lines to your composer.json file :
```
"require": {
    "easycom/magento-multipledomains": "dev-master"
}
```

Or simply tape this line to install the lastest stable version
```
composer require easycom/magento-multipledomains
```

Or, this line to add the master working version :
```
composer require easycom/magento-multipledomains dev-master
```


## Screenshots

### Capture of configuration in backend
<img src='https://raw.githubusercontent.com/Easycom-Solutions/Easy_MultipleDomains/master/screenshots/screenshot-001.png' alt="Capture of configuration in backend" /><br/>

### Capture of frontend with default base_url
<img src='https://raw.githubusercontent.com/Easycom-Solutions/Easy_MultipleDomains/master/screenshots/screenshot-002.png' alt="Capture of frontend with default base_url" /><br/>

### Capture of frontend with additionnal domain and custom theme configuration
<img src='https://raw.githubusercontent.com/Easycom-Solutions/Easy_MultipleDomains/master/screenshots/screenshot-003.png' alt="Capture of frontend with additionnal domain and custom theme configuration" /><br/>

## Rewrites
This module rewrites two class of Magento Core.
See htdocs/app/code/community/MultipleDomains/Rewrite for details

Note that you have to apply a patch for retro-compatibility of the module magento Magento 1.6 and lower.

## Issue Tracker

If you want to post an issue please use github issue tracker : [Issue Tracker](https://github.com/Easycom-Solutions/Easy_MultipleDomains/issues)


## Contributions


If you want to take a part in improving our extension please create a fork, then feel free to make a pull request.

Everything needed to develop is in the develop/ folder.
You just need virtualbox, vagrant and composer to get started. See README of develop/ folder

