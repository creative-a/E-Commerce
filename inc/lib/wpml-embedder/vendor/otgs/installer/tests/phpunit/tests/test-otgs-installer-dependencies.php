<?php

use \tad\FunctionMocker\FunctionMocker;

class Test_OTGS_Installer_Dependencies extends OTGS_TestCase {

	private function prepare_subject( $repository_id ) {

		WP_Mock::userFunction( 'is_multisite', [
			'return' => false,
		] );

		WP_Mock::userFunction( 'is_admin', [] );

		WP_Mock::userFunction( 'get_site_url', [
			'return' => 'installerSiteUrl.com',
		] );

		WP_Mock::userFunction( 'add_query_arg', [
			'return' => 'installerSiteUrl.com',
		] );

		WP_Mock::userFunction( 'update_option', [] );

		$installer_settings = [
			'repositories' => [
				$repository_id => [
					'data' => [
						'downloads' => [
							'plugins' => [
								'test_plugin' => [
									'url' => 'exampleUrl.com'
								]
							],
						],
						'packages'  => [],
					]
				]
			]
		];

		WP_Mock::userFunction(
			'get_option',
			[
				'args'   => [ 'wp_installer_settings' ],
				'return' => $installer_settings
			]
		);

		WP_Installer()->get_settings(true);

		return new \Installer_Dependencies();
	}

	/**
	 * @test
	 */
	public function it_checks_win_paths() {
		$repository_id = 'wpml';
		$subject       = $this->prepare_subject( $repository_id );

		FunctionMocker::replace( 'constant', 'WINNT' );

		WP_Mock::userFunction( 'wp_tempnam', [
			'return' => 'C:\Users\AppData\Local\Temp/2b69dce87dbe75c99f04c2c365e15189tmp',
			'times' => 1,
		] );
		WP_Mock::userFunction( 'wp_delete_file', [
			'args' => 'C:\Users\AppData\Local\Temp/2b69dce87dbe75c99f04c2c365e15189tmp',
			'times' => 1,
		] );

		$result = $subject->is_win_paths_exception( $repository_id );
		$this->assertFalse( $result );
	}

	/**
	 * @test
	 */
	public function it_detects_win_paths_exception() {
		$repository_id = 'wpml';
		$subject       = $this->prepare_subject( $repository_id );

		FunctionMocker::replace( 'constant', 'WINNT' );

		$very_long_filename = $this->generate_random_filename( 256 );

		WP_Mock::userFunction( 'wp_tempnam', [
			'return' => $very_long_filename,
			'times' => 1,
		] );
		WP_Mock::userFunction( 'wp_delete_file', [
			'args' => $very_long_filename,
			'times' => 1,
		] );

		$result = $subject->is_win_paths_exception( $repository_id );
		$this->assertTrue( $result );
	}

	private function generate_random_filename( $length = 10 ) {
		$characters       = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$random_filename   = '';
		for ( $i = 0; $i < $length - 3; $i ++ ) {
			$random_filename .= $characters[ rand( 0, strlen( $characters ) - 1 ) ];
		}

		return $random_filename . 'tmp';
	}

	/**
	 * @test
	 */
	public function it_does_not_check_win_paths_on_other_systems_than_windows() {
		$repository_id = 'wpml';
		$subject       = $this->prepare_subject( $repository_id );

		WP_Mock::userFunction( 'wp_tempnam', [
			'times' => 0,
		] );
		WP_Mock::userFunction( 'wp_delete_file', [
			'times' => 0,
		] );

		FunctionMocker::replace( 'constant', 'LINUX' );
		$result = $subject->is_win_paths_exception( $repository_id );
		$this->assertFalse( $result );
	}
}