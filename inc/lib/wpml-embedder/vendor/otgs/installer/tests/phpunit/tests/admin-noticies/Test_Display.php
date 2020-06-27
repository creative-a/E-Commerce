<?php

namespace OTGS\Installer\AdminNotices;

use function OTGS\Installer\FP\partial;

/**
 * Class Test_Display
 * @package OTGS\Installer\AdminNotices
 * @group admin-notices
 */
class Test_Display extends \OTGS_TestCase {

	public function tearDown() {
		parent::tearDown();
		unset( $_GET['page'] );
	}

	/**
	 * @test
	 */
	public function it_does_not_add_hooks_if_no_notices() {
		$config  = [];
		$subject = new Display(
			[],
			$config,
			new MessageTexts( [] ),
			function () { return false; }
		);

		$this->mockOnPluginsPage();
		$this->expectAdminMessageActionNotAdded( $subject );

		$subject->addHooks();
	}

	/**
	 * @test
	 */
	public function it_can_show_message_on_plugins_screen() {
		$message_id = 123;
		$config     = [ 'repo' => [ 'xyz' => [ $message_id => [ 'screens' => [ 'plugins' ] ] ] ] ];
		$subject    = new Display(
			[ 'repo' => [ 'xyz' => [ $message_id ] ] ],
			$config,
			new MessageTexts( [] ),
			function () { return false; }
		);

		$this->mockOnPluginsPage();
		$this->expectAdminMessageActionAdded( $subject );

		$subject->addHooks();
	}

	/**
	 * @test
	 */
	public function it_shows_notice_on_a_configurable_page() {
		$page         = 'sitepress-multilingual-cms/menu/languages/php';
		$_GET['page'] = $page;
		$message_id   = 123;
		$messages     = [ 'repo' => [ 'wpml' => [ $message_id ] ] ];
		$config       = [ 'repo' => [ 'wpml' => [ $message_id => [ 'pages' => [ $page ] ] ] ] ];
		$subject      = new Display(
			$messages,
			$config,
			new MessageTexts( [] ),
			function () { return false; }
		);

		$this->expectAdminMessageActionAdded( $subject );

		$subject->addHooks();
	}

	/**
	 * @test
	 */
	public function it_shows_no_messages_if_on_other_pages() {
		$message_id = 123;
		$page       = 'sitepress-multilingual-cms/menu/languages/php';
		$config     = [ 'repo' => [ 'wpml' => [ $message_id => [ 'pages' => [ $page ] ] ] ] ];
		$subject    = new Display(
			[ 'repo' => [ 'xyz' => [ $message_id ] ] ],
			$config,
			new MessageTexts( [] ),
			function () { return false; }
		);

		$this->mockOnOtherPages();
		$this->expectAdminMessageActionNotAdded( $subject );

		$subject->addHooks();

	}

	/**
	 * @test
	 */
	public function it_renders_a_notice_if_on_repo_page() {
		$page         = 'sitepress-multilingual-cms/menu/languages/php';
		$_GET['page'] = $page;
		$message_id   = 123;
		$messages     = [ 'repo' => [ 'wpml' => [ $message_id ] ] ];
		$message_html = '<strong>some</strong> message';

		$config  = [ 'repo' => [ 'wpml' => [ $message_id => [ 'pages' => [ $page ] ] ] ] ];
		$subject = new Display(
			$messages,
			$config,
			new MessageTexts( [ 'repo' => [ 'wpml' => [ $message_id => function () use ( $message_html ) { return $message_html; } ] ] ] ),
			function () { return false; }
		);

		$this->expectOutputString( $message_html );
		$subject->addNotices();
	}

	/**
	 * @test
	 */
	public function it_renders_a_notice_if_on_configured_screen() {
		$message_id   = 123;
		$messages     = [ 'repo' => [ 'wpml' => [ $message_id ] ] ];
		$message_html = '<strong>some</strong> message';

		$this->mockOnPluginsPage();

		$config  = [ 'repo' => [ 'wpml' => [ $message_id => [ 'screens' => [ 'plugins' ] ] ] ] ];
		$subject = new Display(
			$messages,
			$config,
			new MessageTexts( [ 'repo' => [ 'wpml' => [ $message_id => function ( $id ) use ( $message_html ) { return $id . $message_html; } ] ] ] ),
			function () { return false; }
		);

		$this->expectOutputString( $message_id . $message_html );
		$subject->addNotices();
	}

