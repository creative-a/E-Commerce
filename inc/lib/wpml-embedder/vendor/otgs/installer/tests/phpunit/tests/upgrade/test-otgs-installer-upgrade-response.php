<?php

use function Patchwork\Utils\args;

/**
 * Class Test_OTGS_Installer_Upgrade_Response
 *
 * @group installer-upgrade
 * @group installer-487
 */
class Test_OTGS_Installer_Upgrade_Response extends OTGS_TestCase {

	/**
	 * @test
	 */
	public function it_adds_hooks() {
		$subject = new OTGS_Installer_Upgrade_Response(
			array(),
			$this->get_installer_repositories_mock(),
			$this->get_installer_source_factory(),
			$this->get_installer_package_product_finder()
		);

		define( 'DOING_AJAX', true );
		$_POST['action'] = 'installer_download_plugin';

		WP_Mock::expectFilterAdded( 'site_transient_update_plugins', array( $subject, 'modify_upgrade_response' ) );
		WP_Mock::expectFilterAdded( 'pre_set_site_transient_update_plugins', array(
			$subject,
			'modify_upgrade_response'
		) );
		$subject->add_hooks();
		unset( $_POST['action'] );
	}

	/**
	 * @test
	 */
	public function it_does_not_add_site_transient_update_plugins_if_not_running_installer_ajax_for_downloading_plugin() {
		$subject = new OTGS_Installer_Upgrade_Response(
			array(),
			$this->get_installer_repositories_mock(),
			$this->get_installer_source_factory(),
			$this->get_installer_package_product_finder()
		);

		$_POST['action'] = 'something_else';

		WP_Mock::expectFilterNotAdded( 'site_transient_update_plugins', array( $subject, 'modify_upgrade_response' ) );
		WP_Mock::expectFilterAdded( 'pre_set_site_transient_update_plugins', array(
			$subject,
			'modify_upgrade_response'
		) );
		$subject->add_hooks();
	}

	/**
	 * @test
	 */
	public function it_should_skip_upgrade_if_subscription_is_not_valid() {
		$plugin = new OTGS_Installer_Plugin( array(
			'id'      => 'types/wpcf.php',
			'name'    => 'Toolset Types',
			'url'     => 'http://toolset.com',
			'version' => '1.0',
			'slug'    => 'types',
			'repo'    => 'toolset',
		) );

		$plugins    = array( $plugin );
		$repository = new OTGS_Installer_Repository( array(
			'id'           => 'toolset',
			'subscription' => null,
			'packages'     => array()
		) );

		$repositories = $this->get_installer_repositories_mock();
		$repositories->method( 'get' )
		             ->with( 'toolset' )
		             ->willReturn( $repository );

		$installer_source       = $this->get_installer_source_factory();
		$package_product_finder = $this->get_installer_package_product_finder();
		$subject                = new OTGS_Installer_Upgrade_Response(
			$plugins,
			$repositories,
			$installer_source,
			$package_product_finder
		);

		$this->assertEquals( array(), $subject->modify_upgrade_response( array() ) );
	}

	/**
	 * @test
	 */
	public function it_should_skip_upgrade_process_if_plugin_is_already_cached_in_the_list_of_upgrades() {
		$plugin = new OTGS_Installer_Plugin( array(
			'id'      => 'types/wpcf.php',
			'name'    => 'Toolset Types',
			'url'     => 'http://toolset.com',
			'version' => '1.0',
			'slug'    => 'types',
			'repo'    => 'toolset',
		) );

		$subscription = $this->getMockBuilder( 'OTGS_Installer_Subscription' )
		                     ->setMethods( array( 'is_valid' ) )
		                     ->disableOriginalConstructor()
		                     ->getMock();

		$subscription->method( 'is_valid' )
		             ->willReturn( true );

		$plugins    = array( $plugin );
		$repository = new OTGS_Installer_Repository( array(
			'id'           => 'toolset',
			'subscription' => $subscription,
			'packages'     => array()
		) );

		$repositories = $this->get_installer_repositories_mock();
		$repositories->method( 'get' )
		             ->with( 'toolset' )
		             ->willReturn( $repository );

		$installer_source       = $this->get_installer_source_factory();
		$package_product_finder = $this->get_installer_package_product_finder();
		$subject                = new OTGS_Installer_Upgrade_Response(
			$plugins,
			$repositories,
			$installer_source,
			$package_product_finder
		);

		$upgrade_response                             = new stdClass();
		$upgrade_response->response['types/wpcf.php'] = array();
		$expected                                     = clone $upgrade_response;

		$this->assertEquals( $expected, $subject->modify_upgrade_response( $upgrade_response ) );
	}

