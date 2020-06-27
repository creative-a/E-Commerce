<?php

use PHPUnit\Framework\TestCase;

class OTGS_Products_Config_Xml_Test extends TestCase {

	/**
	 * @test
	 */
	public function it_gets_product_api_urls() {

		$subject = new OTGS_Products_Config_Xml( TEST_ROOT_DIR . '/data/repositories.xml' );

		$urls = $subject->get_products_api_urls();

		$this->assertEquals(
			[
				'wpml'    => 'https://api.wpml.org/',
				'toolset' => 'https://api.toolset.com/'
			],
			$urls );
	}

	/**
	 * @test
	 */
	public function it_gets_product_url() {
		$subject = new OTGS_Products_Config_Xml( TEST_ROOT_DIR . '/data/repositories.xml' );

		$this->assertEquals( 'http://mocked_wpml_products_url.com/xyz', $subject->get_repository_products_url( 'wpml' ) );
	}
}
