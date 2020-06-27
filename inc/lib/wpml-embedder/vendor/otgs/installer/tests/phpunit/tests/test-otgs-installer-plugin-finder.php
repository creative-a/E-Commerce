<?php

/**
 * Class Test_OTGS_Installer_Plugin_Finder
 *
 * @group installer-465
 * @group installer-487
 */
class Test_OTGS_Installer_Plugin_Finder extends OTGS_TestCase {

	/**
	 * @test
	 */
	public function it_gets_plugin() {
		$plugin_factory = $this->get_plugin_factory_mock();

		$plugin     = $this->get_repositories();
		$plugin     = $plugin['wpml']['data']['downloads']['plugins'][0];
		$plugin_obj = $this->getMockBuilder( 'OTGS_Installer_Plugin' )
		                   ->disableOriginalConstructor()
		                   ->getMock();

		$wp_plugins = array(
			'plugin/plugin.php' => array(
				'Version' => '1.0',
			),
		);

		WP_Mock::wpFunction( 'get_plugins', array(
			'return' => $wp_plugins,
		) );

		$plugin_factory->expects( $this->once() )
		               ->method( 'create' )
		               ->with( array(
			               'name'              => $plugin['name'],
			               'slug'              => $plugin['slug'],
			               'description'       => $plugin['description'],
			               'changelog'         => $plugin['changelog'],
			               'version'           => $plugin['version'],
			               'installed_version' => '1.0',
			               'date'              => $plugin['date'],
			               'url'               => $plugin['url'],
			               'free_on_wporg'     => $plugin['free-on-wporg'],
			               'fallback_on_wporg' => $plugin['fallback-free-on-wporg'],
			               'basename'          => $plugin['basename'],
			               'external_repo'     => $plugin['external-repo'],
			               'is_lite'           => $plugin['is-lite'],
			               'repo'              => $plugin['repo'],
			               'id'                => 'plugin/plugin.php',
			               'channel'           => 'production',
			               'tested'           => '1.0.0',
		               ) )
		               ->willReturn( $plugin_obj );

		$plugin_obj->method( 'get_slug' )
		           ->willReturn( 'plugin' );

		$plugin_obj->method( 'get_repo' )
		           ->willReturn( 'wpml' );

		$subject = new OTGS_Installer_Plugin_Finder( $plugin_factory, $this->get_repositories() );
		$this->assertEquals( $plugin_obj, $subject->get_plugin( 'plugin', 'wpml' ) );
	}

	/**
	 * @test
	 */
	public function it_gets_plugin_without_tested_attribute() {
		$plugin_factory = $this->get_plugin_factory_mock();

		$plugin     = $this->get_repositories_without_tested_attribute();
		$plugin     = $plugin['wpml']['data']['downloads']['plugins'][0];
		$plugin_obj = $this->getMockBuilder( 'OTGS_Installer_Plugin' )
		                   ->disableOriginalConstructor()
		                   ->getMock();

		$wp_plugins = array(
			'plugin/plugin.php' => array(
				'Version' => '1.0',
			),
		);

		WP_Mock::wpFunction( 'get_plugins', array(
			'return' => $wp_plugins,
		) );

		$plugin_factory->expects( $this->once() )
		               ->method( 'create' )
		               ->with( array(
			               'name'              => $plugin['name'],
			               'slug'              => $plugin['slug'],
			               'description'       => $plugin['description'],
			               'changelog'         => $plugin['changelog'],
			               'version'           => $plugin['version'],
			               'installed_version' => '1.0',
			               'date'              => $plugin['date'],
			               'url'               => $plugin['url'],
			               'free_on_wporg'     => $plugin['free-on-wporg'],
			               'fallback_on_wporg' => $plugin['fallback-free-on-wporg'],
			               'basename'          => $plugin['basename'],
			               'external_repo'     => $plugin['external-repo'],
			               'is_lite'           => $plugin['is-lite'],
			               'repo'              => $plugin['repo'],
			               'id'                => 'plugin/plugin.php',
			               'channel'           => 'production',
			               'tested'           => null,
		               ) )
		               ->willReturn( $plugin_obj );

		$plugin_obj->method( 'get_slug' )
		           ->willReturn( 'plugin' );

		$plugin_obj->method( 'get_repo' )
		           ->willReturn( 'wpml' );

		$subject = new OTGS_Installer_Plugin_Finder( $plugin_factory, $this->get_repositories_without_tested_attribute() );
		$this->assertEquals( $plugin_obj, $subject->get_plugin( 'plugin', 'wpml' ) );
	}

