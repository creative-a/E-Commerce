<?php

/**
 * Class Test_OTGS_Installer_Icons
 *
 * @group installer-393
 * @group installer-products-map
 */
class Test_OTGS_Installer_Icons extends OTGS_TestCase {

	/**
	 * @test
	 */
	public function it_adds_hooks() {
		$subject = new OTGS_Installer_Icons( $this->get_installer_mock() );
		\WP_Mock::expectFilterAdded( 'otgs_installer_upgrade_check_response', array(
			$subject,
			'add_icons_on_response'
		), 10, 2 );
		$subject->add_hooks();
	}

	/**
	 * @test
	 */
	public function it_adds_icons_on_response_if_product_is_found() {
		$installer = $this->get_installer_mock();
		$subject   = new OTGS_Installer_Icons( $installer );
		$product   = 'wpml-media';

		$installer_url = 'http://something/';
		$installer->method( 'plugin_url' )
		          ->willReturn( $installer_url );

		$installer->method( 'get_repositories' )
		          ->willReturn( array( 'wpml' => array(), 'toolset' => array() ) );

		$repository = 'wpml';

		$installer->settings['repositories'][ $repository ]['data']['products-map'][ $product ] = $product;

		$base             = $installer_url . '/../icons/plugin-icons/' . $repository . '/' . $product . '/icon';
		$response         = new stdClass();
		$response->plugin = $product;
		$expected         = new stdClass();
		$expected->icons  = array(
			'svg' => $base . '.svg',
			'1x'  => $base . '-128x128.png',
			'2x'  => $base . '-256x256.png',
		);
		$expected->plugin = $product;

		$this->assertEquals( $expected, $subject->add_icons_on_response( $response, $product ) );
	}

	/**
	 * @test
	 * @group installer-526
	 */
	public function it_fallsback_on_plugin_name_when_plugin_is_not_found_by_id_() {
		$installer = $this->get_installer_mock();
		$subject   = new OTGS_Installer_Icons( $installer );
		$plugin_name   = 'wpml-media';
		$plugin_id = 'wpml-media/plugin.php';
		$plugin_folder = 'folder';

		$installer_url = 'http://something/';
		$installer->method( 'plugin_url' )
		          ->willReturn( $installer_url );

		$installer->method( 'get_repositories' )
		          ->willReturn( array( 'wpml' => array(), 'toolset' => array() ) );

		$repository = 'wpml';

		$installer->settings['repositories'][ $repository ]['data']['products-map'][ $plugin_name ] = $plugin_folder;

		$base             = $installer_url . '/../icons/plugin-icons/' . $repository . '/' . $plugin_folder . '/icon';
		$response         = new stdClass();
		$response->plugin = $plugin_id;
		$expected         = new stdClass();
		$expected->icons  = array(
			'svg' => $base . '.svg',
			'1x'  => $base . '-128x128.png',
			'2x'  => $base . '-256x256.png',
		);
		$expected->plugin = $plugin_id;

		$this->assertEquals( $expected, $subject->add_icons_on_response( $response, $plugin_name ) );
	}

	/**
	 * @test
	 */
	public function it_does_not_add_icons_on_response_if_product_is_not_found() {
		$installer        = $this->get_installer_mock();
		$subject          = new OTGS_Installer_Icons( $installer );
		$response         = new stdClass();
		$response->plugin = 'some-plugin';

		$installer->method( 'get_repositories' )
		          ->willReturn( array( 'wpml' => array(), 'toolset' => array() ) );

		$this->assertEquals( $response, $subject->add_icons_on_response( $response, 'some-id' ) );
	}

	public function get_installer_mock() {
		return $this->getMockBuilder( 'WP_Installer' )
		            ->setMethods( array( 'plugin_url', 'get_repositories' ) )
		            ->disableOriginalConstructor()
		            ->getMock();
	}
}