<?php

/**
 * Class Test_OTGS_Installer_WP_Components_Sender
 *
 * @group local-components
 * @group installer-370
 */
class Test_OTGS_Installer_WP_Components_Sender extends OTGS_TestCase {

	/**
	 * @test
	 */
	public function it_sends_request_to_server() {
		$wpml_api_url = 'https://api.wpml.org';
		$toolset_api_url = 'https://api.wp-types.com';
		$wpml_site_key = 'my-wpml-site-key';
		$toolset_site_key = 'my-toolset-site-key';
		$site_url = 'www.my-site.org';
		$components = array(
			'plugin' => array(
				array(
					'File' => 'myplugin-plugin.php',
				)
			),
			'theme' => array(
				array(
					'Template' => 'my-theme',
				)
			)
		);

		WP_Mock::wpFunction( 'get_site_url', array(
			'times' => 2,
			'return' => $site_url,
		));

		$wpml_args = array(
			'body' => array(
				'action'     => 'update_site_components',
				'site_key'   => $wpml_site_key,
				'site_url'   => $site_url,
				'components' => $components,
				'phpversion' => phpversion(),
				'force'      => true,
			)
		);

		\WP_Mock::onFilter( 'installer_fetch_components_data_request' )
		        ->with( $wpml_args )
		        ->reply( $wpml_args );

		WP_Mock::wpFunction( 'wp_remote_post', array(
			'times' => 1,
			'args' => array(
				$wpml_api_url . '?action=update_site_components',
				$wpml_args,
			),
		));

		$toolset_args = array(
			'body' => array(
				'action'     => 'update_site_components',
				'site_key'   => $toolset_site_key,
				'site_url'   => $site_url,
				'components' => $components,
				'phpversion' => phpversion(),
				'force'      => true,
			)
		);

		WP_Mock::wpFunction( 'wp_remote_post', array(
			'times' => 1,
			'args' => array(
				$toolset_api_url . '?action=update_site_components',
				$toolset_args,
			),
		));

		$installer = $this->getMockBuilder( 'WP_Installer' )
			->setMethods( array( 'get_repositories', 'get_site_key', 'get_settings', 'save_settings', 'load_repositories_list' ) )
			->disableOriginalConstructor()
			->getMock();

		$installer->method( 'get_settings' )
			->willReturn( false );

		$installer->expects($this->once())
			->method( 'save_settings' );

		$repositories = array(
			'wpml' => array(
				'api-url' => $wpml_api_url,
			),
			'toolset' => array(
				'api-url' => $toolset_api_url,
			),
		);

		$installer->method( 'get_repositories' )
		          ->willReturnOnConsecutiveCalls( false, $repositories );

		$installer->expects($this->once())
		          ->method( 'load_repositories_list' );

		$installer->method( 'get_site_key' )
			->withConsecutive(
				array( 'wpml' ),
				array( 'toolset' ),
				array( 'wpml' ),
				array( 'toolset' )
			)
			->willReturnOnConsecutiveCalls( $wpml_site_key, $toolset_site_key, $wpml_site_key, $toolset_site_key );

		$installer->method( 'get_repositories' )
			->willReturn( $repositories );

		$settings = $this->getMockBuilder( 'OTGS_Installer_WP_Share_Local_Components_Setting' )
			->setMethods( array( 'is_repo_allowed' ) )
			->disableOriginalConstructor()
			->getMock();

		$settings->method( 'is_repo_allowed' )
			->willReturn( true );

		$subject = new OTGS_Installer_WP_Components_Sender( $installer, $settings );
		$subject->send( $components, true );
	}

