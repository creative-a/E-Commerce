<?php

namespace OTGS\Installer\Rest;

use \tad\FunctionMocker\FunctionMocker;
use \Mockery;
use \WP_Mock;
use \WP_Installer;

class Test_Push extends \OTGS_TestCase {

	/**
	 * @test
	 */
	public function it_registers_route() {
		WP_Mock::userFunction(
			'register_rest_route',
			[
				'args' => [
					Push::REST_NAMESPACE,
					'push/fetch-subscription',
					[
						'methods'  => 'GET',
						'callback' => 'OTGS\Installer\Rest\Push::fetch_subscription',
					],
				],
				'return' => true,
			]
		);

		Push::register_routes();

		$this->assertTrue(true);
	}

	/**
	 * @test
	 */
	public function it_fetches_subscription() {
		$installer = Mockery::mock(\WP_Installer::class);
		$installer->shouldReceive('refresh_subscriptions_data')
		          ->once();

		$last_update_time = 1000;
		$installer->shouldReceive( 'get_last_subscriptions_refresh' )->andReturn( $last_update_time );
		$updateAfter = 10800; // 3 hours;

		$installer->settings = ['last_subscriptions_update' => $last_update_time];
		Mockery::mock('overload:\WP_REST_Response');
		FunctionMocker::replace( 'time', $last_update_time + $updateAfter );

		WP_Mock::userFunction(
			'OTGS_Installer',
			[
				'return' => $installer,
			]
		);

		$result = Push::fetch_subscription();

		$this->assertInstanceOf('\WP_REST_Response', $result);
	}

	/**
	 * @test
	 */
	public function it_does_not_fetches_subscription_if_time_interval_did_not_pass() {
		$installer = Mockery::mock(WP_Installer::class);
		$installer->shouldReceive('refresh_subscriptions_data')
		          ->never();

		$last_update_time = 1000;
		$installer->shouldReceive( 'get_last_subscriptions_refresh' )->andReturn( $last_update_time );
		$updateAfter = 3600; // 1 hour;

		Mockery::mock('overload:\WP_REST_Response');
		FunctionMocker::replace( 'time', $last_update_time + $updateAfter );

		WP_Mock::userFunction(
			'OTGS_Installer',
			[
				'return' => $installer,
			]
		);

		$result = Push::fetch_subscription();

		$this->assertInstanceOf('\WP_REST_Response', $result);
	}

	/**
	 * @test
	 */
	public function it_fetches_subscription_when_last_subscriptions_update_not_set() {
		$installer = Mockery::mock(WP_Installer::class);
		$installer->shouldReceive('refresh_subscriptions_data')
		          ->once();

		$last_update_time = 0;
		$installer->shouldReceive( 'get_last_subscriptions_refresh' )->andReturn( $last_update_time );
		$updateAfter = 10800; // 3 hours;

		$installer->settings = [];
		Mockery::mock('overload:\WP_REST_Response');
		FunctionMocker::replace( 'time', $last_update_time + $updateAfter );

		WP_Mock::userFunction(
			'OTGS_Installer',
			[
				'return' => $installer,
			]
		);

		$result = Push::fetch_subscription();

		$this->assertInstanceOf('\WP_REST_Response', $result);
	}

}