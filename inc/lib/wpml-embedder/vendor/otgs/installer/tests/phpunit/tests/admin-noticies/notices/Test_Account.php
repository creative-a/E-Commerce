<?php

namespace OTGS\Installer\AdminNotices\Notices;

use OTGS\Installer\AdminNotices\Store;
use tad\FunctionMocker\FunctionMocker;
use OTGS\Installer\AdminNotices\storeMock;

/**
 * Class Test_Account
 * @package OTGS\Installer\AdminNotices
 * @group admin-notices
 */
class Test_Account extends \OTGS_TestCase {

	use storeMock;

	/**
	 * @test
	 */
	public function it_adds_hooks() {
		\WP_Mock::expectFilterAdded( 'otgs_installer_admin_notices_config', Account::class . '::config' );
		\WP_Mock::expectFilterAdded( 'otgs_installer_admin_notices_texts', Account::class . '::texts' );
		Account::addHooks( \Mockery::mock( \WP_Installer::class ) );
	}

	/**
	 * @test
	 */
	public function it_gets_no_message_when_there_are_not_any() {
		$installer = \Mockery::mock( \WP_Installer::class );
		$installer->shouldReceive( 'get_site_key_nags_config' )->andReturn( [] );

		$this->assertEquals( [], Account::getCurrentNotices( $installer, [] ) );
	}

	/**
	 * @test
	 */
	public function it_gets_register_message() {
		$installer = \Mockery::mock( \WP_Installer::class );
		$repo      = 'toolset';
		$installer->shouldReceive( 'get_site_key_nags_config' )->andReturn(
			[ [ 'repository_id' => $repo ] ]
		);
		$installer->shouldReceive( 'repository_has_subscription' )->with( $repo )->andReturn( false );
		$installer->shouldReceive( 'repository_has_expired_subscription' )->with( $repo, 30 * DAY_IN_SECONDS )->andReturn( false );
		$installer->shouldReceive( 'repository_has_refunded_subscription' )->with( $repo )->andReturn( false );
		$installer->shouldReceive( 'get_installer_site_url' )->andReturn( 'https://my-site.com' );

		self::initializeOldInstall( $repo );

		$expected = [ 'repo' => [ $repo => [ Account::NOT_REGISTERED ] ] ];

		$this->assertEquals( $expected, Account::getCurrentNotices( $installer, [] ) );
	}

	/**
	 * @test
	 * @dataProvider dp_siteUrl
	 */
	public function it_does_not_get_register_message_on_development_sites( $url ) {
		$installer = \Mockery::mock( \WP_Installer::class );
		$repo      = 'toolset';
		$installer->shouldReceive( 'get_site_key_nags_config' )->andReturn(
			[ [ 'repository_id' => $repo ] ]
		);
		$installer->shouldReceive( 'repository_has_subscription' )->with( $repo )->andReturn( false );
		$installer->shouldReceive( 'repository_has_expired_subscription' )->with( $repo, 30 * DAY_IN_SECONDS )->andReturn( false );
		$installer->shouldReceive( 'repository_has_refunded_subscription' )->with( $repo )->andReturn( false );
		$installer->shouldReceive( 'get_installer_site_url' )->andReturn( $url );

		self::initializeOldInstall( $repo );

		$this->assertEquals( [], Account::getCurrentNotices( $installer, [] ) );
	}

	/**
	 * @test
	 */
	public function it_should_delay_register_message_on_new_installs() {
		$installer = \Mockery::mock( \WP_Installer::class );
		$repo      = 'toolset';
		$installer->shouldReceive( 'get_site_key_nags_config' )->andReturn(
			[ [ 'repository_id' => $repo ] ]
		);
		$installer->shouldReceive( 'repository_has_subscription' )->with( $repo )->andReturn( false );
		$installer->shouldReceive( 'repository_has_expired_subscription' )->with( $repo, 30 * DAY_IN_SECONDS )->andReturn( false );
		$installer->shouldReceive( 'repository_has_refunded_subscription' )->with( $repo )->andReturn( false );
		$installer->shouldReceive( 'get_installer_site_url' )->andReturn( 'https://my-site.com' );

		$this->assertEquals( [], Account::getCurrentNotices( $installer, [] ) );
	}

	public function dp_siteUrl() {
		return [
			[ 'https://my-site.dev' ],
			[ 'https://my-site.local' ],
			[ 'https://my-site.test' ],
		];
	}

