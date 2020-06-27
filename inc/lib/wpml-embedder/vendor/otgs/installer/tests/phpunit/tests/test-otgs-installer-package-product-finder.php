<?php

/**
 * Class Test_OTGS_Installer_Package_Product_Finder
 *
 * @group installer-upgrade
 * @group installer-487
 */
class Test_OTGS_Installer_Package_Product_Finder extends OTGS_TestCase {

	/**
	 * @test
	 */
	public function it_finds_product_by_matching_subscription_type() {
		$subject = new OTGS_Installer_Package_Product_Finder();

		$product_found = $this->getMockBuilder( 'OTGS_Installer_Package_Product' )
		                      ->disableOriginalConstructor()
		                      ->getMock();

		$repository = $this->get_repository_mock();
		$repository->method( 'get_product_by_subscription_type' )
			->with( 4 )
			->willReturn( $product_found );

		$subscription = $this->get_subscription_mock();
		$subscription->method( 'get_type' )
			->willReturn( 4 );
		$this->assertEquals( $product_found, $subject->get_product_in_repository_by_subscription( $repository, $subscription ) );
	}

	/**
	 * @test
	 */
	public function it_does_not_find_product_by_matching_subscription_type() {
		$subject = new OTGS_Installer_Package_Product_Finder();

		$repository = $this->get_repository_mock();
		$repository->method( 'get_product_by_subscription_type' )
		           ->with( 4 )
		           ->willReturn( null );

		$subscription = $this->get_subscription_mock();
		$subscription->method( 'get_type' )
		             ->willReturn( 4 );
		$this->assertNull( $subject->get_product_in_repository_by_subscription( $repository, $subscription ) );
	}

	/**
	 * @test
	 */
	public function it_finds_product_by_matching_subscription_type_equivalent() {
		$subject = new OTGS_Installer_Package_Product_Finder();

		$product_found = $this->getMockBuilder( 'OTGS_Installer_Package_Product' )
		                      ->disableOriginalConstructor()
		                      ->getMock();

		$repository = $this->get_repository_mock();
		$repository->method( 'get_product_by_subscription_type' )
		           ->with( 4 )
		           ->willReturn( null );

		$repository->method( 'get_product_by_subscription_type_equivalent' )
		           ->with( 4 )
		           ->willReturn( $product_found );

		$subscription = $this->get_subscription_mock();
		$subscription->method( 'get_type' )
		             ->willReturn( 4 );
		$this->assertEquals( $product_found, $subject->get_product_in_repository_by_subscription( $repository, $subscription ) );
	}

	/**
	 * @test
	 */
	public function it_does_not_find_product_by_matching_subscription_type_equivalent() {
		$subject = new OTGS_Installer_Package_Product_Finder();

		$repository = $this->get_repository_mock();
		$repository->method( 'get_product_by_subscription_type' )
		           ->with( 4 )
		           ->willReturn( null );

		$repository->method( 'get_product_by_subscription_type_equivalent' )
		           ->with( 4 )
		           ->willReturn( null );

		$subscription = $this->get_subscription_mock();
		$subscription->method( 'get_type' )
		             ->willReturn( 4 );
		$this->assertNull( $subject->get_product_in_repository_by_subscription( $repository, $subscription ) );
	}

	/**
	 * @test
	 */
	public function it_finds_product_by_matching_subscription_typeon_upgrades() {
		$subject = new OTGS_Installer_Package_Product_Finder();

		$product_found = $this->getMockBuilder( 'OTGS_Installer_Package_Product' )
		                      ->disableOriginalConstructor()
		                      ->getMock();

		$repository = $this->get_repository_mock();
		$repository->method( 'get_product_by_subscription_type' )
		           ->with( 4 )
		           ->willReturn( null );

		$repository->method( 'get_product_by_subscription_type_equivalent' )
		           ->with( 4 )
		           ->willReturn( null );

		$repository->method( 'get_product_by_subscription_type_on_upgrades' )
		           ->with( 4 )
		           ->willReturn( $product_found );

		$subscription = $this->get_subscription_mock();
		$subscription->method( 'get_type' )
		             ->willReturn( 4 );
		$this->assertEquals( $product_found, $subject->get_product_in_repository_by_subscription( $repository, $subscription ) );
	}

