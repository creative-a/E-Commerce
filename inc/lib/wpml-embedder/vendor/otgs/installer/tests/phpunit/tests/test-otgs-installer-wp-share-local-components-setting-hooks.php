<?php

/**
 * Class Test_OTGS_Installer_WP_Share_Local_Components_Setting_Hooks
 *
 * @group installer-419
 */
class Test_OTGS_Installer_WP_Share_Local_Components_Setting_Hooks extends OTGS_TestCase {

	/**
	 * @test
	 */
	public function it_add_hooks() {
		$subject = new OTGS_Installer_WP_Share_Local_Components_Setting_Hooks( $this->get_template_service_mock(),
		                                                                       $this->get_setting_mock() );
		\WP_Mock::expectActionAdded( 'otgs_installer_render_local_components_setting',
		                             array(
			                             $subject,
			                             'render_local_components_setting',
		                             ),
		                             10,
		                             5 );

		$subject->add_hooks();
	}

	/**
	 * @test
	 * @throws \InvalidArgumentException
	 */
	public function it_renders_local_components_setting() {
		$plugin_name = 'WPML';
		$plugin_uri  = 'http://wpml.org/account';
		$plugin_site = 'wpml.org';
		$repo        = strtolower( $plugin_name );
		$nonce       = 'nonce';

		WP_Mock::userFunction( 'wp_create_nonce',
		                     array(
			                     'args'   => OTGS_Installer_WP_Components_Setting_Ajax::AJAX_ACTION,
			                     'return' => $nonce,
		                     ) );

		WP_Mock::userFunction( 'checked',
		                     array(
			                     'args'   => array(
				                     true,
				                     true,
				                     false
			                     ),
			                     'return' => 'checked=checked',
		                     ) );

		$model = array(
			'strings'                    => array(
				'heading'                 => 'Reporting to',
				'report_to'               => 'Report to',
				'radio_report_yes'        => 'Send theme and plugins information, in order to get faster support and compatibility alerts',
				'radio_report_no'         => 'Don\'t send this information and skip compatibility alerts',
				'which_theme_and_plugins' => 'which theme and plugins you are using.',

			),
			'nonce'                      => array(
				'action' => OTGS_Installer_WP_Components_Setting_Ajax::AJAX_ACTION,
				'value'  => $nonce,
			),
			'custom_raw_heading'         => null,
			'custom_raw_label'           => null,
			'custom_privacy_policy_text' => null,
			'privacy_policy_url'         => 'http://domain.tld',
			'privacy_policy_text'        => 'Privacy and data usage policy',
			'component_name'             => $plugin_name,
			'company_url'                => $plugin_uri,
			'company_site'               => $plugin_site,
			'repo'                       => $repo,
			'is_repo_allowed'            => true,
			'has_setting'                => 0,
		);

		$template_service = $this->get_template_service_mock();
		$template_service->expects( $this->once() )
		                 ->method( 'show' )
		                 ->with( $model, OTGS_Installer_WP_Share_Local_Components_Setting_Hooks::TEMPLATE_CHECKBOX );

		$setting = $this->get_setting_mock();
		$setting->method( 'is_repo_allowed' )
		        ->willReturn( true );

		$subject = new OTGS_Installer_WP_Share_Local_Components_Setting_Hooks( $template_service, $setting );
		$subject->render_local_components_setting( array(
			                                           'plugin_name'        => $plugin_name,
			                                           'plugin_uri'         => $plugin_uri,
			                                           'plugin_site'        => $plugin_site,
			                                           'privacy_policy_url' => 'http://domain.tld',
		                                           ) );
	}

	/**
	 * @test
	 * @throws \InvalidArgumentException
	 */
	public function it_does_NOT_enqueue_the_ui() {
		$template_service = $this->get_template_service_mock();

		$setting = $this->get_setting_mock();

		$subject = new OTGS_Installer_WP_Share_Local_Components_Setting_Hooks( $template_service, $setting );

		WP_Mock::userFunction( 'wp_enqueue_style', array( 'times' => 0 ) );

		WP_Mock::userFunction( 'wp_enqueue_script', array( 'times' => 0 ) );

		$subject->render_local_components_setting( array(
			                                           'plugin_name'        => 'a-plugin-name',
			                                           'plugin_uri'         => 'a-plugin-uri',
			                                           'plugin_site'        => 'a-plugin-site',
			                                           'privacy_policy_url' => 'http://domain.tld',
			                                           'use_styles'         => false,
		                                           ) );
	}

