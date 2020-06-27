<?php

/**
 * Class Test_OTGS_Installer_Plugins_Page_Notice
 *
 * @group local-components
 * @group installer-370
 */
class Test_OTGS_Installer_Plugins_Page_Notice extends OTGS_TestCase {

	const REPO                      = 'wpml';
	const PRODUCT                   = 'WPML';
	const ADMIN_URL                 = 'http://something/wp-admin/';
	const EXPECTED_REGISTER_MESSAGE = 'You are using an unregistered version of WPML and are not receiving compatibility and security updates. <a href="http://something/wp-admin/&repository=wpml&action=register">Register now</a>';
	const EXPECTED_EXPIRED_MESSAGE  = 'You are using an expired account of WPML. <a href="http://something/wp-admin/&repository=wpml&action=expired">Extend your subscription</a>';
	const EXPECTED_REFUNDED_MESSAGE = 'Remember to remove WPML from this website. <a href="http://something/wp-admin/&repository=wpml&action=refunded">Check my order status</a>';

	/**
	 * @test
	 */
	public function it_adds_hooks() {
		$template_service = $this->getMockBuilder( 'OTGS_Php_Template_Service' )
		                         ->disableOriginalConstructor()
		                         ->getMock();

		$plugin_id                 = 'my-plugin';
		$plugin_data[ $plugin_id ] = array(
			OTGS_Installer_Plugins_Page_Notice::DISPLAY_SUBSCRIPTION_NOTICE_KEY => false,
			OTGS_Installer_Plugins_Page_Notice::DISPLAY_SETTING_NOTICE_KEY      => false,
		);

		$plugin_finder = $this->get_plugin_finder_mock();

		$subject = new OTGS_Installer_Plugins_Page_Notice( $template_service, $plugin_finder );
		$subject->add_plugin( $plugin_id, $plugin_data );
		\WP_Mock::expectActionAdded( 'after_plugin_row_' . $plugin_id, array(
			$subject,
			'show_purchase_notice_under_plugin'
		), 10, 2 );
		$subject->add_hooks();
	}

	/**
	 * @test
	 * @dataProvider dpNoticeType
	 */
	public function it_shows_purchase_notice_under_plugin_in_single_site( $type, $model ) {
		$template_service = $this->mockTemplateService();

		$wp_list_table = $this->mockWPListTable();

		$wp_list_table->expects( $this->once() )
		              ->method( 'get_column_count' )
		              ->willReturn( 1 );

		$this->mockBlogInfo();

		WP_Mock::wpFunction( 'is_multisite', array(
			'return' => false,
		) );

		WP_Mock::wpFunction( 'admin_url', array(
			'return' => self::ADMIN_URL,
			'args'   => 'plugin-install.php?tab=commercial',
		) );

		$plugin = $this->mockInstallerPlugin();

		$plugin->method( 'get_external_repo' )
		       ->willReturn( false );

		$plugin_finder = $this->get_plugin_finder_mock();
		$plugin_finder->method( 'get_plugin_by_name' )
		              ->with( 'Toolset Views Lite' )
		              ->willReturn( $plugin );

		$subject = new OTGS_Installer_Plugins_Page_Notice( $template_service, $plugin_finder );

		$template_service->expects( $this->once() )
		                 ->method( 'show' )
		                 ->with( $model, OTGS_Installer_Plugins_Page_Notice::TEMPLATE );

		$plugin_data = array( 'Name' => 'Toolset Views Lite' );

		$subject->add_plugin( 'my-plugin.php', [
			'display_subscription_notice' => [
				'repo'    => self::REPO,
				'product' => self::PRODUCT,
				'type'    => $type,
			]
		] );
		$subject->show_purchase_notice_under_plugin( 'my-plugin.php', $plugin_data );
	}

	public function dpNoticeType() {
		return [
			[ 'register', $this->getExpectedRegisterModel() ],
			[ 'expired', $this->getExpectedExpiredModel() ],
			[ 'refunded', $this->getExpectedRefundedModel() ]
		];
	}

	/**
	 * @test
	 * @group installer-482
	 */
	public function it_does_show_subscription_notice_under_plugin_if_it_has_external_repository_and_is_not_lite() {
		$template_service = $this->mockTemplateService();

		$this->mockWPListTable();

		$this->mockBlogInfo();

		WP_Mock::wpFunction( 'is_multisite', array(
			'return' => false,
		) );

		$plugin = $this->getMockBuilder( 'OTGS_Installer_Plugin' )
		               ->setMethods( array( 'get_external_repo', 'is_lite' ) )
		               ->disableOriginalConstructor()
		               ->getMock();

		$plugin->method( 'is_lite' )
		       ->willReturn( 0 );

		$plugin->method( 'get_external_repo' )
		       ->willReturn( 'toolset' );

		$plugin_finder = $this->get_plugin_finder_mock();
		$plugin_finder->method( 'get_plugin_by_name' )
		              ->with( 'Toolset Views Lite' )
		              ->willReturn( $plugin );

		$subject = new OTGS_Installer_Plugins_Page_Notice( $template_service, $plugin_finder );

		$template_service->expects( $this->once() )
		                 ->method( 'show' );

		$plugin_data = array( 'Name' => 'Toolset Views Lite' );

		$subject->add_plugin( 'my-plugin.php', array( 'display_subscription_notice' => true ) );
		$subject->show_purchase_notice_under_plugin( 'my-plugin.php', $plugin_data );
	}

