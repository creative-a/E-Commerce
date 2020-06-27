<?php

use PHPUnit\Framework\TestCase;

class OTGS_Installer_Integration_Base_Test extends TestCase {

	protected $wp_installer_settings = [];
	protected $wp_installer_logs = [];

	/**
	 * @var OTGS_Installer_API_Mocker
	 */
	protected $api_mocker;

	public function __construct() {
		parent::__construct();

		$this->api_mocker = new OTGS_Installer_API_Mocker();
	}


	function setUp() {
		parent::setUp();
		WP_Mock::setUp();
	}

	function tearDown() {
		WP_Mock::tearDown();
		parent::tearDown();
	}

	/**
	 * @return WP_Installer
	 */
	protected function get_installer_instance() {
		\WP_Mock::userFunction(
			'get_option',
			[
				'args' => 'wp_installer_settings',
				'return' => [],
			]
		);
		\WP_Mock::userFunction( 'is_multisite', [ 'return' => false ] );
		\WP_Mock::userFunction(
			'update_option',
			[
				'args' => ['wp_installer_settings', WP_Mock\Functions::type('string')],
				'return' => true,
			]
		);

		\WP_Mock::userFunction(
			'get_option',
			[
				'args' => 'otgs-installer-log',
				'return' => [],
			]
		);

		WP_Mock\Handler::register_handler( 'update_option', [ $this, 'record_installer_options_update' ] );

		return new WP_Installer();
	}

	public function record_installer_options_update( $option, $value, $autoload = 'yes' ) {
		if ( $option === 'wp_installer_settings' ) {
			$_settings = base64_decode( $value );
			if ( $this->is_gz_on() ) {
				$_settings = gzuncompress( $_settings );
			}

			$this->wp_installer_settings = unserialize( $_settings );
		}

		if ( $option === 'otgs-installer-log' ) {
			$this->wp_installer_logs = $value;
		}
	}

	private function is_gz_on() {
		return function_exists( 'gzuncompress' ) && function_exists( 'gzcompress' );
	}
}