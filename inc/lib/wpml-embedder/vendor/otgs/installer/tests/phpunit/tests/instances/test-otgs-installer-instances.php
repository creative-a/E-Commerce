<?php

/**
 * Class Test_OTGS_Installer_Instances
 *
 * @group installer-521
 */
class Test_OTGS_Installer_Instances extends OTGS_TestCase {

	/**
	 * @test
	 */
	public function it_get_instances() {
		$instances = array(
			array(
				'bootfile' => 'path/to/installer',
				'high_priority' => '',
				'version' => '1.0.0',
				'delegated' => true,
			),
		);

		$subject = new OTGS_Installer_Instances( $instances );
		$instances_obj = $subject->get();

		$expected_instance_obj = new OTGS_Installer_Instance();
		$expected_instance_obj->set_bootfile( 'path/to/installer' )
			->set_high_priority( '' )
			->set_version( '1.0.0' )
			->set_delegated( true );

		$this->assertCount(1, $instances_obj);
		$this->assertEquals($expected_instance_obj, $instances_obj[0]);

	}
}