<?php

/**
 * Class Test_OTGS_Installer_Connection_Test
 *
 * @group installer-513
 */
class Test_OTGS_Installer_Connection_Test extends OTGS_TestCase {

	/**
	 * @test
	 */
	public function it_returns_false_when_download_is_not_found() {
		$repositories     = $this->get_repositories_mock();
		$upgrade_response = $this->get_upgrade_response_mock();
		$plugin_id        = 'sitepress-multilingual-cms/sitepress.php';

		WP_Mock::wpFunction( 'get_site_transient', array(
			'args'   => 'update_plugins',
			'return' => array(),
		) );

		$logger_storage = $this->get_logger_storage_mock();
		$log_factory = $this->get_log_factory_mock();

		$subject = new OTGS_Installer_Connection_Test( $repositories, $upgrade_response, $logger_storage, $log_factory );
		$this->assertFalse( $subject->get_download_status( $plugin_id ) );
	}

	/**
	 * @test
	 * @dataProvider dp_success_code
	 */
	public function it_returns_true_for_available_download_url( $success_code ) {
		$repositories     = $this->get_repositories_mock();
		$upgrade_response = $this->get_upgrade_response_mock();
		$plugin_id        = 'sitepress-multilingual-cms/sitepress.php';
		$download         = 1;
		$version          = '4.0.5';
		$download_url     = "http://wpml.org/?download=$download&version=$version";

		$plugin_upgrade          = new stdClass();
		$plugin_upgrade->package = $download_url;

		$wp_upgrade_response           = new stdClass();
		$wp_upgrade_response->response = array(
			$plugin_id => $plugin_upgrade,
		);

		WP_Mock::wpFunction( 'get_site_transient', array(
			'args'   => 'update_plugins',
			'return' => $wp_upgrade_response,
		) );

		WP_Mock::wpFunction( 'wp_parse_url', array(
			'args'   => $download_url,
			'return' => function ( $url ) {
				return parse_url( $url );
			},
		) );

		$response = array(
			'response' => array(
				'code' => $success_code
			)
		);

		WP_Mock::wpFunction( 'wp_remote_head', array(
			'args'   => $download_url,
			'return' => $response,
		) );

		WP_Mock::wpFunction( 'is_wp_error', array(
			'args'   => array( $response ),
			'return' => false,
		) );

		$upgrade_response->method( 'modify_upgrade_response' )
		                 ->with( $wp_upgrade_response )
		                 ->willReturn( $wp_upgrade_response );

		$logger_storage = $this->get_logger_storage_mock();
		$log_factory = $this->get_log_factory_mock();

		$subject           = new OTGS_Installer_Connection_Test( $repositories, $upgrade_response, $logger_storage, $log_factory );
		$this->assertTrue( $subject->get_download_status( $plugin_id ) );
	}

	public function dp_success_code() {
		return array(
			array( 200 ),
			array( 302 ),
		);
	}

