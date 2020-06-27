<?php

/**
 * Class Test_OTGS_Installer_Logger
 *
 * @group logger
 * @group installer-487
 * @group installer-505
 */
class Test_OTGS_Installer_Logger extends OTGS_TestCase {

	/**
	 * @test
	 */
	public function it_gets_api_log() {
		$installer = $this->getMockBuilder( 'WP_Installer' )
		                  ->setMethods( array( 'log', 'api_debug_log', 'get_api_debug' ) )
		                  ->disableOriginalConstructor()
		                  ->getMock();

		$logger_storage = $this->getMockBuilder( 'OTGS_Installer_Logger_Storage' )
			->setMethods( array( 'add' ) )
			->disableOriginalConstructor()
			->getMock();

		$api_log = 'log text';

		$installer->method( 'get_api_debug' )->willReturn( $api_log );
		$subject = new OTGS_Installer_Logger( $installer, $logger_storage );
		$this->assertSame( $api_log, $subject->get_api_log() );
	}

	/**
	 * @test
	 */
	public function it_adds_api_log() {
		$installer = $this->getMockBuilder( 'WP_Installer' )
		                  ->setMethods( array( 'log', 'api_debug_log', 'get_api_debug' ) )
		                  ->disableOriginalConstructor()
		                  ->getMock();

		$logger_storage = $this->getMockBuilder( 'OTGS_Installer_Logger_Storage' )
		                       ->setMethods( array( 'add' ) )
		                       ->disableOriginalConstructor()
		                       ->getMock();

		$api_log = 'log text';

		$installer->expects( $this->once() )->method( 'api_debug_log' )->with( $api_log );
		$subject = new OTGS_Installer_Logger( $installer, $logger_storage );
		$subject->add_api_log( $api_log );
	}

	/**
	 * @test
	 */
	public function it_adds_log() {
		$installer = $this->getMockBuilder( 'WP_Installer' )
		                  ->setMethods( array( 'log', 'api_debug_log', 'get_api_debug' ) )
		                  ->disableOriginalConstructor()
		                  ->getMock();

		$logger_storage = $this->getMockBuilder( 'OTGS_Installer_Logger_Storage' )
		                       ->setMethods( array( 'add' ) )
		                       ->disableOriginalConstructor()
		                       ->getMock();

		$api_log = 'log text';

		$installer->expects( $this->once() )->method( 'log' )->with( $api_log );
		$subject = new OTGS_Installer_Logger( $installer, $logger_storage );
		$subject->add_log( $api_log );
	}

	/**
	 * @test
	 */
	public function it_saves_log() {
		$installer = $this->getMockBuilder( 'WP_Installer' )
		                  ->setMethods( array( 'log', 'api_debug_log', 'get_api_debug' ) )
		                  ->disableOriginalConstructor()
		                  ->getMock();

		$logger_storage = $this->getMockBuilder( 'OTGS_Installer_Logger_Storage' )
		                       ->setMethods( array( 'add' ) )
		                       ->disableOriginalConstructor()
		                       ->getMock();

		$log = $this->getMockBuilder( 'OTGS_Installer_Log' )
			->disableOriginalConstructor()
			->getMock();

		$api_log = 'log text';

		$logger_storage->expects( $this->once() )
			->method( 'add' )
			->with( $log );

		$subject = new OTGS_Installer_Logger( $installer, $logger_storage );
		$subject->save_log( $log );
	}
}