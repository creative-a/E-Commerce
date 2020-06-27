<?php

namespace OTGS\Installer\AdminNotices\Notices;

use tad\FunctionMocker\FunctionMocker;

/**
 * Class Test_Texts
 * @package OTGS\Installer\AdminNotices
 * @group admin-notices
 */
class Test_Texts extends \OTGS_TestCase {

	/**
	 * @test
	 * @dataProvider dpType
	 */
	public function it_has_buttons_on_not_registered_notice( $type, $repo ) {
		$registerUrl = 'http://some-url';
		FunctionMocker::replace( 'WP_Installer::menu_url', $registerUrl );

		$notice = call_user_func( [ $type, 'notRegistered' ] );

		$dom = new \DOMDocument();
		$dom->loadHTML( $notice );
		$buttons = $dom->getElementsByTagName( 'a' );

		$this->assertEquals( 'otgs-installer-notice-status-item otgs-installer-notice-status-item-btn', $buttons->item( 0 )->getAttribute( 'class' ) );
		$this->assertEquals( $registerUrl, $buttons->item( 0 )->getAttribute( 'href' ) );
		$this->assertEquals( 'Register', $buttons->item( 0 )->textContent );

		$this->assertEquals(
			'otgs-installer-notice-status-item otgs-installer-notice-status-item-link installer-dismiss-nag',
			$buttons->item( 1 )->getAttribute( 'class' )
		);
		$this->assertEquals(
			'This is a development / staging site',
			$buttons->item( 1 )->textContent
		);
		$this->assertEquals(
			$repo,
			$buttons->item( 1 )->getAttribute( 'data-repository' )
		);
		$this->assertEquals(
			Account::NOT_REGISTERED,
			$buttons->item( 1 )->getAttribute( 'data-notice' )
		);
	}

	/**
	 * @test
	 * @dataProvider dpType
	 */
	public function it_has_buttons_on_expired_notice( $type, $repo ) {
		$menuUrl = 'http://some-url';
		$productURL = 'http://product-url';

		FunctionMocker::replace( 'WP_Installer::menu_url', $menuUrl );

		$installerInstance = \Mockery::mock( '\WP_Installer' );
		$installerInstance->shouldReceive( 'get_product_data' )->with( $repo, 'url' )->andReturn( $productURL );
		FunctionMocker::replace( 'WP_Installer::instance', $installerInstance );

		$notice = call_user_func( [ $type, 'expired' ] );

		$dom            = new \DOMDocument();
		$internalErrors = libxml_use_internal_errors( true );
		$dom->loadHTML( $notice );
		libxml_use_internal_errors( $internalErrors );
		$buttons = $dom->getElementsByTagName( 'a' );

		$this->assertEquals( 'otgs-installer-notice-status-item otgs-installer-notice-status-item-btn', $buttons->item( 0 )->getAttribute( 'class' ) );
		$this->assertEquals( $productURL . '/account', $buttons->item( 0 )->getAttribute( 'href' ) );
		$this->assertEquals( 'Extend your subscription', $buttons->item( 0 )->textContent );

		$this->assertEquals(
			'otgs-installer-notice-status-item otgs-installer-notice-status-item-link otgs-installer-notice-status-item-link-refresh',
			$buttons->item( 1 )->getAttribute( 'class' )
		);
		$this->assertEquals( $menuUrl . '&validate_repository=' . $repo, $buttons->item( 1 )->getAttribute( 'href' ) );
		$this->assertEquals(
			'Check my order status',
			$buttons->item( 1 )->textContent
		);
	}

	/**
	 * @test
	 * @dataProvider dpType
	 */
	public function it_has_button_on_refunded_notice( $type, $repo ) {
		$menuUrl = 'http://some-url';
		FunctionMocker::replace( 'WP_Installer::menu_url', $menuUrl );

		$notice = call_user_func( [ $type, 'refunded' ] );

		$dom            = new \DOMDocument();
		$internalErrors = libxml_use_internal_errors( true );
		$dom->loadHTML( $notice );
		libxml_use_internal_errors( $internalErrors );
		$buttons = $dom->getElementsByTagName( 'a' );

		$this->assertEquals( 'otgs-installer-notice-status-item otgs-installer-notice-status-item-btn', $buttons->item( 0 )->getAttribute( 'class' ) );
		$this->assertEquals( $menuUrl . '&validate_repository=' . $repo, $buttons->item( 0 )->getAttribute( 'href' ) );
		$this->assertEquals( 'Check my order status', $buttons->item( 0 )->textContent );

	}

	public function dpType() {
		return [
			[ WPMLTexts::class, 'wpml' ],
			[ ToolsetTexts::class, 'toolset' ]
		];
	}
}
