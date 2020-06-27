<?php

/**
 * Class Test_Repository_Refund_Template
 * @group templates
 */
class Test_Repository_Refund_Template extends OTGS_TestCase {

	public function setUp() {
		parent::setUp();
		\WP_Mock::userFunction( 'esc_url', [
			'return' => function ( $url ) {
				return str_replace( '&', '&amp;', $url );
			},
		] );
	}


	/**
	 * @test
	 */
	public function it_renders() {
		$model = (object) [
			'productName'                 => 'Product Name',
			'repoId'                      => 'repo',
			'siteUrl'                     => 'http://site-url',
			'productUrl'                  => 'http://product-url',
			'updateSiteKeyNonce'          => 'update-nonce',
			'removeSiteKeyNonce'          => 'remove-nonce',
			'expired'                     => true,
			'shouldDisplayUnregisterLink' => true,
		];

		ob_start();
		\OTGS\Installer\Templates\Repository\Refunded::render( $model );
		$html = ob_get_clean();

		$dom = new DOMDocument();
		$dom->loadHTML( $html );

		$xpath = new DOMXPath( $dom );

		$updateLinkXpath = '//a[contains(@class, "update_site_key_js" )]';
		$this->hasLink( $model, $xpath, $updateLinkXpath, $model->updateSiteKeyNonce );

		$updateLinkXpath = '//a[contains(@class, "remove_site_key_js" )]';
		$this->hasLink( $model, $xpath, $updateLinkXpath, $model->removeSiteKeyNonce );

	}

	/**
	 * @test
	 */
	public function it_renders_without_unregister_link() {
		$model = (object) [
			'productName'                 => 'Product Name',
			'repoId'                      => 'repo',
			'siteUrl'                     => 'http://site-url',
			'productUrl'                  => 'http://product-url',
			'updateSiteKeyNonce'          => 'update-nonce',
			'removeSiteKeyNonce'          => 'remove-nonce',
			'expired'                     => true,
			'shouldDisplayUnregisterLink' => false,
		];

		ob_start();
		\OTGS\Installer\Templates\Repository\Refunded::render( $model );
		$html = ob_get_clean();

		$dom = new DOMDocument();
		$dom->loadHTML( $html );

		$xpath = new DOMXPath( $dom );

		$updateLinkXpath = '//a[contains(@class, "update_site_key_js" )]';
		$this->hasLink( $model, $xpath, $updateLinkXpath, $model->updateSiteKeyNonce );

		$updateLinkXpath = '//a[contains(@class, "remove_site_key_js" )]';
		$this->hasNotLink( $model, $xpath, $updateLinkXpath, $model->removeSiteKeyNonce );

	}

	private function hasLink( $model, DOMXPath $xpath, $linkXpath, $nonce ) {
		$link = $xpath->query( $linkXpath );
		$this->assertEquals( 1, $link->length );

		$link = $link->item( 0 );
		$this->assertEquals( $model->repoId, $link->getAttribute( 'data-repository' ) );
		$this->assertEquals( $nonce, $link->getAttribute( 'data-nonce' ) );
	}

	private function hasNotLink( $model, DOMXPath $xpath, $linkXpath, $nonce ) {
		$link = $xpath->query( $linkXpath );
		$this->assertEquals( 0, $link->length );
	}
}