	/**
	 * @test
	 *
	 * E.g.: Toolset Views appears in both WPML and Toolset repositories (Toolset Views Lite), when client
	 * has a Toolset subscription we should take upgrades from toolset.com instead of wpml.org
	 */
	public function it_should_skip_upgrade_process_if_plugin_is_registered_on_external_repo() {
		$plugin = new OTGS_Installer_Plugin( array(
			'id'            => 'types/wpcf.php',
			'name'          => 'Toolset Types',
			'url'           => 'http://toolset.com',
			'version'       => '1.0',
			'slug'          => 'types',
			'repo'          => 'wpml',
			'external_repo' => 'toolset',
		) );

		$source_subscription = $this->getMockBuilder( 'OTGS_Installer_Subscription' )
		                            ->setMethods( array( 'is_valid' ) )
		                            ->disableOriginalConstructor()
		                            ->getMock();

		$source_subscription->method( 'is_valid' )
		                    ->willReturn( true );

		$external_subscription = $this->getMockBuilder( 'OTGS_Installer_Subscription' )
		                              ->setMethods( array( 'is_valid' ) )
		                              ->disableOriginalConstructor()
		                              ->getMock();

		$plugins             = array( $plugin );
		$source_repository   = new OTGS_Installer_Repository( array(
			'id'           => 'wpml',
			'subscription' => $source_subscription,
			'packages'     => array()
		) );
		$external_repository = new OTGS_Installer_Repository( array(
			'id'           => 'toolset',
			'subscription' => $external_subscription,
			'packages'     => array()
		) );

		$repositories = $this->get_installer_repositories_mock();
		$repositories->method( 'get' )
		             ->withConsecutive(
			             array( 'wpml' ),
			             array( 'toolset' )
		             )
		             ->willReturnOnConsecutiveCalls(
			             $source_repository,
			             $external_repository
		             );

		$installer_source       = $this->get_installer_source_factory();
		$package_product_finder = $this->get_installer_package_product_finder();

		$product_found = $this->getMockBuilder( 'OTGS_Installer_Package_Product' )
		                      ->setMethods( array( 'is_plugin_registered' ) )
		                      ->disableOriginalConstructor()
		                      ->getMock();

		$product_found->method( 'is_plugin_registered' )
		              ->willReturn( true );

		$package_product_finder->method( 'get_product_in_repository_by_subscription' )
		                       ->withConsecutive(
			                       array( $source_repository ),
			                       array( $external_repository )
		                       )
		                       ->willReturnOnConsecutiveCalls(
			                       null,
			                       $product_found
		                       );

		$subject = new OTGS_Installer_Upgrade_Response(
			$plugins,
			$repositories,
			$installer_source,
			$package_product_finder
		);

		$upgrade_response                                = new stdClass();
		$upgrade_response->response['some-other-plugin'] = array();
		$expected                                        = clone $upgrade_response;

		$this->assertEquals( $expected, $subject->modify_upgrade_response( $upgrade_response ) );
	}

