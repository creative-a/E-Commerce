<?php

/**
 * Class Test_OTGS_Installer_Repository_Factory
 *
 * @group installer-487
 */
class Test_OTGS_Installer_Repository_Factory extends OTGS_TestCase {

	/**
	 * @test
	 */
	public function it_creates_repository() {
		$subject = new OTGS_Installer_Repository_Factory();
		$this->assertInstanceOf( 'OTGS_Installer_Repository', $subject->create_repository( array() ) );
	}

	/**
	 * @test
	 */
	public function it_creates_package() {
		$subject = new OTGS_Installer_Repository_Factory();
		$this->assertInstanceOf( 'OTGS_Installer_Package', $subject->create_package( array() ) );
	}

	/**
	 * @test
	 */
	public function it_creates_product() {
		$subject = new OTGS_Installer_Repository_Factory();
		$this->assertInstanceOf( 'OTGS_Installer_Package_Product', $subject->create_product( array() ) );
	}

}