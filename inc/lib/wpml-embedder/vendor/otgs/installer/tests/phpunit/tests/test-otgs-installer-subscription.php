<?php

/**
 * Class Test_OTGS_Installer_Subscription
 *
 * @group installer-419
 * @group admin-notices
 */
class Test_OTGS_Installer_Subscription extends OTGS_TestCase {

	/**
	 * @test
	 */
	public function it_creates_instance_with_no_data() {
		$subject = new OTGS_Installer_Subscription();
		$this->assertSame( \OTGS_Installer_Subscription::SUBSCRIPTION_STATUS_TEXT_MISSING, $subject->get_subscription_status_text() );
		$this->assertFalse( $subject->is_valid() );
	}

	/**
	 * @test
	 */
	public function it_creates_instance_with_lifetime_subscription() {
		$data                 = array();
		$data['data']         = new stdClass();
		$data['data']->status = \OTGS_Installer_Subscription::SUBSCRIPTION_STATUS_ACTIVE_NO_EXPIRATION;

		$subject = new OTGS_Installer_Subscription( $data );
		$this->assertSame( \OTGS_Installer_Subscription::SUBSCRIPTION_STATUS_TEXT_VALID, $subject->get_subscription_status_text() );
		$this->assertTrue( $subject->is_valid() );
	}

	/**
	 * @test
	 */
	public function it_creates_instance_with_expired_subscription_status() {
		$data                 = array();
		$data['data']         = new stdClass();
		$data['data']->status = \OTGS_Installer_Subscription::SUBSCRIPTION_STATUS_EXPIRED;

		$subject = new OTGS_Installer_Subscription( $data );
		$this->assertSame( \OTGS_Installer_Subscription::SUBSCRIPTION_STATUS_TEXT_EXPIRED, $subject->get_subscription_status_text() );
		$this->assertFalse( $subject->is_valid() );
	}

	/**
	 * @test
	 */
	public function it_creates_instance_with_expired_subscription() {
		$data                  = array();
		$data['data']          = new stdClass();
		$data['data']->expires = '2000-12-31';

		$subject = new OTGS_Installer_Subscription( $data );
		$this->assertSame( \OTGS_Installer_Subscription::SUBSCRIPTION_STATUS_TEXT_EXPIRED, $subject->get_subscription_status_text() );
		$this->assertFalse( $subject->is_valid() );
	}

	/**
	 * @test
	 */
	public function it_creates_instance_with_a_valid_subscription() {
		$day     = '2100-12-31';
		$subject = new OTGS_Installer_Subscription( $this->createActiveSubscriptionExpired( $day ) );
		$this->assertSame( 'valid', $subject->get_subscription_status_text() );
		$this->assertTrue( $subject->is_valid() );
	}

	/**
	 * @test
	 */
	public function it_creates_instance_with_a_refunded_subscription() {
		$subject = new OTGS_Installer_Subscription( $this->createRefunded() );
		$this->assertSame( 'refunded', $subject->get_subscription_status_text() );
		$this->assertTrue( $subject->is_refunded() );
	}

	/**
	 * @test
	 */
	public function it_confirms_yesterday_is_invalid() {
		$yesterday = date( 'Y-m-d', time() - DAY_IN_SECONDS );
		$subject   = new OTGS_Installer_Subscription( $this->createActiveSubscriptionExpired( $yesterday ) );
		$this->assertFalse( $subject->is_valid() );
	}

	/**
	 * @test
	 */
	public function it_confirms_tomorrow_is_valid() {
		$tomorrow = date( 'Y-m-d', time() + DAY_IN_SECONDS );
		$subject  = new OTGS_Installer_Subscription( $this->createActiveSubscriptionExpired( $tomorrow ) );
		$this->assertTrue( $subject->is_valid() );
	}

	/**
	 * @test
	 */
	public function it_confirms_yesterday_is_valid_using_expired_for_period() {
		$yesterday = date( 'Y-m-d', time() - DAY_IN_SECONDS );
		$subject   = new OTGS_Installer_Subscription( $this->createActiveSubscriptionExpired( $yesterday ) );
		$this->assertTrue( $subject->is_valid( 2 * DAY_IN_SECONDS ) );
	}

	/**
	 * @test
	 */
	public function it_confirms_yesterday_is_invalid_via_installer() {
		$repo              = 'wpml';
		$subject           = new WP_Installer();
		$yesterday         = date( 'Y-m-d', time() - DAY_IN_SECONDS );
		$subject->settings = [ 'repositories' => [ $repo => [ 'subscription' => $this->createActiveSubscriptionExpired( $yesterday ) ] ] ];
		$this->assertFalse( $subject->repository_has_valid_subscription( $repo ) );
		$this->assertTrue( $subject->repository_has_expired_subscription( $repo ) );

	}

	/**
	 * @test
	 */
	public function it_confirms_tomorrow_is_valid_via_installer() {
		$repo              = 'wpml';
		$subject           = new WP_Installer();
		$tomorrow          = date( 'Y-m-d', time() + DAY_IN_SECONDS );
		$subject->settings = [ 'repositories' => [ $repo => [ 'subscription' => $this->createActiveSubscriptionExpired( $tomorrow ) ] ] ];
		$this->assertTrue( $subject->repository_has_valid_subscription( $repo ) );
		$this->assertFalse( $subject->repository_has_expired_subscription( $repo ) );
	}

	/**
	 * @test
	 */
	public function it_confirms_yesterday_is_valid_using_expired_for_period_via_installer() {
		$repo              = 'wpml';
		$subject           = new WP_Installer();
		$yesterday         = date( 'Y-m-d', time() - DAY_IN_SECONDS );
		$subject->settings = [ 'repositories' => [ $repo => [ 'subscription' => $this->createActiveSubscriptionExpired( $yesterday ) ] ] ];
		$this->assertTrue( $subject->repository_has_valid_subscription( $repo, 2 * DAY_IN_SECONDS ) );
		$this->assertFalse( $subject->repository_has_expired_subscription( $repo, 2 * DAY_IN_SECONDS ) );
	}

	/**
	 * @test
	 */
	public function it_confirms_refunded_via_installer() {
		$repo              = 'wpml';
		$subject           = new WP_Installer();
		$subject->settings = [ 'repositories' => [ $repo => [ 'subscription' => $this->createRefunded() ] ] ];
		$this->assertTrue( $subject->repository_has_refunded_subscription( $repo ) );
	}


	/**
	 * @param $day
	 *
	 * @return array
	 */
	private function createActiveSubscriptionExpired( $day ) {
		$data                  = [];
		$data['data']          = new stdClass();
		$data['data']->status  = \OTGS_Installer_Subscription::SUBSCRIPTION_STATUS_ACTIVE;
		$data['data']->expires = $day;
		$data['key']           = 'some-key';

		return $data;
	}

	/**
	 * @return array
	 */
	private function createRefunded() {
		$data                 = [];
		$data['data']         = new stdClass();
		$data['data']->status = \OTGS_Installer_Subscription::SUBSCRIPTION_STATUS_INACTIVE;
		$data['data']->notes  = 'Payment refunded to user';

		return $data;
	}

}
