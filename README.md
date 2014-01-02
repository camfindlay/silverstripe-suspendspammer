# Suspend Spammer #

[![Build Status](https://api.travis-ci.org/camfindlay/silverstripe-suspendspammer.png)](https://travis-ci.org/camfindlay/silverstripe-suspendspammer)

## Overview ##
This module checks new Member object writes against a user defined set of trigger keywords which may indicated that 
the Member is infact a spammer. This is for use alongside the silverstripe/silverstripe-forum.

## Maintainers ##
Cam Findlay <cam@silverstripe.com>

## Requirements ##
 * SilverStripe 2.4


## Installation ##

Best practice is to install via composer (otherwise download files and unzip to your webroot):

    composer require camfindlay/silverstripe-suspendspammer dev-master

Run dev/build in the browser (http://<yourwebsite>/dev/build?flush=all) 

or 

via command line

    sake (cd <yourwebroot> & ./sapphire/sake dev/build flush=all)

## Usage ##
Simply go to the CMS, access the *Spam Keywords* menu then add any spam related keywords you wish to check when a new 
member registers. 

By default SuspendSpammer check the Occupation and Company fields (added by the silverstripe/forum module). 
This can be changed by setting the following static in your _config.php file and supplying an array of keywords.

    SuspendSpammer::$fields_to_check = array( 'Occupation', 'Company', 'AnyOtherKeywords' );

You can also create a CSV file of keywords using the column heading 'Title' and import through the default ModelAdmin 
importer.