<?php

/**
 * @group template-service
 * @group installer-370
 */
class Test_OTGS_Php_Template_Service_Loader extends OTGS_TestCase {

	/**
	 * @test
	 */
	public function it_gets_template() {
		$paths = 'templates';

		$subject = new OTGS_Php_Template_Service_Loader( $paths );
		$this->assertInstanceOf( 'OTGS_Php_Template_Service', $subject->get_service() );
	}
}