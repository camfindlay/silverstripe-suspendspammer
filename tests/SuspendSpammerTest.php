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
	public function testLegitUserNotBlocked()
	{

		$user = DataObject::get_one("Member", "Nickname = 'legituser'");

		$this->assertTrue($user ? true : false);
		$this->assertFalse($user->IsSuspended());

	}

	/**
	 * Ensure a spammer get their account suspended straight away.
	 */
	public function testSpamUserSuspended()
	{

		$spammer = new Member();
		$spammer->Nickname = "loveguru69";
		$spammer->FirstName = "LoveGuru";
		$spammer->Occupation = "Astrology";
		$spammer->Company = "vashikaran specialist mantra";
		$spammer->Email = "loveguru69@gmail.com";

		$spammer->write();

		$user = DataObject::get_one("Member", "Nickname = 'loveguru69'");

		$this->assertTrue($user ? true : false);
		$this->assertTrue($user->IsSuspended());


	}


}
