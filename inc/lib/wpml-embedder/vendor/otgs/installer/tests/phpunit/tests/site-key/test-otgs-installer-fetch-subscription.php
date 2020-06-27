<?php

/**
 * Class Test_OTGS_Installer_Fetch_Subscription
 *
 * @group fetch-subscription
 * @group installer-487
 */
class Test_OTGS_Installer_Fetch_Subscription extends OTGS_TestCase {

	/**
	 * @test
	 * @dataProvider dp_required_fields
	 * @expectedException OTGS_Installer_Fetch_Subscription_Exception
	 */
	public function it_throws_exception_if_any_required_field_is_missing( $repository_id, $site_key, $source ) {
		$package_source = $this->get_package_source_factory_mock();
		$plugin_finder  = $this->get_plugin_finder_mock();
		$repositories   = $this->get_repositories_mock();
		$logger         = $this->get_logger_mock();

		$log_factory = $this->getMockBuilder( 'OTGS_Installer_Log_Factory' )
		                    ->disableOriginalConstructor()
		                    ->getMock();

		$subject = new OTGS_Installer_Fetch_Subscription( $package_source, $plugin_finder, $repositories, $logger, $log_factory );
		$subject->get( $repository_id, $site_key, $source );
	}

	public function dp_required_fields() {
		return array(
			array( '', 'sitekey', 'Source' ),
			array( 'wpml', '', 'Source' ),
			array( 'wpml', 'sitekey', '' ),
		);
	}