	/**
	 * @test
	 */
	public function it_shows_notice_if_plugin_is_lite() {
		$template_service = $this->mockTemplateService();

		$wp_list_table = $this->mockWPListTable();

		$wp_list_table->expects( $this->once() )
		              ->method( 'get_column_count' )
		              ->willReturn( 1 );

		$this->mockBlogInfo();

		WP_Mock::wpFunction( 'is_multisite', array(
			'return' => false,
		) );

		$admin_url = 'http://something/wp-admin/';

		WP_Mock::wpFunction( 'admin_url', array(
			'return' => $admin_url,
			'args'   => 'plugin-install.php?tab=commercial',
		) );

		$model = array(
			'strings'   => array(
				'valid_subscription' => sprintf( __( 'You are using the complementary %s. For the %s, %s.', 'installer' ),
					'Toolset Views Lite', '<a href="https://wpml.org/documentation/developing-custom-multilingual-sites/types-and-views-lite/?utm_source=viewsplugin&utm_campaign=wpml-toolset-lite&utm_medium=plugins-page&utm_term=features-link">' . __( 'complete set of features', 'installer' ) . '</a>', '<a href="https://toolset.com/?add-to-cart=631305&buy_now=1&apply_coupon=eyJjb3Vwb25fbmFtZSI6IndwbWwgY291cG9uIGJhc2ljIiwiY291cG9uX2lkIjoiODAyMDE2In0=">' . __( 'upgrade to Toolset', 'installer' ) . '</a>' ),
			),
			'css'       => array(
				'tr_classes'     => 'plugin-update-tr installer-plugin-update-tr js-otgs-plugin-tr',
				'notice_classes' => 'update-message notice inline notice-otgs',
			),
			'col_count' => 1,
		);

		$plugin = $this->getMockBuilder( 'OTGS_Installer_Plugin' )
		               ->setMethods( array( 'get_external_repo', 'get_name', 'is_lite' ) )
		               ->disableOriginalConstructor()
		               ->getMock();

		$plugin->method( 'get_external_repo' )
		       ->willReturn( 'toolset' );

		$plugin->method( 'is_lite' )
		       ->willReturn( 1 );

		$plugin->method( 'get_name' )
		       ->willReturn( 'Toolset Views Lite' );

		$plugin_finder = $this->get_plugin_finder_mock();
		$plugin_finder->method( 'get_plugin_by_name' )
		              ->with( 'Toolset Views Lite' )
		              ->willReturn( $plugin );

		$subject = new OTGS_Installer_Plugins_Page_Notice( $template_service, $plugin_finder );

		$template_service->expects( $this->once() )
		                 ->method( 'show' )
		                 ->with( $model, OTGS_Installer_Plugins_Page_Notice::TEMPLATE );

		$plugin_data = array( 'Name' => 'Toolset Views Lite' );

		$subject->add_plugin( 'my-plugin.php', array( 'display_subscription_notice' => true ) );
		$subject->show_purchase_notice_under_plugin( 'my-plugin.php', $plugin_data );
	}

	/**
	 * @test
	 */
	public function it_shows_purchase_notice_under_plugin_in_multisite_and_network_admin() {
		$template_service = $this->mockTemplateService();

		$wp_list_table = $this->mockWPListTable();

		$wp_list_table->expects( $this->once() )
		              ->method( 'get_column_count' )
		              ->willReturn( 1 );

		WP_Mock::wpFunction( 'is_multisite', array(
			'return' => true,
		) );

		WP_Mock::wpFunction( 'is_network_admin', array(
			'return' => true,
		) );

		WP_Mock::wpFunction( 'network_admin_url', array(
			'args'   => 'plugin-install.php?tab=commercial',
			'return' => self::ADMIN_URL,
		) );

		$this->mockBlogInfo();

		$model = $this->getExpectedRegisterModel();

		$plugin = $this->mockInstallerPlugin();

		$plugin->method( 'get_external_repo' )
		       ->willReturn( false );

		$plugin_finder = $this->get_plugin_finder_mock();
		$plugin_finder->method( 'get_plugin_by_name' )
		              ->with( 'Toolset Views Lite' )
		              ->willReturn( $plugin );

		$subject = new OTGS_Installer_Plugins_Page_Notice( $template_service, $plugin_finder );

		$template_service->expects( $this->once() )
		                 ->method( 'show' )
		                 ->with( $model, OTGS_Installer_Plugins_Page_Notice::TEMPLATE );

		$plugin_data = array( 'Name' => 'Toolset Views Lite' );

		$subject->add_plugin( 'my-plugin.php', [
			'display_subscription_notice' => [
				'repo'    => self::REPO,
				'product' => self::PRODUCT,
				'type'    => 'register',
			]
		] );
		$subject->show_purchase_notice_under_plugin( 'my-plugin.php', $plugin_data );
	}

