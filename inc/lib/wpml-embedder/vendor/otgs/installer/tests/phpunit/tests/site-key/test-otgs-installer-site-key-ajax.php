<?php

/**
 * Class Test_OTGS_Installer_Site_Key_Ajax
 *
 * @group site-key
 * @group installer-487
 */
class Test_OTGS_Installer_Site_Key_Ajax extends OTGS_TestCase {

	/**
	 * @test
	 */
	public function it_adds_hooks() {
		$subscription_fetch   = $this->get_fetch_subscription_mock();
		$logger               = $this->get_logger_mock();
		$repositories         = $this->get_repositories_mock();
		$subscription_factory = $this->get_subscription_factory_mock();
		$subject              = new OTGS_Installer_Site_Key_Ajax( $subscription_fetch, $logger, $repositories, $subscription_factory );
		\WP_Mock::expectActionAdded( 'wp_ajax_save_site_key', array( $subject, 'save' ) );
		\WP_Mock::expectActionAdded( 'wp_ajax_remove_site_key', array( $subject, 'remove' ) );
		\WP_Mock::expectActionAdded( 'wp_ajax_update_site_key', array( $subject, 'update' ) );
		\WP_Mock::expectActionAdded( 'wp_ajax_find_account', array( $subject, 'find' ) );
		$subject->add_hooks();
	}

	/**
	 * @test
	 * @dataProvider dp_required_arguments
	 */
	public function it_returns_json_error_when_trying_to_save_site_key_missing_required_arguments( $repository, $nonce, $site_key ) {
		$subscription_fetch   = $this->get_fetch_subscription_mock();
		$logger               = $this->get_logger_mock();
		$repositories         = $this->get_repositories_mock();
		$subscription_factory = $this->get_subscription_factory_mock();
		$subject              = new OTGS_Installer_Site_Key_Ajax( $subscription_fetch, $logger, $repositories, $subscription_factory );

		$_POST['repository_id']             = $repository;
		$_POST['nonce']                     = $nonce;
		$_POST[ 'site_key_' . $repository ] = $site_key;

		WP_Mock::userFunction( 'wp_send_json_success', array() );
		WP_Mock::userFunction( 'wp_send_json_error', array(
			'times' => 1,
			'args'  => 'Invalid request!'
		) );

		WP_Mock::passthruFunction( 'sanitize_text_field' );

		$subject->save();
	}

	/**
	 * @test
	 */
	public function it_returns_json_error_if_nonce_is_not_valid() {
		$subscription_fetch   = $this->get_fetch_subscription_mock();
		$logger               = $this->get_logger_mock();
		$repositories         = $this->get_repositories_mock();
		$subscription_factory = $this->get_subscription_factory_mock();
		$subject              = new OTGS_Installer_Site_Key_Ajax( $subscription_fetch, $logger, $repositories, $subscription_factory );

		$_POST['repository_id'] = 'wpml';
		$_POST['nonce']         = 'invalid_nonce';
		$_POST['site_key_wpml'] = 'sitekey';

		WP_Mock::userFunction( 'wp_send_json_success', array() );
		WP_Mock::userFunction( 'wp_send_json_error', array(
			'times' => 1,
			'args'  => 'Invalid request!'
		) );

		WP_Mock::userFunction( 'wp_verify_nonce', array(
			'args'   => array( $_POST['nonce'], 'save_site_key_wpml' ),
			'return' => false,
		) );

		WP_Mock::passthruFunction( 'sanitize_text_field' );

		$subject->save();
	}

	public function dp_required_arguments() {
		return array(
			array( '', 'valid_nonce', 'sitekey' ),
			array( 'wpml', '', 'sitekey' ),
			array( 'wpml', 'valid_nonce', '' ),
		);
	}

