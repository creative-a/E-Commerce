<?php

/**
 * Class Test_OTGS_Installer_WP_Components_Storage
 *
 * @group local-components
 * @group installer-370
 */
class Test_OTGS_Installer_WP_Components_Storage extends OTGS_TestCase {

	/**
	 * @test
	 */
	public function it_refreshes_cache() {
		$theme_name = 'MyThemeName';
		$theme_version = '1.0.0';
		$theme_template = 'my-theme-name';

		$current_theme = $this->getMockBuilder( 'WP_Theme' )
			->setMethods( array( 'get', 'get_template' ) )
			->disableOriginalConstructor()
			->getMock();

		$current_theme->method( 'get' )
			->withConsecutive(
				array( 'Name' ),
				array( 'Version' )
			)
			->willReturnOnConsecutiveCalls( $theme_name, $theme_version );

		$current_theme->method( 'get_template' )
		              ->willReturn( $theme_template );

		$plugin_file = 'my-plugin-path/plugin.php';
		$plugin_name = 'MyPlugin';
		$plugin_version = '1.0.0';
		$installed_plugins = array(
			$plugin_file => array(
				'Name' => $plugin_name,
				'Version' => $plugin_version,
			)
		);

		WP_Mock::wpFunction( 'wp_get_theme', array(
			'return' => $current_theme,
		));

		WP_Mock::wpFunction( 'get_plugins', array(
			'return' => $installed_plugins,
		));

		WP_Mock::wpFunction( 'is_plugin_active', array(
			'args' => $plugin_file,
			'return' => true,
		));

		$components = array(
			'theme' => array(
				array(
					'Template' => $theme_template,
					'Name' => $theme_name,
					'Version' => $theme_version,
				)
			),
			'plugin' => array(
				array(
					'File' => $plugin_file,
					'Name' => $plugin_name,
					'Version' => $plugin_version,
				)
			)
		);

		WP_Mock::wpFunction( 'update_option', array(
			'times' => 1,
			'args' => array(
				OTGS_Installer_WP_Components_Storage::COMPONENTS_CACHE_OPTION_KEY,
				$components,
			),
		));

		$subject = new OTGS_Installer_WP_Components_Storage();
		$subject->refresh_cache();
	}

	/**
	 * @test
	 */
	public function it_returns_true_because_cache_is_empty() {
		WP_Mock::wpFunction( 'get_option', array(
			'return' => false,
			'args' => array(
				OTGS_Installer_WP_Components_Storage::COMPONENTS_CACHE_OPTION_KEY,
			),
		));

		$subject = new OTGS_Installer_WP_Components_Storage();
		$this->assertTrue( $subject->is_outdated() );
	}

	/**
	 * @test
	 */
	public function it_returns_true_because_theme_has_different_version() {
		$theme_version = '1.0.1';
		$theme_template = 'my-theme-name';

		$current_theme = $this->getMockBuilder( 'WP_Theme' )
		                      ->setMethods( array( 'get', 'get_template' ) )
		                      ->disableOriginalConstructor()
		                      ->getMock();

		$current_theme->method( 'get' )
			->with( 'Version' )
			->willReturn( $theme_version );

		$current_theme->method( 'get_template' )
			->willReturn( $theme_template );

		$components = array(
			'theme' => array(
				array(
					'Template' => $theme_template,
					'Version' => '1.0.0',
				),
			),
		);

		WP_Mock::wpFunction( 'get_option', array(
			'return' => $components,
			'args' => array(
				OTGS_Installer_WP_Components_Storage::COMPONENTS_CACHE_OPTION_KEY,
			),
		));

		WP_Mock::wpFunction( 'get_option', array(
			'return' => array(),
			'args' => array(
				'active_plugins',
			),
		));

		WP_Mock::wpFunction( 'wp_get_theme', array(
			'return' => $current_theme,
		));

		WP_Mock::wpFunction( 'get_plugins', array());

		$subject = new OTGS_Installer_WP_Components_Storage();
		$this->assertTrue( $subject->is_outdated() );
	}

