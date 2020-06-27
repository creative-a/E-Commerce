<?php

namespace OTGS\Installer\AdminNotices;

trait StoreMock {

	private $storeData = [];

	public function setUp() {
		parent::setUp();

		\WP_Mock::userFunction( 'get_option',
			[
				'return' => function( $key, $default ) {
					return isset( $this->storeData[$key] ) ? $this->storeData[$key] : $default;
				}
			]
		);
		\WP_Mock::userFunction( 'update_option',
			[
				'return' => function( $key, $value , $autoload ) {
					$this->storeData[$key] = $value;
				}
			]
		);
	}


}