	/**
	 * @test
	 * @expectedException OTGS_Installer_Fetch_Subscription_Exception
	 */
	public function it_throws_exception_when_http_request_fails() {
		$package_source = $this->get_package_source_factory_mock();
		$plugin_finder  = $this->get_plugin_finder_mock();
		$repositories   = $this->get_repositories_mock();
		$logger         = $this->get_logger_mock();
		$api_url        = 'https://api.wpml.org';
		$site_key       = 'site_key';
		$source         = 'request_source';
		$site_url       = 'http://dev.otgs';
		$repository_id  = 'wpml';
		$theme          = 'My Theme';
		$site_name      = 'My Site';
		$versions       = array( 'my-plugin' => '1.0' );

		$response = $this->getMockBuilder( 'WP_Error' )
		                 ->setMethods( array( 'get_error_message' ) )
		                 ->disableOriginalConstructor()
		                 ->getMock();

		$args['body'] = array(
			'action'   => 'site_key_validation',
			'site_key' => $site_key,
			'site_url' => $site_url,
			'source'   => $source
		);

		if ( $repository_id === 'wpml' ) {
			$args['body']['using_icl']    = false;
			$args['body']['wpml_version'] = '';
		}

		$args['body']['installer_version'] = WP_INSTALLER_VERSION;
		$args['body']['theme']             = $theme;
		$args['body']['site_name']         = $site_name;
		$args['body']['repository_id']     = $repository_id;
		$args['body']['versions']          = $versions;
		$args['timeout']                   = 45;

		$installed_plugin = $this->getMockBuilder( 'OTGS_Installer_Plugin' )
		                         ->setMethods( array( 'get_installed_version', 'get_slug' ) )
		                         ->disableArgumentCloning()
		                         ->getMock();

		$installed_plugin->method( 'get_installed_version' )
		                 ->willReturn( '1.0' );

		$installed_plugin->method( 'get_slug' )
		                 ->willReturn( 'my-plugin' );

		$plugin_finder->method( 'get_otgs_installed_plugins' )
		              ->willReturn( array( $installed_plugin ) );

		$theme_data = $this->getMockBuilder( 'WP_Theme' )
		                   ->setMethods( array( 'get' ) )
		                   ->disableOriginalConstructor()
		                   ->getMock();

		$theme_data->method( 'get' )
		           ->with( 'Name' )
		           ->willReturn( $theme );

		$package_source_data = $this->getMockBuilder( 'OTGS_Installer_Source' )
		                            ->setMethods( array( 'get' ) )
		                            ->disableOriginalConstructor()
		                            ->getMock();

		$package_source_data->method( 'get' )
		                    ->willReturn( false );

		$package_source->method( 'create' )
		               ->willReturn( $package_source_data );

		$repository = $this->getMockBuilder( 'OTGS_Installer_Repository' )
		                   ->setMethods( array( 'get_api_url' ) )
		                   ->disableOriginalConstructor()
		                   ->getMock();

		$repository->method( 'get_api_url' )
		           ->willReturn( $api_url );

		$repositories->method( 'get' )
		             ->with( $repository_id )
		             ->willReturn( $repository );

		WP_Mock::wpFunction( 'wp_get_theme', array(
			'return' => $theme_data,
		) );

		WP_Mock::wpFunction( 'get_bloginfo', array(
			'args'   => 'name',
			'return' => $site_name,
		) );

		WP_Mock::wpFunction( 'wp_remote_post', array(
			'args'   => array( $api_url, $args ),
			'return' => $response,
		) );

		WP_Mock::wpFunction( 'is_multisite', array(
			'return' => false,
		) );

		WP_Mock::wpFunction( 'get_site_url', array(
			'return' => $site_url,
		) );

		WP_Mock::wpFunction( 'is_wp_error', array(
			'args'   => array( $response ),
			'return' => true,
		) );

		$response_body = 'body';

		WP_Mock::wpFunction( 'wp_remote_retrieve_body', array(
			'args'   => $response,
			'return' => $response_body,
		) );

		\WP_Mock::onFilter( 'installer_fetch_subscription_data_request' )
		        ->with( $args )
		        ->reply( $args );

		$logger->expects( $this->exactly( 3 ) )
		       ->method( 'add_api_log' )
		       ->withConsecutive(
			       array( "POST {$api_url}" ),
			       array( $args ),
			       array( $response )
		       );

		$logger->expects( $this->once() )
		       ->method( 'add_log' )
		       ->with( "POST {$api_url} - fetch subscription data" );

		$log = $this->getMockBuilder( 'OTGS_Installer_Log' )
			->setMethods( array( 'set_request_args', 'set_request_url', 'set_response', 'set_component' ) )
			->disableOriginalConstructor()
			->getMock();

		$log->method( 'set_request_args' )
			->willReturn(  $log );

		$log->method( 'set_request_url' )
		    ->willReturn(  $log );

		$log->method( 'set_response' )
		    ->willReturn(  $log );

		$log->method( 'set_component' )
		    ->willReturn(  $log );

		$log_factory = $this->getMockBuilder( 'OTGS_Installer_Log_Factory' )
			->setMethods( array( 'create' ) )
			->disableOriginalConstructor()
			->getMock();

		$log_factory->method( 'create' )
			->willReturn( $log );

		$logger->expects( $this->once() )
		       ->method( 'save_log' )
		       ->with( $log );

		$subject = new OTGS_Installer_Fetch_Subscription( $package_source, $plugin_finder, $repositories, $logger, $log_factory, $log_factory );
		$subject->get( $repository_id, $site_key, $source );
	}

