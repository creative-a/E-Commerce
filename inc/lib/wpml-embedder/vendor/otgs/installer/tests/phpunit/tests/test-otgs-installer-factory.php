<?php

/**
 * Class Test_OTGS_Installer_WP_Share_Local_Components_Setting_Hooks_Factory
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 *
 * @group installer-419
 * @group installer-487
 */
class Test_OTGS_Installer_Factory extends OTGS_TestCase {

	/**
	 * @test
	 */
	public function it_creates_instance_of_local_components_settings_hooks() {
		/** @var WP_Installer $installer */
		$installer = $this->get_installer_mock();

		$service = $this->getMockBuilder( 'OTGS_Php_Template_Service' )
		                ->disableOriginalConstructor()
		                ->getMock();

		\Mockery::mock( 'overload:OTGS_Installer_Twig_Template_Service_Loader' )->shouldReceive( 'get_service' )->andReturn( $service );

		$subject = new OTGS_Installer_Factory( $installer );
		$this->assertInstanceOf( 'OTGS_Installer_WP_Share_Local_Components_Setting_Hooks',
			$subject->create_settings_hooks() );
	}

	/**
	 * @test
	 */
	public function it_creates_instance_of_site_key_ajax_handler() {
		$installer_mock = $this->get_installer_mock();
		$installer_mock->method( 'get_settings' )
		               ->willReturn( array( 'repositories' => array() ) );

		\Mockery::mock( 'overload:OTGS_Installer_Plugin_Finder' )->shouldReceive( 'get_all' )->andReturn( array() );
		$subject = new OTGS_Installer_Factory( $installer_mock );
		$this->assertInstanceOf( 'OTGS_Installer_Site_Key_Ajax', $subject->create_site_key_ajax_handler() );
	}

	/**
	 * @test
	 */
	public function it_creates_instance_of_upgrade_response() {
		$installer_mock = $this->get_installer_mock();
		$installer_mock->method( 'get_settings' )
		               ->willReturn( array( 'repositories' => array() ) );

		\Mockery::mock( 'overload:OTGS_Installer_Plugin_Finder' )->shouldReceive( 'get_all' )->andReturn( array() );
		$subject = new OTGS_Installer_Factory( $installer_mock );
		$this->assertInstanceOf( 'OTGS_Installer_Upgrade_Response', $subject->create_upgrade_response() );
	}

	/**
	 * @test
	 */
	public function it_loads_installer_support_hooks() {
		$installer_mock = $this->get_installer_mock();
		$installer_mock->method( 'get_settings' )
		               ->willReturn( array( 'repositories' => array() ) );

		define( 'DOING_AJAX', true );

		\Mockery::mock( 'overload:OTGS_Installer_Plugin_Finder' )->shouldReceive( 'get_all' )->andReturn( array() );
		\Mockery::mock( 'overload:OTGS_Installer_Support_Hooks' )->shouldReceive( 'add_hooks' )->once();
		\Mockery::mock( 'overload:OTGS_Installer_Connection_Test_Ajax' )->shouldReceive( 'add_hooks' )->once();


		$subject = new OTGS_Installer_Factory( $installer_mock );
		$subject->load_installer_support_hooks();
	}

	/**
	 * @test
	 */
	public function it_does_not_load_installer_support_ajax_hook_when_it_is_not_an_ajax_request() {
		$installer_mock = $this->get_installer_mock();
		$installer_mock->method( 'get_settings' )
		               ->willReturn( array( 'repositories' => array() ) );

		\Mockery::mock( 'overload:OTGS_Installer_Plugin_Finder' )->shouldReceive( 'get_all' )->andReturn( array() );
		\Mockery::mock( 'overload:OTGS_Installer_Support_Hooks' )->shouldReceive( 'add_hooks' )->once();
		\Mockery::mock( 'overload:OTGS_Installer_Connection_Test_Ajax' )->shouldReceive( 'add_hooks' )->never();


		$subject = new OTGS_Installer_Factory( $installer_mock );
		$subject->load_installer_support_hooks();
	}

	private function get_installer_mock() {
		return $this->getMockBuilder( 'WP_Installer' )
		            ->setMethods( array( 'get_settings', 'plugin_path' ) )
		            ->disableOriginalConstructor()
		            ->getMock();
	}
}