	/**
	 * @test
	 */
	public function it_gets_plugin_without_passing_repository() {
		$plugin_factory = $this->get_plugin_factory_mock();

		$plugin     = $this->get_repositories();
		$plugin     = $plugin['wpml']['data']['downloads']['plugins'][0];
		$plugin_obj = $this->getMockBuilder( 'OTGS_Installer_Plugin' )
		                   ->disableOriginalConstructor()
		                   ->getMock();

		$wp_plugins = array(
			'plugin/plugin.php' => array(
				'Version' => '1.0',
			),
		);

		WP_Mock::wpFunction( 'get_plugins', array(
			'return' => $wp_plugins,
		) );

		$plugin_factory->expects( $this->once() )
		               ->method( 'create' )
		               ->with( array(
			               'name'              => $plugin['name'],
			               'slug'              => $plugin['slug'],
			               'description'       => $plugin['description'],
			               'changelog'         => $plugin['changelog'],
			               'version'           => $plugin['version'],
			               'installed_version' => '1.0',
			               'date'              => $plugin['date'],
			               'url'               => $plugin['url'],
			               'free_on_wporg'     => $plugin['free-on-wporg'],
			               'fallback_on_wporg' => $plugin['fallback-free-on-wporg'],
			               'basename'          => $plugin['basename'],
			               'external_repo'     => $plugin['external-repo'],
			               'is_lite'           => $plugin['is-lite'],
			               'repo'              => 'wpml',
			               'id'                => 'plugin/plugin.php',
			               'channel'           => 'production',
			               'tested'           => '1.0.0',
		               ) )
		               ->willReturn( $plugin_obj );

		$plugin_obj->method( 'get_slug' )
		           ->willReturn( 'plugin' );

		$subject = new OTGS_Installer_Plugin_Finder( $plugin_factory, $this->get_repositories() );
		$this->assertEquals( $plugin_obj, $subject->get_plugin( 'plugin' ) );
	}

	/**
	 * @test
	 */
	public function it_returns_null_when_no_plugin_is_found() {
		$plugin_factory = $this->get_plugin_factory_mock();

		$plugin     = $this->get_repositories();
		$plugin     = $plugin['wpml']['data']['downloads']['plugins'][0];
		$plugin_obj = $this->getMockBuilder( 'OTGS_Installer_Plugin' )
		                   ->disableOriginalConstructor()
		                   ->getMock();

		$wp_plugins = array(
			'plugin-new/plugin.php' => array(
				'Version' => '1.0',
			),
		);

		WP_Mock::wpFunction( 'get_plugins', array(
			'return' => $wp_plugins,
		) );

		WP_Mock::wpFunction( 'wp_list_filter', array(
			'return' => array( 'plugin/plugin.php' => array() ),
			'args'   => array( $wp_plugins, array( 'Name' => 'Plugin' ) ),
		) );

		$plugin_factory->expects( $this->once() )
		               ->method( 'create' )
		               ->with( array(
			               'name'              => $plugin['name'],
			               'slug'              => $plugin['slug'],
			               'description'       => $plugin['description'],
			               'changelog'         => $plugin['changelog'],
			               'version'           => $plugin['version'],
			               'installed_version' => null,
			               'date'              => $plugin['date'],
			               'url'               => $plugin['url'],
			               'free_on_wporg'     => $plugin['free-on-wporg'],
			               'fallback_on_wporg' => $plugin['fallback-free-on-wporg'],
			               'basename'          => $plugin['basename'],
			               'external_repo'     => $plugin['external-repo'],
			               'is_lite'           => $plugin['is-lite'],
			               'repo'              => 'wpml',
			               'id'                => 'plugin/plugin.php',
			               'channel'           => 'production',
			               'tested'           => '1.0.0',
		               ) )
		               ->willReturn( $plugin_obj );

		$plugin_obj->method( 'get_slug' )
		           ->willReturn( 'plugin-new-one' );

		$plugin_obj->method( 'get_repo' )
		           ->willReturn( 'wpml' );

		$subject = new OTGS_Installer_Plugin_Finder( $plugin_factory, $this->get_repositories() );
		$this->assertEquals( null, $subject->get_plugin( 'plugin', 'wpml' ) );
	}

