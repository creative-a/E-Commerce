<?php

/**
 * Class Test_OTGS_Installer_Repositories
 *
 * @group installer-487
 * @group repositories
 */
class Test_OTGS_Installer_Repositories extends OTGS_TestCase {

	const WPML_API         = 'api.wpml.org';
	const TOOLSET_API      = 'api.toolset.com';

	/**
	 * @test
	 */
	public function it_gets_all_repositories() {
		$installer            = $this->get_installer_mock();
		$repository_factory   = $this->get_repository_factory_mock();
		$subscription_factory = $this->get_subscription_factory_mock();

		$wpml_repo = $this->getMockBuilder( 'OTGS_Installer_Repository' )
		                  ->disableOriginalConstructor()
		                  ->getMock();

		$toolset_repo = $this->getMockBuilder( 'OTGS_Installer_Repository' )
		                     ->disableOriginalConstructor()
		                     ->getMock();

		$wpml_subscription = $this->getMockBuilder( 'OTGS_Installer_Subscription' )
		                          ->disableOriginalConstructor()
		                          ->getMock();

		$toolset_subscription = $this->getMockBuilder( 'OTGS_Installer_Subscription' )
		                             ->disableOriginalConstructor()
		                             ->getMock();

		$wpml_package = $this->getMockBuilder( 'OTGS_Installer_Package' )
		                     ->disableOriginalConstructor()
		                     ->getMock();

		$toolset_package = $this->getMockBuilder( 'OTGS_Installer_Package' )
		                        ->disableOriginalConstructor()
		                        ->getMock();

		$this->mock_create_repository_method(
			$repository_factory,
			$wpml_subscription,
			$wpml_package,
			$toolset_subscription,
			$toolset_package,
			$wpml_repo,
			$toolset_repo
		);

		$wpml_package_product = $this->getMockBuilder( 'OTGS_Installer_Package_Product' )
		                             ->disableOriginalConstructor()
		                             ->getMock();

		$toolset_package_product = $this->getMockBuilder( 'OTGS_Installer_Package_Product' )
		                                ->disableOriginalConstructor()
		                                ->getMock();

		$this->mock_create_package_method(
			$repository_factory,
			$wpml_package_product,
			$toolset_package_product,
			$wpml_package,
			$toolset_package
		);

		$this->mock_create_product_method( $repository_factory, $wpml_package_product, $toolset_package_product );

		$wpml_user_subscription = array(
			'data' => array(),
		);

		$toolset_user_subscription = array(
			'data' => array(),
		);

		$subscription_factory->method( 'create' )
		                     ->withConsecutive(
			                     array( $wpml_user_subscription ),
			                     array( $toolset_user_subscription )
		                     )
		                     ->willReturn( $wpml_subscription, $toolset_subscription );

		$settings = $this->get_setting_repositories( $wpml_user_subscription, $toolset_user_subscription );

		$installer_repositories = array(
			'wpml'    => array(
				'api-url'  => self::WPML_API,
			),
			'toolset' => array(
				'api-url'  => self::TOOLSET_API,
			),
		);

		$installer->method( 'get_settings' )->willReturn( $settings );
		$installer->method( 'get_repositories' )->willReturn( $installer_repositories );

		$subject = new OTGS_Installer_Repositories( $installer, $repository_factory, $subscription_factory );

		$expected = array( $wpml_repo, $toolset_repo );
		$this->assertEquals( $expected, $subject->get_all() );
	}

