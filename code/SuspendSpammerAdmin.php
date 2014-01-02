<?php
/**
 * {@link ModelAdmin} to manage the suspected spammer keywords via the CMS.
 *
 * @author Cam Findlay <cam@silverstripe.com>
 * @package suspendspammer
 **/

class SuspendSpammerAdmin extends ModelAdmin {
	
	static $managed_models = array('SuspendSpammerKeyword');

	static $url_segment = 'spamkeywords';
	
	static $menu_title = 'Spam Keywords';
}