	/**
	 * @test
	 */
	public function it_does_not_find_product_by_matching_subscription_typeon_upgrades() {
		$subject = new OTGS_Installer_Package_Product_Finder();

		$repository = $this->get_repository_mock();
		$repository->method( 'get_product_by_subscription_type' )
		           ->with( 4 )
		           ->willReturn( null );

		$repository->method( 'get_product_by_subscription_type_equivalent' )
		           ->with( 4 )
		           ->willReturn( null );

		$repository->method( 'get_product_by_subscription_type_on_upgrades' )
		           ->with( 4 )
		           ->willReturn( null );

		$subscription = $this->get_subscription_mock();
		$subscription->method( 'get_type' )
		             ->willReturn( 4 );
		$this->assertNull( $subject->get_product_in_repository_by_subscription( $repository, $subscription ) );
	}

	/**
	 * @test
	 */
	public function it_didn_find_any_product() {
		$subject = new OTGS_Installer_Package_Product_Finder();

		$repository = $this->get_repository_mock();
		$repository->method( 'get_product_by_subscription_type' )
		           ->with( 4 )
		           ->willReturn( null );

		$repository->method( 'get_product_by_subscription_type_equivalent' )
		           ->with( 4 )
		           ->willReturn( null );

		$repository->method( 'get_product_by_subscription_type_on_upgrades' )
		           ->with( 4 )
		           ->willReturn( null );

		$subscription = $this->get_subscription_mock();
		$subscription->method( 'get_type' )
		             ->willReturn( 4 );
		$this->assertNull( $subject->get_product_in_repository_by_subscription( $repository, $subscription ) );
	}

	/**
	 * @test
	 */
	public function it_uses_repository_subscription_as_fallback_when_subscription_type_is_not_passed() {
		$subject = new OTGS_Installer_Package_Product_Finder();

		$product_found = $this->getMockBuilder( 'OTGS_Installer_Package_Product' )
		                      ->disableOriginalConstructor()
		                      ->getMock();

		$repository = $this->get_repository_mock();
		$repository->method( 'get_product_by_subscription_type' )
		           ->with( 4 )
		           ->willReturn( null );

		$repository->method( 'get_product_by_subscription_type_equivalent' )
		           ->with( 4 )
		           ->willReturn( null );

		$repository->method( 'get_product_by_subscription_type_on_upgrades' )
		           ->with( 4 )
		           ->willReturn( $product_found );

		$subscription = $this->get_subscription_mock();
		$subscription->method( 'get_type' )
		             ->willReturn( 4 );

		$repository->method( 'get_subscription' )
			->willReturn( $subscription );

		$this->assertEquals( $product_found, $subject->get_product_in_repository_by_subscription( $repository, null ) );
	}

	/**
	 * @test
	 */
	public function it_returns_null_if_repository_does_not_have_a_subscription() {
		$subject = new OTGS_Installer_Package_Product_Finder();

		$repository = $this->get_repository_mock();
		$repository->method( 'get_subscription' )->willReturn( null );

		$this->assertNull( $subject->get_product_in_repository_by_subscription( $repository ) );
	}

	private function get_repository_mock() {
		return $this->getMockBuilder( 'OTGS_Installer_Repository' )
			->setMethods( array( 'get_product_by_subscription_type', 'get_product_by_subscription_type_equivalent', 'get_product_by_subscription_type_on_upgrades', 'get_subscription' ) )
			->disableOriginalConstructor()
			->getMock();
	}

	private function get_subscription_mock() {
		return $this->getMockBuilder( 'OTGS_Installer_Subscription' )
			->setMethods( array( 'get_type' ) )
			->disableOriginalConstructor()
			->getMock();
	}
}