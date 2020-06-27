<?php

/**
 * Class Test_OTGS_Installer_Repository
 *
 * @group installer-upgrade
 * @group installer-487
 */
class Test_OTGS_Installer_Repository extends OTGS_TestCase {

	/**
	 * @test
	 */
	public function it_gets_product_by_subscription_type() {
		$subscription = $this->getMockBuilder( 'OTGS_Installer_Subscription' )
		                     ->setMethods( array( 'get_type' ) )
		                     ->disableOriginalConstructor()
		                     ->getMock();

		$subscription->method( 'get_type' )
		             ->willReturn( 4 );

		$package = $this->getMockBuilder( 'OTGS_Installer_Package' )
		                ->setMethods( array(
			                'get_product_by_subscription_type',
			                'get_product_by_subscription_type_equivalent',
			                'get_product_by_subscription_type_on_upgrades'
		                ) )
		                ->disableOriginalConstructor()
		                ->getMock();

		$product = $this->getMockBuilder( 'OTGS_Installer_Package_Product' )
		                ->disableOriginalConstructor()
		                ->getMock();

		$package->method( 'get_product_by_subscription_type' )
		        ->with( 4 )
		        ->willReturn( $product );

		$packages = array( $package );

		$params = array(
			'id'           => 'wpml',
			'subscription' => $subscription,
			'packages'     => $packages,
		);

		$subject = new OTGS_Installer_Repository( $params );
		$this->assertEquals( $product, $subject->get_product_by_subscription_type() );
	}

	/**
	 * @test
	 */
	public function it_gets_product_by_subscription_type_equivalent() {
		$subscription = $this->getMockBuilder( 'OTGS_Installer_Subscription' )
		                     ->setMethods( array( 'get_type' ) )
		                     ->disableOriginalConstructor()
		                     ->getMock();

		$subscription->method( 'get_type' )
		             ->willReturn( 4 );

		$package = $this->getMockBuilder( 'OTGS_Installer_Package' )
		                ->setMethods( array(
			                'get_product_by_subscription_type',
			                'get_product_by_subscription_type_equivalent',
			                'get_product_by_subscription_type_on_upgrades'
		                ) )
		                ->disableOriginalConstructor()
		                ->getMock();

		$product = $this->getMockBuilder( 'OTGS_Installer_Package_Product' )
		                ->disableOriginalConstructor()
		                ->getMock();

		$package->method( 'get_product_by_subscription_type_equivalent' )
		        ->with( 4 )
		        ->willReturn( $product );

		$packages = array( $package );
		$params   = array(
			'id'           => 'wpml',
			'subscription' => $subscription,
			'packages'     => $packages,
		);

		$subject = new OTGS_Installer_Repository( $params );
		$this->assertEquals( $product, $subject->get_product_by_subscription_type_equivalent() );
	}

	/**
	 * @test
	 */
	public function it_gets_product_by_subscription_type_on_upgrades() {
		$subscription = $this->getMockBuilder( 'OTGS_Installer_Subscription' )
		                     ->setMethods( array( 'get_type' ) )
		                     ->disableOriginalConstructor()
		                     ->getMock();

		$subscription->method( 'get_type' )
		             ->willReturn( 4 );

		$package = $this->getMockBuilder( 'OTGS_Installer_Package' )
		                ->setMethods( array(
			                'get_product_by_subscription_type',
			                'get_product_by_subscription_type_equivalent',
			                'get_product_by_subscription_type_on_upgrades'
		                ) )
		                ->disableOriginalConstructor()
		                ->getMock();

		$product = $this->getMockBuilder( 'OTGS_Installer_Package_Product' )
		                ->disableOriginalConstructor()
		                ->getMock();

		$package->method( 'get_product_by_subscription_type_on_upgrades' )
		        ->with( 4 )
		        ->willReturn( $product );

		$packages = array( $package );
		$params   = array(
			'id'           => 'wpml',
			'subscription' => $subscription,
			'packages'     => $packages,
		);

		$subject = new OTGS_Installer_Repository( $params );
		$this->assertEquals( $product, $subject->get_product_by_subscription_type_on_upgrades() );
	}

	/**
	 * @test
	 */
	public function it_did_not_find_any_product() {
		$subscription = $this->getMockBuilder( 'OTGS_Installer_Subscription' )
		                     ->setMethods( array( 'get_type' ) )
		                     ->disableOriginalConstructor()
		                     ->getMock();

		$subscription->method( 'get_type' )
		             ->willReturn( 4 );

		$package = $this->getMockBuilder( 'OTGS_Installer_Package' )
		                ->setMethods( array(
			                'get_product_by_subscription_type',
			                'get_product_by_subscription_type_equivalent',
			                'get_product_by_subscription_type_on_upgrades'
		                ) )
		                ->disableOriginalConstructor()
		                ->getMock();

		$package->method( 'get_product_by_subscription_type_on_upgrades' )
		        ->with( 4 )
		        ->willReturn( null );

		$packages = array( $package );
		$params   = array(
			'id'           => 'wpml',
			'subscription' => $subscription,
			'packages'     => $packages,
		);

		$subject = new OTGS_Installer_Repository( $params );
		$this->assertEquals( null, $subject->get_product_by_subscription_type_on_upgrades() );
	}

	/**
	 * @test
	 * @group installer-271
	 */
	public function it_gets_default_api_url() {
		$api_url = 'https://api.wpml.org';
		$subject = new OTGS_Installer_Repository( array( 'api_url' => $api_url ) );
		$this->assertEquals( $api_url, $subject->get_api_url() );
	}

	/**
	 * @test
	 * @group installer-271
	 */
	public function it_gets_non_ssl_api_url() {
		$api_url = 'https://api.wpml.org';
		$non_ssl_api_url = 'http://api.wpml.org';

		\WP_Mock::userFunction( 'wp_parse_url', array(
			'return' => function( $url ) {
				return parse_url( $url );
			}
		) );

		$subject = new OTGS_Installer_Repository( array( 'api_url' => $api_url ) );
		$this->assertEquals( $non_ssl_api_url, $subject->get_api_url( false ) );
	}
}