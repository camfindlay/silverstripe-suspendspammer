<?php
/**
 * Tests the decorted Member Object to block suspected spam registrations.
 *
 * @author Cam Findlay <cam@silverstripe.com>
 * @package suspendspammer
 */
class SuspendSpammerTest extends SapphireTest {

	static $fixture_file = 'suspendspammer/tests/SuspendSpammerTest.yml';

	/**
	 * Ensure a spammer get their account suspended straight away.
	 */
	public function testSpamUserSuspended()	{
		$spammer = Member::create();
		$spammer->Nickname = 'loveguru69';
		$spammer->FirstName = 'LoveGuru';
		$spammer->Occupation = 'Astrology';
		$spammer->Company = 'vashikaran specialist mantra';
		$spammer->Email = 'loveguru69@gmail.com';
		$spammer->write();

		$user = Member::get()->filter('Nickname','loveguru69')->first();
		$this->assertEquals($user->ForumStatus, 'Ghost');
	}

	public function testUnchangedFieldsDoesNotTriggerStatusChange() {
		$spammer = Member::create();
		$spammer->Nickname = 'loveguru69';
		$spammer->FirstName = 'LoveGuru';
		$spammer->Occupation = 'Astrology';
		$spammer->Company = 'vashikaran specialist mantra';
		$spammer->Email = 'loveguru69@gmail.com';
		$spammer->write();

		$user = Member::get()->filter('Nickname','loveguru69')->first();
		$this->assertEquals($user->ForumStatus, 'Ghost');

		$user->ForumStatus = 'Normal';
		$user->write();

		$this->assertEquals($user->ForumStatus, 'Normal');
	}

}