	/**
	 * @test
	 */
	public function it_gets_single_repository() {
		$installer            = $this->get_installer_mock();
		$repository_factory   = $this->get_repository_factory_mock();
		$subscription_factory = $this->get_subscription_factory_mock();

		$wpml_repo = $this->getMockBuilder( 'OTGS_Installer_Repository' )
		                  ->disableOriginalConstructor()
		                  ->getMock();

		$toolset_repo = $this->getMockBuilder( 'OTGS_Installer_Repository' )
		                     ->disableOriginalConstructor()
		                     ->getMock();

		$wpml_repo->method( 'get_id' )->willReturn( 'wpml' );
		$toolset_repo->method( 'get_id' )->willReturn( 'toolset' );

		$wpml_subscription = $this->getMockBuilder( 'OTGS_Installer_Subscription' )
		                          ->disableOriginalConstructor()
		                          ->getMock();

		$toolset_subscription = $this->getMockBuilder( 'OTGS_Installer_Subscription' )
		                             ->disableOriginalConstructor()
		                             ->getMock();

		$wpml_package = $this->getMockBuilder( 'OTGS_Installer_Package' )
		                     ->disableOriginalConstructor()
		                     ->getMock();

		$toolset_package = $this->getMockBuilder( 'OTGS_Installer_Package' )
		                        ->disableOriginalConstructor()
		                        ->getMock();

		$this->mock_create_repository_method( $repository_factory, $wpml_subscription, $wpml_package, $toolset_subscription, $toolset_package, $wpml_repo, $toolset_repo );

		$wpml_package_product = $this->getMockBuilder( 'OTGS_Installer_Package_Product' )
		                             ->disableOriginalConstructor()
		                             ->getMock();

		$toolset_package_product = $this->getMockBuilder( 'OTGS_Installer_Package_Product' )
		                                ->disableOriginalConstructor()
		                                ->getMock();

		$this->mock_create_package_method(
			$repository_factory,
			$wpml_package_product,
			$toolset_package_product,
			$wpml_package,
			$toolset_package
		);

		$this->mock_create_product_method( $repository_factory, $wpml_package_product, $toolset_package_product );

		$wpml_user_subscription = array(
			'data' => array(),
		);

		$toolset_user_subscription = array(
			'data' => array(),
		);

		$subscription_factory->method( 'create' )
		                     ->withConsecutive(
			                     array( $wpml_user_subscription ),
			                     array( $toolset_user_subscription )
		                     )
		                     ->willReturn( $wpml_subscription, $toolset_subscription );

		$settings = $this->get_setting_repositories( $wpml_user_subscription, $toolset_user_subscription );

		$installer_repositories = array(
			'wpml'    => array(
				'api-url'  => self::WPML_API,
			),
			'toolset' => array(
				'api-url'  => self::TOOLSET_API,
			),
		);

		$installer->method( 'get_settings' )->willReturn( $settings );
		$installer->method( 'get_repositories' )->willReturn( $installer_repositories );

		$subject = new OTGS_Installer_Repositories( $installer, $repository_factory, $subscription_factory );

		$this->assertEquals( $wpml_repo, $subject->get( 'wpml' ) );
	}