	/**
	 * @test
	 */
	public function it_should_catch_exception_coming_from_subscription_fetch() {
		$subscription_fetch   = $this->get_fetch_subscription_mock();
		$logger               = $this->get_logger_mock();
		$repositories         = $this->get_repositories_mock();
		$subscription_factory = $this->get_subscription_factory_mock();
		$subject              = new OTGS_Installer_Site_Key_Ajax( $subscription_fetch, $logger, $repositories, $subscription_factory );

		$_POST['repository_id'] = 'wpml';
		$_POST['nonce']         = 'valid_nonce';
		$_POST['site_key_wpml'] = 'sitekey';

		$exception = \Mockery::mock( 'OTGS_Installer_Site_Key_Exception' );

		$repository_data = $this->getMockBuilder( 'OTGS_Installer_Repository' )
		                        ->setMethods( array( 'get_product_name' ) )
		                        ->disableOriginalConstructor()
		                        ->getMock();

		$repositories->method( 'get' )
		             ->with( 'wpml' )
		             ->willReturn( $repository_data );

		$repositories->expects( $this->never() )
		             ->method( 'save_subscription' );

		$subscription_fetch->expects( $this->once() )
		                   ->method( 'get' )
		                   ->with( $_POST['repository_id'], $_POST['site_key_wpml'], WP_Installer::SITE_KEY_VALIDATION_SOURCE_REGISTRATION )
		                   ->willThrowException( $exception );

		WP_Mock::userFunction( 'wp_send_json_success', array(
			'times' => 1,
			'args'  => array( array( 'error' => '' ) ),
		) );

		WP_Mock::userFunction( 'wp_verify_nonce', array(
			'args'   => array( $_POST['nonce'], 'save_site_key_wpml' ),
			'return' => true,
		) );

		WP_Mock::passthruFunction( 'sanitize_text_field' );
		$subject->save();
	}

	/**
	 * @test
	 */
	public function it_should_return_invalid_site_key_error() {
		$subscription_fetch   = $this->get_fetch_subscription_mock();
		$logger               = $this->get_logger_mock();
		$repositories         = $this->get_repositories_mock();
		$subscription_factory = $this->get_subscription_factory_mock();
		$subject              = new OTGS_Installer_Site_Key_Ajax( $subscription_fetch, $logger, $repositories, $subscription_factory );

		$_POST['repository_id'] = 'wpml';
		$_POST['nonce']         = 'valid_nonce';
		$_POST['site_key_wpml'] = 'sitekey';

		$repository_data = $this->getMockBuilder( 'OTGS_Installer_Repository' )
		                        ->setMethods( array( 'get_product_name' ) )
		                        ->disableOriginalConstructor()
		                        ->getMock();

		$repositories->method( 'get' )
		             ->with( 'wpml' )
		             ->willReturn( $repository_data );

		$repositories->expects( $this->never() )
		             ->method( 'save_subscription' );

		$subscription_fetch->method( 'get' )
		                   ->with( $_POST['repository_id'], $_POST['site_key_wpml'], WP_Installer::SITE_KEY_VALIDATION_SOURCE_REGISTRATION )
		                   ->willReturn( false );

		WP_Mock::userFunction( 'wp_send_json_success', array(
			'times' => 1,
			'args'  => array( array( 'error' => 'Invalid site key for the current site.<br /><div class="installer-footnote">Please note that the site key is case sensitive.</div>' ) ),
		) );

		WP_Mock::userFunction( 'wp_verify_nonce', array(
			'args'   => array( $_POST['nonce'], 'save_site_key_wpml' ),
			'return' => true,
		) );

		WP_Mock::passthruFunction( 'sanitize_text_field' );

		$subject->save();
	}