	/**
	 * @test
	 */
	public function it_should_skip_upgrade_process_if_plugin_is_not_registered_in_the_user_product() {
		$plugin = new OTGS_Installer_Plugin( array(
			'id'                => 'types/wpcf.php',
			'name'              => 'Toolset Types',
			'url'               => 'http://toolset.com',
			'version'           => '1.0',
			'slug'              => 'types',
			'repo'              => 'wpml',
			'external_repo'     => 'toolset',
			'installed_version' => '0.5',
		) );

		$source_subscription = $this->getMockBuilder( 'OTGS_Installer_Subscription' )
		                            ->setMethods( array( 'is_valid' ) )
		                            ->disableOriginalConstructor()
		                            ->getMock();

		$source_subscription->method( 'is_valid' )
		                    ->willReturn( false );

		$external_subscription = $this->getMockBuilder( 'OTGS_Installer_Subscription' )
		                              ->setMethods( array( 'is_valid' ) )
		                              ->disableOriginalConstructor()
		                              ->getMock();

		$plugins             = array( $plugin );
		$source_repository   = new OTGS_Installer_Repository( array(
			'id'           => 'wpml',
			'subscription' => $source_subscription,
			'packages'     => array()
		) );
		$external_repository = new OTGS_Installer_Repository( array(
			'id'           => 'toolset',
			'subscription' => $external_subscription,
			'packages'     => array()
		) );

		$repositories = $this->get_installer_repositories_mock();
		$repositories->method( 'get' )
		             ->withConsecutive(
			             array( 'wpml' ),
			             array( 'toolset' )
		             )
		             ->willReturnOnConsecutiveCalls(
			             $source_repository,
			             $external_repository
		             );

		$installer_source       = $this->get_installer_source_factory();
		$package_product_finder = $this->get_installer_package_product_finder();

		$product_found = $this->getMockBuilder( 'OTGS_Installer_Package_Product' )
		                      ->setMethods( array( 'is_plugin_registered' ) )
		                      ->disableOriginalConstructor()
		                      ->getMock();

		$product_found->method( 'is_plugin_registered' )
		              ->with( 'types' )
		              ->willReturn( true );

		$package_product_finder->method( 'get_product_in_repository_by_subscription' )
		                       ->withConsecutive(
			                       array( $source_repository ),
			                       array( $external_repository )
		                       )
		                       ->willReturnOnConsecutiveCalls(
			                       $product_found,
			                       null
		                       );

		$subject = new OTGS_Installer_Upgrade_Response(
			$plugins,
			$repositories,
			$installer_source,
			$package_product_finder
		);

		$upgrade_response                                = new stdClass();
		$upgrade_response->response['some-other-plugin'] = array();
		$expected                                        = clone $upgrade_response;

		$this->assertEquals( $expected, $subject->modify_upgrade_response( $upgrade_response ) );
	}

	/**
	 * @test
	 * @group adriano
	 */
	public function it_should_skip_upgrade_process_if_no_product_is_found_in_the_site_subscription() {
		$plugin = new OTGS_Installer_Plugin( array(
			'id'                => 'types/wpcf.php',
			'name'              => 'Toolset Types',
			'url'               => 'http://toolset.com',
			'version'           => '1.0',
			'slug'              => 'types',
			'repo'              => 'wpml',
			'external_repo'     => 'toolset',
			'installed_version' => '0.5',
		) );

		$source_subscription = $this->getMockBuilder( 'OTGS_Installer_Subscription' )
		                            ->setMethods( array( 'is_valid' ) )
		                            ->disableOriginalConstructor()
		                            ->getMock();

		$source_subscription->method( 'is_valid' )
		                    ->willReturn( true );

		$plugins           = array( $plugin );
		$source_repository = new OTGS_Installer_Repository( array(
			'id'           => 'wpml',
			'subscription' => $source_subscription,
			'packages'     => array()
		) );

		$repositories = $this->get_installer_repositories_mock();
		$repositories->method( 'get' )
		             ->withConsecutive(
			             array( 'wpml' ),
			             array( 'toolset' )
		             )
		             ->willReturnOnConsecutiveCalls(
			             $source_repository,
			             false
		             );

		$installer_source       = $this->get_installer_source_factory();
		$package_product_finder = $this->get_installer_package_product_finder();

		$product_found = $this->getMockBuilder( 'OTGS_Installer_Package_Product' )
		                      ->setMethods( array( 'is_plugin_registered' ) )
		                      ->disableOriginalConstructor()
		                      ->getMock();

		$product_found->method( 'is_plugin_registered' )
		              ->with( 'types' )
		              ->willReturn( true );

		$package_product_finder->method( 'get_product_in_repository_by_subscription' )
		                       ->withConsecutive(
			                       array( $source_repository ),
			                       array( $source_repository )
		                       )
		                       ->willReturnOnConsecutiveCalls(
			                       null,
			                       null
		                       );

		$subject = new OTGS_Installer_Upgrade_Response(
			$plugins,
			$repositories,
			$installer_source,
			$package_product_finder
		);

		$upgrade_response                                = new stdClass();
		$upgrade_response->response['some-other-plugin'] = array();
		$expected                                        = clone $upgrade_response;

		$this->assertEquals( $expected, $subject->modify_upgrade_response( $upgrade_response ) );
	}

