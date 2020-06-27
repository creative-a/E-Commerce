<?php

namespace OTGS\Installer\AdminNotices;

/**
 * Class Test_Loader
 * @package OTGS\Installer\AdminNotices
 * @group admin-notices
 */
class Test_Loader extends \OTGS_TestCase {

	use StoreMock;

	/**
	 * @test
	 */
	public function it_adds_hooks() {
		\WP_Mock::expectActionAdded( 'current_screen', Loader::class . '::initDisplay' );
		Loader::addHooks( false );
	}

	/**
	 * @test
	 */
	public function it_adds_ajax_hooks() {
		\WP_Mock::expectActionAdded( 'wp_ajax_installer_dismiss_nag', Dismissed::class . '::dismissNotice' );
		Loader::addHooks( true );
	}

	/**
	 * @test
	 */
	public function it_does_not_change_dismissed_messages_if_they_have_not_timed_out() {
		$messageId                    = 123;
		$slightlyLessThanTwoMonthsAgo = time() - 2 * MONTH_IN_SECONDS + 10;
		$repo                         = 'wpml';
		$dismissed                    = [ 'repo' => [ $repo => [ $messageId => $slightlyLessThanTwoMonthsAgo ] ] ];
		$this->mockMessagesFilter( $repo, $messageId );

		$store = new Store();
		$store->save( 'dismissed', $dismissed );

		\WP_Mock::passthruFunction( 'remove_action' );
		Loader::initDisplay();

		$newDismissed = $store->get( 'dismissed', [] );
		$this->assertTrue( isset( $newDismissed['repo'][ $repo ][ $messageId ] ) );
	}

	/**
	 * @test
	 */
	public function it_clears_expired_dismissed_messages_from_store() {
		$messageId                    = 123;
		$slightlyMoreThanTwoMonthsAgo = time() - 2 * MONTH_IN_SECONDS - 10;
		$repo                         = 'wpml';
		$dismissed                    = [ 'repo' => [ $repo => [ $messageId => $slightlyMoreThanTwoMonthsAgo ] ] ];
		$this->mockMessagesFilter( $repo, $messageId );

		$store = new Store();
		$store->save( 'dismissed', $dismissed );

		\WP_Mock::passthruFunction( 'remove_action' );
		Loader::initDisplay();

		$newDismissed = $store->get( 'dismissed', [] );
		$this->assertFalse( isset( $newDismissed['repo'][ $repo ][ $messageId ] ) );
	}

	/**
	 * @test
	 */
	public function it_allows_custom_timeout_of_dismissed_messages() {
		$messageId                  = 123;
		$slightlyMoreThanOneWeekAgo = time() - WEEK_IN_SECONDS - 10;
		$repo                       = 'wpml';
		$dismissed                  = [ 'repo' => [ $repo => [ $messageId => $slightlyMoreThanOneWeekAgo ] ] ];
		$this->mockMessagesFilter( $repo, $messageId );
		\WP_Mock::onFilter( 'otgs_installer_admin_notices_dismissed_time' )->with(
			2 * MONTH_IN_SECONDS,
			$repo,
			$messageId
		)->reply( WEEK_IN_SECONDS );

		$store = $this->createStore( $dismissed );

		\WP_Mock::passthruFunction( 'remove_action' );
		Loader::initDisplay();

		$newDismissed = $store->get( 'dismissed', [] );
		$this->assertFalse( isset( $newDismissed['repo'][ $repo ][ $messageId ] ) );
	}

	/**
	 * @param $messageId
	 */
	private function mockMessagesFilter( $repo, $messageId ) {
		$messages = [ 'repo' => [ $repo => [ $messageId ] ] ];
		\WP_Mock::onFilter( 'otgs_installer_admin_notices' )->with( [] )->reply( $messages );
	}

	/**
	 * @param array $dismissed
	 *
	 * @return Store
	 */
	private function createStore( array $dismissed ) {
		$store = new Store();
		$store->save( 'dismissed', $dismissed );

		return $store;
	}


}