	/**
	 * @test
	 */
	public function it_should_save_subscription() {
		$subscription_fetch   = $this->get_fetch_subscription_mock();
		$logger               = $this->get_logger_mock();
		$repositories         = $this->get_repositories_mock();
		$subscription_factory = $this->get_subscription_factory_mock();
		$subject              = new OTGS_Installer_Site_Key_Ajax( $subscription_fetch, $logger, $repositories, $subscription_factory );

		$_POST['repository_id'] = 'wpml';
		$_POST['nonce']         = 'valid_nonce';
		$_POST['site_key_wpml'] = 'sitekey';

		$log = 'log message';

		$logger->method( 'get_api_log' )
			->willReturn( $log );

		$repository_data = $this->getMockBuilder( 'OTGS_Installer_Repository' )
		                        ->setMethods( array( 'get_product_name', 'set_subscription' ) )
		                        ->disableOriginalConstructor()
		                        ->getMock();

		$repositories->method( 'get' )
		             ->with( 'wpml' )
		             ->willReturn( $repository_data );

		$subscription = $this->getMockBuilder( 'OTGS_Installer_Subscription' )
		                     ->disableOriginalConstructor()
		                     ->getMock();

		$site_url = 'http://wpml.dev';

		WP_Mock::userFunction( 'get_site_url', array(
			'return' => $site_url,
		) );

		WP_Mock::userFunction( 'get_current_user_id', array(
			'return' => 1,
		) );

		$subscription_from_server = new stdClass();

		$subscription_factory->method( 'create' )
		                     ->with( array(
				                     'data'          => $subscription_from_server,
				                     'key'           => $_POST['site_key_wpml'],
				                     'site_url'      => $site_url,
				                     'registered_by' => 1,
			                     )
		                     )
		                     ->willReturn( $subscription );

		$repository_data->expects( $this->once() )
		                ->method( 'set_subscription' )
		                ->with( $subscription );

		$repositories->expects( $this->once() )
		             ->method( 'save_subscription' )
		             ->with( $repository_data );

		$subscription_fetch->method( 'get' )
		                   ->with( $_POST['repository_id'], $_POST['site_key_wpml'], WP_Installer::SITE_KEY_VALIDATION_SOURCE_REGISTRATION )
		                   ->willReturn( $subscription_from_server );

		WP_Mock::userFunction( 'wp_send_json_success', array(
			'times' => 1,
			'args'  => array( array( 'error' => '', 'debug' => $log ) ),
		) );

		WP_Mock::userFunction( 'wp_verify_nonce', array(
			'args'   => array( $_POST['nonce'], 'save_site_key_wpml' ),
			'return' => true,
		) );

		$repositories->expects( $this->once() )
		             ->method( 'refresh' );

		WP_Mock::passthruFunction( 'sanitize_text_field' );

		WP_Mock::expectAction('otgs_installer_clean_plugins_update_cache');

		$subject->save();
	}

	/**
	 * @test
	 */
	public function it_should_not_remove_subscription_if_nonce_is_invalid() {
		$subscription_fetch   = $this->get_fetch_subscription_mock();
		$logger               = $this->get_logger_mock();
		$repositories         = $this->get_repositories_mock();
		$subscription_factory = $this->get_subscription_factory_mock();
		$subject              = new OTGS_Installer_Site_Key_Ajax( $subscription_fetch, $logger, $repositories, $subscription_factory );

		$_POST['repository_id'] = 'wpml';
		$_POST['nonce']         = 'valid_nonce';
		$_POST['site_key_wpml'] = 'sitekey';

		$repository_data = $this->getMockBuilder( 'OTGS_Installer_Repository' )
		                        ->setMethods( array( 'get_product_name', 'set_subscription' ) )
		                        ->disableOriginalConstructor()
		                        ->getMock();

		$repositories->method( 'get' )
		             ->with( 'wpml' )
		             ->willReturn( $repository_data );

		$site_url = 'http://wpml.dev';

		WP_Mock::userFunction( 'get_site_url', array(
			'return' => $site_url,
		) );

		WP_Mock::userFunction( 'get_current_user_id', array(
			'return' => 1,
		) );

		$repository_data->expects( $this->never() )
		                ->method( 'set_subscription' );

		$repositories->expects( $this->never() )
		             ->method( 'save_subscription' );

		$repositories->expects( $this->once() )
		             ->method( 'refresh' );

		WP_Mock::userFunction( 'wp_send_json_success', array(
			'times' => 1,
		) );

		WP_Mock::userFunction( 'wp_verify_nonce', array(
			'args'   => array( $_POST['nonce'], 'remove_site_key_wpml' ),
			'return' => false,
		) );

		WP_Mock::passthruFunction( 'sanitize_text_field' );

		$subject->remove();
	}

