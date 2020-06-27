<?php

use PHPUnit\Framework\TestCase;

class OTGS_Installer_Products_Parser_Test extends TestCase {

	/**
	 * @var OTGS_Installer_Logger_Storage
	 */
	private $logger_storage;

	/**
	 * @var WP_Installer_Channels
	 */
	private $installer_channels;

	public function setUp() {
		\WP_Mock::setUp();
	}

	public function tearDown() {
		\WP_Mock::tearDown();
	}

	public function __construct() {
		$this->logger_storage     = $this->createMock( OTGS_Installer_Logger_Storage::class );
		$this->installer_channels = $this->createMock( WP_Installer_Channels::class );
		parent::__construct();
	}

	/**
	 * @test
	 */
	public function it_parse_products_response() {
		$api_response   = 'api response';
		$response_body  = '
		{
		  "downloads": {
		    "plugins": {
		      "sitepress-multilingual-cms": {
		        "name": "WPML Multilingual CMS",
		        "slug": "sitepress-multilingual-cms",
		        "description": "Description\r\n",
		        "changelog": "Changelog",
		        "version": "4.2.7.1",
		        "tested": "5.1.1",
		        "date": "2019-06-17 07:28:56",
		        "url": "https:\/\/wpml.org\/?download=6088&version=4.2.7.1",
		        "free-on-wporg": 0,
		        "fallback-free-on-wporg": 0,
		        "external-repo": "",
		        "basename": "sitepress-multilingual-cms",
		        "group": "add-on",
		        "recommended": 1,
		        "glue_check_type": "constant",
		        "glue_check_value": "ICL_SITEPRESS_VERSION",
		        "short_description": "",
		        "channels": {
		          "beta": {
		            "changelog": "Changelog",
		            "version": "4.3.0-b.6",
		            "channel": 2,
		            "date": "2019-08-16 15:03:32",
		            "url": "https:\/\/wpml.org\/?download=6088&version=4.3.0-b.6"
		          }
		        }
		      },
		      "wpml-string-translation": {
		        "name": "String Translation",
		        "slug": "wpml-string-translation",
		        "description": "Description\r\n",
		        "changelog": "Changelog",
		        "version": "2.10.5.1",
		        "tested": "5.1.1",
		        "date": "2019-06-17 07:28:59",
		        "url": "https:\/\/wpml.org\/?download=6092&version=2.10.5.1",
		        "free-on-wporg": 0,
		        "fallback-free-on-wporg": 0,
		        "external-repo": "",
		        "basename": "wpml-string-translation",
		        "group": "add-on",
		        "recommended": 1,
		        "glue_check_type": "",
		        "glue_check_value": "",
		        "short_description": "",
		        "channels": {
		          "beta": {
		            "changelog": "Changelog",
		            "version": "3.0.0-b.6",
		            "channel": 2,
		            "date": "2019-08-16 15:03:27",
		            "url": "https:\/\/wpml.org\/?download=6092&version=3.0.0-b.6"
		          }
		        }
		      }
		    }
		  }
		}
		';
		$products_url   = 'http://url_to_products.json';
		$repository_id  = 'repository_id';
		$products_array = json_decode( $response_body, true );

		$expected_products = array(
			'downloads' =>
				array(
					'plugins' =>
						array(
							'sitepress-multilingual-cms' =>
								array(
									'name'                   => 'WPML Multilingual CMS',
									'slug'                   => 'sitepress-multilingual-cms',
									'description'            => "Description\r\n",
									'changelog'              => 'Changelog',
									'version'                => '4.2.7.1',
									'tested'                 => '5.1.1',
									'date'                   => '2019-06-17 07:28:56',
									'url'                    => 'https://wpml.org/?download=6088&version=4.2.7.1',
									'free-on-wporg'          => 0,
									'fallback-free-on-wporg' => 0,
									'external-repo'          => '',
									'basename'               => 'sitepress-multilingual-cms',
									'group'                  => 'add-on',
									'recommended'            => 1,
									'glue_check_type'        => 'constant',
									'glue_check_value'       => 'ICL_SITEPRESS_VERSION',
									'short_description'      => '',
									'channels'               =>
										array(
											'beta' =>
												array(
													'changelog' => 'Changelog',
													'version'   => '4.3.0-b.6',
													'channel'   => 2,
													'date'      => '2019-08-16 15:03:32',
													'url'       => 'https://wpml.org/?download=6088&version=4.3.0-b.6',
												),
										),
									'release-notes'          => '',
								),
							'wpml-string-translation'    =>
								array(
									'name'                   => 'String Translation',
									'slug'                   => 'wpml-string-translation',
									'description'            => "Description\r\n",
									'changelog'              => 'Changelog',
									'version'                => '2.10.5.1',
									'tested'                 => '5.1.1',
									'date'                   => '2019-06-17 07:28:59',
									'url'                    => 'https://wpml.org/?download=6092&version=2.10.5.1',
									'free-on-wporg'          => 0,
									'fallback-free-on-wporg' => 0,
									'external-repo'          => '',
									'basename'               => 'wpml-string-translation',
									'group'                  => 'add-on',
									'recommended'            => 1,
									'glue_check_type'        => '',
									'glue_check_value'       => '',
									'short_description'      => '',
									'channels'               =>
										array(
											'beta' =>
												array(
													'changelog' => 'Changelog',
													'version'   => '3.0.0-b.6',
													'channel'   => 2,
													'date'      => '2019-08-16 15:03:27',
													'url'       => 'https://wpml.org/?download=6092&version=3.0.0-b.6',
												),
										),
									'release-notes'          => '',
								),
						),
				),
		);

		\WP_Mock::userFunction( 'wp_remote_retrieve_body', [
			'args'   => [ $api_response ],
			'return' => $response_body,
		] );

		$this->installer_channels
			->method( 'filter_downloads_by_channel' )
			->with(
				$this->equalTo( $repository_id ),
				$this->identicalTo( $products_array['downloads'] )
			)
			->willReturn( $products_array['downloads'] );

		$subject  = new OTGS_Installer_Products_Parser( $this->installer_channels, $this->logger_storage );
		$products = $subject->get_products_from_response( $products_url, $repository_id, $api_response );
		$this->assertEquals( $expected_products, $products );
	}

