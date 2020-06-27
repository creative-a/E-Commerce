<?php

namespace OTGS\Installer\AdminNotices;
/**
 * Class Test_Storage
 * @package OTGS\Installer\AdminNotices
 * @group admin-notices
 */
class Test_Storage extends \OTGS_TestCase {

	use StoreMock;

	/**
	 * @test
	 */
	public function it_stores_state() {
		$key   = 'some-key';
		$state = [ 'some' => 'data' ];

		$store = new Store();
		$store->save( $key, $state );

		$store = new Store();
		$this->assertEquals( $state, $store->get( $key, [] ) );
	}
}