	/**
	 * @test
	 */
	public function it_should_remove_subscription() {
		$subscription_fetch   = $this->get_fetch_subscription_mock();
		$logger               = $this->get_logger_mock();
		$repositories         = $this->get_repositories_mock();
		$subscription_factory = $this->get_subscription_factory_mock();
		$subject              = new OTGS_Installer_Site_Key_Ajax( $subscription_fetch, $logger, $repositories, $subscription_factory );

		$_POST['repository_id'] = 'wpml';
		$_POST['nonce']         = 'valid_nonce';
		$_POST['site_key_wpml'] = 'sitekey';

		$repository_data = $this->getMockBuilder( 'OTGS_Installer_Repository' )
		                        ->setMethods( array( 'get_product_name', 'set_subscription' ) )
		                        ->disableOriginalConstructor()
		                        ->getMock();

		$repositories->method( 'get' )
		             ->with( 'wpml' )
		             ->willReturn( $repository_data );

		$site_url = 'http://wpml.dev';

		WP_Mock::userFunction( 'get_site_url', array(
			'return' => $site_url,
		) );

		WP_Mock::userFunction( 'get_current_user_id', array(
			'return' => 1,
		) );

		$repository_data->expects( $this->once() )
		                ->method( 'set_subscription' )
		                ->with( null );

		$repositories->expects( $this->once() )
		             ->method( 'save_subscription' );

		$repositories->expects( $this->once() )
		             ->method( 'refresh' );

		WP_Mock::userFunction( 'wp_send_json_success', array(
			'times' => 1,
		) );

		WP_Mock::userFunction( 'wp_verify_nonce', array(
			'args'   => array( $_POST['nonce'], 'remove_site_key_wpml' ),
			'return' => true,
		) );

		WP_Mock::passthruFunction( 'sanitize_text_field' );

		WP_Mock::expectAction('otgs_installer_clean_plugins_update_cache');

		$subject->remove();
	}

	/**
	 * @test
	 */
	public function it_should_not_update_site_key_when_required_arguments_are_missing() {
		$subscription_fetch   = $this->get_fetch_subscription_mock();
		$logger               = $this->get_logger_mock();
		$repositories         = $this->get_repositories_mock();
		$subscription_factory = $this->get_subscription_factory_mock();
		$subject              = new OTGS_Installer_Site_Key_Ajax( $subscription_fetch, $logger, $repositories, $subscription_factory );

		$_POST['repository_id'] = '';
		$_POST['nonce']         = 'valid_nonce';

		$repositories->expects( $this->never() )
		             ->method( 'get' )
		             ->with( 'wpml' );

		WP_Mock::userFunction( 'wp_send_json_success', array(
			'times' => 1,
		) );

		WP_Mock::userFunction( 'wp_verify_nonce', array(
			'args'   => array( $_POST['nonce'], 'update_site_key_wpml' ),
			'return' => true,
		) );

		WP_Mock::passthruFunction( 'sanitize_text_field' );
		$subject->update();
	}

	/**
	 * @test
	 */
	public function it_should_not_update_site_key_when_it_is_not_found() {
		$subscription_fetch   = $this->get_fetch_subscription_mock();
		$logger               = $this->get_logger_mock();
		$repositories         = $this->get_repositories_mock();
		$subscription_factory = $this->get_subscription_factory_mock();
		$subject              = new OTGS_Installer_Site_Key_Ajax( $subscription_fetch, $logger, $repositories, $subscription_factory );

		$_POST['repository_id'] = 'wpml';
		$_POST['nonce']         = 'valid_nonce';

		$subscription = $this->getMockBuilder( 'OTGS_Installer_Subscription' )
		                     ->setMethods( array( 'get_site_key' ) )
		                     ->disableOriginalConstructor()
		                     ->getMock();

		$subscription->method( 'get_site_key' )
		             ->willReturn( '' );

		$repository_data = $this->getMockBuilder( 'OTGS_Installer_Repository' )
		                        ->setMethods( array( 'get_product_name', 'set_subscription', 'get_subscription' ) )
		                        ->disableOriginalConstructor()
		                        ->getMock();

		$repository_data->method( 'get_subscription' )
		                ->willReturn( $subscription );

		$subscription_fetch->expects( $this->never() )
		                   ->method( 'get' );

		$repositories->method( 'get' )
		             ->with( 'wpml' )
		             ->willReturn( $repository_data );

		WP_Mock::userFunction( 'wp_send_json_success', array(
			'times' => 1,
		) );

		WP_Mock::userFunction( 'wp_verify_nonce', array(
			'args'   => array( $_POST['nonce'], 'update_site_key_wpml' ),
			'return' => true,
		) );

		WP_Mock::passthruFunction( 'sanitize_text_field' );

		$subject->update();
	}