	/**
	 * @test
	 * @group installer-434
	 */
	public function it_does_not_send_request_to_the_server_if_site_key_is_missing() {
		$wpml_api_url = 'https://api.wpml.org';
		$toolset_api_url = 'https://api.wp-types.com';
		$wpml_site_key = '';
		$toolset_site_key = '';
		$components = array(
			'plugin' => array(
				array(
					'File' => 'myplugin-plugin.php',
				)
			),
			'theme' => array(
				array(
					'Template' => 'my-theme',
				)
			)
		);

		WP_Mock::wpFunction( 'wp_remote_post', array(
			'times' => 0,
		));

		$installer = $this->getMockBuilder( 'WP_Installer' )
		                  ->setMethods( array( 'get_repositories', 'get_site_key', 'get_settings', 'save_settings', 'load_repositories_list' ) )
		                  ->disableOriginalConstructor()
		                  ->getMock();

		$installer->method( 'get_settings' )
		          ->willReturn( false );

		$installer->expects($this->once())
		          ->method( 'save_settings' );

		$repositories = array(
			'wpml' => array(
				'api-url' => $wpml_api_url,
			),
			'toolset' => array(
				'api-url' => $toolset_api_url,
			),
		);

		$installer->method( 'get_repositories' )
		          ->willReturnOnConsecutiveCalls( false, $repositories );

		$installer->expects($this->once())
		          ->method( 'load_repositories_list' );

		$installer->method( 'get_site_key' )
		          ->withConsecutive(
			          array( 'wpml' ),
			          array( 'toolset' )
		          )
		          ->willReturnOnConsecutiveCalls( $wpml_site_key, $toolset_site_key );

		$installer->method( 'get_repositories' )
		          ->willReturn( $repositories );

		$settings = $this->getMockBuilder( 'OTGS_Installer_WP_Share_Local_Components_Setting' )
		                 ->setMethods( array( 'is_repo_allowed' ) )
		                 ->disableOriginalConstructor()
		                 ->getMock();

		$settings->method( 'is_repo_allowed' )
		         ->willReturn( true );

		$subject = new OTGS_Installer_WP_Components_Sender( $installer, $settings );
		$subject->send( $components );
	}

	/**
	 * @test
	 * @group installer-434
	 */
	public function it_does_not_send_request_to_the_server_if_user_does_not_allow_us() {
		$wpml_api_url = 'https://api.wpml.org';
		$toolset_api_url = 'https://api.wp-types.com';
		$wpml_site_key = 'wpml-site-key';
		$toolset_site_key = 'toolset-site-key';
		$components = array(
			'plugin' => array(
				array(
					'File' => 'myplugin-plugin.php',
				)
			),
			'theme' => array(
				array(
					'Template' => 'my-theme',
				)
			)
		);

		WP_Mock::wpFunction( 'wp_remote_post', array(
			'times' => 0,
		));

		$installer = $this->getMockBuilder( 'WP_Installer' )
		                  ->setMethods( array( 'get_repositories', 'get_site_key', 'get_settings', 'save_settings', 'load_repositories_list' ) )
		                  ->disableOriginalConstructor()
		                  ->getMock();

		$installer->method( 'get_settings' )
		          ->willReturn( false );

		$installer->expects($this->once())
		          ->method( 'save_settings' );

		$repositories = array(
			'wpml' => array(
				'api-url' => $wpml_api_url,
			),
			'toolset' => array(
				'api-url' => $toolset_api_url,
			),
		);

		$installer->method( 'get_repositories' )
		          ->willReturnOnConsecutiveCalls( false, $repositories );

		$installer->expects($this->once())
		          ->method( 'load_repositories_list' );

		$installer->method( 'get_site_key' )
		          ->withConsecutive(
			          array( 'wpml' ),
			          array( 'toolset' )
		          )
		          ->willReturnOnConsecutiveCalls( $wpml_site_key, $toolset_site_key );

		$installer->method( 'get_repositories' )
		          ->willReturn( $repositories );

		$settings = $this->getMockBuilder( 'OTGS_Installer_WP_Share_Local_Components_Setting' )
		                 ->setMethods( array( 'is_repo_allowed' ) )
		                 ->disableOriginalConstructor()
		                 ->getMock();

		$settings->method( 'is_repo_allowed' )
		         ->willReturn( false );

		$subject = new OTGS_Installer_WP_Components_Sender( $installer, $settings );
		$subject->send( $components );
	}
}