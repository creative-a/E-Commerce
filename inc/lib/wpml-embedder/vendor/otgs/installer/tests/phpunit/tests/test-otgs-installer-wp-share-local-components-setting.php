<?php

/**
 * Class Test_OTGS_Installer_WP_Share_Local_Components_Setting
 *
 * @group local-components
 * @group installer-370
 */
class Test_OTGS_Installer_WP_Share_Local_Components_Setting extends OTGS_TestCase {

	/**
	 * @test
	 */
	public function it_saves_setting() {
		$settings = array( 'wpml' => 1 );

		WP_Mock::wpFunction( 'get_option', array(
			'return' => $settings,
			'args' => array(
				OTGS_Installer_WP_Share_Local_Components_Setting::OPTION_KEY,
			),
		));

		WP_Mock::wpFunction( 'update_option', array(
			'times' => 1,
			'args' => array(
				OTGS_Installer_WP_Share_Local_Components_Setting::OPTION_KEY,
				array(
					'wpml' => 1,
				)
			),
		));

		$subject = new OTGS_Installer_WP_Share_Local_Components_Setting();
		$subject->save( $settings );
	}

	/**
	 * @test
	 */
	public function it_checks_if_repo_is_allowed() {
		$settings = array(
			'wpml' => 1,
		);

		WP_Mock::wpFunction( 'get_option', array(
			'times' => 3,
			'return' => $settings,
		));

		$subject = new OTGS_Installer_WP_Share_Local_Components_Setting();
		$this->assertTrue( $subject->is_repo_allowed( 'wpml' ) );
		$this->assertFalse( $subject->is_repo_allowed( 'toolset' ) );
		$this->assertFalse( $subject->is_repo_allowed( 'unknown-repo' ) );
	}
}