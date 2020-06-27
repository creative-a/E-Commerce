<?php

/**
 * Class Test_Repository_Expired_Template
 * @group templates
 */
class Test_Repository_Expired_Template extends OTGS_TestCase {

	public function setUp() {
		parent::setUp();
		\WP_Mock::userFunction( 'esc_url', [
			'return' => function ( $url ) { return str_replace( '&', '&amp;', $url ); }
		] );
	}


	/**
	 * @test
	 */
	public function it_renders_when_end_user_product_exists() {
		$model = (object) [
			'productName'        => 'Product Name',
			'repoId'             => 'repo',
			'updateSiteKeyNonce' => 'update-nonce',
			'productUrl'         => 'http://product-url',
			'endUserRenewalUrl'  => 'http://renewal-url',
			'siteKey'            => 'some-site-key',
			'removeSiteKeyNonce' => 'remove-nonce',
			'findAccountNonce'   => 'find-account-nonce',
			'expired'            => true,
		];

		ob_start();
		\OTGS\Installer\Templates\Repository\Expired::render( $model );
		$html = ob_get_clean();

		$this->assertHasUpdateButton( $model, $html );

		$this->assertHasAccountLink( $model, $html );

		$this->assertHasEndUserRenewalLink( $model, $html );

		$this->assertHasFindAccount( $model, $html );

	}

	/**
	 * @test
	 */
	public function it_renders_only_extend_button_when_end_user_product_does_not_exist() {
		$model = (object) [
			'productName'        => 'Product Name',
			'repoId'             => 'repo',
			'updateSiteKeyNonce' => 'update-nonce',
			'productUrl'         => 'http://product-url',
			'endUserRenewalUrl'  => null,
			'siteKey'            => 'some-site-key',
			'removeSiteKeyNonce' => 'remove-nonce',
			'findAccountNonce'   => 'find-account-nonce',
			'expired'            => true,
		];

		ob_start();
		\OTGS\Installer\Templates\Repository\Expired::render( $model );
		$html = ob_get_clean();

		$this->assertHasUpdateButton( $model, $html );

		$this->assertHasAccountLink( $model, $html );

		$this->assertNotHaveEndUserRenewalLink( $model, $html );

		$this->assertNotHaveFindAccount( $model, $html );
	}

	/**
	 * @param $model
	 * @param $html
	 */
	private function assertHasAccountLink( $model, $html ) {
		$this->assertContains( 'href="' . $model->productUrl . '/account"', $html );
	}

	/**
	 * @param $model
	 * @param $html
	 */
	private function assertHasEndUserRenewalLink( $model, $html ) {
		$this->assertContains( 'href="' . $model->endUserRenewalUrl . '&amp;token=' . $model->siteKey . '"', $html );
	}

	/**
	 * @param $model
	 * @param $html
	 */
	private function assertNotHaveEndUserRenewalLink( $model, $html ) {
		$this->assertNotContains( 'href="' . $model->endUserRenewalUrl . '&amp;token=' . $model->siteKey . '"', $html );
	}


	/**
	 * @param object $model
	 * @param string $html
	 */
	private function assertHasUpdateButton( $model, $html ) {
		$dom = new DOMDocument();
		$dom->loadHTML( $html );
		$xpath = new DOMXPath( $dom );

		$update_site_key_button = $xpath->query( '//a[contains(@class, "update_site_key_js" )]' );
		$this->assertEquals( 1, $update_site_key_button->length );

		foreach ( $update_site_key_button as $button ) {
			$this->assertEquals( $model->repoId, $button->getAttribute( 'data-repository' ) );
			$this->assertEquals( $model->updateSiteKeyNonce, $button->getAttribute( 'data-nonce' ) );
		}
	}

	/**
	 * @param object $model
	 * @param string $html
	 */
	private function assertHasFindAccount( $model, $html ) {
		$dom = new DOMDocument();
		$dom->loadHTML( $html );
		$xpath = new DOMXPath( $dom );

		$findAccountButton = $xpath->query( '//a[contains(@class, "js-find-account" )]' );
		$this->assertEquals( 1, $findAccountButton->length );

		foreach ( $findAccountButton as $button ) {
			$this->assertEquals( $model->repoId, $button->getAttribute( 'data-repository' ) );
			$this->assertEquals( $model->findAccountNonce, $button->getAttribute( 'data-nonce' ) );
		}

		$emailInput = $xpath->query( '//div[contains(@class, "js-find-account-section")]//input' );
		$this->assertEquals( 1, $emailInput->length );

	}

	/**
	 * @param object $model
	 * @param string $html
	 */
	private function assertNotHaveFindAccount( $model, $html ) {
		$dom = new DOMDocument();
		$dom->loadHTML( $html );
		$xpath = new DOMXPath( $dom );

		$findAccountButton = $xpath->query( '//a[contains(@class, "js-find-account" )]' );
		$this->assertEquals( 0, $findAccountButton->length );
	}
}
