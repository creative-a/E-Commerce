<?php

/**
 * Class Test_OTGS_Installer_WP_Components_Setting_Resources
 *
 * @group local-components
 * @group installer-370
 */
class Test_OTGS_Installer_WP_Components_Setting_Resources extends OTGS_TestCase {
	public static function setUpBeforeClass() {
		parent::setUpBeforeClass();

		define( 'SCRIPT_DEBUG', true );
	}

	/**
	 * @test
	 */
	public function it_adds_hooks() {
		$installer = $this->getMockBuilder( 'WP_Installer' )
		                  ->disableOriginalConstructor()
		                  ->getMock();

		$subject = new OTGS_Installer_WP_Components_Setting_Resources( $installer );
		\WP_Mock::expectActionAdded( 'admin_enqueue_scripts', array( $subject, 'enqueue_resources' ) );
		$subject->add_hooks();
	}

	/**
	 * @test
	 */
	public function it_enqueue_resources() {
		$installer = $this->getMockBuilder( 'WP_Installer' )
		                  ->setMethods( array( 'res_url', 'vendor_url' ) )
		                  ->disableOriginalConstructor()
		                  ->getMock();

		$installer->method( 'res_url' )
		          ->willReturn( WP_INSTALLER_URL );
		$installer->method( 'vendor_url' )
		          ->willReturn( WP_INSTALLER_URL . '/vendor' );

		$subject = new OTGS_Installer_WP_Components_Setting_Resources( $installer );





		WP_Mock::wpFunction( 'wp_register_style',
			array(
				'times' => 1,
				'args'  => array(
					OTGS_Installer_WP_Components_Setting_Resources::HANDLES_OTGS_INSTALLER_UI,
					WP_INSTALLER_URL . '/dist/css/component-settings-reports/styles.css',
					array( ),
					WP_INSTALLER_VERSION,
				),
			) );
		WP_Mock::wpFunction( 'wp_enqueue_script',
		                     array(
			                     'times' => 1,
			                     'args'  => array(
				                     'otgs-installer-components-save-setting',
				                     WP_INSTALLER_URL . '/res/js/save-components-setting.js',
				                     array(),
				                     WP_INSTALLER_VERSION,
			                     ),
		                     ) );

		$subject->enqueue_resources();
	}
}