	/**
	 * @test
	 */
	public function it_gets_plugin_even_when_fallback_on_wporg_free_on_wporg_fields_are_not_set() {
		$plugin_factory = $this->get_plugin_factory_mock();

		$plugin     = $this->get_old_repositories_missing_some_default_fields();
		$plugin     = $plugin['wpml']['data']['downloads']['plugins'][0];
		$plugin_obj = $this->getMockBuilder( 'OTGS_Installer_Plugin' )
		                   ->disableOriginalConstructor()
		                   ->getMock();
		$wp_plugins = array(
			'plugin/plugin.php' => array(
				'Version' => '1.0',
			),
		);

		WP_Mock::wpFunction( 'get_plugins', array(
			'return' => $wp_plugins,
		) );

		$plugin_factory->expects( $this->once() )
		               ->method( 'create' )
		               ->with( array(
			               'name'              => $plugin['name'],
			               'slug'              => $plugin['slug'],
			               'description'       => $plugin['description'],
			               'changelog'         => $plugin['changelog'],
			               'version'           => $plugin['version'],
			               'installed_version' => '1.0',
			               'date'              => $plugin['date'],
			               'url'               => $plugin['url'],
			               'free_on_wporg'     => '',
			               'fallback_on_wporg' => '',
			               'basename'          => $plugin['basename'],
			               'external_repo'     => $plugin['external-repo'],
			               'is_lite'           => $plugin['is-lite'],
			               'repo'              => $plugin['repo'],
			               'id'                => 'plugin/plugin.php',
			               'channel'           => 'production',
			               'tested'           => '1.0.0',
		               ) )
		               ->willReturn( $plugin_obj );

		$plugin_obj->method( 'get_slug' )
		           ->willReturn( 'plugin' );

		$plugin_obj->method( 'get_repo' )
		           ->willReturn( 'wpml' );

		$subject = new OTGS_Installer_Plugin_Finder( $plugin_factory, $this->get_old_repositories_missing_some_default_fields() );
		$this->assertEquals( $plugin_obj, $subject->get_plugin( 'plugin', 'wpml' ) );
	}

	/**
	 * @test
	 */
	public function it_gets_plugin_by_name() {
		$plugin_factory = $this->get_plugin_factory_mock();

		$plugin     = $this->get_repositories();
		$plugin     = $plugin['wpml']['data']['downloads']['plugins'][0];
		$plugin_obj = $this->getMockBuilder( 'OTGS_Installer_Plugin' )
		                   ->disableOriginalConstructor()
		                   ->getMock();

		$wp_plugins = array(
			'plugin/plugin.php' => array(
				'Version' => '1.0',
			),
		);

		WP_Mock::wpFunction( 'get_plugins', array(
			'return' => $wp_plugins,
		) );

		$plugin_factory->expects( $this->once() )
		               ->method( 'create' )
		               ->with( array(
			               'name'              => $plugin['name'],
			               'slug'              => $plugin['slug'],
			               'description'       => $plugin['description'],
			               'changelog'         => $plugin['changelog'],
			               'version'           => $plugin['version'],
			               'installed_version' => '1.0',
			               'date'              => $plugin['date'],
			               'url'               => $plugin['url'],
			               'free_on_wporg'     => $plugin['free-on-wporg'],
			               'fallback_on_wporg' => $plugin['fallback-free-on-wporg'],
			               'basename'          => $plugin['basename'],
			               'external_repo'     => $plugin['external-repo'],
			               'is_lite'           => $plugin['is-lite'],
			               'repo'              => $plugin['repo'],
			               'id'                => 'plugin/plugin.php',
			               'channel'           => 'production',
			               'tested'           => '1.0.0',
		               ) )
		               ->willReturn( $plugin_obj );

		$plugin_obj->method( 'get_repo' )
		           ->willReturn( 'wpml' );

		$plugin_obj->method( 'get_name' )
		           ->willReturn( 'Toolset Views Lite' );

		$subject = new OTGS_Installer_Plugin_Finder( $plugin_factory, $this->get_repositories() );
		$this->assertEquals( $plugin_obj, $subject->get_plugin_by_name( 'Toolset Views Lite' ) );
	}