	/**
	 * @test
	 */
	public function it_returns_null_when_no_repository_is_found() {
		$installer            = $this->get_installer_mock();
		$repository_factory   = $this->get_repository_factory_mock();
		$subscription_factory = $this->get_subscription_factory_mock();

		$wpml_repo = $this->getMockBuilder( 'OTGS_Installer_Repository' )
		                  ->disableOriginalConstructor()
		                  ->getMock();

		$toolset_repo = $this->getMockBuilder( 'OTGS_Installer_Repository' )
		                     ->disableOriginalConstructor()
		                     ->getMock();

		$wpml_repo->method( 'get_id' )->willReturn( 'wpml' );
		$toolset_repo->method( 'get_id' )->willReturn( 'toolset' );

		$wpml_subscription = $this->getMockBuilder( 'OTGS_Installer_Subscription' )
		                          ->disableOriginalConstructor()
		                          ->getMock();

		$toolset_subscription = $this->getMockBuilder( 'OTGS_Installer_Subscription' )
		                             ->disableOriginalConstructor()
		                             ->getMock();

		$wpml_package = $this->getMockBuilder( 'OTGS_Installer_Package' )
		                     ->disableOriginalConstructor()
		                     ->getMock();

		$toolset_package = $this->getMockBuilder( 'OTGS_Installer_Package' )
		                        ->disableOriginalConstructor()
		                        ->getMock();

		$this->mock_create_repository_method(
			$repository_factory,
			$wpml_subscription,
			$wpml_package,
			$toolset_subscription,
			$toolset_package,
			$wpml_repo,
			$toolset_repo
		);

		$wpml_package_product = $this->getMockBuilder( 'OTGS_Installer_Package_Product' )
		                             ->disableOriginalConstructor()
		                             ->getMock();

		$toolset_package_product = $this->getMockBuilder( 'OTGS_Installer_Package_Product' )
		                                ->disableOriginalConstructor()
		                                ->getMock();

		$this->mock_create_package_method(
			$repository_factory,
			$wpml_package_product,
			$toolset_package_product,
			$wpml_package,
			$toolset_package
		);

		$this->mock_create_product_method( $repository_factory, $wpml_package_product, $toolset_package_product );

		$wpml_user_subscription = array(
			'data' => array(),
		);

		$toolset_user_subscription = array(
			'data' => array(),
		);

		$subscription_factory->method( 'create' )
		                     ->withConsecutive(
			                     array( $wpml_user_subscription ),
			                     array( $toolset_user_subscription )
		                     )
		                     ->willReturn( $wpml_subscription, $toolset_subscription );

		$settings = $this->get_setting_repositories( $wpml_user_subscription, $toolset_user_subscription );

		$installer_repositories = array(
			'wpml'    => array(
				'api-url'  => self::WPML_API,
			),
			'toolset' => array(
				'api-url'  => self::TOOLSET_API,
			),
		);

		$installer->method( 'get_settings' )->willReturn( $settings );
		$installer->method( 'get_repositories' )->willReturn( $installer_repositories );

		$subject = new OTGS_Installer_Repositories( $installer, $repository_factory, $subscription_factory );

		$this->assertNull( $subject->get( 'unknown-repository' ) );
	}

	private function get_setting_repositories( $wpml_user_subscription, $toolset_user_subscription ) {
		return array(
			'repositories' => array(
				'wpml'    => array(
					'subscription' => $wpml_user_subscription,
					'data'         => array(
						'product-name' => 'WPML',
						'packages'     => array(
							1 => array(
								'id'          => 123,
								'name'        => 'WPML Package',
								'description' => 'WPML Package Description',
								'image_url'   => 'http://wpml.dev',
								'order'       => 1,
								'parent'      => '',
								'products'    => array(
									array(
										'id'                           => 0,
										'name'                         => 'WPML Product',
										'description'                  => 'WPML Product description',
										'price'                        => 100,
										'subscription_type'            => 2,
										'subscription_type_text'       => 'Type Text',
										'subscription_info'            => 'Info',
										'subscription_type_equivalent' => 3,
										'url'                          => 'http://dev.otgs',
										'renewals'                     => array(),
										'upgrades'                     => array(),
										'plugins'                      => array(),
										'downloads'                    => array(),
									)
								),
							),
						),
					),
				),
				'toolset' => array(
					'subscription' => $toolset_user_subscription,
					'data'         => array(
						'product-name' => 'Toolset',
						'packages'     => array(
							1 => array(
								'id'          => 123,
								'name'        => 'Toolset Package',
								'description' => 'Toolset Package Description',
								'image_url'   => 'http://wpml.dev',
								'order'       => 1,
								'parent'      => '',
								'products'    => array(
									array(
										'id'                           => 1,
										'name'                         => 'Toolset Product',
										'description'                  => 'Toolset Product description',
										'price'                        => 100,
										'subscription_type'            => 2,
										'subscription_type_text'       => 'Type Text',
										'subscription_info'            => 'Info',
										'subscription_type_equivalent' => 3,
										'url'                          => 'http://dev.otgs',
										'renewals'                     => array(),
										'upgrades'                     => array(),
										'plugins'                      => array(),
										'downloads'                    => array(),
									)
								),
							),
						),
					),
				),
			),
		);
	}