	/**
	 * @test
	 */
	public function it_returns_false_when_download_url_is_not_available() {
		$repositories     = $this->get_repositories_mock();
		$upgrade_response = $this->get_upgrade_response_mock();
		$plugin_id        = 'sitepress-multilingual-cms/sitepress.php';
		$download         = 1;
		$version          = '4.0.5';
		$download_url     = "http://wpml.org/?download=$download&version=$version";

		$plugin_upgrade          = new stdClass();
		$plugin_upgrade->package = $download_url;

		$wp_upgrade_response           = new stdClass();
		$wp_upgrade_response->response = array(
			$plugin_id => $plugin_upgrade,
		);

		WP_Mock::wpFunction( 'get_site_transient', array(
			'args'   => 'update_plugins',
			'return' => $wp_upgrade_response,
		) );

		WP_Mock::wpFunction( 'wp_parse_url', array(
			'args'   => $download_url,
			'return' => function ( $url ) {
				return parse_url( $url );
			},
		) );

		$response = array(
			'response' => array(
				'code' => 400
			)
		);

		WP_Mock::wpFunction( 'wp_remote_head', array(
			'args'   => $download_url,
			'return' => $response,
		) );

		WP_Mock::wpFunction( 'is_wp_error', array(
			'args'   => array( $response ),
			'return' => false,
		) );

		$upgrade_response->method( 'modify_upgrade_response' )
		                 ->with( $wp_upgrade_response )
		                 ->willReturn( $wp_upgrade_response );

		$logger_storage = $this->get_logger_storage_mock();
		$log_factory = $this->get_log_factory_mock();

		$log = $this->getMockBuilder( 'OTGS_Installer_Log' )
		            ->setMethods( array( 'set_request_url', 'set_component', 'set_response' ) )
		            ->disableOriginalConstructor()
		            ->getMock();

		$log->method( 'set_request_url' )
		    ->with( $download_url )
		    ->willReturn( $log );

		$log->method( 'set_component' )
		    ->with( OTGS_Installer_Logger_Storage::PRODUCTS_FILE_CONNECTION_TEST )
		    ->willReturn( $log );

		$log->method( 'set_response' )
		    ->with( sprintf(
				    '%s: an error occurred while trying to get information of this download URL. Error: %s, download: %s, version: %s.',
				    $plugin_id,
				    'Invalid response',
				    $download,
				    $version )
		    )
		    ->willReturn( $log );

		$log_factory->method( 'create' )
		            ->willReturn( $log );

		$logger_storage->expects( $this->once() )
		               ->method( 'add' )
		               ->with( $log );

		$subject           = new OTGS_Installer_Connection_Test( $repositories, $upgrade_response, $logger_storage, $log_factory );
		$this->assertFalse( $subject->get_download_status( $plugin_id ) );
	}

	/**
	 * @test
	 */
	public function it_returns_false_when_request_response_is_wp_error_for_download_url() {
		$repositories     = $this->get_repositories_mock();
		$upgrade_response = $this->get_upgrade_response_mock();
		$plugin_id        = 'sitepress-multilingual-cms/sitepress.php';
		$download         = 1;
		$version          = '4.0.5';
		$download_url     = "http://wpml.org/?download=$download&version=$version";

		$plugin_upgrade          = new stdClass();
		$plugin_upgrade->package = $download_url;

		$wp_upgrade_response           = new stdClass();
		$wp_upgrade_response->response = array(
			$plugin_id => $plugin_upgrade,
		);

		WP_Mock::wpFunction( 'get_site_transient', array(
			'args'   => 'update_plugins',
			'return' => $wp_upgrade_response,
		) );

		WP_Mock::wpFunction( 'wp_parse_url', array(
			'args'   => $download_url,
			'return' => function ( $url ) {
				return parse_url( $url );
			},
		) );

		$response = $this->getMockBuilder( 'WP_Error' )
		                 ->setMethods( array( 'get_error_message' ) )
		                 ->disableOriginalConstructor()
		                 ->getMock();

		$error_message = 'error';

		$response->method( 'get_error_message' )
		         ->willReturn( $error_message );

		WP_Mock::wpFunction( 'wp_remote_head', array(
			'args'   => $download_url,
			'return' => $response,
		) );

		WP_Mock::wpFunction( 'is_wp_error', array(
			'args'   => array( $response ),
			'return' => true,
		) );

		$upgrade_response->method( 'modify_upgrade_response' )
		                 ->with( $wp_upgrade_response )
		                 ->willReturn( $wp_upgrade_response );

		$logger_storage = $this->get_logger_storage_mock();
		$log_factory = $this->get_log_factory_mock();

		$log = $this->getMockBuilder( 'OTGS_Installer_Log' )
		            ->setMethods( array( 'set_request_url', 'set_component', 'set_response' ) )
		            ->disableOriginalConstructor()
		            ->getMock();

		$log->method( 'set_request_url' )
		    ->with( $download_url )
		    ->willReturn( $log );

		$log->method( 'set_component' )
		    ->with( OTGS_Installer_Logger_Storage::PRODUCTS_FILE_CONNECTION_TEST )
		    ->willReturn( $log );

		$log->method( 'set_response' )
		    ->with( sprintf(
				    '%s: an error occurred while trying to get information of this download URL. Error: %s, download: %s, version: %s.',
				    $plugin_id,
				    $error_message,
				    $download,
				    $version )
		    )
		    ->willReturn( $log );

		$log_factory->method( 'create' )
		            ->willReturn( $log );

		$logger_storage->expects( $this->once() )
		               ->method( 'add' )
		               ->with( $log );

		$subject           = new OTGS_Installer_Connection_Test( $repositories, $upgrade_response, $logger_storage, $log_factory );
		$this->assertFalse( $subject->get_download_status( $plugin_id ) );
	}