	/**
	 * @test
	 */
	public function it_returns_valid_subscription() {
		$package_source = $this->get_package_source_factory_mock();
		$plugin_finder  = $this->get_plugin_finder_mock();
		$repositories   = $this->get_repositories_mock();
		$logger         = $this->get_logger_mock();
		$api_url        = 'api.wpml.org';
		$site_key       = 'site_key';
		$source         = 'request_source';
		$site_url       = 'http://dev.otgs';
		$repository_id  = 'wpml';
		$theme          = 'My Theme';
		$site_name      = 'My Site';
		$versions       = array( 'my-plugin' => '1.0' );

		$body                    = new stdClass();
		$body->subscription_data = array( 'data' => 'data' );
		$body                    = serialize( $body );
		$data                    = unserialize( $body );

		$response = $this->getMockBuilder( 'WP_Error' )
		                 ->setMethods( array( 'get_error_message' ) )
		                 ->disableOriginalConstructor()
		                 ->getMock();

		$args['body'] = array(
			'action'   => 'site_key_validation',
			'site_key' => $site_key,
			'site_url' => $site_url,
			'source'   => $source
		);

		if ( $repository_id === 'wpml' ) {
			$args['body']['using_icl']    = false;
			$args['body']['wpml_version'] = '';
		}

		$args['body']['installer_version'] = WP_INSTALLER_VERSION;
		$args['body']['theme']             = $theme;
		$args['body']['site_name']         = $site_name;
		$args['body']['repository_id']     = $repository_id;
		$args['body']['versions']          = $versions;
		$args['timeout']                   = 45;

		$installed_plugin = $this->getMockBuilder( 'OTGS_Installer_Plugin' )
		                         ->setMethods( array( 'get_installed_version', 'get_slug' ) )
		                         ->disableArgumentCloning()
		                         ->getMock();

		$installed_plugin->method( 'get_installed_version' )
		                 ->willReturn( '1.0' );

		$installed_plugin->method( 'get_slug' )
		                 ->willReturn( 'my-plugin' );

		$plugin_finder->method( 'get_otgs_installed_plugins' )
		              ->willReturn( array( $installed_plugin ) );

		$theme_data = $this->getMockBuilder( 'WP_Theme' )
		                   ->setMethods( array( 'get' ) )
		                   ->disableOriginalConstructor()
		                   ->getMock();

		$theme_data->method( 'get' )
		           ->with( 'Name' )
		           ->willReturn( $theme );

		$package_source_data = $this->getMockBuilder( 'OTGS_Installer_Source' )
		                            ->setMethods( array( 'get' ) )
		                            ->disableOriginalConstructor()
		                            ->getMock();

		$package_source_data->method( 'get' )
		                    ->willReturn( false );

		$package_source->method( 'create' )
		               ->willReturn( $package_source_data );

		$repository = $this->getMockBuilder( 'OTGS_Installer_Repository' )
		                   ->setMethods( array( 'get_api_url' ) )
		                   ->disableOriginalConstructor()
		                   ->getMock();

		$repository->method( 'get_api_url' )
		           ->willReturn( $api_url );

		$repositories->method( 'get' )
		             ->with( $repository_id )
		             ->willReturn( $repository );

		WP_Mock::wpFunction( 'wp_get_theme', array(
			'return' => $theme_data,
		) );

		WP_Mock::wpFunction( 'get_bloginfo', array(
			'args'   => 'name',
			'return' => $site_name,
		) );

		WP_Mock::wpFunction( 'wp_remote_post', array(
			'args'   => array( $api_url, $args ),
			'return' => $response,
		) );

		WP_Mock::wpFunction( 'is_multisite', array(
			'return' => false,
		) );

		WP_Mock::wpFunction( 'get_site_url', array(
			'return' => $site_url,
		) );

		WP_Mock::wpFunction( 'is_wp_error', array(
			'args'   => array( $response ),
			'return' => false,
		) );

		WP_Mock::wpFunction( 'wp_remote_retrieve_body', array(
			'args'   => array( $response ),
			'return' => $body,
		) );

		WP_Mock::wpFunction( 'is_serialized', array(
			'args'   => array( $body ),
			'return' => true,
		) );

		\WP_Mock::onFilter( 'installer_fetch_subscription_data_request' )
		        ->with( $args )
		        ->reply( $args );

		$logger->expects( $this->exactly( 3 ) )
		       ->method( 'add_api_log' )
		       ->withConsecutive(
			       array( "POST {$api_url}" ),
			       array( $args ),
			       array( $data )
		       );

		$logger->expects( $this->once() )
		       ->method( 'add_log' )
		       ->with( "POST {$api_url} - fetch subscription data" );

		$log_factory = $this->getMockBuilder( 'OTGS_Installer_Log_Factory' )
		                    ->disableOriginalConstructor()
		                    ->getMock();

		$subject = new OTGS_Installer_Fetch_Subscription( $package_source, $plugin_finder, $repositories, $logger, $log_factory );
		$this->assertEquals( $data->subscription_data, $subject->get( $repository_id, $site_key, $source ) );
	}