	/**
	 * @test
	 */
	public function it_should_catch_exception_coming_from_subscription_fetch_when_updating_site_key() {
		$subscription_fetch   = $this->get_fetch_subscription_mock();
		$logger               = $this->get_logger_mock();
		$repositories         = $this->get_repositories_mock();
		$subscription_factory = $this->get_subscription_factory_mock();
		$subject              = new OTGS_Installer_Site_Key_Ajax( $subscription_fetch, $logger, $repositories, $subscription_factory );

		$_POST['repository_id'] = 'wpml';
		$_POST['nonce']         = 'valid_nonce';

		$exception = \Mockery::mock( 'OTGS_Installer_Site_Key_Exception' );

		$repository_data = $this->getMockBuilder( 'OTGS_Installer_Repository' )
		                        ->setMethods( array( 'get_product_name', 'get_subscription' ) )
		                        ->disableOriginalConstructor()
		                        ->getMock();

		$subscription = $this->getMockBuilder( 'OTGS_Installer_Subscription' )
		                     ->setMethods( array( 'get_site_key' ) )
		                     ->disableOriginalConstructor()
		                     ->getMock();

		$subscription->method( 'get_site_key' )
		             ->willReturn( 'sitekey' );

		$repository_data->method( 'get_subscription' )
		                ->willReturn( $subscription );

		$repositories->method( 'get' )
		             ->with( 'wpml' )
		             ->willReturn( $repository_data );

		$repositories->expects( $this->never() )
		             ->method( 'save_subscription' );

		$subscription_fetch->expects( $this->once() )
		                   ->method( 'get' )
		                   ->with( $_POST['repository_id'], 'sitekey', WP_Installer::SITE_KEY_VALIDATION_SOURCE_REGISTRATION )
		                   ->willThrowException( $exception );

		WP_Mock::userFunction( 'wp_send_json_success', array(
			'times' => 1,
			'args'  => array( array( 'error' => '' ) ),
		) );

		WP_Mock::userFunction( 'wp_verify_nonce', array(
			'args'   => array( $_POST['nonce'], 'update_site_key_wpml' ),
			'return' => true,
		) );

		WP_Mock::passthruFunction( 'sanitize_text_field' );
		$subject->update();
	}

