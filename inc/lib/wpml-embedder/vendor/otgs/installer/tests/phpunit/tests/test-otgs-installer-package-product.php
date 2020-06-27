<?php

/**
 * Class Test_OTGS_Installer_Package_Product
 *
 * @group installer-487
 */
class Test_OTGS_Installer_Package_Product extends OTGS_TestCase {

	/**
	 * @test
	 */
	public function it_returns_true_when_plugin_is_registered_in_the_product() {
		$subject = new OTGS_Installer_Package_Product( array( 'plugins' => array( 'plugin-test' ) ) );
		$this->assertTrue( $subject->is_plugin_registered( 'plugin-test' ) );
	}

	/**
	 * @test
	 */
	public function it_returns_false_when_plugin_is_not_registered_in_the_product() {
		$plugin = $this->getMockBuilder( 'OTGS_Installer_Plugin' )
		               ->setMethods( array( 'get_slug' ) )
		               ->disableOriginalConstructor()
		               ->getMock();

		$plugin->method( 'get_slug' )
		       ->willReturn( 'plugin-test-new' );

		$subject = new OTGS_Installer_Package_Product( array( 'plugins' => array( $plugin ) ) );
		$this->assertFalse( $subject->is_plugin_registered( 'plugin-test' ) );
	}
}