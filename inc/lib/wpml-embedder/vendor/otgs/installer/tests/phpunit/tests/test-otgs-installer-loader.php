<?php

/**
 * Class Test_OTGS_Installer_Loader
 *
 * @group installer-509
 */
class Test_OTGS_Installer_Loader extends OTGS_TestCase {

	public function setUp() {
		parent::setUp();

		WP_Mock::userFunction( 'is_admin', array() );
	}

	/**
	 * @test
	 */
	public function it_runs_installer_loader() {
		$factory = $this->getMockBuilder( 'OTGS_Installer_Factory' )
		                ->setMethods( array(
			                'load_wp_components_hooks',
		                ) )
		                ->disableOriginalConstructor()
		                ->getMock();

		$factory->expects( $this->once() )->method( 'load_wp_components_hooks' )->willReturn( $factory );

		$subject = new OTGS_Installer_Loader( $factory );

		WP_Mock::expectActionAdded( 'otgs_installer_initialized', array(
			$subject,
			'load_actions_after_installer_init'
		) );

		$subject->init();
	}

	/**
	 * @test
	 * @group installer-509
	 */
	public function it_loads_actions_after_installer_init() {
		$factory = $this->getMockBuilder( 'OTGS_Installer_Factory' )
		                ->setMethods( [
			                'load_resources',
			                'load_settings_hooks',
			                'load_local_components_ajax_settings',
			                'load_filename_hooks',
			                'load_icons',
			                'load_debug_info_hooks',
			                'load_upgrade_response',
			                'load_site_key_ajax_handler',
			                'load_installer_support_hooks',
			                'load_translation_service_info_hooks',
			                'load_buy_url_hooks',
			                'load_admin_notice_hooks'
		                ] )
		                ->disableOriginalConstructor()
		                ->getMock();

		$factory->expects( $this->once() )->method( 'load_resources' )->willReturn( $factory );
		$factory->expects( $this->once() )->method( 'load_settings_hooks' )->willReturn( $factory );
		$factory->expects( $this->once() )->method( 'load_local_components_ajax_settings' )->willReturn( $factory );
		$factory->expects( $this->once() )->method( 'load_filename_hooks' )->willReturn( $factory );
		$factory->expects( $this->once() )->method( 'load_icons' )->willReturn( $factory );
		$factory->expects( $this->once() )->method( 'load_debug_info_hooks' )->willReturn( $factory );
		$factory->expects( $this->once() )->method( 'load_upgrade_response' )->willReturn( $factory );
		$factory->expects( $this->once() )->method( 'load_site_key_ajax_handler' )->willReturn( $factory );
		$factory->expects( $this->once() )->method( 'load_installer_support_hooks' )->willReturn( $factory );
		$factory->expects( $this->once() )->method( 'load_translation_service_info_hooks' )->willReturn( $factory );
		$factory->expects( $this->once() )->method( 'load_buy_url_hooks' )->willReturn( $factory );
		$factory->expects( $this->once() )->method( 'load_admin_notice_hooks' )->willReturn( $factory );

		$subject = new OTGS_Installer_Loader( $factory );
		$subject->load_actions_after_installer_init();
	}
}
