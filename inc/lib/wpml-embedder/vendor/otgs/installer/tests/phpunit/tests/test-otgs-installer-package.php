<?php

/**
 * Class Test_OTGS_Installer_Package
 *
 * @group installer-upgrade
 * @group installer-487
 */
class Test_OTGS_Installer_Package extends OTGS_TestCase {

	/**
	 * @test
	 */
	public function it_gets_product_by_subscription_type() {
		$product = $this->getMockBuilder( 'OTGS_Installer_Package_Product' )
			->setMethods( array( 'get_subscription_type', 'get_subscription_type_equivalent' ) )
			->disableOriginalConstructor()
			->getMock();

		$product->method( 'get_subscription_type' )
			->willReturn( 4 );

		$products = array( $product );
		$subject = new OTGS_Installer_Package( array(
			'products' => $products,
		) );
		$this->assertEquals( $product, $subject->get_product_by_subscription_type( 4 ) );
	}

	/**
	 * @test
	 */
	public function it_returns_null_when_no_product_is_found_when_getting_product_by_subscription_type() {
		$product = $this->getMockBuilder( 'OTGS_Installer_Package_Product' )
		                ->setMethods( array( 'get_subscription_type', 'get_subscription_type_equivalent' ) )
		                ->disableOriginalConstructor()
		                ->getMock();

		$product->method( 'get_subscription_type' )
		        ->willReturn( 3 );

		$products = array( $product );
		$subject = new OTGS_Installer_Package( array(
			'products' => $products,
		) );
		$this->assertEquals( null, $subject->get_product_by_subscription_type( 4 ) );
	}

	/**
	 * @test
	 */
	public function it_gets_product_by_subscription_type_equivalent() {
		$product = $this->getMockBuilder( 'OTGS_Installer_Package_Product' )
		                ->setMethods( array( 'get_subscription_type', 'get_subscription_type_equivalent' ) )
		                ->disableOriginalConstructor()
		                ->getMock();

		$product->method( 'get_subscription_type_equivalent' )
		        ->willReturn( 4 );

		$products = array( $product );
		$subject = new OTGS_Installer_Package( array(
			'products' => $products,
		) );
		$this->assertEquals( $product, $subject->get_product_by_subscription_type_equivalent( 4 ) );
	}

	/**
	 * @test
	 */
	public function it_returns_null_when_no_product_is_found_when_getting_product_by_subscription_type_equivalent() {
		$product = $this->getMockBuilder( 'OTGS_Installer_Package_Product' )
		                ->setMethods( array( 'get_subscription_type', 'get_subscription_type_equivalent' ) )
		                ->disableOriginalConstructor()
		                ->getMock();

		$product->method( 'get_subscription_type_equivalent' )
		        ->willReturn( 3 );

		$products = array( $product );
		$subject = new OTGS_Installer_Package( array(
			'products' => $products,
		) );
		$this->assertEquals( null, $subject->get_product_by_subscription_type_equivalent( 4 ) );
	}

	/**
	 * @test
	 */
	public function it_gets_product_by_subscription_type_on_upgrades() {
		$product = $this->getMockBuilder( 'OTGS_Installer_Package_Product' )
		                ->setMethods( array( 'get_subscription_type', 'get_subscription_type_equivalent', 'get_upgrades' ) )
		                ->disableOriginalConstructor()
		                ->getMock();

		$product->method( 'get_upgrades' )
			->willReturn( array( array( 'subscription_type' => 4 ) ) );

		$products = array( $product );
		$subject = new OTGS_Installer_Package( array(
			'products' => $products,
		) );

		$this->assertEquals( $product, $subject->get_product_by_subscription_type_on_upgrades( 4 ) );
	}

	/**
	 * @test
	 */
	public function it_returns_null_when_no_product_is_found_when_getting_product_by_subscription_type_on_upgrades() {
		$product = $this->getMockBuilder( 'OTGS_Installer_Package_Product' )
		                ->setMethods( array( 'get_subscription_type', 'get_subscription_type_equivalent', 'get_upgrades' ) )
		                ->disableOriginalConstructor()
		                ->getMock();

		$product->method( 'get_upgrades' )
		        ->willReturn( array( array( 'subscription_type' => 1 ) ) );

		$products = array( $product );
		$subject = new OTGS_Installer_Package( array(
			'products' => $products,
		) );

		$this->assertEquals( null, $subject->get_product_by_subscription_type_on_upgrades( 4 ) );
	}
}