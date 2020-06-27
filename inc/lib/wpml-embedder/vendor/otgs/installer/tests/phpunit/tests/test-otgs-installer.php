<?php

class Test_OTGS_Installer extends OTGS_TestCase {

	public function setUp() {
		parent::setUp();

		WP_Mock::userFunction( 'is_admin', array() );
	}

	/**
	 * @test
	 */
	public function it_returns_warning_when_user_has_no_capability_to_show_products() {
		WP_Mock::userFunction( 'get_option', [ 'args' => [ 'wp_installer_settings' ] ] );
		WP_Mock::wpFunction( 'is_multisite', array(
			'return' => false,
		) );

		\WP_Mock::userFunction( 'update_option', [] );

		\WP_Mock::userFunction( 'get_site_url', array(
			'return' => 'http://dev.otgs',
		) );

		\WP_Mock::userFunction( 'current_user_can', array(
			'return' => false,
		) );

		\WP_Mock::userFunction( 'wp_die', array(
			'times' => 1,
			'args'  => array(
				'Sorry, you are not allowed to manage Installer for this site.'
			)
		) );

		$subject = new WP_Installer();
		$subject->show_products( [] );
	}
}
