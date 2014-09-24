<?php
/**
 * Tests the decorted Member Object to block suspected spam registrations.
 *
 * @author Cam Findlay <cam@silverstripe.com>
 * @package suspendspammer
 */
class SuspendSpammerTest extends SapphireTest {

	static $fixture_file = 'suspendspammer/tests/SuspendSpammerTest.yml';

	public function setUp() {
		Config::nest();
		parent::setUp();

		Config::inst()->update('Email', 'admin_email', 'no-reply@somewhere.com');
		Config::inst()->update('Member', 'email_to', 'someone@somewhere.com');
		Config::inst()->update('Member', 'enable_email', true);
	}

	public function tearDown() {
		parent::tearDown();
		Config::unnest();
	}

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
		$this->assertEmailSent('someone@somewhere.com', 'no-reply@somewhere.com', 'Suspected spammer registration suspended.');

		$user->ForumStatus = 'Normal';
		$user->write();

		$this->assertEquals($user->ForumStatus, 'Normal');
	}

}
