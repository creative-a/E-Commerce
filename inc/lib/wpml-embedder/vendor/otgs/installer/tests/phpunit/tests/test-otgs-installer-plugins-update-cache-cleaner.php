<?php

/**
 * Class Test_OTGS_Installer_Plugins_Update_Cache_Cleaner
 *
 * @group installer-531
 */
class Test_OTGS_Installer_Plugins_Update_Cache_Cleaner extends OTGS_TestCase {

	/**
	 * @test
	 */
	public function it_adds_hooks() {
		$subject = new OTGS_Installer_Plugins_Update_Cache_Cleaner();
		\WP_Mock::expectActionAdded( 'otgs_installer_clean_plugins_update_cache', array( $subject, 'clean_plugins_update_cache' ) );
		$subject->add_hooks();
	}

	/**
	 * @test
	 */
	public function it_invalidates_plugins_update_cache() {
		\WP_Mock::userFunction( 'delete_site_transient', array( 'args' => 'update_plugins', 'times' => 1 ) );

		$subject = new OTGS_Installer_Plugins_Update_Cache_Cleaner();
		$subject->clean_plugins_update_cache();
	}
}