	/**
	 * @test
	 */
	public function it_refreshes_repositories_data_in_installer_settings_option() {
		$installer            = $this->get_installer_mock();
		$repository_factory   = $this->get_repository_factory_mock();
		$subscription_factory = $this->get_subscription_factory_mock();

		$wpml_repo = $this->getMockBuilder( 'OTGS_Installer_Repository' )
		                  ->disableOriginalConstructor()
		                  ->getMock();

		$toolset_repo = $this->getMockBuilder( 'OTGS_Installer_Repository' )
		                     ->disableOriginalConstructor()
		                     ->getMock();

		$wpml_repo->method( 'get_id' )->willReturn( 'wpml' );
		$toolset_repo->method( 'get_id' )->willReturn( 'toolset' );

		$wpml_subscription = $this->getMockBuilder( 'OTGS_Installer_Subscription' )
		                          ->disableOriginalConstructor()
		                          ->getMock();

		$toolset_subscription = $this->getMockBuilder( 'OTGS_Installer_Subscription' )
		                             ->disableOriginalConstructor()
		                             ->getMock();

		$wpml_package = $this->getMockBuilder( 'OTGS_Installer_Package' )
		                     ->disableOriginalConstructor()
		                     ->getMock();

		$toolset_package = $this->getMockBuilder( 'OTGS_Installer_Package' )
		                        ->disableOriginalConstructor()
		                        ->getMock();

		$this->mock_create_repository_method(
			$repository_factory,
			$wpml_subscription,
			$wpml_package,
			$toolset_subscription,
			$toolset_package,
			$wpml_repo,
			$toolset_repo
		);

		$wpml_package_product = $this->getMockBuilder( 'OTGS_Installer_Package_Product' )
		                             ->disableOriginalConstructor()
		                             ->getMock();

		$toolset_package_product = $this->getMockBuilder( 'OTGS_Installer_Package_Product' )
		                                ->disableOriginalConstructor()
		                                ->getMock();

		$this->mock_create_package_method(
			$repository_factory,
			$wpml_package_product,
			$toolset_package_product,
			$wpml_package,
			$toolset_package
		);

		$this->mock_create_product_method( $repository_factory, $wpml_package_product, $toolset_package_product );

		$wpml_user_subscription = array(
			'data' => array(),
		);

		$toolset_user_subscription = array(
			'data' => array(),
		);

		$subscription_factory->method( 'create' )
		                     ->withConsecutive(
			                     array( $wpml_user_subscription ),
			                     array( $toolset_user_subscription )
		                     )
		                     ->willReturn( $wpml_subscription, $toolset_subscription );

		$settings = $this->get_setting_repositories( $wpml_user_subscription, $toolset_user_subscription );

		$installer_repositories = array(
			'wpml'    => array(
				'api-url'  => self::WPML_API,
			),
			'toolset' => array(
				'api-url'  => self::TOOLSET_API,
			),
		);

		$installer->method( 'get_settings' )->willReturn( $settings );
		$installer->method( 'get_repositories' )->willReturn( $installer_repositories );

		$installer->expects( $this->once() )
		          ->method( 'refresh_repositories_data' );

		$subject = new OTGS_Installer_Repositories( $installer, $repository_factory, $subscription_factory );
		$subject->refresh();
	}

