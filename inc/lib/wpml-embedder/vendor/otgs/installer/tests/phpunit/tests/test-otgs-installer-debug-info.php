<?php

/**
 * Class Test_OTGS_Installer_Debug_Info
 *
 * @group installer-501
 * @group debug-installer
 */
class Test_OTGS_Installer_Debug_Info extends OTGS_TestCase {

	/**
	 * @test
	 */
	public function it_adds_hooks() {
		$installer = $this->getMockBuilder( 'WP_Installer' )
			->disableOriginalConstructor()
			->getMock();

		$products_config_storage = $this->createMock(OTGS_Products_Config_Db_Storage::class);

		$subject = new OTGS_Installer_Debug_Info( $installer, $products_config_storage );
		\WP_Mock::expectFilterAdded( 'icl_get_extra_debug_info', array( $subject, 'add_installer_config_in_debug_information' ) );
		$subject->add_hooks();
	}

	/**
	 * @test
	 */
	public function it_adds_installer_info() {
		global $wp_installer_instances;

		$installer = $this->getMockBuilder( 'WP_Installer' )
			->setMethods( array( 'get_settings', 'version', 'get_repositories' ) )
		                  ->disableOriginalConstructor()
		                  ->getMock();

		$version = '1.0';
		$wpml_api_url = 'api.wpml.org';
		$toolset_api_url = 'api.toolset.com';
		$bucket_url      = 'https://bucket_url.com';

		$products_config_storage = $this->createMock(OTGS_Products_Config_Db_Storage::class);
		$products_config_storage->method('get_repository_products_url')
		     ->withConsecutive(['wpml'], ['toolset'])
		     ->willReturnOnConsecutiveCalls($bucket_url, null);


		$repositories_settings = array(
			'wpml' => array(
				'subscription' => array(),
			),
			'toolset' => array(),
		);

		$repositories = array(
			'wpml' => array(
				'api-url' => $wpml_api_url,
			),
			'toolset' => array(
				'api-url' => $toolset_api_url,
			),
		);

		$settings = array( 'repositories' => $repositories_settings );

		$installer->method( 'get_settings' )
			->willReturn( $settings );

		$installer->method( 'get_repositories' )
		          ->willReturn( $repositories );

		$installer->method( 'version' )
			->willReturn( $version );

		$expected = array(
			'installer' => array(
				'version' => $version,
				'repositories' => array(
					'wpml' => array(
						'api-url' => $wpml_api_url,
						'subscription' => array(),
						'bucket-url' => $bucket_url,
					),
					'toolset' => array(
						'api-url' => $toolset_api_url,
						'subscription' => '',
						'bucket-url' => 'not assigned',
					)
				),
				'instances' => $wp_installer_instances,
			),
		);

		$subject = new OTGS_Installer_Debug_Info( $installer, $products_config_storage );
		$this->assertEquals( $expected, $subject->add_installer_config_in_debug_information( array() ) );
	}
}