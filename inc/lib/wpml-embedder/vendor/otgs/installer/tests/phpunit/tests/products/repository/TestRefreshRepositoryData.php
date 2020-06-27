<?php


class TestRefreshRepositoryData extends InstallerIntegrationTest {

	/** @var array $wp_installer_settings */
	private $wp_installer_settings;

	/** @var string $site_url */
	private $site_url;

	public function setUp() {
		parent::setUp();
		\WP_Mock::userFunction( 'is_multisite', [ 'return' => false ] );
		$this->site_url = 'http://my-site.com';
		\WP_Mock::userFunction( 'get_site_url', [ 'return' => $this->site_url ] );

		\WP_Mock::userFunction( 'update_option', [] );
		\WP_Mock::userFunction( 'get_option', [] );
		\WP_Mock\Handler::register_handler( 'update_option', [ $this, 'record_installer_settings_update' ] );
	}

	/**
	 * @test
	 */
	public function it_get_repository_from_main_CDN_when_there_is_no_bucket() {
		$subject = new WP_Installer();

		\WP_Mock::userFunction( 'delete_site_transient', [ 'return' => true ] );

		$subject->refresh_repositories_data();

		$this->assertEquals(
			'wpml-the-wordpress-multilingual-plugin',
			$this->wp_installer_settings['repositories']['wpml']['data']['id']
		);

	}

	/**
	 * @test
	 */
	public function it_get_repository_from_main_CDN_when_there_is_a_bucket_that_is_bypassed() {
		$site_key = 'site-key';
		$this->register_site_in_repository( $site_key, $this->site_url );

		\WP_Mock::userFunction( 'delete_site_transient', [ 'return' => true ] );

		$subject = new WP_Installer();

		$subject->settings['repositories']['wpml']['subscription']['key'] = $site_key;

		$subject->refresh_repositories_data( true );

		$this->assertEquals(
			'wpml-the-wordpress-multilingual-plugin',
			$this->wp_installer_settings['repositories']['wpml']['data']['id']
		);
	}

	public function record_installer_settings_update( $option, $value, $autoload = 'yes' ) {
		if ( $option === 'wp_installer_settings' ) {
			$_settings = base64_decode( $value );
			if ( $this->is_gz_on() ) {
				$_settings = gzuncompress( $_settings );
			}

			$this->wp_installer_settings = unserialize( $_settings );
		}
	}

	private function is_gz_on() {
		return function_exists( 'gzuncompress' ) && function_exists( 'gzcompress' );
	}

}
