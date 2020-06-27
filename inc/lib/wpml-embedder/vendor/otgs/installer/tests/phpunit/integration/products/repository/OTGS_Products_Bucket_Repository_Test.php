<?php

use PHPUnit\Framework\TestCase;

class OTGS_Products_Bucket_Repository_Test extends TestCase {

	/**
	 * @var OTGS_Installer_API_Mocker
	 */
	private $api_mocker;
	private $repository_id = 'wpml';
	private $api_url = 'http://api.wpml.test';
	private $site_key = 'site-key';
	private $site_url = 'http://site-url.com';

	public function __construct() {
		parent::__construct();
		$this->api_mocker = new OTGS_Installer_API_Mocker();
	}

	function setUp() {
		parent::setUp();
		WP_Mock::setUp();
	}

	function tearDown() {
		WP_Mock::tearDown();
		parent::tearDown();
	}

	/**
	 * @test
	 */
	public function it_returns_bucket_url_for_registered_site() {
		$api_urls = [ $this->repository_id => $this->api_url ];
		$subject = $this->get_subject($api_urls);

		$this->api_mocker->mockEndpointResponse(
			OTGS_Installer_Endpoint_Mock::createPostMock(
				$this->api_url,
				[
					'action' => 'product_bucket_url',
					'site_key' => $this->site_key,
					'site_url' => $this->site_url,
				],
				[
					'body' => 'bucket_wpml.json',
					'response' => [ 'code' => 200 ]
				]
			)
		);

		$bucket_url    = $subject->get_products_bucket_url( $this->repository_id, $this->site_key, $this->site_url );
		$bucket_number = $this->get_bucket_number( $bucket_url );
		$this->assertGreaterThanOrEqual( ICL_REP_BUCKET_MIN, $bucket_number );
		$this->assertLessThanOrEqual( ICL_REP_BUCKET_MAX, $bucket_number );
	}

	/**
	 * @test
	 */
	public function it_returns_same_url_if_called_twice() {
		$api_urls = [ $this->repository_id => $this->api_url ];
		$subject = $this->get_subject($api_urls);

		$this->api_mocker->mockEndpointResponse(
			OTGS_Installer_Endpoint_Mock::createPostMock(
				$this->api_url,
				[
					'action' => 'product_bucket_url',
					'site_key' => $this->site_key,
					'site_url' => $this->site_url,
				],
				[
					'body' => 'bucket_wpml.json',
					'response' => [ 'code' => 200 ]
				]
			)
		);

		$bucket_url_1 = $subject->get_products_bucket_url( $this->repository_id, $this->site_key, $this->site_url );
		$bucket_url_2 = $subject->get_products_bucket_url( $this->repository_id, $this->site_key, $this->site_url );

		$this->assertEquals( $bucket_url_1, $bucket_url_2 );

		$bucket_number = $this->get_bucket_number( $bucket_url_1 );
		$this->assertGreaterThanOrEqual( ICL_REP_BUCKET_MIN, $bucket_number );
		$this->assertLessThanOrEqual( ICL_REP_BUCKET_MAX, $bucket_number );
	}

	/**
	 * @test
	 */
	public function it_does_not_return_products_url_if_response_is_wp_error() {
		$api_urls = [ $this->repository_id => $this->api_url ];
		$subject = $this->get_subject($api_urls);

		$this->api_mocker->mockEndpointResponse(
			OTGS_Installer_Endpoint_Mock::createPostMock(
				$this->api_url,
				[
					'action' => 'product_bucket_url',
					'site_key' => $this->site_key,
					'site_url' => $this->site_url,
				],
				[
					'body' => 'bucket_wpml.json',
					'response' => [ 'code' => 500 ]
				],
				true
			)
		);

		$this->assertNull( $subject->get_products_bucket_url( $this->repository_id, $this->site_key, $this->site_url ) );
	}

	/**
	 * @return OTGS_Products_Bucket_Repository
	 */
	private function get_subject($api_urls) {
		$subject = new OTGS_Products_Bucket_Repository( $api_urls );

		return $subject;
	}

	private function get_bucket_number( $bucket_url ) {
		$bucket_parts = explode( '-', $bucket_url );

		return substr( end( $bucket_parts ), 0, - strlen( '.json' ) );
	}
}