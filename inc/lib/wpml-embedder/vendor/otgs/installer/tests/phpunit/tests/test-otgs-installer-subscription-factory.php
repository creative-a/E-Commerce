<?php

/**
 * Class Test_OTGS_Installer_Subscription_Factory
 *
 * @group installer-487
 */
class Test_OTGS_Installer_Subscription_Factory extends OTGS_TestCase {

	/**
	 * @test
	 */
	public function it_creates_instance_of_subscription() {
		$subject = new OTGS_Installer_Subscription_Factory();
		$this->assertInstanceOf( 'OTGS_Installer_Subscription', $subject->create() );
	}
}