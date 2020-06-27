<?php
/**
 * Class Test_OTGS_Installer_Connection_Test_Ajax
 *
 * @group installer-support
 * @group installer-516
 */

class Test_OTGS_Installer_Connection_Test_Ajax extends OTGS_TestCase {

	public function tearDown() {
		parent::tearDown();

		unset( $_POST['type'], $_POST['repository'] );
	}

	/**
	 * @test
	 */
	public function it_adds_hooks() {
		$connection_test = $this->getMockBuilder( 'OTGS_Installer_Connection_Test' )
		                        ->disableOriginalConstructor()
		                        ->getMock();

		$subject = new OTGS_Installer_Connection_Test_Ajax( $connection_test );
		\WP_Mock::expectActionAdded( 'wp_ajax_' . OTGS_Installer_Connection_Test_Ajax::ACTION, array(
			$subject,
			'test_connection'
		) );
		$subject->add_hooks();
	}

	/**
	 * @test
	 * @dataProvider dp_invalid_request
	 */
	public function it_returns_json_error_when_connection_is_not_valid( $nonce, $type, $repository, $is_nonce_valid ) {
		$connection_test = $this->getMockBuilder( 'OTGS_Installer_Connection_Test' )
		                        ->disableOriginalConstructor()
		                        ->getMock();

		$_POST['nonce'] = $nonce;
		$_POST['type'] = $type;
		$_POST['repository'] = $repository;

		\WP_Mock::userFunction( 'wp_verify_nonce', array(
			'args' => array( $nonce, OTGS_Installer_Connection_Test_Ajax::ACTION ),
			'return' => $is_nonce_valid,
		) );

		\WP_Mock::userFunction( 'wp_send_json_error', array(
			'times' => 1,
		) );

		$subject = new OTGS_Installer_Connection_Test_Ajax( $connection_test );
		$subject->test_connection();
	}

	public function dp_invalid_request() {
		return array(
			'nonce not set'      => array( null, 'api', 'wpml', true ),
			'nonce not valid'    => array( 'invalid_nonce', 'api', 'wpml', false ),
			'type not set'       => array( 'nonce_value', null, 'wpml', true ),
			'repository not set' => array( 'nonce_value', 'api', null, true ),
		);
	}

	/**
	 * @test
	 */
	public function it_returns_json_error_when_api_connection_fails() {
		$connection_test = $this->getMockBuilder( 'OTGS_Installer_Connection_Test' )
		                        ->disableOriginalConstructor()
		                        ->getMock();

		$_POST['nonce'] = 'valid_nonce';
		$_POST['type'] = 'api';
		$_POST['repository'] = 'wpml';

		\WP_Mock::userFunction( 'wp_verify_nonce', array(
			'args' => array( $_POST['nonce'], OTGS_Installer_Connection_Test_Ajax::ACTION ),
			'return' => true,
		) );

		\WP_Mock::userFunction( 'wp_send_json_error', array(
			'times' => 1,
		) );

		$connection_test->expects( $this->once() )
		                ->method( 'get_api_status' )
		                ->willReturn( false );

		$subject = new OTGS_Installer_Connection_Test_Ajax( $connection_test );
		$subject->test_connection();
	}

	/**
	 * @test
	 */
	public function it_returns_json_success_when_api_connection_succeed() {
		$connection_test = $this->getMockBuilder( 'OTGS_Installer_Connection_Test' )
		                        ->disableOriginalConstructor()
		                        ->getMock();

		$_POST['nonce'] = 'valid_nonce';
		$_POST['type'] = 'api';
		$_POST['repository'] = 'wpml';

		\WP_Mock::userFunction( 'wp_verify_nonce', array(
			'args' => array( $_POST['nonce'], OTGS_Installer_Connection_Test_Ajax::ACTION ),
			'return' => true,
		) );

		\WP_Mock::userFunction( 'wp_send_json_success', array(
			'times' => 1,
		) );

		$connection_test->expects( $this->once() )
		                ->method( 'get_api_status' )
		                ->willReturn( true );

		$subject = new OTGS_Installer_Connection_Test_Ajax( $connection_test );
		$subject->test_connection();
	}
}
