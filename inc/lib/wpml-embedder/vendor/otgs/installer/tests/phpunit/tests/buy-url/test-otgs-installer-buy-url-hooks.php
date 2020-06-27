<?php

/**
 * Class Test_OTGS_Installer_Buy_URL_Hooks
 *
 * @group installer-546
 */
class Test_OTGS_Installer_Buy_URL_Hooks extends OTGS_TestCase {

	/**
	 * @test
	 */
	public function it_adds_hooks() {
		$subject = new OTGS_Installer_Buy_URL_Hooks( 'types' );
		\WP_Mock::expectFilterAdded( 'wp_installer_buy_url', array( $subject, 'append_installer_source' ) );
		$subject->add_hooks();
	}

	/**
	 * @test
	 */
	public function it_appends_installer_source() {
		$embedded_at = 'types';
		$subject     = new OTGS_Installer_Buy_URL_Hooks( $embedded_at );

		$url      = 'https://toolset.com/?add-to-cart=1089281&buy_now=1&icl_site_url=http://installer544.local';
		$expected = 'https://toolset.com/?add-to-cart=1089281&buy_now=1&icl_site_url=http://installer544.local&' . $embedded_at;

		\WP_Mock::userFunction( 'add_query_arg', array(
			'args'   => array( 'embedded_at', $embedded_at, $url ),
			'return' => $expected,
		) );

		$this->assertEquals( $expected, $subject->append_installer_source( $url ) );
	}
}