	/**
	 * @test
	 *
	 * @dataProvider dp_ui_settings
	 *
	 * @param bool $use_styles
	 * @param bool $use_radio
	 */
	public function it_enqueues_the_ui( $use_styles, $use_radio ) {
		$template_service = $this->get_template_service_mock();

		$setting = $this->get_setting_mock();

		$subject = new OTGS_Installer_WP_Share_Local_Components_Setting_Hooks( $template_service, $setting );

		\WP_Mock::userFunction( 'wp_enqueue_style',
		                       array(
								   'args'  => array( OTGS_Installer_WP_Components_Setting_Resources::HANDLES_OTGS_INSTALLER_UI ),
								   'times' => (int) $use_styles,
		                       ) );

		$must_use_otgs_ui_assets = $use_styles && ! $use_radio;
		\WP_Mock::userFunction( 'wp_enqueue_style', array( 'times' => (int) $must_use_otgs_ui_assets, 'args' => array( 'otgsSwitcher' ) ) );

		$subject->render_local_components_setting( array(
			'plugin_name'        => 'a-plugin-name',
			'plugin_uri'         => 'a-plugin-uri',
			'plugin_site'        => 'a-plugin-site',
			'privacy_policy_url' => 'http://domain.tld',
			'use_styles'         => $use_styles,
			'use_radio'          => $use_radio,
		                                           ) );
	}


	public function dp_ui_settings() {
		return array(
			'no styles, use radio'  => array( false, true ),
			'no styles, no radio'   => array( false, false ),
			'use styles, use radio' => array( true, true ),
			'use styles, no radio'  => array( true, false ),
		);
	}
	/**
	 * @test
	 * @throws \InvalidArgumentException
	 * @expectedException \InvalidArgumentException
	 * @expectedExceptionMessage Arguments are missing
	 */
	public function it_throws_an_exception_on_missing_arguments() {
		$template_service = $this->get_template_service_mock();

		$setting = $this->get_setting_mock();

		$subject = new OTGS_Installer_WP_Share_Local_Components_Setting_Hooks( $template_service, $setting );
		$subject->render_local_components_setting( array() );
	}

	/**
	 * @test
	 * @throws \InvalidArgumentException
	 * @expectedException \InvalidArgumentException
	 * @expectedExceptionMessageRegExp /.* is missing/
	 */
	public function it_throws_an_exception_on_missing_required_arguments() {
		$template_service = $this->get_template_service_mock();

		$setting = $this->get_setting_mock();

		$subject = new OTGS_Installer_WP_Share_Local_Components_Setting_Hooks( $template_service, $setting );
		$subject->render_local_components_setting( array( 1, 2, 3 ) );
	}

	/**
	 * @return OTGS_Php_Template_Service|PHPUnit_Framework_MockObject_MockObject
	 */
	private function get_template_service_mock() {
		return $this->getMockBuilder( 'OTGS_Php_Template_Service' )
		            ->setMethods( array( 'show' ) )
		            ->disableOriginalConstructor()
		            ->getMock();
	}

	/**
	 * @return OTGS_Installer_WP_Share_Local_Components_Setting|PHPUnit_Framework_MockObject_MockObject
	 */
	private function get_setting_mock() {
		return $this->getMockBuilder( 'OTGS_Installer_WP_Share_Local_Components_Setting' )
		            ->setMethods( array( 'is_repo_allowed' ) )
		            ->disableOriginalConstructor()
		            ->getMock();
	}

	/**
	 * @return OTGS_Installer_WP_Components_Setting_Resources|PHPUnit_Framework_MockObject_MockObject
	 */
	private function get_resources() {
		return $this->getMockBuilder( 'OTGS_Installer_WP_Components_Setting_Resources' )
		            ->disableOriginalConstructor()
		            ->getMock();
	}
}