	/**
	 * @test
	 */
	public function it_should_remove_local_subscription_when_it_is_missing_in_the_server() {
		$subscription_fetch   = $this->get_fetch_subscription_mock();
		$logger               = $this->get_logger_mock();
		$repositories         = $this->get_repositories_mock();
		$subscription_factory = $this->get_subscription_factory_mock();
		$subject              = new OTGS_Installer_Site_Key_Ajax( $subscription_fetch, $logger, $repositories, $subscription_factory );

		$_POST['repository_id'] = 'wpml';
		$_POST['nonce']         = 'valid_nonce';

		$repository_data = $this->getMockBuilder( 'OTGS_Installer_Repository' )
		                        ->setMethods( array( 'get_product_name', 'get_subscription', 'set_subscription' ) )
		                        ->disableOriginalConstructor()
		                        ->getMock();

		$subscription = $this->getMockBuilder( 'OTGS_Installer_Subscription' )
		                     ->setMethods( array( 'get_site_key' ) )
		                     ->disableOriginalConstructor()
		                     ->getMock();

		$subscription->method( 'get_site_key' )
		             ->willReturn( 'sitekey' );

		$repository_data->method( 'get_subscription' )
		                ->willReturn( $subscription );

		$repository_data->expects( $this->once() )
			->method( 'set_subscription' )
			->with( null );

		$repositories->method( 'get' )
		             ->with( 'wpml' )
		             ->willReturn( $repository_data );

		$repositories->expects( $this->once() )
		             ->method( 'save_subscription' );

		$repositories->expects( $this->once() )
		             ->method( 'refresh' );

		$subscription_fetch->expects( $this->once() )
		                   ->method( 'get' )
		                   ->with( $_POST['repository_id'], 'sitekey', WP_Installer::SITE_KEY_VALIDATION_SOURCE_REGISTRATION )
		                   ->willReturn( null );

		WP_Mock::userFunction( 'wp_send_json_success', array(
			'times' => 1,
			'args'  => array( array( 'error' => 'Invalid site key for the current site. If the error persists, try to un-register first and then register again with the same site key.' ) ),
		) );

		WP_Mock::userFunction( 'wp_verify_nonce', array(
			'args'   => array( $_POST['nonce'], 'update_site_key_wpml' ),
			'return' => true,
		) );

		WP_Mock::passthruFunction( 'sanitize_text_field' );

		WP_Mock::expectAction('otgs_installer_clean_plugins_update_cache');

		$subject->update();
	}

	/**
	 * @test
	 */
	public function it_should_update_subscription() {
		$subscription_fetch   = $this->get_fetch_subscription_mock();
		$logger               = $this->get_logger_mock();
		$repositories         = $this->get_repositories_mock();
		$subscription_factory = $this->get_subscription_factory_mock();
		$subject              = new OTGS_Installer_Site_Key_Ajax( $subscription_fetch, $logger, $repositories, $subscription_factory );

		$_POST['repository_id'] = 'wpml';
		$_POST['nonce']         = 'valid_nonce';

		$repository_data = $this->getMockBuilder( 'OTGS_Installer_Repository' )
		                        ->setMethods( array( 'get_product_name', 'get_subscription', 'set_subscription' ) )
		                        ->disableOriginalConstructor()
		                        ->getMock();

		$subscription = $this->getMockBuilder( 'OTGS_Installer_Subscription' )
		                     ->setMethods( array( 'get_site_key' ) )
		                     ->disableOriginalConstructor()
		                     ->getMock();

		$subscription->method( 'get_site_key' )
		             ->willReturn( 'sitekey' );

		$repository_data->method( 'get_subscription' )
		                ->willReturn( $subscription );

		$site_url = 'http://wpml.dev';

		WP_Mock::userFunction( 'get_site_url', array(
			'return' => $site_url,
		) );

		WP_Mock::userFunction( 'get_current_user_id', array(
			'return' => 1,
		) );

		$subscription_from_server = new stdClass();

		$subscription_factory->method( 'create' )
		                     ->with( array(
				                     'data'          => $subscription_from_server,
				                     'key'           => $_POST['site_key_wpml'],
				                     'site_url'      => $site_url,
				                     'registered_by' => 1,
			                     )
		                     )
		                     ->willReturn( $subscription );

		$repository_data->expects( $this->once() )
		                ->method( 'set_subscription' )
		                ->with( $subscription );

		$repositories->method( 'get' )
		             ->with( 'wpml' )
		             ->willReturn( $repository_data );

		$repositories->expects( $this->once() )
		             ->method( 'save_subscription' );

		$repositories->expects( $this->once() )
		             ->method( 'refresh' );

		$subscription_fetch->expects( $this->once() )
		                   ->method( 'get' )
		                   ->with( $_POST['repository_id'], 'sitekey', WP_Installer::SITE_KEY_VALIDATION_SOURCE_REGISTRATION )
		                   ->willReturn( $subscription_from_server );

		WP_Mock::userFunction( 'wp_send_json_success', array(
			'times' => 1,
			'args'  => array( array( 'error' => '' ) ),
		) );

		WP_Mock::userFunction( 'wp_verify_nonce', array(
			'args'   => array( $_POST['nonce'], 'update_site_key_wpml' ),
			'return' => true,
		) );

		WP_Mock::passthruFunction( 'sanitize_text_field' );

		WP_Mock::expectAction('otgs_installer_clean_plugins_update_cache');

		$subject->update();
	}

