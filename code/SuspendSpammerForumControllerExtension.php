<?php
/**
 * Suspends a suspected spammer post based on user input of common spammer related words.
 *
 * @author Cam Findlay <cam@silverstripe.com>
 * @package suspendspammer
 */
class SuspendSpammerForumControllerExtension extends DataExtension {

	public function beforePostMessage($data, $member = null){
		if(!$member){
			$member = Member::currentUser();
		}
		
		//If member has CMS access they don't need to be checked out.
		if(Permission::check('CMS_ACCESS_CMSMain', 'any', $member)){
			return;
		}
		
		//Is this the Members first message and they are not already a ghost or banned.
		if($member->ForumStatus == 'Normal' && $member->NumPosts() == 0
		){
			
			$content = $data['Title'] .' '. $data['Content'];
			
			//Check title and content for spam keywords.
			$spam_words = SuspendSpammerKeyword::get();
			
			//ensure it returns as an array.
			if($spam_words->Exists()) {
				
				$spam_words = $spam_words->map()->toArray();
				$matches = array();
				
				//Check words for known spammer content
				$matchFound = preg_match_all(
									"/\b(" . implode($spam_words,"|") . ")\b/i", 
									$content, 
									$matches
								);
				
				//@TODO do phone number pattern recognittion in the future as this is a 
				//common indication of the human spammers this module is design to counter.

				if($matchFound) {
					//If true, ghost the member.
					$member->ForumStatus = 'Ghost';
					$member->write();
					
					//Email the admin
					if(Config::inst()->get('SuspendSpammerEmail', 'enable_email')) {
							$body = "<h1>Suspected Spammer Post</h1>
							<p>Email: " . $member->Email . "</p>
							<p>" . $content . "</p>";

							SuspendSpammerEmail::create($body)
								->send();
					}
					
				}
				
			}
						
		}
		
	}

}
