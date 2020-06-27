<?php

class OTGS_Products_Repository_Test extends OTGS_Installer_Integration_Base_Test {

	/**
	 * @test
	 */
	public function it_does_not_refresh_repositories_with_invalid_json() {
		/** @var WP_Installer $subject */
		$subject = $this->get_installer_instance();
		$this->prepare_installer_instance();

		$this->api_mocker->mockEndpointResponse(
			OTGS_Installer_Endpoint_Mock::createGetMock(
				constant('OTGS_INSTALLER_WPML_PRODUCTS'),
				[
					'body' => 'products_wpml_invalid.json',
					'response' => [ 'code' => 200 ]
				]
			)
		);

		$this->api_mocker->mockEndpointResponse(
			OTGS_Installer_Endpoint_Mock::createGetMock(
				constant('OTGS_INSTALLER_TOOLSET_PRODUCTS'),
				[
					'body' => 'products_toolset_invalid.json',
					'response' => [ 'code' => 200 ]
				]
			)
		);

		$subject->refresh_repositories_data();

		$this->assertEmpty(
			$this->wp_installer_settings['repositories']
		);

		$this->assert_log(
			[
				'request_url' => 'http://mocked_toolset_products_url.com/xyz',
				'response' => 'Error in response parsing from http://mocked_toolset_products_url.com/xyz.',
				'component' => 'repositories-fetching'
			],
			0
		);

		$this->assert_log(
			[
				'request_url' => 'http://mocked_wpml_products_url.com/xyz',
				'response' => 'Error in response parsing from http://mocked_wpml_products_url.com/xyz.',
				'component' => 'repositories-fetching'
			],
			1
		);
	}

	/**
	 * @test
	 */
	public function it_does_not_refresh_repository_when_wp_error_in_response() {
		/** @var WP_Installer $subject */
		$subject = $this->get_installer_instance();
		$this->prepare_installer_instance();

		$this->api_mocker->mockEndpointResponse(
			OTGS_Installer_Endpoint_Mock::createGetMock(
				constant('OTGS_INSTALLER_WPML_PRODUCTS'),
				[
					'body' => 'products_wpml_invalid.json',
					'response' => [ 'code' => 200 ]
				],
				true
			)
		);

		$this->api_mocker->mockEndpointResponse(
			OTGS_Installer_Endpoint_Mock::createGetMock(
				constant('OTGS_INSTALLER_TOOLSET_PRODUCTS'),
				[
					'body' => 'products_toolset.json',
					'response' => [ 'code' => 200 ]
				]
			)
		);

		$subject->refresh_repositories_data();

		$this->assertTrue(
			!isset($this->wp_installer_settings['repositories']['wpml'])
		);

		$this->assertEquals(
			'toolset-plugins-for-wordpress-development',
			$this->wp_installer_settings['repositories']['toolset']['data']['id']
		);

		$this->assertEquals(
			'types',
			$this->wp_installer_settings['repositories']['toolset']['data']['downloads']['plugins']['types']['slug']
		);

		$this->assert_log(
			[
				'request_url' => 'http://mocked_wpml_products_url.com/xyz',
				'response' => 'Installer cannot contact our updates server to get information about the available products and check for new versions. If you are seeing this message for the first time, you can ignore it, as it may be a temporary communication problem. If the problem persists and your WordPress admin is slowing down, you can disable automated version checks. Add the following line to your wp-config.php file:<br /><br /><code>define("OTGS_DISABLE_AUTO_UPDATES", true);</code>',
				'component' => 'repositories-fetching'
			],
			0
		);
	}