	/**
	 * @test
	 */
	public function it_should_fallback_on_wporg_repository_when_user_has_no_valid_subscription_and_plugin_has_fallback_on_wporg() {
		$plugin = new OTGS_Installer_Plugin( array(
			'id'                    => 'types/wpcf.php',
			'name'                  => 'Toolset Types',
			'url'                   => 'http://toolset.com',
			'version'               => '1.0',
			'slug'                  => 'types',
			'repo'                  => 'wpml',
			'external_repo'         => 'toolset',
			'installed_version'     => '0.5',
			'is_free_on_wporg'      => 1,
			'has_fallback_on_wporg' => 1,
		) );

		$source_subscription = $this->getMockBuilder( 'OTGS_Installer_Subscription' )
		                            ->setMethods( array( 'is_valid', 'get_site_key' ) )
		                            ->disableOriginalConstructor()
		                            ->getMock();

		$source_subscription->method( 'is_valid' )
		                    ->willReturn( false );

		$source_subscription->method( 'get_site_key' )
		                    ->willReturn( '' );

		$external_subscription = $this->getMockBuilder( 'OTGS_Installer_Subscription' )
		                              ->setMethods( array( 'is_valid' ) )
		                              ->disableOriginalConstructor()
		                              ->getMock();

		$plugins             = array( $plugin );
		$source_repository   = new OTGS_Installer_Repository( array(
			'id'           => 'wpml',
			'subscription' => $source_subscription,
			'packages'     => array()
		) );
		$external_repository = new OTGS_Installer_Repository( array(
			'id'           => 'toolset',
			'subscription' => $external_subscription,
			'packages'     => array()
		) );

		$repositories = $this->get_installer_repositories_mock();
		$repositories->method( 'get' )
		             ->withConsecutive(
			             array( 'wpml' ),
			             array( 'toolset' )
		             )
		             ->willReturnOnConsecutiveCalls(
			             $source_repository,
			             $external_repository
		             );

		$installer_source       = $this->get_installer_source_factory();
		$package_product_finder = $this->get_installer_package_product_finder();

		$product_found = $this->getMockBuilder( 'OTGS_Installer_Package_Product' )
		                      ->setMethods( array( 'is_plugin_registered' ) )
		                      ->disableOriginalConstructor()
		                      ->getMock();

		$product_found->method( 'is_plugin_registered' )
		              ->with( 'types' )
		              ->willReturn( true );

		$package_product_finder->method( 'get_product_in_repository_by_subscription' )
		                       ->withConsecutive(
			                       array( $source_repository ),
			                       array( $external_repository )
		                       )
		                       ->willReturnOnConsecutiveCalls(
			                       $product_found,
			                       null
		                       );

		$subject = new OTGS_Installer_Upgrade_Response(
			$plugins,
			$repositories,
			$installer_source,
			$package_product_finder
		);

		$upgrade_response                                = new stdClass();
		$upgrade_response->response['some-other-plugin'] = array();
		$expected                                        = clone $upgrade_response;

		$this->assertEquals( $expected, $subject->modify_upgrade_response( $upgrade_response ) );
	}

