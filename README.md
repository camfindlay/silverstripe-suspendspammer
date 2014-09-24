# Suspend Spammer #

[![Build Status](https://travis-ci.org/camfindlay/silverstripe-suspendspammer.png?branch=master)](https://travis-ci.org/camfindlay/silverstripe-suspendspammer)

## Overview ##
This module checks new Member object writes against a user defined set of trigger keywords which may indicated that 
the Member is in fact a spammer. This is for use alongside the silverstripe/silverstripe-forum.

## Maintainers ##
Cam Findlay <cam@silverstripe.com>

## Requirements ##
 * SilverStripe 3.1 (Framework and CMS)
 * [Forum module 0.7 (compatible with 3.1 SilverStripe core)](https://github.com/silverstripe/silverstripe-forum)

## Installation ##

Best practice is to install via composer (otherwise download files and unzip to your webroot):

    composer require camfindlay/silverstripe-suspendspammer dev-master

Run dev/build in the browser (http://<yourwebsite>/dev/build?flush=all) 

or 

via command line

    sake (cd <yourwebroot> && ./sapphire/sake dev/build flush=all)

## Usage ##
Simply go to the CMS, access the *Spam Keywords* menu then add any spam related keywords you wish to check when a new 
member registers. 

By default SuspendSpammer check the Occupation and Company fields (added by the silverstripe/forum module). 
This can be changed by setting the following static in your _config/config.yml file and supplying an array of keywords.

    Member:
      fields_to_check:
        - Occupation
        - Company
        - AnyOtherKeywords

You can also create a CSV file of keywords using the column heading 'Title' and import through the default ModelAdmin 
importer.

## Email Notifications ##
In order to be notified when a suspected spam registration is automatically suspended you can enable email notification and designate an email address to receive the notification emails. 

The notification email includes the email address of the suspected spammer (so you can look them up in the Security section of SilverStripe CMS) as well as the fields set in the '$fields_to_check' against which the spam keywords matched. 

This will allow you to perform a quick smoke test in case there was a false-positive and the registration was a legitimate member.

To enable set the following in your *_config/config.yml* file:  
    
    Admin:
      admin_email: 'admin@yourdomain.com'
    Member:
      email_to: you@yourdomain.com
      enable_email: true
    
The 'admin_email' is the address the email will come from, 'email_to' sets the address of the person you wish to be notified of the spam registration. Lastly you have to enable notification emails with 'enable_email'.