	/**
	 * @test
	 */
	public function it_does_not_refresh_repository_when_response_code_not_200() {
		/** @var WP_Installer $subject */
		$subject = $this->get_installer_instance();
		$this->prepare_installer_instance();

		$this->api_mocker->mockEndpointResponse(
			OTGS_Installer_Endpoint_Mock::createGetMock(
				constant('OTGS_INSTALLER_WPML_PRODUCTS'),
				[
					'body' => 'products_wpml.json',
					'response' => [ 'code' => 200 ]
				]
			)
		);

		$this->api_mocker->mockEndpointResponse(
			OTGS_Installer_Endpoint_Mock::createGetMock(
				constant('OTGS_INSTALLER_TOOLSET_PRODUCTS'),
				[
					'body' => 'products_toolset.json',
					'response' => [ 'code' => 404 ]
				]
			)
		);

		$subject->refresh_repositories_data();

		$this->assertTrue(
			!isset($this->wp_installer_settings['repositories']['toolset'])
		);

		$this->assertEquals(
			'wpml-the-wordpress-multilingual-plugin',
			$this->wp_installer_settings['repositories']['wpml']['data']['id']
		);

		$this->assertEquals(
			'sitepress-multilingual-cms',
			$this->wp_installer_settings['repositories']['wpml']['data']['downloads']['plugins']['sitepress-multilingual-cms']['slug']
		);

		$this->assert_log(
			[
				'request_url' => 'http://mocked_toolset_products_url.com/xyz',
				'response' => 'Information about new versions is invalid. It may be a temporary communication problem, please check for updates again.',
				'component' => 'repositories-fetching'
			],
			0
		);
	}

	/**
	 * @test
	 */
	public function it_refresh_repositories() {
		/** @var WP_Installer $subject */
		$subject = $this->get_installer_instance();
		$this->prepare_installer_instance();

		$this->api_mocker->mockEndpointResponse(
			OTGS_Installer_Endpoint_Mock::createGetMock(
				constant('OTGS_INSTALLER_WPML_PRODUCTS'),
				[
					'body' => 'products_wpml.json',
					'response' => [ 'code' => 200 ]
				]
			)
		);

		$this->api_mocker->mockEndpointResponse(
			OTGS_Installer_Endpoint_Mock::createGetMock(
				constant('OTGS_INSTALLER_TOOLSET_PRODUCTS'),
				[
					'body' => 'products_toolset.json',
					'response' => [ 'code' => 200 ]
				]
			)
		);

		$subject->refresh_repositories_data();

		$this->assertEmpty( $this->wp_installer_logs );

		$this->assertEquals(
			'wpml-the-wordpress-multilingual-plugin',
			$this->wp_installer_settings['repositories']['wpml']['data']['id']
		);

		$this->assertEquals(
			'sitepress-multilingual-cms',
			$this->wp_installer_settings['repositories']['wpml']['data']['downloads']['plugins']['sitepress-multilingual-cms']['slug']
		);

		$this->assertEquals(
			'toolset-plugins-for-wordpress-development',
			$this->wp_installer_settings['repositories']['toolset']['data']['id']
		);

		$this->assertEquals(
			'types',
			$this->wp_installer_settings['repositories']['toolset']['data']['downloads']['plugins']['types']['slug']
		);
	}

	/**
	 * @test
	 */
	public function it_refresh_repositories_without_invalid_products() {
		/** @var WP_Installer $subject */
		$subject = $this->get_installer_instance();
		$this->prepare_installer_instance();

		$this->api_mocker->mockEndpointResponse(
			OTGS_Installer_Endpoint_Mock::createGetMock(
				constant('OTGS_INSTALLER_WPML_PRODUCTS'),
				[
					'body' => 'products_wpml_invalid_product.json',
					'response' => [ 'code' => 200 ]
				]
			)
		);

		$this->api_mocker->mockEndpointResponse(
			OTGS_Installer_Endpoint_Mock::createGetMock(
				constant('OTGS_INSTALLER_TOOLSET_PRODUCTS'),
				[
					'body' => 'products_toolset.json',
					'response' => [ 'code' => 200 ]
				]
			)
		);

		$subject->refresh_repositories_data();

		$this->assert_log(
			[
				'request_url' => 'http://mocked_wpml_products_url.com/xyz',
				'response' => 'Error in response parsing from http://mocked_wpml_products_url.com/xyz.',
				'component' => 'repositories-fetching'
			],
			0
		);
	}

	private function prepare_installer_instance() {
		$site_url = 'http://site-url.com';

		\WP_Mock::userFunction(
			'get_site_url',
			[
				'return' => $site_url,
			]
		);

		\WP_Mock::userFunction(
			'delete_site_transient',
			[
				'args' => ['update_plugins'],
			]
		);
	}

	private function assert_log( $expectedLog, $logId ) {
		$this->assertEquals(
			$expectedLog['request_url'],
			$this->wp_installer_logs[$logId]['request_url']
		);

		$this->assertEquals(
			$expectedLog['response'],
			$this->wp_installer_logs[$logId]['response']
		);
		$this->assertEquals(
			$expectedLog['component'],
			$this->wp_installer_logs[$logId]['component']
		);
	}
}