	/**
	 * @test
	 */
	public function it_saves_repository_subscription_on_installer_settings_option() {
		$installer            = $this->get_installer_mock();
		$repository_factory   = $this->get_repository_factory_mock();
		$subscription_factory = $this->get_subscription_factory_mock();

		$wpml_repo = $this->getMockBuilder( 'OTGS_Installer_Repository' )
		                  ->disableOriginalConstructor()
		                  ->getMock();

		$toolset_repo = $this->getMockBuilder( 'OTGS_Installer_Repository' )
		                     ->disableOriginalConstructor()
		                     ->getMock();

		$wpml_repo->method( 'get_id' )->willReturn( 'wpml' );
		$toolset_repo->method( 'get_id' )->willReturn( 'toolset' );

		$wpml_subscription = $this->getMockBuilder( 'OTGS_Installer_Subscription' )
		                          ->disableOriginalConstructor()
		                          ->getMock();

		$toolset_subscription = $this->getMockBuilder( 'OTGS_Installer_Subscription' )
		                             ->disableOriginalConstructor()
		                             ->getMock();

		$wpml_package = $this->getMockBuilder( 'OTGS_Installer_Package' )
		                     ->disableOriginalConstructor()
		                     ->getMock();

		$toolset_package = $this->getMockBuilder( 'OTGS_Installer_Package' )
		                        ->disableOriginalConstructor()
		                        ->getMock();

		$this->mock_create_repository_method(
			$repository_factory,
			$wpml_subscription,
			$wpml_package,
			$toolset_subscription,
			$toolset_package,
			$wpml_repo,
			$toolset_repo
		);

		$wpml_package_product = $this->getMockBuilder( 'OTGS_Installer_Package_Product' )
		                             ->disableOriginalConstructor()
		                             ->getMock();

		$toolset_package_product = $this->getMockBuilder( 'OTGS_Installer_Package_Product' )
		                                ->disableOriginalConstructor()
		                                ->getMock();

		$this->mock_create_package_method(
			$repository_factory,
			$wpml_package_product,
			$toolset_package_product,
			$wpml_package,
			$toolset_package
		);

		$this->mock_create_product_method( $repository_factory, $wpml_package_product, $toolset_package_product );

		$wpml_user_subscription = array(
			'data' => array(),
		);

		$toolset_user_subscription = array(
			'data' => array(),
		);

		$subscription_factory->method( 'create' )
		                     ->withConsecutive(
			                     array( $wpml_user_subscription ),
			                     array( $toolset_user_subscription )
		                     )
		                     ->willReturn( $wpml_subscription, $toolset_subscription );

		$settings = $this->get_setting_repositories( $wpml_user_subscription, $toolset_user_subscription );

		$installer_repositories = array(
			'wpml'    => array(
				'api-url'  => self::WPML_API,
			),
			'toolset' => array(
				'api-url'  => self::TOOLSET_API,
			),
		);

		$installer->method( 'get_settings' )->willReturn( $settings );
		$installer->method( 'get_repositories' )->willReturn( $installer_repositories );

		$site_key      = 'sitekey';
		$data          = new stdClass();
		$registered_by = 'adriano';
		$site_url      = 'wpml.dev';

		$new_wpml_subscription = $this->getMockBuilder( 'OTGS_Installer_Subscription' )
		                              ->setMethods( array(
			                              'get_site_key',
			                              'get_data',
			                              'get_registered_by',
			                              'get_site_url'
		                              ) )
		                              ->disableOriginalConstructor()
		                              ->getMock();

		$new_wpml_subscription->method( 'get_site_key' )->willReturn( $site_key );
		$new_wpml_subscription->method( 'get_data' )->willReturn( $data );
		$new_wpml_subscription->method( 'get_registered_by' )->willReturn( $registered_by );
		$new_wpml_subscription->method( 'get_site_url' )->willReturn( $site_url );

		$repository_containing_subscription_to_save = $this->getMockBuilder( 'OTGS_Installer_Repository' )
		                                                   ->setMethods( array( 'get_subscription', 'get_id' ) )
		                                                   ->disableOriginalConstructor()
		                                                   ->getMock();

		$repository_containing_subscription_to_save->method( 'get_subscription' )->willReturn( $new_wpml_subscription );
		$repository_containing_subscription_to_save->method( 'get_id' )->willReturn( 'wpml' );

		$installer->expects( $this->once() )->method( 'save_settings' );

		$subject = new OTGS_Installer_Repositories( $installer, $repository_factory, $subscription_factory );
		$subject->save_subscription( $repository_containing_subscription_to_save );

		$expected = array(
			'key'           => $site_key,
			'data'          => $data,
			'registered_by' => $registered_by,
			'site_url'      => $site_url,
		);

		$this->assertEquals( $expected, $installer->settings['repositories']['wpml']['subscription'] );
	}

