<?php
/**
 * Stores user created suspected spam keywords as a {@link DataObject}.
 *
 * @author Cam Findlay <cam@silverstripe.com>
 * @package suspendspammer
 * */

class SuspendSpammerKeyword extends DataObject {

	private static $db = array(
		'Title' => 'Varchar(255)'
	);

	private static $field_labels = array(
		'Title' => 'Spam Keyword'
	);
	
	public function requireDefaultRecords() {
			parent::requireDefaultRecords();
			//Ensure at least 1 spam keyword exists.
			if( !SuspendSpammerKeyword::get()->Exists() ) {
				$keyword = SuspendSpammerKeyword::create();
				$keyword->Title = 'astrologer';
				$keyword->write();
			}

		}
		
	/**
	 * Trims and makes the keywords lowercase for comparison.
	 */
	public function onBeforeWrite(){
		$this->Title = preg_replace('/\s+/', ' ', strtolower(trim($this->Title)));
		parent::onBeforeWrite();
	}

}