	/**
	 * @test
	 */
	public function it_returns_null_when_trying_to_get_plugin_by_name_and_it_is_not_found() {
		$plugin_factory = $this->get_plugin_factory_mock();

		$plugin     = $this->get_repositories();
		$plugin     = $plugin['wpml']['data']['downloads']['plugins'][0];
		$plugin_obj = $this->getMockBuilder( 'OTGS_Installer_Plugin' )
		                   ->disableOriginalConstructor()
		                   ->getMock();

		$wp_plugins = array(
			'plugin/plugin.php' => array(
				'Version' => '1.0',
				'Name'    => 'Some other plugin'
			),
		);

		WP_Mock::wpFunction( 'get_plugins', array(
			'return' => $wp_plugins,
		) );

		$plugin_factory->expects( $this->once() )
		               ->method( 'create' )
		               ->with( array(
			               'name'              => $plugin['name'],
			               'slug'              => $plugin['slug'],
			               'description'       => $plugin['description'],
			               'changelog'         => $plugin['changelog'],
			               'version'           => $plugin['version'],
			               'installed_version' => '1.0',
			               'date'              => $plugin['date'],
			               'url'               => $plugin['url'],
			               'free_on_wporg'     => $plugin['free-on-wporg'],
			               'fallback_on_wporg' => $plugin['fallback-free-on-wporg'],
			               'basename'          => $plugin['basename'],
			               'external_repo'     => $plugin['external-repo'],
			               'is_lite'           => $plugin['is-lite'],
			               'repo'              => 'wpml',
			               'id'                => 'plugin/plugin.php',
			               'channel'           => 'production',
			               'tested'           => '1.0.0',
		               ) )
		               ->willReturn( $plugin_obj );

		$plugin_obj->method( 'get_repo' )
		           ->willReturn( 'wpml' );

		$plugin_obj->method( 'get_name' )
		           ->willReturn( 'Plugin New' );

		$subject = new OTGS_Installer_Plugin_Finder( $plugin_factory, $this->get_repositories() );
		$this->assertEquals( null, $subject->get_plugin_by_name( 'Toolset Views Lite' ) );
	}

	/**
	 * @test
	 */
	public function it_gets_plugin_id_by_name_as_fallback_when_plugin_id_didn_not_match() {
		$plugin_factory = $this->get_plugin_factory_mock();

		$plugin     = $this->get_repositories();
		$plugin     = $plugin['wpml']['data']['downloads']['plugins'][0];
		$plugin_obj = $this->getMockBuilder( 'OTGS_Installer_Plugin' )
		                   ->disableOriginalConstructor()
		                   ->getMock();

		$wp_plugins = array(
			'plugin-new/plugin.php' => array(
				'Version' => '1.0',
			),
		);

		WP_Mock::wpFunction( 'get_plugins', array(
			'return' => $wp_plugins,
		) );

		WP_Mock::wpFunction( 'wp_list_filter', array(
			'return' => array( 'plugin-new/plugin.php' => array() ),
			'args'   => array( $wp_plugins, array( 'Name' => 'Plugin' ) ),
		) );

		$plugin_factory->expects( $this->once() )
		               ->method( 'create' )
		               ->with( array(
			               'name'              => $plugin['name'],
			               'slug'              => $plugin['slug'],
			               'description'       => $plugin['description'],
			               'changelog'         => $plugin['changelog'],
			               'version'           => $plugin['version'],
			               'installed_version' => '1.0',
			               'date'              => $plugin['date'],
			               'url'               => $plugin['url'],
			               'free_on_wporg'     => $plugin['free-on-wporg'],
			               'fallback_on_wporg' => $plugin['fallback-free-on-wporg'],
			               'basename'          => $plugin['basename'],
			               'external_repo'     => $plugin['external-repo'],
			               'is_lite'           => $plugin['is-lite'],
			               'repo'              => 'wpml',
			               'id'                => 'plugin-new/plugin.php',
			               'channel'           => 'production',
			               'tested'           => '1.0.0',
		               ) )
		               ->willReturn( $plugin_obj );

		$plugin_obj->method( 'get_name' )
		           ->willReturn( 'Plugin' );

		$plugin_obj->method( 'get_repo' )
		           ->willReturn( 'wpml' );

		$plugin_obj->method( 'get_name' )
		           ->willReturn( 'Plugin' );

		$subject = new OTGS_Installer_Plugin_Finder( $plugin_factory, $this->get_repositories() );
		$this->assertEquals( $plugin_obj, $subject->get_plugin_by_name( 'Plugin' ) );
	}