	/**
	 * @test
	 */
	public function it_shows_purchase_notice_under_plugin_in_multisite_and_not_network_admin() {
		$template_service = $this->mockTemplateService();

		$wp_list_table = $this->mockWPListTable();

		$wp_list_table->expects( $this->once() )
		              ->method( 'get_column_count' )
		              ->willReturn( 1 );

		WP_Mock::wpFunction( 'is_multisite', array(
			'return' => true,
		) );

		$admin_url = 'http://something/wp-admin/';

		WP_Mock::wpFunction( 'is_network_admin', array(
			'return' => false,
		) );

		WP_Mock::wpFunction( 'admin_url', array(
			'args'   => 'options-general.php?page=installer',
			'return' => $admin_url,
		) );

		$this->mockBlogInfo();

		$model = $this->getExpectedRegisterModel();

		$plugin = $this->mockInstallerPlugin();

		$plugin->method( 'get_external_repo' )
		       ->willReturn( false );

		$plugin_finder = $this->get_plugin_finder_mock();
		$plugin_finder->method( 'get_plugin_by_name' )
		              ->with( 'Toolset Views Lite' )
		              ->willReturn( $plugin );

		$subject = new OTGS_Installer_Plugins_Page_Notice( $template_service, $plugin_finder );

		$template_service->expects( $this->once() )
		                 ->method( 'show' )
		                 ->with( $model, OTGS_Installer_Plugins_Page_Notice::TEMPLATE );

		$plugin_data = array( 'Name' => 'Toolset Views Lite' );

		$subject->add_plugin( 'my-plugin.php', [
			'display_subscription_notice' => [
				'repo'    => self::REPO,
				'product' => self::PRODUCT,
				'type'    => 'register',
			]
		] );
		$subject->show_purchase_notice_under_plugin( 'my-plugin.php', $plugin_data );
	}

	private function get_plugin_finder_mock() {
		return $this->getMockBuilder( 'OTGS_Installer_Plugin_Finder' )
		            ->setMethods( array( 'get_plugin_by_name' ) )
		            ->disableOriginalConstructor()
		            ->getMock();
	}

	private function mockTemplateService() {
		$template_service = $this->getMockBuilder( 'OTGS_Php_Template_Service' )
		                         ->setMethods( array( 'show' ) )
		                         ->disableOriginalConstructor()
		                         ->getMock();

		return $template_service;
	}

	private function mockWPListTable() {
		$wp_list_table = $this->getMockBuilder( 'WP_Plugins_List_Table' )
		                      ->setMethods( array( 'get_column_count' ) )
		                      ->disableOriginalConstructor()
		                      ->getMock();

		WP_Mock::wpFunction( '_get_list_table', array(
			'return' => $wp_list_table,
			'args'   => 'WP_Plugins_List_Table',
		) );

		return $wp_list_table;
	}

	private function mockInstallerPlugin() {
		$plugin = $this->getMockBuilder( 'OTGS_Installer_Plugin' )
		               ->setMethods( array( 'get_external_repo' ) )
		               ->disableOriginalConstructor()
		               ->getMock();

		return $plugin;
	}

	private function mockBlogInfo() {
		WP_Mock::wpFunction( 'get_bloginfo', array(
			'return' => '4.6',
			'args'   => 'version',
		) );
	}

	private function getExpectedRegisterModel() {
		$model = array(
			'strings'   => array(
				'valid_subscription' => self::EXPECTED_REGISTER_MESSAGE,
			),
			'css'       => array(
				'tr_classes'     => 'plugin-update-tr installer-plugin-update-tr js-otgs-plugin-tr',
				'notice_classes' => 'update-message notice inline notice-otgs',
			),
			'col_count' => 1,
		);

		return $model;
	}

	private function getExpectedExpiredModel() {
		$model = array(
			'strings'   => array(
				'valid_subscription' => self::EXPECTED_EXPIRED_MESSAGE,
			),
			'css'       => array(
				'tr_classes'     => 'plugin-update-tr installer-plugin-update-tr js-otgs-plugin-tr',
				'notice_classes' => 'update-message notice inline notice-otgs',
			),
			'col_count' => 1,
		);

		return $model;
	}

	private function getExpectedRefundedModel() {
		$model = array(
			'strings'   => array(
				'valid_subscription' => self::EXPECTED_REFUNDED_MESSAGE,
			),
			'css'       => array(
				'tr_classes'     => 'plugin-update-tr installer-plugin-update-tr js-otgs-plugin-tr',
				'notice_classes' => 'update-message notice inline notice-otgs notice-otgs-refund'
			),
			'col_count' => 1,
		);

		return $model;
	}
}