	/**
	 * @test
	 */
	public function it_does_not_render_a_notice_if_not_on_repo_page() {
		$message_id   = 123;
		$messages     = [ 'repo' => [ 'wpml' => [ $message_id ] ] ];
		$message_html = '<strong>some</strong> message';

		$this->mockOnOtherPages();

		$config  = [ 'repo' => [ 'wpml' => [ $message_id => [ 'pages' => [ 'some-page' ] ] ] ] ];
		$subject = new Display(
			$messages,
			$config,
			new MessageTexts( [ 'repo' => [ 'wpml' => [ $message_id => function () use ( $message_html ) { return $message_html; } ] ] ] ),
			function () { return false; }
		);

		$this->expectOutputString( '' );
		$subject->addNotices();
	}

	/**
	 * @test
	 */
	public function it_does_not_render_a_notice_if_it_has_been_dismissed() {
		$page         = 'sitepress-multilingual-cms/menu/languages/php';
		$_GET['page'] = $page;
		$message_id   = 123;
		$messages     = [ 'repo' => [ 'wpml' => [ $message_id ] ] ];
		$message_html = '<strong>some</strong> message';

		$config    = [ 'repo' => [ 'wpml' => [ $message_id => [ 'pages' => [ $page ] ] ] ] ];
		$dismissed = partial(
			Dismissed::class . '::isDismissed',
			[ 'repo' => [ 'wpml' => [ $message_id => time() ] ] ]
		);
		$subject   = new Display(
			$messages,
			$config,
			new MessageTexts( [ 'repo' => [ 'wpml' => [ $message_id => function () use ( $message_html ) { return $message_html; } ] ] ] ),
			$dismissed
		);

		$this->expectOutputString( '' );
		$subject->addNotices();
	}

	/**
	 * @test
	 */
	public function it_enqueues_on_WP_dashboard() {
		$message_id = 123;
		$messages   = [ 'repo' => [ 'wpml' => [ $message_id ] ] ];
		$config     = [ 'repo' => [ 'wpml' => [ $message_id => [ 'screens' => [ 'dashboard' ] ] ] ] ];
		$subject    = new Display(
			$messages,
			$config,
			new MessageTexts( [] ),
			function () { return false; }
		);

		$this->mockOnWPDashboard();
		$this->expectAdminMessageActionAdded( $subject );

		$subject->addHooks();
	}

	/**
	 * @test
	 */
	public function it_enqueues_scripts() {
		\WP_Mock::userFunction( 'is_multisite', [ 'return' => false ] );
		\WP_Mock::passthruFunction( 'untrailingslashit' );
		$plugin_url = 'http://xyz.com/plugins';
		\WP_Mock::userFunction( 'plugins_url', [ 'return' => $plugin_url ] );

		\WP_Mock::userFunction( 'wp_enqueue_style', [
			'times' => 1,
			'args' => [ 'installer-admin-notices', $plugin_url . '/res/css/admin-notices.css', [], WP_INSTALLER_VERSION ]
		] );
		$subject = new Display(
			[],
			[],
			new MessageTexts( [] ),
			function () { return false; }
		);


		$subject->addScripts();
	}

	private function expectAdminMessageActionAdded( $subject ) {
		\WP_Mock::expectActionAdded( 'admin_notices', [ $subject, 'addNotices' ] );
		\WP_Mock::expectActionAdded( 'admin_enqueue_scripts', [ $subject, 'addScripts' ] );
	}

	private function expectAdminMessageActionNotAdded( Display $subject ) {
		\WP_Mock::expectActionNotAdded( 'admin_notices', [ $subject, 'addNotices' ] );
		\WP_Mock::expectActionNotAdded( 'admin_enqueue_scripts', [ $subject, 'addScripts' ] );
	}

	private function mockOnPluginsPage() {
		$this->mockOnScreen( 'plugins' );
	}

	private function mockOnOtherPages() {
		$this->mockOnScreen( 'some-other-page' );
	}

	private function mockOnWPDashboard() {
		$this->mockOnScreen( 'dashboard' );
	}

	private function mockOnScreen( $screenId ) {
		$currentScreen     = \Mockery::mock( 'WP_Screen' );
		$currentScreen->id = $screenId;
		\WP_Mock::userFunction( 'get_current_screen', [ 'return' => $currentScreen ] );
	}

}