	/**
	 * @test
	 */
	public function it_removes_subscription_from_repository_on_installer_settings_if_repository_has_no_subscription() {
		$installer            = $this->get_installer_mock();
		$repository_factory   = $this->get_repository_factory_mock();
		$subscription_factory = $this->get_subscription_factory_mock();

		$wpml_repo = $this->getMockBuilder( 'OTGS_Installer_Repository' )
		                  ->disableOriginalConstructor()
		                  ->getMock();

		$toolset_repo = $this->getMockBuilder( 'OTGS_Installer_Repository' )
		                     ->disableOriginalConstructor()
		                     ->getMock();

		$wpml_repo->method( 'get_id' )->willReturn( 'wpml' );
		$toolset_repo->method( 'get_id' )->willReturn( 'toolset' );

		$wpml_subscription = $this->getMockBuilder( 'OTGS_Installer_Subscription' )
		                          ->disableOriginalConstructor()
		                          ->getMock();

		$toolset_subscription = $this->getMockBuilder( 'OTGS_Installer_Subscription' )
		                             ->disableOriginalConstructor()
		                             ->getMock();

		$wpml_package = $this->getMockBuilder( 'OTGS_Installer_Package' )
		                     ->disableOriginalConstructor()
		                     ->getMock();

		$toolset_package = $this->getMockBuilder( 'OTGS_Installer_Package' )
		                        ->disableOriginalConstructor()
		                        ->getMock();

		$this->mock_create_repository_method(
			$repository_factory,
			$wpml_subscription,
			$wpml_package,
			$toolset_subscription,
			$toolset_package,
			$wpml_repo,
			$toolset_repo
		);

		$wpml_package_product = $this->getMockBuilder( 'OTGS_Installer_Package_Product' )
		                             ->disableOriginalConstructor()
		                             ->getMock();

		$toolset_package_product = $this->getMockBuilder( 'OTGS_Installer_Package_Product' )
		                                ->disableOriginalConstructor()
		                                ->getMock();

		$this->mock_create_package_method(
			$repository_factory,
			$wpml_package_product,
			$toolset_package_product,
			$wpml_package,
			$toolset_package
		);

		$this->mock_create_product_method( $repository_factory, $wpml_package_product, $toolset_package_product );

		$wpml_user_subscription = array(
			'data' => array(),
		);

		$toolset_user_subscription = array(
			'data' => array(),
		);

		$subscription_factory->method( 'create' )
		                     ->withConsecutive(
			                     array( $wpml_user_subscription ),
			                     array( $toolset_user_subscription )
		                     )
		                     ->willReturn( $wpml_subscription, $toolset_subscription );

		$settings = $this->get_setting_repositories( $wpml_user_subscription, $toolset_user_subscription );

		$installer_repositories = array(
			'wpml'    => array(
				'api-url'  => self::WPML_API,
			),
			'toolset' => array(
				'api-url'  => self::TOOLSET_API,
			),
		);

		$installer->method( 'get_settings' )->willReturn( $settings );
		$installer->method( 'get_repositories' )->willReturn( $installer_repositories );

		$repository_containing_subscription_to_save = $this->getMockBuilder( 'OTGS_Installer_Repository' )
		                                                   ->setMethods( array( 'get_subscription', 'get_id' ) )
		                                                   ->disableOriginalConstructor()
		                                                   ->getMock();

		$repository_containing_subscription_to_save->method( 'get_subscription' )->willReturn( null );
		$repository_containing_subscription_to_save->method( 'get_id' )->willReturn( 'wpml' );

		$installer->expects( $this->once() )->method( 'save_settings' );

		$installer->settings['repositories']['wpml'] = array();

		$subject = new OTGS_Installer_Repositories( $installer, $repository_factory, $subscription_factory );
		$subject->save_subscription( $repository_containing_subscription_to_save );

		$this->assertArrayNotHasKey( 'subscription', $installer->settings['repositories']['wpml'] );
	}

	private function get_installer_mock() {
		return $this->getMockBuilder( 'WP_Installer' )
		            ->setMethods( array(
			            'get_settings',
			            'refresh_repositories_data',
			            'save_settings',
			            'get_repositories'
		            ) )
		            ->disableOriginalConstructor()
		            ->getMock();
	}