	/**
	 * @test
	 */
	public function it_does_not_get_register_message_if_config_callback_returns_false() {
		$installer = \Mockery::mock( \WP_Installer::class );
		$repo      = 'toolset';
		$installer->shouldReceive( 'get_site_key_nags_config' )->andReturn(
			[ [ 'repository_id' => $repo, 'condition_cb' => function() { return false; } ] ]
		);
		$installer->shouldReceive( 'repository_has_subscription' )->with( $repo )->andReturn( false );
		$installer->shouldReceive( 'repository_has_expired_subscription' )->with( $repo, 30 * DAY_IN_SECONDS  )->andReturn( false );
		$installer->shouldReceive( 'repository_has_refunded_subscription' )->with( $repo )->andReturn( false );
		$installer->shouldReceive( 'get_installer_site_url' )->andReturn( 'https://my-site.com' );

		self::initializeOldInstall( $repo );

		$this->assertEquals( [], Account::getCurrentNotices( $installer, [] ) );
	}

	/**
	 * @test
	 */
	public function it_gets_expired_message() {
		$installer = \Mockery::mock( \WP_Installer::class );
		$repo      = 'toolset';
		$installer->shouldReceive( 'get_site_key_nags_config' )->andReturn(
			[ [ 'repository_id' => $repo ] ]
		);
		$installer->shouldReceive( 'repository_has_subscription' )->with( $repo )->andReturn( true );
		$installer->shouldReceive( 'repository_has_expired_subscription' )->with( $repo, 30 * DAY_IN_SECONDS )->andReturn( true );
		$installer->shouldReceive( 'repository_has_refunded_subscription' )->with( $repo )->andReturn( false );
		$installer->shouldReceive( 'get_installer_site_url' )->andReturn( 'https://my-site.com' );

		$expected = [ 'repo' => [ $repo => [ Account::EXPIRED ] ] ];

		$this->assertEquals( $expected, Account::getCurrentNotices( $installer, [] ) );
	}

	/**
	 * @test
	 */
	public function it_gets_refunded_message() {
		$installer = \Mockery::mock( \WP_Installer::class );
		$repo      = 'toolset';
		$installer->shouldReceive( 'get_site_key_nags_config' )->andReturn(
			[ [ 'repository_id' => $repo ] ]
		);
		$installer->shouldReceive( 'repository_has_subscription' )->with( $repo )->andReturn( true );
		$installer->shouldReceive( 'repository_has_expired_subscription' )->with( $repo, 30 * DAY_IN_SECONDS )->andReturn( false );
		$installer->shouldReceive( 'repository_has_refunded_subscription' )->with( $repo )->andReturn( true );
		$installer->shouldReceive( 'get_installer_site_url' )->andReturn( 'https://my-site.com' );

		$expected = [ 'repo' => [ $repo => [ Account::REFUNDED ] ] ];

		$this->assertEquals( $expected, Account::getCurrentNotices( $installer, [] ) );
	}


	/**
	 * @test
	 */
	public function it_displays_on_correct_screens() {

		$initialScreens = [ 'repo' => [ 'initial' => [] ] ];

		$expectedScreens = array_merge_recursive( $initialScreens, [
			'repo' => [
				'wpml'    => [
					Account::NOT_REGISTERED => [ 'screens' => [ 'plugins' ] ],
					Account::EXPIRED        => [ 'screens' => [ 'plugins' ] ],
					Account::REFUNDED       => [ 'screens' => [ 'plugins', 'dashboard' ] ],
				],
				'toolset' => [
					Account::NOT_REGISTERED => [ 'screens' => [ 'plugins' ] ],
					Account::EXPIRED        => [ 'screens' => [ 'plugins' ] ],
					Account::REFUNDED       => [ 'screens' => [ 'plugins', 'dashboard' ] ],
				],
			]
		] );

		$this->assertEquals( $expectedScreens, Account::screens( $initialScreens ) );
	}