	/**
	 * @test
	 * @dataProvider dp_success_code
	 */
	public function it_returns_true_for_api_request_success( $code ) {
		$repositories = $this->get_repositories_mock();
		$api_url      = 'http://api.wpml.org';
		$repo_id      = 'wpml';

		$repository = $this->getMockBuilder( 'OTGS_Installer_Repository' )
		                   ->setMethods( array( 'get_api_url' ) )
		                   ->disableOriginalConstructor()
		                   ->getMock();

		$repository->method( 'get_api_url' )
		           ->willReturn( $api_url );

		$repositories->method( 'get' )
		             ->with( $repo_id )
		             ->willReturn( $repository );

		$upgrade_response = $this->get_upgrade_response_mock();

		$response = array(
			'response' => array(
				'code' => $code,
			)
		);

		WP_Mock::wpFunction( 'is_wp_error', array(
			'args'   => array( $response ),
			'return' => false,
		) );

		WP_Mock::wpFunction( 'wp_remote_get', array(
			'args'   => $api_url,
			'return' => $response,
		) );

		$logger_storage = $this->get_logger_storage_mock();
		$log_factory = $this->get_log_factory_mock();

		$subject = new OTGS_Installer_Connection_Test( $repositories, $upgrade_response, $logger_storage, $log_factory );
		$this->assertTrue( $subject->get_api_status( $repo_id ) );
	}

	/**
	 * @test
	 */
	public function it_returns_false_for_api_request_when_it_returns_invalid_code() {
		$repositories = $this->get_repositories_mock();
		$api_url      = 'http://api.wpml.org';
		$repo_id      = 'wpml';

		$repository = $this->getMockBuilder( 'OTGS_Installer_Repository' )
		                   ->setMethods( array( 'get_api_url' ) )
		                   ->disableOriginalConstructor()
		                   ->getMock();

		$repository->method( 'get_api_url' )
		           ->willReturn( $api_url );

		$repositories->method( 'get' )
		             ->with( $repo_id )
		             ->willReturn( $repository );

		$upgrade_response = $this->get_upgrade_response_mock();

		$response = array(
			'response' => array(
				'code' => 400,
			)
		);

		WP_Mock::wpFunction( 'is_wp_error', array(
			'args'   => array( $response ),
			'return' => false,
		) );

		WP_Mock::wpFunction( 'wp_remote_get', array(
			'args'   => $api_url,
			'return' => $response,
		) );

		$logger_storage = $this->get_logger_storage_mock();
		$log_factory = $this->get_log_factory_mock();

		$log = $this->getMockBuilder( 'OTGS_Installer_Log' )
		            ->setMethods( array( 'set_request_url', 'set_component', 'set_response' ) )
		            ->disableOriginalConstructor()
		            ->getMock();

		$log->method( 'set_request_url' )
		    ->with( $api_url )
		    ->willReturn( $log );

		$log->method( 'set_component' )
		    ->with( OTGS_Installer_Logger_Storage::PRODUCTS_FILE_CONNECTION_TEST )
		    ->willReturn( $log );

		$log->method( 'set_response' )
		    ->with( sprintf( "Your site can't communicate with %s. Code %d.", $api_url, $response['response']['code'] ) )
		    ->willReturn( $log );

		$log_factory->method( 'create' )
		            ->willReturn( $log );

		$logger_storage->expects( $this->once() )
		               ->method( 'add' )
		               ->with( $log );

		$subject = new OTGS_Installer_Connection_Test( $repositories, $upgrade_response, $logger_storage, $log_factory );
		$this->assertFalse( $subject->get_api_status( $repo_id ) );
	}

