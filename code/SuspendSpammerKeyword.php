<?php
/**
 * Stores user created suspected spam keywords as a {@link DataObject}.
 *
 * @author Cam Findlay <cam@silverstripe.com>
 * @package suspendspammer
 * */

class SuspendSpammerKeyword extends DataObject {

	public static $db = array(
		'Title' => 'Varchar(255)'
	);

	public static $field_labels = array(
		'Title' => 'Spam Keyword'
	);

	/**
	 * Trims and makes the keywords lowercase for comparison.
	 */
	public function onBeforeWrite(){
		$this->Title = preg_replace('/\s+/', ' ', strtolower(trim($this->Title)));
		parent::onBeforeWrite();
	}

}
