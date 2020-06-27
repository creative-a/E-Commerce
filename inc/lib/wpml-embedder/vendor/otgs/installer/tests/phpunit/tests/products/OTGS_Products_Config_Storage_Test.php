<?php

use PHPUnit\Framework\TestCase;

class OTGS_Products_Config_Storage_Test extends TestCase {
	public function setUp() {
		\WP_Mock::setUp();
	}

	public function tearDown() {
		\WP_Mock::tearDown();
	}

	/**
	 * @test
	 */
	public function it_returns_products_url_for_repository() {
		$repository_id = 'test_repository';
		$expected_products_config = [$repository_id => 'products_url'];

		\WP_Mock::userFunction( 'get_option', array(
			'args' => [OTGS_Products_Config_Db_Storage::PRODUCTS_CONFIG_KEY, array()],
			'return' => $expected_products_config,
		) );

		$subject = new OTGS_Products_Config_Db_Storage();

		$result = $subject->get_repository_products_url( $repository_id );
		$this->assertEquals( $expected_products_config[$repository_id], $result );
	}

	/**
	 * @test
	 */
	public function it_returns_null_when_products_url_for_repository_not_set() {
		$repository_id = 'test_repository';
		$not_stored_repository_id = 'not_stpred_test_repository';
		$expected_products_config = [$repository_id => 'products_url'];

		\WP_Mock::userFunction( 'get_option', array(
			'args' => [OTGS_Products_Config_Db_Storage::PRODUCTS_CONFIG_KEY, array()],
			'return' => $expected_products_config,
		) );

		$subject = new OTGS_Products_Config_Db_Storage();

		$result = $subject->get_repository_products_url( $not_stored_repository_id );
		$this->assertEquals( null, $result );
	}

	/**
	 * @test
	 */
	public function it_stores_products_url_for_repository() {
		$repository_id = 'test_repository';
		$expected_products_config = [$repository_id => 'products_url'];
		$new_repository_url = 'new_products_url';

		\WP_Mock::userFunction( 'get_option', array(
			'args' => [OTGS_Products_Config_Db_Storage::PRODUCTS_CONFIG_KEY, array()],
			'return' => $expected_products_config,
		) );

		\WP_Mock::userFunction( 'update_option', array(
			'args' => [OTGS_Products_Config_Db_Storage::PRODUCTS_CONFIG_KEY, [ $repository_id => $new_repository_url], 'yes' ],
			'times' => 1,
			'return' => true,
		) );

		$subject = new OTGS_Products_Config_Db_Storage();
		$result = $subject->store_repository_products_url( $repository_id, $new_repository_url);
		$this->assertTrue( $result );
	}

	/**
	 * @test
	 */
	public function it_clears_products_url_for_repository() {
		$repository_id1 = 'test_repository1';
		$repository_id2 = 'test_repository2';
		$repository2_url = 'products_url2';
		$expected_products_config = [
			$repository_id1 => 'products_url1',
			$repository_id2 => $repository2_url,
		];

		\WP_Mock::userFunction( 'get_option', array(
			'args' => [OTGS_Products_Config_Db_Storage::PRODUCTS_CONFIG_KEY, array()],
			'return' => $expected_products_config,
		) );

		\WP_Mock::userFunction( 'update_option', array(
			'args' => [OTGS_Products_Config_Db_Storage::PRODUCTS_CONFIG_KEY, [ $repository_id2 => $repository2_url], 'yes' ],
			'times' => 1,
			'return' => true,
		) );

		$subject = new OTGS_Products_Config_Db_Storage();
		$result = $subject->clear_repository_products_url( $repository_id1 );
		$this->assertTrue( $result );
	}

	/**
	 * @test
	 */
	public function it_returns_false_when_unable_to_store_products_url_for_repository() {
		$repository_id = 'test_repository';
		$expected_products_config = [$repository_id => 'products_url'];
		$new_repository_url = 'new_products_url';

		\WP_Mock::userFunction( 'get_option', array(
			'args' => [OTGS_Products_Config_Db_Storage::PRODUCTS_CONFIG_KEY, array()],
			'return' => $expected_products_config,
		) );

		\WP_Mock::userFunction( 'update_option', array(
			'args' => [OTGS_Products_Config_Db_Storage::PRODUCTS_CONFIG_KEY, [ $repository_id => $new_repository_url], 'yes' ],
			'times' => 1,
			'return' => false,
		) );

		$subject = new OTGS_Products_Config_Db_Storage();
		$result = $subject->store_repository_products_url( $repository_id, $new_repository_url);
		$this->assertFalse( $result );
	}
}
