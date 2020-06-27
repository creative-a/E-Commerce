<?php

/**
 * Class Test_OTGS_Installer_Instances_Factory
 *
 * @group installer-521
 */
class Test_OTGS_Installer_Instances_Factory extends OTGS_TestCase {

	/**
	 * @test
	 */
	public function it_creates_instance_of_installer_instances() {
		$subject = new OTGS_Installer_Instances_Factory();
		$this->assertInstanceOf( 'OTGS_Installer_Instances', $subject->create() );
	}
}