	/**
	 * @test
	 */
	public function it_returns_outdated_because_theme_is_different() {
		$theme_version = '1.0.0';
		$theme_template = 'my-new-theme-name';

		$current_theme = $this->getMockBuilder( 'WP_Theme' )
		                      ->setMethods( array( 'get', 'get_template' ) )
		                      ->disableOriginalConstructor()
		                      ->getMock();

		$current_theme->method( 'get' )
		              ->with( 'Version' )
		              ->willReturn( $theme_version );

		$current_theme->method( 'get_template' )
		              ->willReturn( $theme_template );

		$components = array(
			'theme' => array(
				array(
					'Template' => 'my-old-theme',
					'Version' => '1.0.0',
				),
			),
		);

		WP_Mock::wpFunction( 'get_option', array(
			'return' => $components,
			'args' => array(
				OTGS_Installer_WP_Components_Storage::COMPONENTS_CACHE_OPTION_KEY,
			),
		));

		WP_Mock::wpFunction( 'get_option', array(
			'return' => array(),
			'args' => array(
				'active_plugins',
			),
		));

		WP_Mock::wpFunction( 'wp_get_theme', array(
			'return' => $current_theme,
		));

		WP_Mock::wpFunction( 'get_plugins', array());

		$subject = new OTGS_Installer_WP_Components_Storage();
		$this->assertTrue( $subject->is_outdated() );
	}

	/**
	 * @test
	 */
	public function it_returns_true_because_there_are_plugins_with_different_version() {
		$theme_version = '1.0.0';
		$theme_template = 'my-theme-name';

		$current_theme = $this->getMockBuilder( 'WP_Theme' )
		                      ->setMethods( array( 'get', 'get_template' ) )
		                      ->disableOriginalConstructor()
		                      ->getMock();

		$current_theme->method( 'get' )
		              ->with( 'Version' )
		              ->willReturn( $theme_version );

		$current_theme->method( 'get_template' )
		              ->willReturn( $theme_template );

		$plugin_file = 'my-plugin-path/plugin.php';
		$plugin_name = 'MyPlugin';
		$plugin_version = '1.0.0';
		$installed_plugins = array(
			$plugin_file => array(
				'Name' => $plugin_name,
				'Version' => '1.0.1',
			)
		);

		$components = array(
			'theme' => array(
				array(
					'Template' => $theme_template,
					'Name' => $current_theme,
					'Version' => $theme_version,
				)
			),
			'plugin' => array(
				array(
					'File' => $plugin_file,
					'Name' => $plugin_name,
					'Version' => $plugin_version,
				)
			)
		);

		WP_Mock::wpFunction( 'get_option', array(
			'return' => $components,
			'args' => array(
				OTGS_Installer_WP_Components_Storage::COMPONENTS_CACHE_OPTION_KEY,
			),
		));

		WP_Mock::wpFunction( 'get_option', array(
			'return' => array(),
			'args' => array(
				'active_plugins',
			),
		));

		WP_Mock::wpFunction( 'wp_get_theme', array(
			'return' => $current_theme,
		));

		WP_Mock::wpFunction( 'get_plugins', array(
			'return' => $installed_plugins,
		));

		WP_Mock::wpFunction( 'is_plugin_active', array(
			'args' => $plugin_file,
			'return' => true,
		));

		WP_Mock::wpFunction( 'wp_list_pluck', array(
			'return' => array(
				$plugin_file,
			),
			'args' => array( $components['plugin'], 'File' ),
		));

		$subject = new OTGS_Installer_WP_Components_Storage();
		$this->assertTrue( $subject->is_outdated() );
	}