	/**
	 * @test
	 */
	public function it_returns_false_when_api_request_responds_with_wp_error() {
		$repositories = $this->get_repositories_mock();
		$api_url      = 'http://api.wpml.org';
		$repo_id      = 'wpml';

		$repository = $this->getMockBuilder( 'OTGS_Installer_Repository' )
		                   ->setMethods( array( 'get_api_url' ) )
		                   ->disableOriginalConstructor()
		                   ->getMock();

		$repository->method( 'get_api_url' )
		           ->willReturn( $api_url );

		$repositories->method( 'get' )
		             ->with( $repo_id )
		             ->willReturn( $repository );

		$upgrade_response = $this->get_upgrade_response_mock();

		$response = $this->getMockBuilder( 'WP_Error' )
		                 ->setMethods( array( 'get_error_message', 'get_error_code' ) )
		                 ->disableOriginalConstructor()
		                 ->getMock();

		$response->method( 'get_error_message' )
		         ->willReturn( 'error' );

		$error_code = 400;

		$response->method( 'get_error_code' )
		         ->willReturn( $error_code );

		WP_Mock::wpFunction( 'is_wp_error', array(
			'args'   => array( $response ),
			'return' => true,
		) );

		WP_Mock::wpFunction( 'wp_remote_get', array(
			'args'   => $api_url,
			'return' => $response,
		) );

		$logger_storage = $this->get_logger_storage_mock();
		$log_factory = $this->get_log_factory_mock();

		$log = $this->getMockBuilder( 'OTGS_Installer_Log' )
		            ->setMethods( array( 'set_request_url', 'set_component', 'set_response' ) )
		            ->disableOriginalConstructor()
		            ->getMock();

		$log->method( 'set_request_url' )
		    ->with( $api_url )
		    ->willReturn( $log );

		$log->method( 'set_component' )
		    ->with( OTGS_Installer_Logger_Storage::PRODUCTS_FILE_CONNECTION_TEST )
		    ->willReturn( $log );

		$log->method( 'set_response' )
		    ->with( sprintf( "Your site can't communicate with %s. Code %d: %s.", $api_url, $error_code, 'error' ) )
		    ->willReturn( $log );

		$log_factory->method( 'create' )
		            ->willReturn( $log );

		$logger_storage->expects( $this->once() )
		               ->method( 'add' )
		               ->with( $log );

		$subject = new OTGS_Installer_Connection_Test( $repositories, $upgrade_response, $logger_storage, $log_factory );
		$this->assertFalse( $subject->get_api_status( $repo_id ) );
	}

	private function get_repositories_mock() {
		return $this->getMockBuilder( 'OTGS_Installer_Repositories' )
		            ->disableOriginalConstructor()
		            ->getMock();
	}

	private function get_upgrade_response_mock() {
		return $this->getMockBuilder( 'OTGS_Installer_Upgrade_Response' )
		            ->disableOriginalConstructor()
		            ->getMock();
	}

	private function get_logger_storage_mock() {
		return $this->getMockBuilder( 'OTGS_Installer_Logger_Storage' )
		            ->disableOriginalConstructor()
		            ->getMock();
	}

	private function get_log_factory_mock() {
		return $this->getMockBuilder( 'OTGS_Installer_Log_Factory' )
		            ->disableOriginalConstructor()
		            ->getMock();
	}
}