	private function get_repository_factory_mock() {
		return $this->getMockBuilder( 'OTGS_Installer_Repository_Factory' )
		            ->setMethods( array( 'create_repository', 'create_package', 'create_product' ) )
		            ->disableOriginalConstructor()
		            ->getMock();
	}

	private function get_subscription_factory_mock() {
		return $this->getMockBuilder( 'OTGS_Installer_Subscription_Factory' )
		            ->setMethods( array( 'create' ) )
		            ->disableOriginalConstructor()
		            ->getMock();
	}

	private function mock_create_repository_method(  $repository_factory,  $wpml_subscription,  $wpml_package,  $toolset_subscription,  $toolset_package,  $wpml_repo,  $toolset_repo ) {
		$repository_factory->method( 'create_repository' )
		                   ->withConsecutive(
			                   array(
				                   array(
					                   'id'           => 'wpml',
					                   'subscription' => $wpml_subscription,
					                   'packages'     => array( $wpml_package ),
					                   'product_name' => 'WPML',
					                   'api_url'      => self::WPML_API,
				                   )
			                   ),
			                   array(
				                   array(
					                   'id'           => 'toolset',
					                   'subscription' => $toolset_subscription,
					                   'packages'     => array( $toolset_package ),
					                   'product_name' => 'Toolset',
					                   'api_url'      => self::TOOLSET_API,
				                   )
			                   )
		                   )
		                   ->willReturn( $wpml_repo, $toolset_repo );
	}

	private function mock_create_package_method( $repository_factory, $wpml_package_product, $toolset_package_product,  $wpml_package,  $toolset_package ) {
		$repository_factory->method( 'create_package' )
		                   ->withConsecutive(
			                   array(
				                   array(
					                   'key'         => 1,
					                   'id'          => 123,
					                   'name'        => 'WPML Package',
					                   'description' => 'WPML Package Description',
					                   'image_url'   => 'http://wpml.dev',
					                   'order'       => 1,
					                   'parent'      => '',
					                   'products'    => array( $wpml_package_product ),
				                   )
			                   ),
			                   array(
				                   array(
					                   'key'         => 1,
					                   'id'          => 123,
					                   'name'        => 'Toolset Package',
					                   'description' => 'Toolset Package Description',
					                   'image_url'   => 'http://wpml.dev',
					                   'order'       => 1,
					                   'parent'      => '',
					                   'products'    => array( $toolset_package_product ),
				                   )
			                   )
		                   )
		                   ->willReturn( $wpml_package, $toolset_package );
	}

	private function mock_create_product_method( $repository_factory, $wpml_package_product, $toolset_package_product ) {
		$repository_factory->method( 'create_product' )
		                   ->withConsecutive(
			                   array(
				                   array(
					                   'id'                           => 0,
					                   'name'                         => 'WPML Product',
					                   'description'                  => 'WPML Product description',
					                   'price'                        => 100,
					                   'subscription_type'            => 2,
					                   'subscription_type_text'       => 'Type Text',
					                   'subscription_info'            => 'Info',
					                   'subscription_type_equivalent' => 3,
					                   'url'                          => 'http://dev.otgs',
					                   'renewals'                     => array(),
					                   'upgrades'                     => array(),
					                   'plugins'                      => array(),
					                   'downloads'                    => array(),
				                   )
			                   ),
			                   array(
				                   array(
					                   'id'                           => 0,
					                   'name'                         => 'Toolset Product',
					                   'description'                  => 'Toolset Product description',
					                   'price'                        => 100,
					                   'subscription_type'            => 2,
					                   'subscription_type_text'       => 'Type Text',
					                   'subscription_info'            => 'Info',
					                   'subscription_type_equivalent' => 3,
					                   'url'                          => 'http://dev.otgs',
					                   'renewals'                     => array(),
					                   'upgrades'                     => array(),
					                   'plugins'                      => array(),
					                   'downloads'                    => array(),
				                   )
			                   )
		                   )
		                   ->willReturn( $wpml_package_product, $toolset_package_product );
	}
}
