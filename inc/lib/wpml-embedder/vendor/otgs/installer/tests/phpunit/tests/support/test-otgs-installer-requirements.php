<?php
/**
 * Class Test_OTGS_Installer_Requirements
 *
 * @group installer-518
 */

class Test_OTGS_Installer_Requirements extends OTGS_TestCase {

	/**
	 * @test
	 */
	public function it_gets_requirements() {

		$requirements = array(
			array(
				'name'   => 'cURL',
				'active' => true,
			),
			array(
				'name'   => 'simpleXML',
				'active' => true,
			),
		);

		$subject = new OTGS_Installer_Requirements();
		$this->assertEquals( $requirements, $subject->get() );
	}
}