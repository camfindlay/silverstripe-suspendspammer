<?php
/**
 * Suspends a suspected spammer registration based on user input of common spammer related words.
 *
 * @author Cam Findlay <cam@silverstripe.com>
 * @package suspendspammer
 */
class SuspendSpammerMemberExtension extends DataExtension
{

    //Commonly filled in fields in the forum module to check.
    private static $fields_to_check = array(
        'Occupation',
        'Company'
    );

    /**
     * Decorate the Member object to ghost the user if suspected of being a spammer.
     */
    public function onBeforeWrite()
    {
        parent::onBeforeWrite();

        // we only want to run this if any of the fields to check have been modified.
        $shouldRun = false;
        foreach ($this->owner->config()->fields_to_check as $field) {
            if ($this->owner->isChanged($field)) {
                $shouldRun = true;
                break;
            }
        }

        if (!$shouldRun) {
            return;
        }

        $spam_needles = SuspendSpammerKeyword::get();
        if ($spam_needles) {
            $spam_needles = $spam_needles->map()->toArray();
        } else {
            $spam_needles = array();
        }

        //if anything matches do something about it to stop the spam registration.
        if (0 < count(array_intersect($this->spamHaystack(), $spam_needles))) {
            //Ghost a spammer.
            $this->owner->ForumStatus = 'Ghost';

            //Email the admin
            if (Config::inst()->get('SuspendSpammerEmail', 'enable_email') && !$this->owner->ID) {
                $body = "<h1>Suspected Spammer Registration</h1>
				<ul>
				<li>Email: " . $this->owner->Email . "</li>";
                foreach ($this->owner->config()->fields_to_check as $field) {
                    $body .= "<li>" . $field . ": " . $this->owner->$field . "</li>";
                }
                $body .= "</ul>";

                SuspendSpammerEmail::create($body)
                    ->send();
            }
        }
    }

    /**
     * Creates an array to hold common used fields upon registration which would denote Member as a spammer.
     *
     * @retrun array haystack of words to check against known spam related words.
     */
    private function spamHaystack()
    {
        $spamstring = '';

        foreach ($this->owner->config()->fields_to_check as $field) {
            $spamstring .= $this->owner->$field . ' ';
        }

        $spam_haystack = array_map('strtolower', explode(' ', $spamstring));
        return $spam_haystack;
    }
}