	/**
	 * @test
	 */
	public function it_should_skip_upgrade_if_plugin_is_up_to_date() {
		$plugin = new OTGS_Installer_Plugin( array(
			'id'                    => 'types/wpcf.php',
			'name'                  => 'Toolset Types',
			'url'                   => 'http://toolset.com',
			'version'               => '1.0',
			'slug'                  => 'types',
			'repo'                  => 'wpml',
			'external_repo'         => 'toolset',
			'installed_version'     => '1.0',
			'is_free_on_wporg'      => 1,
			'has_fallback_on_wporg' => 1,
		) );

		$source_subscription = $this->getMockBuilder( 'OTGS_Installer_Subscription' )
		                            ->setMethods( array( 'is_valid', 'get_site_key' ) )
		                            ->disableOriginalConstructor()
		                            ->getMock();

		$source_subscription->method( 'is_valid' )
		                    ->willReturn( true );

		$source_subscription->method( 'get_site_key' )
		                    ->willReturn( '' );

		$external_subscription = $this->getMockBuilder( 'OTGS_Installer_Subscription' )
		                              ->setMethods( array( 'is_valid' ) )
		                              ->disableOriginalConstructor()
		                              ->getMock();

		$plugins             = array( $plugin );
		$source_repository   = new OTGS_Installer_Repository( array(
			'id'           => 'wpml',
			'subscription' => $source_subscription,
			'packages'     => array()
		) );
		$external_repository = new OTGS_Installer_Repository( array(
			'id'           => 'toolset',
			'subscription' => $external_subscription,
			'packages'     => array()
		) );

		$repositories = $this->get_installer_repositories_mock();
		$repositories->method( 'get' )
		             ->withConsecutive(
			             array( 'wpml' ),
			             array( 'toolset' )
		             )
		             ->willReturnOnConsecutiveCalls(
			             $source_repository,
			             $external_repository
		             );

		$installer_source       = $this->get_installer_source_factory();
		$package_product_finder = $this->get_installer_package_product_finder();

		$product_found = $this->getMockBuilder( 'OTGS_Installer_Package_Product' )
		                      ->setMethods( array( 'is_plugin_registered' ) )
		                      ->disableOriginalConstructor()
		                      ->getMock();

		$product_found->method( 'is_plugin_registered' )
		              ->with( 'types' )
		              ->willReturn( true );

		$package_product_finder->method( 'get_product_in_repository_by_subscription' )
		                       ->withConsecutive(
			                       array( $source_repository ),
			                       array( $external_repository )
		                       )
		                       ->willReturnOnConsecutiveCalls(
			                       $product_found,
			                       null
		                       );

		$subject = new OTGS_Installer_Upgrade_Response(
			$plugins,
			$repositories,
			$installer_source,
			$package_product_finder
		);

		$upgrade_response                                = new stdClass();
		$upgrade_response->response['some-other-plugin'] = array();
		$expected                                        = clone $upgrade_response;

		$this->assertEquals( $expected, $subject->modify_upgrade_response( $upgrade_response ) );
	}