	/**
	 * @test
	 */
	public function it_displays_on_correct_pages() {
		if ( ! defined( 'WPML_PLUGIN_FOLDER' ) ) {
			define( 'WPML_PLUGIN_FOLDER', 'sitepress-multilingual-cms' );
		}

		$initialPages = [ 'repo' => [ 'initial' => [] ] ];

		$wpmlPages     = [
			'pages' =>
				[
					WPML_PLUGIN_FOLDER . '/menu/languages.php',
					WPML_PLUGIN_FOLDER . '/menu/theme-localization.php',
					WPML_PLUGIN_FOLDER . '/menu/settings.php',
					WPML_PLUGIN_FOLDER . '/menu/support.php',
				]
		];
		$toolsetPages  = [
			'pages' => [
				'toolset-dashboard',
			],
		];
		$expectedPages = array_merge_recursive( $initialPages, [
			'repo' => [
				'wpml'    => [
					Account::NOT_REGISTERED => $wpmlPages,
					Account::EXPIRED        => $wpmlPages,
					Account::REFUNDED       => $wpmlPages,
				],
				'toolset' => [
					Account::NOT_REGISTERED => $toolsetPages,
					Account::EXPIRED        => $toolsetPages,
					Account::REFUNDED       => $toolsetPages,
				],
			],
		] );

		$this->assertEquals( $expectedPages, Account::pages( $initialPages ) );
	}

	/**
	 * @test
	 */
	public function it_has_message_texts() {
		$initialTexts = [ 'repo' => [ 'initial' => [] ] ];

		$expectedTexts = array_merge( $initialTexts, [
			'repo' => [
				'wpml'    => [
					Account::NOT_REGISTERED => WPMLTexts::class . '::notRegistered',
					Account::EXPIRED        => WPMLTexts::class . '::expired',
					Account::REFUNDED       => WPMLTexts::class . '::refunded',
				],
				'toolset' => [
					Account::NOT_REGISTERED => ToolsetTexts::class . '::notRegistered',
					Account::EXPIRED        => ToolsetTexts::class . '::expired',
					Account::REFUNDED       => ToolsetTexts::class . '::refunded',
				],
			]
		] );

		$this->assertEquals( $expectedTexts, Account::texts( $initialTexts ) );
	}

	/**
	 * @test
	 */
	public function it_returns_config_with_screens_and_pages() {
		$initialConfig = [ 'repo' => [ 'initial' => [] ] ];
		$config        = Account::config( $initialConfig );

		$messages = [ Account::NOT_REGISTERED, Account::EXPIRED, Account::REFUNDED ];
		$repos    = [ 'wpml', 'toolset' ];
		$types    = [ 'pages', 'screens' ];
		foreach ( $repos as $repo ) {
			foreach ( $messages as $message ) {
				foreach ( $types as $type ) {
					$this->assertTrue( is_array( $config['repo'][ $repo ][ $message ][ $type ] ) );
				}
			}
		}
	}

	/**
	 * @test
	 */
	public function it_renders_texts() {
		FunctionMocker::replace( 'WP_Installer::menu_url', 'any-url' );

		$text = WPMLTexts::notRegistered();
		$this->assertHasTag( 'h2', $text );
		$this->assertHasTag( 'p', $text );
		$this->assertCanDismiss( $text );

		$text = WPMLTexts::expired();
		$this->assertHasTag( 'h2', $text );
		$this->assertHasTag( 'p', $text );
		$this->assertCanDismiss( $text );

		$text = WPMLTexts::refunded();
		$this->assertHasTag( 'h2', $text );
		$this->assertHasTag( 'p', $text );
		$this->assertCanNotDismiss( $text );

		$text = ToolsetTexts::notRegistered();
		$this->assertHasTag( 'h2', $text );
		$this->assertHasTag( 'p', $text );
		$this->assertCanDismiss( $text );

		$text = ToolsetTexts::expired();
		$this->assertHasTag( 'h2', $text );
		$this->assertHasTag( 'p', $text );
		$this->assertCanDismiss( $text );

		$text = ToolsetTexts::refunded();
		$this->assertHasTag( 'h2', $text );
		$this->assertHasTag( 'p', $text );
		$this->assertCanNotDismiss( $text );
	}

	private function assertHasTag( $tag, $text ) {
		$this->assertContains( "<$tag", $text );
		$this->assertContains( "</$tag>", $text );
	}

	private static function initializeOldInstall( $repo ) {
		$store = new Store();
		$slightlyMoreThanOneWeek = time() - WEEK_IN_SECONDS - 10;
		$store->save( Account::GET_FIRST_INSTALL_TIME, [ $repo => $slightlyMoreThanOneWeek ] );
	}

	private function assertCanDismiss( string $text ) {
		$this->assertRegExp('/<div.*?class=".*?is-dismissible.*?"/', $text);
	}

	private function assertCanNotDismiss( string $text ) {
		$this->assertNotRegExp('/<div.*?class=".*?is-dismissible.*?"/', $text);
	}
}