	/**
	 * @test
	 * @dataProvider dpFindUser
	 */
	public function it_should_find_user( $repo, $email, $json, $result ) {
		$_POST['repository_id'] = $repo;
		$_POST['email'] = $email;
		$_POST['nonce'] = 'nonce';

		$site_key = 'somekey';
		$site_url = 'http://some-site.com';

		$subscription_fetch   = $this->get_fetch_subscription_mock();
		$logger               = $this->get_logger_mock();
		$repositories         = $this->get_repositories_mock();
		$subscription_factory = $this->get_subscription_factory_mock();
		$subject              = new OTGS_Installer_Site_Key_Ajax( $subscription_fetch, $logger, $repositories, $subscription_factory );

		$subscription = \Mockery::mock( 'OTGS_Installer_Subscription' );
		$subscription->shouldReceive( 'get_site_key' )->andReturn( $site_key );

		$repository_data = \Mockery::mock( 'OTGS_Installer_Repository' );
		$repository_data->shouldReceive( 'get_api_url' )->andReturn( 'url' );
		$repository_data->shouldReceive( 'get_subscription' )->andReturn( $subscription );

		$repositories->method( 'get' )
		             ->with( $repo )
		             ->willReturn( $repository_data );


		\WP_Mock::userFunction( 'wp_verify_nonce', [
				'return' => function ( $nonce, $action ) use ( $repo ) {
					$this->assertEquals( $_POST['nonce'], $nonce );
					$this->assertEquals( 'find_account_' . $repo, $action );

					return true;
				}
			]
		);

		\WP_Mock::passthruFunction('sanitize_text_field');

		$args['body'] = [
			'action' => 'user_email_exists',
			'umail'  => MD5( $email . $site_key ),
			'site_key' => $site_key,
			'site_url' => $site_url,
		];

		\WP_Mock::userFunction('wp_remote_post', [
			'args' => [ 'url', $args ],
			'return' => [ 'body' => $json ]
		]);

		\WP_Mock::userFunction('wp_remote_retrieve_body', [
			'args' => [ ['body' => $json ] ],
			'return' => $json
		]);

		\WP_Mock::userFunction( 'wp_send_json_success',
			[
				'times' => 1,
				'args' => [ ['found' => $result ] ]
			]
		);

		\WP_Mock::userFunction( 'get_site_url', [ 'return' => $site_url ] );

		$subject->find();
	}

	public function dpFindUser() {
		return [
			[ '', '', false, false ],
			[
				'wpml',
				'me@com.com',
				'{"info":{"user_email_exists":"Check if user email exists service"},"error":"Missing user email"}',
				false
			],
			[
				'wpml',
				'me@com.com',
				'{"info":{"user_email_exists":"Check if user email exists service"},"success":"Success"}',
				true
			],
		];
	}


	private function get_fetch_subscription_mock() {
		return $this->getMockBuilder( 'OTGS_Installer_Fetch_Subscription' )
		            ->setMethods( array( 'get' ) )
		            ->disableOriginalConstructor()
		            ->getMock();
	}

	private function get_logger_mock() {
		return $this->getMockBuilder( 'OTGS_Installer_Logger' )
		            ->disableOriginalConstructor()
		            ->getMock();
	}

	private function get_repositories_mock() {
		return $this->getMockBuilder( 'OTGS_Installer_Repositories' )
		            ->setMethods( array( 'get', 'save_subscription', 'set_subscription', 'refresh' ) )
		            ->disableOriginalConstructor()
		            ->getMock();
	}

	private function get_subscription_factory_mock() {
		return $this->getMockBuilder( 'OTGS_Installer_Subscription_Factory' )
		            ->setMethods( array( 'create' ) )
		            ->disableOriginalConstructor()
		            ->getMock();
	}
}