	/**
	 * @test
	 * @group installer-531
	 */
	public function it_finds_plugin_even_when_its_name_contains_a_tag() {
		$plugin_factory = $this->get_plugin_factory_mock();

		$plugin     = $this->get_repositories();
		$plugin     = $plugin['wpml']['data']['downloads']['plugins'][0];
		$plugin_obj = $this->getMockBuilder( 'OTGS_Installer_Plugin' )
		                   ->disableOriginalConstructor()
		                   ->getMock();

		$plugin_factory->expects( $this->once() )
		               ->method( 'create' )
		               ->with( array(
			               'name'              => $plugin['name'],
			               'slug'              => $plugin['slug'],
			               'description'       => $plugin['description'],
			               'changelog'         => $plugin['changelog'],
			               'version'           => $plugin['version'],
			               'date'              => $plugin['date'],
			               'url'               => $plugin['url'],
			               'free_on_wporg'     => $plugin['free-on-wporg'],
			               'fallback_on_wporg' => $plugin['fallback-free-on-wporg'],
			               'basename'          => $plugin['basename'],
			               'external_repo'     => $plugin['external-repo'],
			               'is_lite'           => $plugin['is-lite'],
			               'repo'              => $plugin['repo'],
			               'installed_version' => '1.0',
			               'id'                => 'plugin-new/plugin.php',
			               'channel'           => 'production',
			               'tested'           => '1.0.0',
		               ) )
		               ->willReturn( $plugin_obj );

		$plugin_obj->method( 'get_name' )
		           ->willReturn( 'Toolset Views <span class="highlight">Lite</span>' );

		$wp_plugins = array(
			'plugin-new/plugin.php' => array(
				'Version' => '1.0',
			),
		);

		WP_Mock::wpFunction( 'get_plugins', array(
			'return' => $wp_plugins,
		) );

		WP_Mock::wpFunction( 'wp_list_filter', array(
			'return' => array( 'plugin-new/plugin.php' => array() ),
			'args'   => array( $wp_plugins, array( 'Name' => 'Plugin' ) ),
		) );

		$subject = new OTGS_Installer_Plugin_Finder( $plugin_factory, $this->get_repositories() );
		$this->assertEquals( $plugin_obj, $subject->get_plugin_by_name( 'Toolset Views Lite' ) );
	}


	private function get_repositories() {
		return array(
			'wpml' => array(
				'data' => array(
					'downloads' => array(
						'plugins' => array(
							array(
								'name'                   => 'Plugin',
								'slug'                   => 'plugin',
								'description'            => '',
								'changelog'              => '',
								'version'                => '',
								'date'                   => '',
								'url'                    => '',
								'free-on-wporg'          => '',
								'fallback-free-on-wporg' => '',
								'basename'               => '',
								'external-repo'          => '',
								'is-lite'                => '',
								'repo'                   => 'wpml',
								'channel'                => 'production',
								'tested'                 => '1.0.0',
							),
						),
					),
				),
			),
		);
	}

	private function get_repositories_without_tested_attribute() {
		return array(
			'wpml' => array(
				'data' => array(
					'downloads' => array(
						'plugins' => array(
							array(
								'name'                   => 'Plugin',
								'slug'                   => 'plugin',
								'description'            => '',
								'changelog'              => '',
								'version'                => '',
								'date'                   => '',
								'url'                    => '',
								'free-on-wporg'          => '',
								'fallback-free-on-wporg' => '',
								'basename'               => '',
								'external-repo'          => '',
								'is-lite'                => '',
								'repo'                   => 'wpml',
								'channel'                => 'production',
							),
						),
					),
				),
			),
		);
	}

	private function get_old_repositories_missing_some_default_fields() {
		return array(
			'wpml' => array(
				'data' => array(
					'downloads' => array(
						'plugins' => array(
							array(
								'name'          => 'Plugin',
								'slug'          => 'plugin',
								'description'   => '',
								'changelog'     => '',
								'version'       => '',
								'date'          => '',
								'url'           => '',
								'basename'      => '',
								'external-repo' => '',
								'is-lite'       => '',
								'repo'          => 'wpml',
								'channel'       => 'production',
								'tested'        => '1.0.0',
							),
						),
					),
				),
			),
		);
	}

	private function get_plugin_factory_mock() {
		return $this->getMockBuilder( 'OTGS_Installer_Plugin_Factory' )
		            ->setMethods( array( 'create' ) )
		            ->disableOriginalConstructor()
		            ->getMock();
	}
}
