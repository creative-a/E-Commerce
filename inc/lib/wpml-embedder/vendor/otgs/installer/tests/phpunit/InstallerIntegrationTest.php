<?php

function response_header( $header ) {
	//header( $header );
}

class InstallerIntegrationTest extends OTGS_TestCase {

	/** @var dbhandler */
	private $dbhandler;

	public function setUp() {
		parent::setUp();
		global $dbhandler, $factory;

		require_once ICL_REP_PATH . 'inc/functions-db.php';
		$this->dbhandler = $dbhandler = new dbhandler( DBH_USER, DBH_PASSWORD, DBH_NAME, DBH_HOST );

		require_once ICL_REP_PATH . 'inc/Factory.php';
		$factory = new Factory( $this->dbhandler );

		\WP_Mock::userFunction( 'wp_remote_post', [] );
		\WP_Mock\Handler::register_handler( 'wp_remote_post', [ $this, 'wp_remote_post' ] );
		\WP_Mock::userFunction( 'wp_remote_get', [] );
		\WP_Mock\Handler::register_handler( 'wp_remote_get', [ $this, 'wp_remote_get' ] );
		\WP_Mock::userFunction( 'wp_remote_retrieve_body', [] );
		\WP_Mock\Handler::register_handler( 'wp_remote_retrieve_body', [ $this, 'wp_remote_retrieve_body' ] );
		\WP_Mock::userFunction( 'is_wp_error', [ 'return' => false ] );


	}

	public function tearDown() {
		$this->dbhandler->query( "DELETE FROM " . DBH_TABLE_PREFIX . "site_keys" );
		$this->dbhandler->query( "DELETE FROM " . DBH_TABLE_PREFIX . "user_buckets" );
		parent::tearDown();
	}

	protected function register_site_in_repository( $site_key, $site_url ) {
		$any_user_id    = 123;
		$any_project_id = 11;

		$key['site_key']      = $site_key;
		$key['website_url']   = $site_url;
		$key['last_used']     = $this->current_time();
		$key['from_purchase'] = 1;
		$key['user_id']       = $any_user_id;
		$key['project_id']    = $any_project_id;

		$this->dbhandler->replace( DBH_TABLE_PREFIX . 'site_keys', $key );
	}

	private function current_time() {
		return gmdate( 'Y-m-d H:i:s', time() );
	}

	public function wp_remote_post( $url, $args ) {
		$response = null;

		if ( $this->is_api_url( $url ) ) {
			ob_start();
			$_POST = $args['body'];
			include ICL_REP_PATH . 'init.php';

			$response['body']             = ob_get_clean();
			$response['response']['code'] = 200;
		}

		return $response;
	}

	public function wp_remote_get( $url ) {
		$response = null;

		if ( $this->is_main_product_json_url( $url ) ) {
			$response['body'] = file_get_contents( TEST_ROOT_DIR . '/data/wpml33-products.json' );
			$response['response']['code'] = 200;
		} elseif ( $this->is_bucket_json_url( $url ) ) {
			$response['body'] = file_get_contents( TEST_ROOT_DIR . '/data/wpml33-products-bucket-version.json' );
			$response['response']['code'] = 200;
		}

		return $response;
	}

	public function wp_remote_retrieve_body( $response ) {
		return $response['body'];
	}

	private function is_api_url( $url ) {
		$repositories_config = new OTGS_Products_Config_Xml( $this->get_xml_config_file() );

		return
			$url === $repositories_config->get_products_api_urls()['wpml'] ||
			$url === ICL_REP_URL;
	}

	private function is_main_product_json_url( $url ) {
		$repositories_config = new OTGS_Products_Config_Xml( $this->get_xml_config_file() );
		$products_url        = $repositories_config->get_repository_products_url( 'wpml' );

		return $url === $products_url;
	}

	private function is_bucket_json_url( $url ) {
		return strpos( $url, 'bucket-' ) > 0;
	}

	private function get_xml_config_file() {
		$file_name         = 'repositories.xml';
		$sandbox_file_name = 'repositories.sandbox.xml';

		if ( file_exists( $this->get_config_file_path( $sandbox_file_name ) ) ) {
			return $this->get_config_file_path( $sandbox_file_name );
		}

		if ( file_exists( $this->get_config_file_path( $file_name ) ) ) {
			return $this->get_config_file_path( $file_name );
		}

		return null;
	}

	/**
	 * @return string
	 */
	private function get_config_file_path( $file_name ) {
		return MAIN_DIR . '/tests/phpunit/data/' . $file_name;
	}


}