	/**
	 * @test
	 * @group installer-522
	 */
	public function it_should_fallback_on_http_if_https_response_is_invalid() {
		$package_source  = $this->get_package_source_factory_mock();
		$plugin_finder   = $this->get_plugin_finder_mock();
		$repositories    = $this->get_repositories_mock();
		$logger          = $this->get_logger_mock();
		$api_url         = 'https://api.wpml.org';
		$non_ssl_api_url = 'http://api.wpml.org';
		$site_key        = 'site_key';
		$source          = 'request_source';
		$site_url        = 'http://dev.otgs';
		$repository_id   = 'wpml';
		$theme           = 'My Theme';
		$site_name       = 'My Site';
		$versions        = array( 'my-plugin' => '1.0' );

		$body        = new stdClass();
		$body->error = 'some error';
		$body        = serialize( $body );
		$data        = unserialize( $body );

		$response = $this->getMockBuilder( 'WP_Error' )
		                 ->setMethods( array( 'get_error_message' ) )
		                 ->disableOriginalConstructor()
		                 ->getMock();

		$response_valid = 'valid response';

		$args['body'] = array(
			'action'   => 'site_key_validation',
			'site_key' => $site_key,
			'site_url' => $site_url,
			'source'   => $source
		);

		if ( $repository_id === 'wpml' ) {
			$args['body']['using_icl']    = false;
			$args['body']['wpml_version'] = '';
		}

		$args['body']['installer_version'] = WP_INSTALLER_VERSION;
		$args['body']['theme']             = $theme;
		$args['body']['site_name']         = $site_name;
		$args['body']['repository_id']     = $repository_id;
		$args['body']['versions']          = $versions;
		$args['timeout']                   = 45;

		$installed_plugin = $this->getMockBuilder( 'OTGS_Installer_Plugin' )
		                         ->setMethods( array( 'get_installed_version', 'get_slug' ) )
		                         ->disableArgumentCloning()
		                         ->getMock();

		$installed_plugin->method( 'get_installed_version' )
		                 ->willReturn( '1.0' );

		$installed_plugin->method( 'get_slug' )
		                 ->willReturn( 'my-plugin' );

		$plugin_finder->method( 'get_otgs_installed_plugins' )
		              ->willReturn( array( $installed_plugin ) );

		$theme_data = $this->getMockBuilder( 'WP_Theme' )
		                   ->setMethods( array( 'get' ) )
		                   ->disableOriginalConstructor()
		                   ->getMock();

		$theme_data->method( 'get' )
		           ->with( 'Name' )
		           ->willReturn( $theme );

		$package_source_data = $this->getMockBuilder( 'OTGS_Installer_Source' )
		                            ->setMethods( array( 'get' ) )
		                            ->disableOriginalConstructor()
		                            ->getMock();

		$package_source_data->method( 'get' )
		                    ->willReturn( false );

		$package_source->method( 'create' )
		               ->willReturn( $package_source_data );

		$repository = $this->getMockBuilder( 'OTGS_Installer_Repository' )
		                   ->setMethods( array( 'get_api_url' ) )
		                   ->disableOriginalConstructor()
		                   ->getMock();

		$repository->method( 'get_api_url' )
		           ->withConsecutive(
			           array( true ),
			           array( false )
		           )
		           ->willReturnOnConsecutiveCalls( $api_url, $non_ssl_api_url );

		$repositories->method( 'get' )
		             ->with( $repository_id )
		             ->willReturn( $repository );

		WP_Mock::wpFunction( 'wp_get_theme', array(
			'return' => $theme_data,
		) );

		WP_Mock::wpFunction( 'get_bloginfo', array(
			'args'   => 'name',
			'return' => $site_name,
		) );

		WP_Mock::wpFunction( 'wp_remote_post', array(
			'args'   => array( $api_url, $args ),
			'return' => $response,
		) );

		WP_Mock::wpFunction( 'wp_remote_post', array(
			'args'   => array( $non_ssl_api_url, $args ),
			'return' => $response_valid,
			'times'  => 1,
		) );

		WP_Mock::wpFunction( 'is_multisite', array(
			'return' => false,
		) );

		WP_Mock::wpFunction( 'get_site_url', array(
			'return' => $site_url,
		) );

		WP_Mock::wpFunction( 'is_wp_error', array(
			'args'   => array( $response ),
			'return' => false,
		) );

		WP_Mock::wpFunction( 'is_wp_error', array(
			'args'   => array( $response_valid ),
			'return' => false,
		) );

		WP_Mock::wpFunction( 'wp_remote_retrieve_body', array(
			'args'   => array( $response ),
			'return' => '',
		) );

		WP_Mock::wpFunction( 'wp_remote_retrieve_body', array(
			'args'   => array( $response_valid ),
			'return' => $body,
		) );

		WP_Mock::wpFunction( 'is_serialized', array(
			'args'   => array( $body ),
			'return' => true,
		) );

		\WP_Mock::onFilter( 'installer_fetch_subscription_data_request' )
		        ->with( $args )
		        ->reply( $args );

		\WP_Mock::onFilter( 'installer_fetch_subscription_data_request' )
		        ->with( $args )
		        ->reply( $args );

		$logger->expects( $this->exactly( 3 ) )
		       ->method( 'add_api_log' )
		       ->withConsecutive(
			       array( "POST {$non_ssl_api_url}" ),
			       array( $args ),
			       array( $data )
		       );

		$logger->expects( $this->once() )
		       ->method( 'add_log' )
		       ->with( "POST {$non_ssl_api_url} - fetch subscription data" );

		$log = $this->getMockBuilder( 'OTGS_Installer_Log' )
			->setMethods( array( 'set_request_args', 'set_request_url', 'set_response', 'set_component' ) )
			->disableOriginalConstructor()
			->getMock();

		$log->method( 'set_request_args' )
			->with( $args )
			->willReturn( $log );

		$log->method( 'set_request_url' )
			->with( $non_ssl_api_url )
		    ->willReturn( $log );

		$log->method( 'set_response' )
		    ->with( 'some error' )
		    ->willReturn( $log );

		$log->method( 'set_component' )
		    ->with( OTGS_Installer_Logger_Storage::COMPONENT_SUBSCRIPTION )
		    ->willReturn( $log );

		$log_factory = $this->getMockBuilder( 'OTGS_Installer_Log_Factory' )
		                    ->disableOriginalConstructor()
		                    ->getMock();

		$log_factory->method( 'create' )
			->willReturn( $log );

		$subject = new OTGS_Installer_Fetch_Subscription( $package_source, $plugin_finder, $repositories, $logger, $log_factory );
		$this->assertFalse( $subject->get( $repository_id, $site_key, $source ) );
	}

	private function get_package_source_factory_mock() {
		return $this->getMockBuilder( 'OTGS_Installer_Source_Factory' )
		            ->setMethods( array( 'create' ) )
		            ->disableOriginalConstructor()
		            ->getMock();
	}

	private function get_plugin_finder_mock() {
		return $this->getMockBuilder( 'OTGS_Installer_Plugin_Finder' )
		            ->setMethods( array( 'get_otgs_installed_plugins' ) )
		            ->disableOriginalConstructor()
		            ->getMock();
	}

	private function get_repositories_mock() {
		return $this->getMockBuilder( 'OTGS_Installer_Repositories' )
		            ->setMethods( array( 'get_all', 'get' ) )
		            ->disableOriginalConstructor()
		            ->getMock();
	}

	private function get_logger_mock() {
		return $this->getMockBuilder( 'OTGS_Installer_Logger' )
		            ->setMethods( array( 'add_api_log', 'add_log', 'save_log' ) )
		            ->disableOriginalConstructor()
		            ->getMock();
	}
}