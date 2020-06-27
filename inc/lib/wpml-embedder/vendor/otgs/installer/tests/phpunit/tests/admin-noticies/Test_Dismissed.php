<?php

namespace OTGS\Installer\AdminNotices;

/**
 * Class Test_Dismissed
 * @package OTGS\Installer\AdminNotices
 * @group admin-notices
 */
class Test_Dismissed extends \OTGS_TestCase {

	use StoreMock;

	/**
	 * @test
	 */
	public function it_dismisses_notice() {
		$repo                = 'wpml';
		$_POST['repository'] = $repo;
		$noticeId           = 'new';
		$_POST['noticeId']  = $noticeId;

		$existingNoticeId = 'original';
		$existing          = [ 'repo' => [ $repo => [ $existingNoticeId => time() ] ] ];

		$store = new Store();
		$store->save( 'dismissed', $existing );

		\WP_Mock::userFunction( 'wp_send_json_success', [ 'times' => 1 ] );
		Dismissed::dismissNotice();

		$dismissed = $store->get( 'dismissed', [] );
		$this->assertTrue( isset( $dismissed['repo'][ $repo ][ $noticeId ] ) );
		$this->assertTrue( isset( $dismissed['repo'][ $repo ][ $existingNoticeId ] ) );

		unset( $_POST['repository'], $_POST['noticeId'] );
	}

}
