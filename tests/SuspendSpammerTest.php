<?php
/**
 * Tests the decorted Member Object to block suspected spam registrations.
 *
 * @author Cam Findlay <cam@silverstripe.com>
 * @package suspendspammer
 */
class SuspendSpammerTest extends SapphireTest {

	static $fixture_file = "suspendspammer/tests/SuspendSpammerTest.yml";


	/**
	 * Ensure the legit user is allowed to be created and not suspended.
	 */
	public function testLegitUserNotBlocked() {

		$user = Member::get()->filter('Nickname','legituser')->First();
		Debug::show($user->ForumStatus);

		$this->assertTrue($user ? true : false);
		$this->assertNotEquals($user->ForumStatus, 'Ghost');

	}

	/**
	 * Ensure a spammer get their account suspended straight away.
	 */
	public function testSpamUserSuspended()	{

		$spammer = Member::create();
		$spammer->Nickname = "loveguru69";
		$spammer->FirstName = "LoveGuru";
		$spammer->Occupation = "Astrology";
		$spammer->Company = "vashikaran specialist mantra";
		$spammer->Email = "loveguru69@gmail.com";

		$spammer->write();

		$user = Member::get()->filter('Nickname','loveguru69')->First();

		$this->assertTrue($user ? true : false);
		$this->assertEquals($user->ForumStatus, 'Ghost');

	}


}