	/**
	 * @test
	 *
	 */
	public function it_throws_exception_when_response_invalid() {
		$api_response   = 'api response';
		$response_body  = '';
		$products_url   = 'http://url_to_products.json';
		$repository_id  = 'repository_id';

		\WP_Mock::userFunction( 'wp_remote_retrieve_body', [
			'args'   => [ $api_response ],
			'return' => $response_body,
		] );

		$this->expectException(OTGS_Installer_Products_Parsing_Exception::class);

		$subject  = new OTGS_Installer_Products_Parser( $this->installer_channels, $this->logger_storage );
		$subject->get_products_from_response( $products_url, $repository_id, $api_response );
	}

	/**
	 * @test
	 *
	 */
	public function it_store_log_when_plugin_config_is_invalid() {
		$api_response   = 'api response';
		$response_body  = '
		{
		  "downloads": {
		    "plugins": {
		      "sitepress-multilingual-cms": {
		        "slug": "sitepress-multilingual-cms",
		        "description": "Description\r\n",
		        "changelog": "Changelog",
		        "version": "4.2.7.1",
		        "tested": "5.1.1",
		        "date": "2019-06-17 07:28:56",
		        "url": "https:\/\/wpml.org\/?download=6088&version=4.2.7.1",
		        "free-on-wporg": 0,
		        "fallback-free-on-wporg": 0,
		        "external-repo": "",
		        "basename": "sitepress-multilingual-cms",
		        "group": "add-on",
		        "recommended": 1,
		        "glue_check_type": "constant",
		        "glue_check_value": "ICL_SITEPRESS_VERSION",
		        "short_description": "",
		        "channels": {
		          "beta": {
		            "changelog": "Changelog",
		            "version": "4.3.0-b.6",
		            "channel": 2,
		            "date": "2019-08-16 15:03:32",
		            "url": "https:\/\/wpml.org\/?download=6088&version=4.3.0-b.6"
		          }
		        }
		      }
		    }
		  }
		}
		';
		$products_url   = 'http://url_to_products.json';
		$repository_id  = 'repository_id';
		$products_array = json_decode( $response_body, true );

		\WP_Mock::userFunction( 'wp_remote_retrieve_body', [
			'args'   => [ $api_response ],
			'return' => $response_body,
		] );

		$this->installer_channels
			->method( 'filter_downloads_by_channel' )
			->with(
				$this->equalTo( $repository_id ),
				$this->identicalTo( $products_array['downloads'] )
			)
			->willReturn( $products_array['downloads'] );

		$expected_log = new OTGS_Installer_Log();
		$expected_log->set_request_url( $products_url )
		    ->set_component( OTGS_Installer_Logger_Storage::COMPONENT_PRODUCTS_PARSING )
		    ->set_response( 'Information about versions of sitepress-multilingual-cms are invalid. It may be a temporary communication problem, please check for updates again.' );

		$this->logger_storage
			->expects($this->once())
			->method( 'add' )
			->with(
				$this->equalTo($expected_log)
			);
		$this->expectException(OTGS_Installer_Products_Parsing_Exception::class);
		$subject  = new OTGS_Installer_Products_Parser( $this->installer_channels, $this->logger_storage );
		$subject->get_products_from_response( $products_url, $repository_id, $api_response );
	}

}