<?php
/**
 * Class Test_OTGS_Installer_Support_Hooks
 *
 * @group installer-508
 */

class Test_OTGS_Installer_Support_Hooks extends OTGS_TestCase {

	/**
	 * @test
	 */
	public function it_adds_hooks() {
		$template_factory = $this->getMockBuilder( 'OTGS_Installer_Support_Template_Factory' )
		                         ->setMethods( array( 'create' ) )
		                         ->disableOriginalConstructor()
		                         ->getMock();

		$subject = new OTGS_Installer_Support_Hooks( $template_factory );
		\WP_Mock::expectActionAdded( 'admin_menu', array( $subject, 'add_support_page' ) );
		\WP_Mock::expectActionAdded( 'admin_enqueue_scripts', array( $subject, 'enqueue_scripts' ) );
		\WP_Mock::expectActionAdded( 'otgs_render_installer_support_link', array( $subject, 'render_link' ) );
		$subject->add_hooks();
	}

	/**
	 * @test
	 */
	public function it_adds_support_page() {
		$template_factory = $this->getMockBuilder( 'OTGS_Installer_Support_Template_Factory' )
		                         ->setMethods( array( 'create' ) )
		                         ->disableOriginalConstructor()
		                         ->getMock();

		$subject = new OTGS_Installer_Support_Hooks( $template_factory );

		WP_Mock::wpFunction( 'add_submenu_page', array(
			'times' => 1,
			'args'  => array(
				'commercial',
				'Installer Support',
				'Installer Support',
				'manage_options',
				'otgs-installer-support',
				array( $subject, 'render_support_page' ),
			),
		) );

		$subject->add_support_page();
	}

	/**
	 * @test
	 */
	public function it_renders_support_page() {
		$template_factory = $this->getMockBuilder( 'OTGS_Installer_Support_Template_Factory' )
		                         ->setMethods( array( 'create' ) )
		                         ->disableOriginalConstructor()
		                         ->getMock();

		$template = $this->getMockBuilder( 'OTGS_Installer_Support_Template' )
		                 ->setMethods( array( 'show' ) )
		                 ->disableOriginalConstructor()
		                 ->getMock();

		$template->expects( $this->once() )
		         ->method( 'show' );

		$template_factory->expects( $this->once() )
		                 ->method( 'create' )
		                 ->willReturn( $template );

		$subject = new OTGS_Installer_Support_Hooks( $template_factory );
		$subject->render_support_page();
	}

	/**
	 * @test
	 * @group wpmlcore-5837
	 */
	public function it_renders_support_link() {
		$template_factory = $this->getMockBuilder( 'OTGS_Installer_Support_Template_Factory' )
		                         ->setMethods( array( 'create' ) )
		                         ->disableOriginalConstructor()
		                         ->getMock();

		$template = $this->getMockBuilder( 'OTGS_Installer_Support_Template' )
		                 ->setMethods( array( 'render_support_link' ) )
		                 ->disableOriginalConstructor()
		                 ->getMock();

		$template->expects( $this->once() )
		         ->method( 'render_support_link' );

		$template_factory->expects( $this->once() )
		                 ->method( 'create' )
		                 ->willReturn( $template );

		$subject = new OTGS_Installer_Support_Hooks( $template_factory );
		$subject->render_link();
	}

	/**
	 * @test
	 * @group installer-524
	 */
	public function it_enqueues_scripts() {
		\WP_Mock::userFunction( 'is_admin', array() );
		\WP_Mock::userFunction( 'untrailingslashit', array() );
		\WP_Mock::userFunction( 'plugins_url', array() );

		\WP_Mock::userFunction( 'wp_enqueue_style', array(
			'args' => array(
				'otgs-installer-support-style',
				WP_Installer()->plugin_url() . '/dist/css/otgs-installer-support/styles.css',
				array(),
				WP_Installer()->version()
			),
			'times' => 1,
		) );

		\WP_Mock::userFunction( 'wp_enqueue_script', array(
			'args' => array(
				'otgs-installer-support-script',
				WP_Installer()->plugin_url() . '/dist/js/otgs-installer-support/app.js',
				array(), WP_Installer()->version()
			),
			'times' => 1,
		) );

		$template_factory = $this->getMockBuilder( 'OTGS_Installer_Support_Template_Factory' )
		                         ->setMethods( array( 'create' ) )
		                         ->disableOriginalConstructor()
		                         ->getMock();

		$subject = new OTGS_Installer_Support_Hooks( $template_factory );
		$subject->enqueue_scripts( 'admin_page_otgs-installer-support' );
	}

	/**
	 * @test
	 * @group installer-524
	 */
	public function it_does_not_enqueue_scripts_when_page_is_not_installer_support() {
		\WP_Mock::userFunction( 'is_admin', array() );
		\WP_Mock::userFunction( 'untrailingslashit', array() );
		\WP_Mock::userFunction( 'plugins_url', array() );

		\WP_Mock::userFunction( 'wp_enqueue_style', array(
			'times' => 0,
		) );

		\WP_Mock::userFunction( 'wp_enqueue_script', array(
			'times' => 0,
		) );

		$template_factory = $this->getMockBuilder( 'OTGS_Installer_Support_Template_Factory' )
		                         ->setMethods( array( 'create' ) )
		                         ->disableOriginalConstructor()
		                         ->getMock();

		$subject = new OTGS_Installer_Support_Hooks( $template_factory );
		$subject->enqueue_scripts( 'admin_page_no_installer_support_page' );
	}
}