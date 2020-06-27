<?php

/**
 * Class Test_Repository_Register_Template
 * @group templates
 */
class Test_Repository_Register_Template extends OTGS_TestCase {

	public function setUp() {
		parent::setUp();
		\WP_Mock::userFunction( 'esc_url', [
			'return' => function ( $url ) { return str_replace( '&', '&amp;', $url ); }
		] );
	}


	/**
	 * @test
	 */
	public function it_renders() {
		$model = (object) [
			'productName'           => 'Product Name',
			'repoId'                => 'repo',
			'saveSiteKeyNonce'      => 'nonce',
			'siteUrl'               => 'http://site-url',
			'siteKeysManagementUrl' => 'http://site-key-management-url',
			'productUrl'            => 'http://product-url',
		];

		ob_start();
		\OTGS\Installer\Templates\Repository\Register::render( $model );
		$html = ob_get_clean();

		$dom = new DOMDocument();
		$dom->loadHTML( $html );

		$this->assertHasRegisterButton( $dom );
		$this->assertHasFormToEnterSiteKey( $dom, $model );

	}

	private function assertHasRegisterButton( DOMDocument $dom ) {
		$xpath = new DOMXPath( $dom );

		$update_site_key_button = $xpath->query( '//a[contains(@class, "enter_site_key_js" )]' );
		$this->assertEquals( 1, $update_site_key_button->length );

	}

	private function assertHasFormToEnterSiteKey( DOMDocument $dom, $model ) {
		$xpath = new DOMXPath( $dom );

		$formXPath  = '//form[@class="otgsi_site_key_form"]';
		$form = $xpath->query( $formXPath );
		$this->assertEquals( 1, $form->length );

		$inputs = [
			'//input[@name="action"][@type="hidden"][@value="save_site_key"]',
			'//input[@name="nonce"][@type="hidden"][@value="' . $model->saveSiteKeyNonce . '"]',
			'//input[@name="repository_id"][@type="hidden"][@value="'. $model->repoId . '"]',
			'//input[@name="site_key_' . $model->repoId . '"][@type="text"]',
			'//input[@type="submit"][@value="OK"]',
			'//input[@type="button"][contains(@class, "cancel_site_key_js")][@value="Cancel registration"]',
		];
		foreach( $inputs as $input ) {
			$this->assertEquals( 1, $xpath->query( $formXPath . $input )->length );
		}
	}

}