	/**
	 * @test
	 */
	public function it_should_modify_the_response_of_upgrade_logic_without_appending_sitekey_when_it_is_not_found() {
		$plugin = new OTGS_Installer_Plugin( array(
			'id'                    => 'types/wpcf.php',
			'name'                  => 'Toolset Types',
			'url'                   => 'http://toolset.com',
			'version'               => '1.0',
			'slug'                  => 'types',
			'repo'                  => 'wpml',
			'external_repo'         => 'toolset',
			'installed_version'     => '0.5',
			'is_free_on_wporg'      => 1,
			'has_fallback_on_wporg' => 1,
			'tested'                => '1.0.0',
		) );

		$source_subscription = $this->getMockBuilder( 'OTGS_Installer_Subscription' )
		                            ->setMethods( array( 'is_valid', 'get_site_key' ) )
		                            ->disableOriginalConstructor()
		                            ->getMock();

		$source_subscription->method( 'is_valid' )
		                    ->willReturn( true );

		$source_subscription->method( 'get_site_key' )
		                    ->willReturn( '' );

		$external_subscription = $this->getMockBuilder( 'OTGS_Installer_Subscription' )
		                              ->setMethods( array( 'is_valid' ) )
		                              ->disableOriginalConstructor()
		                              ->getMock();

		$plugins             = array( $plugin );
		$source_repository   = new OTGS_Installer_Repository( array(
			'id'           => 'wpml',
			'subscription' => $source_subscription,
			'packages'     => array()
		) );
		$external_repository = new OTGS_Installer_Repository( array(
			'id'           => 'toolset',
			'subscription' => $external_subscription,
			'packages'     => array()
		) );

		$repositories = $this->get_installer_repositories_mock();
		$repositories->method( 'get' )
		             ->withConsecutive(
			             array( 'wpml' ),
			             array( 'toolset' )
		             )
		             ->willReturnOnConsecutiveCalls(
			             $source_repository,
			             $external_repository
		             );

		$installer_source       = $this->get_installer_source_factory();
		$package_product_finder = $this->get_installer_package_product_finder();

		$product_found = $this->getMockBuilder( 'OTGS_Installer_Package_Product' )
		                      ->setMethods( array( 'is_plugin_registered' ) )
		                      ->disableOriginalConstructor()
		                      ->getMock();

		$product_found->method( 'is_plugin_registered' )
		              ->with( 'types' )
		              ->willReturn( true );

		$package_product_finder->method( 'get_product_in_repository_by_subscription' )
		                       ->withConsecutive(
			                       array( $external_repository ),
			                       array( $source_repository )
		                       )
		                       ->willReturnOnConsecutiveCalls(
			                       null,
			                       $product_found
		                       );

		$subject = new OTGS_Installer_Upgrade_Response(
			$plugins,
			$repositories,
			$installer_source,
			$package_product_finder
		);

		$upgrade_response                                = new stdClass();
		$upgrade_response->response['some-other-plugin'] = array();

		$expected_response                 = new stdClass();
		$expected_response->id             = 0;
		$expected_response->slug           = $plugin->get_slug();
		$expected_response->plugin         = $plugin->get_id();
		$expected_response->new_version    = $plugin->get_version();
		$expected_response->upgrade_notice = '';
		$expected_response->url            = $plugin->get_url();
		$expected_response->tested         = $plugin->get_tested();

		$expected_upgrade_response                                = new stdClass();
		$expected_upgrade_response->checked['types/wpcf.php']     = $plugin->get_installed_version();
		$expected_upgrade_response->response['types/wpcf.php']    = $expected_response;
		$expected_upgrade_response->response['some-other-plugin'] = array();

		WP_Mock::onFilter( 'otgs_installer_upgrade_check_response' )
		       ->with( $expected_response, $plugin->get_name(), $source_repository->get_id() )
		       ->reply( $expected_response );

		$this->assertEquals( $expected_upgrade_response, $subject->modify_upgrade_response( $upgrade_response ) );
	}

