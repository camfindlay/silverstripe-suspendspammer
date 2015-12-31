<?php
/**
 * {@link ModelAdmin} to manage the suspected spammer keywords via the CMS.
 *
 * @author Cam Findlay <cam@silverstripe.com>
 * @package suspendspammer
 **/

class SuspendSpammerAdmin extends ModelAdmin
{
    
    private static $managed_models = array('SuspendSpammerKeyword');

    private static $url_segment = 'spamkeywords';
    
    private static $menu_title = 'Spam Keywords';
}
