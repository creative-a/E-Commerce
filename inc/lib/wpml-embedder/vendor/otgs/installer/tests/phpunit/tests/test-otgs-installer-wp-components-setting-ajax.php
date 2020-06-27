<?php

/**
 * Class Test_OTGS_Installer_Plugins_Page_Notice
 *
 * @group local-components
 * @group installer-370
 */
class Test_OTGS_Installer_WP_Components_Setting_Ajax extends OTGS_TestCase {

	public function tearDown() {
		parent::tearDown();
		unset( $_POST['nonce'], $_POST['agree'] );
	}

	/**
	 * @test
	 */
	public function it_adds_hooks() {
		$components_setting = $this->getMockBuilder( 'OTGS_Installer_WP_Share_Local_Components_Setting' )
		                           ->disableOriginalConstructor()
		                           ->getMock();

		$installer = $this->getMockBuilder( 'WP_Installer' )
		                  ->disableOriginalConstructor()
		                  ->getMock();

		$subject = new OTGS_Installer_WP_Components_Setting_Ajax( $components_setting, $installer );
		\WP_Mock::expectActionAdded( 'wp_ajax_' . OTGS_Installer_WP_Components_Setting_Ajax::AJAX_ACTION, array(
			$subject,
			'save'
		), 1 );
		$subject->add_hooks();
	}

	/**
	 * @test
	 */
	public function it_saves_setting_on_valid_request() {
		$nonce          = 'nonce';
		$_POST['nonce'] = $nonce;
		$_POST['agree'] = 1;
		$_POST['repo']  = 'wpml';

		WP_Mock::wpFunction( 'wp_verify_nonce', array(
			'return' => true,
			'args'   => array( $nonce, OTGS_Installer_WP_Components_Setting_Ajax::AJAX_ACTION ),
		) );

		$installer = $this->getMockBuilder( 'WP_Installer' )
		                  ->setMethods( array( 'get_repositories' ) )
		                  ->disableOriginalConstructor()
		                  ->getMock();

		$repos = array(
			'wpml'    => 0,
			'toolset' => 0,
		);

		$installer->method( 'get_repositories' )
		          ->willReturn( $repos );

		$components_setting = $this->getMockBuilder( 'OTGS_Installer_WP_Share_Local_Components_Setting' )
		                           ->setMethods( array( 'save' ) )
		                           ->disableOriginalConstructor()
		                           ->getMock();

		$components_setting->expects( $this->once() )
		                   ->method( 'save' )
		                   ->with(
			                   array(
				                   'wpml' => 1,
			                   )
		                   );

		$subject = new OTGS_Installer_WP_Components_Setting_Ajax( $components_setting, $installer );
		$subject->save();
	}

	/**
	 * @test
	 */
	public function it_does_not_save_setting_on_invalid_request() {
		$installer = $this->getMockBuilder( 'WP_Installer' )
		                  ->disableOriginalConstructor()
		                  ->getMock();

		$components_setting = $this->getMockBuilder( 'OTGS_Installer_WP_Share_Local_Components_Setting' )
		                           ->setMethods( array( 'save' ) )
		                           ->disableOriginalConstructor()
		                           ->getMock();

		$components_setting->expects( $this->never() )
		                   ->method( 'save' );

		$subject = new OTGS_Installer_WP_Components_Setting_Ajax( $components_setting, $installer );
		$subject->save();
	}
}