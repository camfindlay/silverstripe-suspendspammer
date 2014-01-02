<?php
/**
 * Suspends a suspected spammer registration based on user input of common spammer related words.
 *
 * @author Cam Findlay <cam@silverstripe.com>
 * @package suspendspammer
 */


class SuspendSpammer extends DataObjectDecorator {

	//Commonly filled in fields in the forum module to check.
	public static $fields_to_check = array( 'Occupation', 'Company' );


	/**
	 * Decorate the Member object to stop the writing of registrations is the user is suspected of being a spammer.
	 */
	public function onBeforeWrite() {
		parent::onBeforeWrite();

		$spam_needles = DataObject::get('SuspendSpammerKeyword');
		if($spam_needles){
			$spam_needles = $spam_needles->map();
		}

		//if anything matches do something about it to stop the spam registration.
		if ( 0 < count( array_intersect( $this->spamHaystack() , $spam_needles ) ) ) {
			$this->owner->SuspendedUntil = date( 'Y-m-d', time() + strtotime( '+ 100 years' ) );

			//@todo - Email the admin to let them know to check the registration and re-enable if it was a false positive.
		}
	}



	/**
	 * Creates an array to hold common used fields upon registration which would denote Member as a spammer.
	 * @retrun array haystack of words to check against known spam related words.
	 */
	private function spamHaystack() {
		
		$spamstring = '';
		
		foreach(self::$fields_to_check as $field){

			$spamstring .= $this->owner->$field . ' ';

		}

		$spam_haystack = array_map( 'strtolower', explode( ' ', $spamstring ) );

		return $spam_haystack;
		
	}

}