	/**
	 * @test
	 * @group installer-560
	 */
	public function it_should_modify_the_response_of_upgrade_logic_appending_sitekey_when_it_is_found() {
		$plugin = new OTGS_Installer_Plugin( array(
			'id'                    => 'types/wpcf.php',
			'name'                  => 'Toolset Types',
			'url'                   => 'http://toolset.com',
			'version'               => '1.0',
			'slug'                  => 'types',
			'repo'                  => 'wpml',
			'external_repo'         => 'toolset',
			'installed_version'     => '0.5',
			'is_free_on_wporg'      => 1,
			'has_fallback_on_wporg' => 1,
			'tested'                => '1.0.0',
		) );

		$source_subscription = $this->getMockBuilder( 'OTGS_Installer_Subscription' )
		                            ->setMethods( array( 'is_valid', 'get_site_key', 'get_site_url' ) )
		                            ->disableOriginalConstructor()
		                            ->getMock();

		$source_subscription->method( 'is_valid' )
		                    ->willReturn( true );

		$source_subscription->method( 'get_site_key' )
		                    ->willReturn( 'mysitekey' );

		$source_subscription->method( 'get_site_url' )
		                    ->willReturn( 'http://dev.otgs' );

		$external_subscription = $this->getMockBuilder( 'OTGS_Installer_Subscription' )
		                              ->setMethods( array( 'is_valid' ) )
		                              ->disableOriginalConstructor()
		                              ->getMock();

		$plugins             = array( $plugin );
		$source_repository   = new OTGS_Installer_Repository( array(
			'id'           => 'wpml',
			'subscription' => $source_subscription,
			'packages'     => array()
		) );
		$external_repository = new OTGS_Installer_Repository( array(
			'id'           => 'toolset',
			'subscription' => $external_subscription,
			'packages'     => array()
		) );

		$repositories = $this->get_installer_repositories_mock();
		$repositories->method( 'get' )
		             ->withConsecutive(
			             array( 'wpml' ),
			             array( 'toolset' )
		             )
		             ->willReturnOnConsecutiveCalls(
			             $source_repository,
			             $external_repository
		             );

		$installer_package_source = $this->getMockBuilder( 'OTGS_Installer_Source' )
		                                 ->setMethods( array( 'get' ) )
		                                 ->disableOriginalConstructor()
		                                 ->getMock();

		$installer_package_source->method( 'get' )
		                         ->willReturn( array() );

		$installer_source = $this->get_installer_source_factory();
		$installer_source->method( 'create' )
		                 ->willReturn( $installer_package_source );

		$package_product_finder = $this->get_installer_package_product_finder();

		$product_found = $this->getMockBuilder( 'OTGS_Installer_Package_Product' )
		                      ->setMethods( array( 'is_plugin_registered' ) )
		                      ->disableOriginalConstructor()
		                      ->getMock();

		$product_found->method( 'is_plugin_registered' )
		              ->with( 'types' )
		              ->willReturn( true );

		$package_product_finder->method( 'get_product_in_repository_by_subscription' )
		                       ->withConsecutive(
			                       array( $external_repository ),
			                       array( $source_repository )
		                       )
		                       ->willReturnOnConsecutiveCalls(
			                       null,
			                       $product_found
		                       );

		$subject = new OTGS_Installer_Upgrade_Response(
			$plugins,
			$repositories,
			$installer_source,
			$package_product_finder
		);

		$url_with_sitekey_site_url = $plugin->get_url() . '&site_key=mysitekey&site_url=http://dev.otgs';

		\WP_Mock::userFunction( 'get_option', ['args' => ['wp_installer_settings'] ] );

		WP_Mock::wpFunction( 'is_multisite', array(
			'return' => false,
		) );

		\WP_Mock::userFunction( 'update_option', [] );

		\WP_Mock::userFunction( 'get_site_url', array(
			'return' => 'http://dev.otgs',
		) );

		WP_Mock::wpFunction( 'add_query_arg', array(
			'return' => $url_with_sitekey_site_url,
			'args'   => array(
				array( 'site_key' => 'mysitekey', 'site_url' => 'http://dev.otgs' ),
				$plugin->get_url()
			)
		) );

		$url_with_wpml_params = $url_with_sitekey_site_url . '&using_icl=0&wpml_version=';

		WP_Mock::wpFunction( 'add_query_arg', array(
			'return' => $url_with_wpml_params,
			'args'   => array(
				array( 'using_icl' => false, 'wpml_version' => '' ),
				$url_with_sitekey_site_url
			)
		) );

		$upgrade_response                                = new stdClass();
		$upgrade_response->response['some-other-plugin'] = array();

		$expected_response                 = new stdClass();
		$expected_response->id             = 0;
		$expected_response->slug           = $plugin->get_slug();
		$expected_response->plugin         = $plugin->get_id();
		$expected_response->new_version    = $plugin->get_version();
		$expected_response->upgrade_notice = '';
		$expected_response->url            = $plugin->get_url();
		$expected_response->package        = $url_with_wpml_params;
		$expected_response->tested         = $plugin->get_tested();

		$expected_upgrade_response                                = new stdClass();
		$expected_upgrade_response->checked['types/wpcf.php']     = $plugin->get_installed_version();
		$expected_upgrade_response->response['types/wpcf.php']    = $expected_response;
		$expected_upgrade_response->response['some-other-plugin'] = array();

		WP_Mock::onFilter( 'otgs_installer_upgrade_check_response' )
		       ->with( $expected_response, $plugin->get_name(), $source_repository->get_id() )
		       ->reply( $expected_response );

		$this->assertEquals( $expected_upgrade_response, $subject->modify_upgrade_response( $upgrade_response ) );
	}

	private function get_installer_repositories_mock() {
		return $this->getMockBuilder( 'OTGS_Installer_Repositories' )
		            ->disableOriginalConstructor()
		            ->getMock();
	}

	private function get_installer_source_factory() {
		return $this->getMockBuilder( 'OTGS_Installer_Source_Factory' )
		            ->disableOriginalConstructor()
		            ->getMock();
	}

	private function get_installer_package_product_finder() {
		return $this->getMockBuilder( 'OTGS_Installer_Package_Product_Finder' )
		            ->setMethods( array( 'get_product_in_repository_by_subscription' ) )
		            ->disableOriginalConstructor()
		            ->getMock();
	}
}