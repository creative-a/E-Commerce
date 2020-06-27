<?php

namespace OTGS\Installer;

/**
 * Class Test_Collection
 * @package OTGS\Installer
 * @group admin-notices
 */
class Test_Collection extends \OTGS_TestCase {

	/**
	 * @test
	 */
	public function it_lifts_array_and_returns_it() {
		$data = [ 1, 2, 3, 4, 'something' ];
		$this->assertEquals( $data, Collection::of( $data )->get() );
	}

	/**
	 * @test
	 */
	public function it_filters() {
		$data      = [ 1, 2, 3, 4 ];
		$predicate = function ( $num ) { return $num > 2; };
		$this->assertEquals( [ 3, 4 ], Collection::of( $data )->filter( $predicate )->values()->get() );
	}

	/**
	 * @test
	 */
	public function it_plucks() {
		$data = [
			[ 'product_id' => 101, 'name' => 'Phone' ],
			[ 'product_id' => 102, 'name' => 'Camera' ],
		];

		$this->assertEquals( [ 101, 102 ], Collection::of( $data )->pluck( 'product_id' )->get() );
		$this->assertEquals( [ 'Phone', 'Camera' ], Collection::of( $data )->pluck( 'name' )->get() );
	}

	/**
	 * @test
	 */
	public function it_reduces() {
		$data = [ 1, 2, 3, 4, 5, 10, 17 ];

		$add = function ( $carry, $item ) { return $carry + $item; };

		$this->assertEquals( 42, Collection::of( $data )->reduce( $add ) );

		$initialValue = 10;
		$this->assertEquals( 52, Collection::of( $data )->reduce( $add, $initialValue ) );
	}

	/**
	 * @test
	 */
	public function it_gets_values() {
		$data = [ 'product_id' => 101, 'name' => 'Phone' ];

		$this->assertEquals( [ 101, 'Phone' ], Collection::of( $data )->values()->get() );
	}


}
