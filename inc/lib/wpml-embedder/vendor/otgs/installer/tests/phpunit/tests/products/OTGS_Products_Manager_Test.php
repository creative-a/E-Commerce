<?php

use PHPUnit\Framework\TestCase;

class OTGS_Products_Manager_Test extends TestCase {

	/**
	 * @var OTGS_Products_Config_Db_Storage
	 */
	private $products_config_storage;

	/**
	 * @var OTGS_Products_Bucket_Repository
	 */
	private $products_bucket_repository;

	/**
	 * @var WP_Installer_Channels
	 */
	private $installer_channels;

	/**
	 * @var OTGS_Products_Config_Xml
	 */
	private $products_config_xml;

	/**
	 * @var OTGS_Installer_Logger_Storage
	 */
	private $logger_storage;

	public function __construct() {
		$this->products_config_storage = $this->createMock(OTGS_Products_Config_Db_Storage::class);
		$this->products_bucket_repository = $this->createMock(OTGS_Products_Bucket_Repository::class);
		$this->products_config_xml = $this->createMock(OTGS_Products_Config_Xml::class);
		$this->installer_channels = $this->createMock(WP_Installer_Channels::class);
		$this->logger_storage = $this->createMock(OTGS_Installer_Logger_Storage::class);

		parent::__construct();
	}

	/**
	 * @test
	 */
	public function it_fetches_products_url_from_endpoint() {
		$site_key = 'test_site_key';
		$site_url = 'http://site-url.com';
		$repository_id = 'test_repository';
		$repository_products_url = 'products_url';

		$this->products_bucket_repository
			->method('get_products_bucket_url')
	        ->with(
	            $this->equalTo($repository_id),
	            $this->equalTo($site_key)
	        )
			->willReturn($repository_products_url);

		$this->products_config_storage
			->expects($this->once())
			->method('store_repository_products_url')
			->with(
			    $this->equalTo($repository_id),
			    $this->equalTo($repository_products_url)
			);

		$subject = $this->get_subject();

		$subject->get_products_url_from_otgs( $repository_id, $site_key, $site_url );
	}

	/**
	 * @test
	 */
	public function it_logs_exception_when_unable_to_fetch_products_url_from_endpoint() {
		$site_key = 'test_site_key';
		$site_url = 'http://site-url.com';
		$repository_id = 'test_repository';

		$error_message = 'Unable to connect to url.';

		$this->products_bucket_repository
			->method('get_products_bucket_url')
			->with(
				$this->equalTo($repository_id),
				$this->equalTo($site_key),
				$this->equalTo($site_url)
			)
			->will( $this->throwException( new Exception( $error_message ) ) );

		$subject = $this->get_subject();

		$expected_log = new OTGS_Installer_Log();
		$expected_log->set_component(OTGS_Installer_Logger_Storage::COMPONENT_PRODUCTS_URL);
		$expected_log->set_response( "Installer cannot contact our updates server to get information about the available products of $repository_id and check for new versions. Error message: $error_message" );

		$this->logger_storage
			->expects($this->once())
			->method('add')
			->with(
				$this->equalTo( $expected_log )
			);

		$subject->get_products_url_from_otgs( $repository_id, $site_key, $site_url );
	}

	/**
	 * @test
	 */
	public function it_returns_product_url_from_local_config() {
		$site_key = 'test_site_key';
		$site_url = 'http://site-url.com';
		$repository_id = 'test_repository';
		$repository_products_url = 'products_url';

		$this->products_config_storage
			->method('get_repository_products_url')
			->with(
				$this->equalTo($repository_id)
			)
			->willReturn($repository_products_url);

		$this->installer_channels
			->method( 'get_channel' )
			->with( $repository_id )
			->willReturn( WP_Installer_Channels::CHANNEL_PRODUCTION );

		$subject = $this->get_subject();

		$result = $subject->get_products_url( $repository_id, $site_key, $site_url, false );
		$this->assertEquals( $repository_products_url, $result );
	}

	/**
	 * @test
	 */
	public function it_allows_product_url_to_be_overriden_by_constant() {
		$site_key = 'test_site_key';
		$site_url = 'http://site-url.com';
		$repository_id = 'repository_id';

		$expected_products_url = 'http://some-other-url.com/xyz';

		define( "OTGS_INSTALLER_REPOSITORY_ID_PRODUCTS", $expected_products_url );

		$subject = $this->get_subject();
		$result = $subject->get_products_url( $repository_id, $site_key, $site_url, false );

		$this->assertEquals( $expected_products_url, $result );
	}

