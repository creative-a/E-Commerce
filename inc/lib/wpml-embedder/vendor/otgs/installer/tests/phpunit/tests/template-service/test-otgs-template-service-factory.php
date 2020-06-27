<?php

use PHPUnit\Framework\TestCase;

class Test_OTGS_Template_Service_Factory extends TestCase {

	/**
	 * @test
	 */
	public function it_creates_template_service() {
		$template_dir = 'templates';

		$subject = new OTGS_Template_Service_Factory();
		$result = $subject->create( $template_dir );

		$this->assertInstanceOf(OTGS_Template_Service::class, $result);
	}
}
