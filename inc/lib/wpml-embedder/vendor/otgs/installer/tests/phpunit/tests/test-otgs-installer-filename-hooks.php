<?php

/**
 * Class Test_OTGS_Installer_Filename_Hooks
 *
 * @group windows-path-size-limitation
 * @group installer-392
 */
class Test_OTGS_Installer_Filename_Hooks extends OTGS_TestCase {

	/**
	 * @test
	 * @dataProvider dp_win_os_names
	 */
	public function it_adds_hooks_if_os_is_win( $win_os_names ) {
		$built_in_functions = $this->getMockBuilder( 'OTGS_Installer_PHP_Functions' )
		                           ->setMethods( array( 'time', 'constant' ) )
		                           ->disableOriginalConstructor()
		                           ->getMock();

		$built_in_functions->method( 'constant' )
		                   ->with( 'PHP_OS' )
		                   ->willReturn( $win_os_names );

		$subject = new OTGS_Installer_Filename_Hooks( $built_in_functions );
		\WP_Mock::expectFilterAdded( 'wp_unique_filename', array( $subject, 'fix_filename_for_win' ), 10, 3 );
		$subject->add_hooks();
	}

	/**
	 * @test
	 */
	public function it_does_not_add_hook_when_os_is_not_win() {
		$built_in_functions = $this->getMockBuilder( 'OTGS_Installer_PHP_Functions' )
		                           ->setMethods( array( 'time', 'constant' ) )
		                           ->disableOriginalConstructor()
		                           ->getMock();

		$built_in_functions->method( 'constant' )
		                   ->with( 'PHP_OS' )
		                   ->willReturn( 'Linux' );

		$subject = new OTGS_Installer_Filename_Hooks( $built_in_functions );
		$this->expectFilterAdded( 'wp_unique_filename', array( $subject, 'fix_filename_for_win' ), 10, 3, 0 );
		$subject->add_hooks();
	}

	/**
	 * @test
	 */
	public function it_fixes_filename() {
		$built_in_functions = $this->getMockBuilder( 'OTGS_Installer_PHP_Functions' )
		                           ->setMethods( array( 'time', 'constant' ) )
		                           ->disableOriginalConstructor()
		                           ->getMock();

		$subject = new OTGS_Installer_Filename_Hooks( $built_in_functions );
		$filename = 'my-filename.tmp';
		$temp_dir = 'temp-dir/';
		$time = 134235;

		$built_in_functions->method( 'time' )
			->willReturn( $time );

		WP_Mock::wpFunction( 'get_temp_dir', array(
			'return' => $temp_dir,
		) );

		$expected = md5( $filename . $time ) . 'tmp';
		$this->assertEquals( $expected, $subject->fix_filename_for_win( $filename, '', $temp_dir ) );
	}

	/**
	 * @return array
	 */
	public function dp_win_os_names() {
		return array(
			array( 'WIN32' ),
			array( 'WINNT' ),
			array( 'Windows' ),
		);
	}

	/**
	 * @test
	 */
	public function it_does_not_fix_filename_because_it_is_not_temp_folder() {
		$built_in_functions = $this->getMockBuilder( 'OTGS_Installer_PHP_Functions' )
		                           ->setMethods( array( 'time', 'constant' ) )
		                           ->disableOriginalConstructor()
		                           ->getMock();

		$subject = new OTGS_Installer_Filename_Hooks( $built_in_functions );
		$filename = 'my-filename.tmp';
		$time = 134235;

		$built_in_functions->method( 'time' )
		                   ->willReturn( $time );

		WP_Mock::wpFunction( 'get_temp_dir', array(
			'return' => 'temp-dir/',
		) );

		$this->assertEquals( $filename, $subject->fix_filename_for_win( $filename, '', 'not-temp-dir/' ) );
	}
}