	/**
	 * @test
	 */
	public function it_returns_true_because_there_are_different_plugins_activated() {
		$theme_version = '1.0.0';
		$theme_template = 'my-theme-name';

		$current_theme = $this->getMockBuilder( 'WP_Theme' )
		                      ->setMethods( array( 'get', 'get_template' ) )
		                      ->disableOriginalConstructor()
		                      ->getMock();

		$current_theme->method( 'get' )
		              ->with( 'Version' )
		              ->willReturn( $theme_version );

		$current_theme->method( 'get_template' )
		              ->willReturn( $theme_template );

		$plugin_file = 'my-plugin-path/plugin.php';
		$plugin_name = 'MyPlugin';
		$plugin_version = '1.0.0';
		$installed_plugins = array(
			$plugin_file => array(
				'Name' => $plugin_name,
				'Version' => $plugin_version,
			)
		);

		$components = array(
			'theme' => array(
				array(
					'Template' => $theme_template,
					'Name' => $current_theme,
					'Version' => $theme_version,
				)
			),
			'plugin' => array(
				array(
					'File' => $plugin_file,
					'Name' => $plugin_name,
					'Version' => $plugin_version,
				),
				array(
					'File' => 'my-fresh-new-plugin/plugin.php',
					'Name' => 'MyFreshNewPlugin',
					'Version' => '1.0.0',
				)
			)
		);

		WP_Mock::wpFunction( 'get_option', array(
			'return' => $components,
			'args' => array(
				OTGS_Installer_WP_Components_Storage::COMPONENTS_CACHE_OPTION_KEY,
			),
		));

		WP_Mock::wpFunction( 'get_option', array(
			'return' => array(),
			'args' => array(
				'active_plugins',
			),
		));

		WP_Mock::wpFunction( 'wp_get_theme', array(
			'return' => $current_theme,
		));

		WP_Mock::wpFunction( 'get_plugins', array(
			'return' => $installed_plugins,
		));

		WP_Mock::wpFunction( 'is_plugin_active', array(
			'args' => $plugin_file,
			'return' => true,
		));

		WP_Mock::wpFunction( 'wp_list_pluck', array(
			'return' => array(
				$plugin_file,
				'my-fresh-new-plugin/plugin.php',
			),
			'args' => array( $components['plugin'], 'File' ),
		));

		$subject = new OTGS_Installer_WP_Components_Storage();
		$this->assertTrue( $subject->is_outdated() );
	}

	/**
	 * @test
	 */
	public function it_returns_false_because_local_components_did_not_change() {
		$theme_version = '1.0.0';
		$theme_template = 'my-theme-name';

		$current_theme = $this->getMockBuilder( 'WP_Theme' )
		                      ->setMethods( array( 'get', 'get_template' ) )
		                      ->disableOriginalConstructor()
		                      ->getMock();

		$current_theme->method( 'get' )
		              ->with( 'Version' )
		              ->willReturn( $theme_version );

		$current_theme->method( 'get_template' )
		              ->willReturn( $theme_template );

		$plugin_file = 'my-plugin-path/plugin.php';
		$plugin_name = 'MyPlugin';
		$plugin_version = '1.0.0';
		$installed_plugins = array(
			$plugin_file => array(
				'Name' => $plugin_name,
				'Version' => $plugin_version,
			)
		);

		$components = array(
			'theme' => array(
				array(
					'Template' => $theme_template,
					'Name' => $current_theme,
					'Version' => $theme_version,
				)
			),
			'plugin' => array(
				array(
					'File' => $plugin_file,
					'Name' => $plugin_name,
					'Version' => $plugin_version,
				),
			)
		);

		WP_Mock::wpFunction( 'get_option', array(
			'return' => $components,
			'args' => array(
				OTGS_Installer_WP_Components_Storage::COMPONENTS_CACHE_OPTION_KEY,
			),
		));

		WP_Mock::wpFunction( 'get_option', array(
			'return' => array(
				$plugin_file,
			),
			'args' => array(
				'active_plugins',
			),
		));

		WP_Mock::wpFunction( 'wp_get_theme', array(
			'return' => $current_theme,
		));

		WP_Mock::wpFunction( 'get_plugins', array(
			'return' => $installed_plugins,
		));

		WP_Mock::wpFunction( 'is_plugin_active', array(
			'args' => $plugin_file,
			'return' => true,
		));

		WP_Mock::wpFunction( 'wp_list_pluck', array(
			'return' => array(
				$plugin_file,
			),
			'args' => array( $components['plugin'], 'File' ),
		));

		$subject = new OTGS_Installer_WP_Components_Storage();
		$this->assertFalse( $subject->is_outdated() );
	}

	/**
	 * @test
	 */
	public function it_gets_components() {
		$components = array( 'something' );
		WP_Mock::wpFunction( 'get_option', array(
			'times' => 1,
			'args' => OTGS_Installer_WP_Components_Storage::COMPONENTS_CACHE_OPTION_KEY,
			'return' => $components,
		));

		$subject = new OTGS_Installer_WP_Components_Storage();
		$this->assertEquals( $components, $subject->get() );
	}
}