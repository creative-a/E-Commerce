<?php

/**
 * Class Test_OTGS_Installer_Log_Factory
 *
 * @group installer-505
 */
class Test_OTGS_Installer_Log_Factory extends OTGS_TestCase {

	/**
	 * @test
	 */
	public function it_returns_instance_of_log() {
		$subject = new OTGS_Installer_Log_Factory();
		$this->assertInstanceOf( 'OTGS_Installer_Log', $subject->create() );
	}
}