	/**
	 * @test
	 */
	public function it_does_not_return_product_url_from_local_config_when_site_key_empty() {
		$site_key =  false;
		$site_url = 'http://site-url.com';
		$repository_id = 'test_repository';
		$repository_products_url = 'products_url';

		$this->products_config_storage = $this->createMock(OTGS_Products_Config_Db_Storage::class);
		$this->products_bucket_repository = $this->createMock(OTGS_Products_Bucket_Repository::class);
		$this->products_config_xml = $this->createMock(OTGS_Products_Config_Xml::class);

		$this->products_config_storage
			->method('get_repository_products_url')
			->with(
				$this->equalTo($repository_id)
			)
			->willReturn($repository_products_url);

		$subject = $this->get_subject();

		$result = $subject->get_products_url( $repository_id, $site_key, $site_url, false );
		$this->assertEquals( null, $result );
	}

	/**
	 * @test
	 */
	public function it_returns_product_url_from_endpoint() {
		$site_key = 'test_site_key';
		$site_url = 'http://site-url.com';
		$repository_id = 'test_repository';
		$repository_products_url = 'products_url';

		$this->products_config_storage
			->method('get_repository_products_url')
			->with(
				$this->equalTo($repository_id)
			)
			->willReturn(null);

		$this->products_bucket_repository
			->method('get_products_bucket_url')
			->with(
				$this->equalTo($repository_id),
				$this->equalTo($site_key),
				$this->equalTo($site_url)
			)
			->willReturn($repository_products_url);

		$this->installer_channels
			->method( 'get_channel' )
			->with( $repository_id )
			->willReturn( WP_Installer_Channels::CHANNEL_PRODUCTION );

		$subject = $this->get_subject();

		$result = $subject->get_products_url( $repository_id, $site_key, $site_url, false );
		$this->assertEquals( $repository_products_url, $result );
	}

	/**
	 * @test
	 */
	public function it_returns_product_url_from_local_configuration_file() {
		$site_key = 'test_site_key';
		$site_url = 'http://site-url.com';
		$repository_id = 'test_repository';
		$repository_products_url = 'products_url';

		$this->products_config_storage
			->method('get_repository_products_url')
			->with(
				$this->equalTo($repository_id)
			)
			->willReturn(null);

		$this->products_bucket_repository
			->method('get_products_bucket_url')
			->with(
				$this->equalTo($repository_id),
				$this->equalTo($site_key),
				$this->equalTo($site_url)
			)
			->willReturn(null);

		$this->products_config_xml
			->method('get_repository_products_url')
			->with(
				$this->equalTo($repository_id)
			)
			->willReturn($repository_products_url);

		$subject = $this->get_subject();

		$result = $subject->get_products_url( $repository_id, $site_key, $site_url, false );
		$this->assertEquals( $repository_products_url, $result );
	}

	/**
	 * @test
	 */
	public function it_returns_product_url_from_xml_if_bucket_is_bypassed() {
		$site_key = 'test_site_key';
		$site_url = 'http://site-url.com';
		$repository_id = 'test_repository';
		$local_products_url = 'local_products_url';

		$this->products_config_storage
			->expects( $this->never() )
			->method('get_repository_products_url')
			->with(
				$this->equalTo($repository_id)
			);

		$this->products_config_xml
			->method('get_repository_products_url')
			->with(
				$this->equalTo($repository_id)
			)
			->willReturn($local_products_url);

		$subject = $this->get_subject();

		$this->assertEquals(
			$local_products_url,
			$subject->get_products_url( $repository_id, $site_key, $site_url, true )
		);
	}

	/**
	 * @test
	 */
	public function it_returns_product_url_from_xml_if_channel_not_production() {
		$site_key = 'test_site_key';
		$site_url = 'http://site-url.com';
		$repository_id = 'test_repository';
		$local_products_url = 'local_products_url';

		$this->installer_channels
			->method( 'get_channel' )
			->with( $repository_id )
			->willReturn( WP_Installer_Channels::CHANNEL_BETA );

		$this->products_bucket_repository
			->expects( $this->never() )
			->method('get_products_bucket_url')
			->with(
				$this->equalTo($repository_id),
				$this->equalTo($site_key),
				$this->equalTo($site_url)
			);

		$this->products_config_storage
			->expects( $this->never() )
			->method('get_repository_products_url')
			->with(
				$this->equalTo($repository_id)
			);

		$this->products_config_xml
			->method('get_repository_products_url')
			->with(
				$this->equalTo($repository_id)
			)
			->willReturn($local_products_url);

		$subject = $this->get_subject();

		$this->assertEquals(
			$local_products_url,
			$subject->get_products_url( $repository_id, $site_key, $site_url, false )
		);
	}

	/**
	 * @return OTGS_Products_Manager
	 */
	private function get_subject() {
		$subject = new OTGS_Products_Manager(
			$this->products_config_storage,
			$this->products_bucket_repository,
			$this->products_config_xml,
			$this->installer_channels,
			$this->logger_storage
		);

		return $subject;
	}

}
