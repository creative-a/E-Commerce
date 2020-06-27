<?php

/**
 * Class Test_OTGS_Installer_Plugin_Factory
 *
 * @group installer-465
 */
class Test_OTGS_Installer_Plugin_Factory extends OTGS_TestCase {

	/**
	 * @test
	 */
	public function it_creates_instance_of_plugin() {
		$subject = new OTGS_Installer_Plugin_Factory();
		$this->assertInstanceOf( 'OTGS_Installer_Plugin', $subject->create() );
	}
}