<?php
/**
 * Suspends a suspected spammer registration based on user input of common spammer related words.
 *
 * @author Cam Findlay <cam@silverstripe.com>
 * @package suspendspammer
 */

class SuspendSpammerExtension extends DataExtension {

	//Commonly filled in fields in the forum module to check.
	private static $fields_to_check = array( 'Occupation', 'Company' );

	//enable emails to be send to admin on suspected spammer registrations.
	private static $enable_email = false;

	//Email address to send suspended registrations to for review.
	private static $email_to;


	/**
	 * Decorate the Member object to stop the writing of registrations is the user is suspected of being a spammer.
	 */
	public function onBeforeWrite() {
		parent::onBeforeWrite();

		$spam_needles = SuspendSpammerKeyword::get();
		if ( $spam_needles ) {
			$spam_needles = $spam_needles->map()->toArray();
		} else {
			$spam_needles = array();
		}

		//if anything matches do something about it to stop the spam registration.
		if ( 0 < count( array_intersect( $this->spamHaystack() , $spam_needles ) ) ) {
			
			//Ghost a spammer.
			$this->owner->ForumStatus = 'Ghost';

			//Email the admin to let them know to check the registration and re-enable if it was a false positive.
			if ( self::$enable_email && !$this->owner->ID ) {
				
				$from = Email::getAdminEmail();
				
				$to = self::$email_to;
				
				$subject = "Suspected spammer registration suspended.";

				$body = "<h1>Suspected Spammer Details</h1>
				<ul>
				<li>Email: " . $this->owner->Email . "</li>";
				foreach ( self::$fields_to_check as $field ) {
					$body .= "<li>" . $field . ": " . $this->owner->$field . "</li>";
				}
				$body .= "</ul>";

				$email = Email::create( $from, $to, $subject, $body );
				$email->send();
			}
		}
	}



	/**
	 * Creates an array to hold common used fields upon registration which would denote Member as a spammer.
	 *
	 * @retrun array haystack of words to check against known spam related words.
	 */
	private function spamHaystack() {

		$spamstring = '';

		foreach ( self::$fields_to_check as $field ) {

			$spamstring .= $this->owner->$field . ' ';

		}

		$spam_haystack = array_map( 'strtolower', explode( ' ', $spamstring ) );

		return $spam_haystack;

	}

}
