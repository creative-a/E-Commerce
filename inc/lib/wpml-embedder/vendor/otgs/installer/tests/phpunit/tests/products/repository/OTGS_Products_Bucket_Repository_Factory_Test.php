<?php

use PHPUnit\Framework\TestCase;

class OTGS_Products_Bucket_Repository_Factory_Test extends TestCase {
	/**
	 * @test
	 */
	public function it_creates_repository() {
		$api_urls = [
			'wpml' => 'https://dummy_address',
			'toolset' => 'https://dummy_address'
		];
		$result = OTGS_Products_Bucket_Repository_Factory::create( $api_urls);

		$this->assertInstanceOf(OTGS_Products_Bucket_Repository::class, $result);
	}
}
