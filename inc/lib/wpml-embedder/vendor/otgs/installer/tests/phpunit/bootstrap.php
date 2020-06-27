<?php
date_default_timezone_set( 'America/New_York' );

use tad\FunctionMocker\FunctionMocker;

define( 'WP_INSTALLER_VERSION', '1.0.0' );
define( 'WP_INSTALLER_URL', 'http://site.org/vendor/otgs/installer' );

define( 'TEST_ROOT_DIR', __DIR__ );

define( 'MAIN_DIR', __DIR__ . '/../../');

$autoloader_dir = __DIR__ . '../../../vendor';
$autoloader = $autoloader_dir . '/autoload.php';

require_once $autoloader;


FunctionMocker::init([
	'whitelist' => [ MAIN_DIR. 'includes', MAIN_DIR . 'vendor' ],
	'redefinable-internals' => ['constant', 'time']
]);

include_once __DIR__ . '/../../includes/class-otgs-installer-autoloader.php';

global $wp_installer_instances;

$wp_installer_instances = array();

$autoloader = new OTGS_Installer_Autoloader();
$autoloader->initialize();

if ( file_exists( __DIR__ . '/db_config.php' ) ) {
	require_once __DIR__ . '/db_config.php';
} else {
	define( 'DBH_DRIVER', 'mysql' );
	define( 'DBH_NAME', 'wp_installer_test' );
	define( 'DBH_USER', 'root' );
	define( 'DBH_PASSWORD', 'root' );
	define( 'DBH_HOST', 'mysql' );
	define( 'DBH_TABLE_PREFIX', 'wp_' );
	define( 'DBH_DEBUG', true );
}

define( 'ICL_REP_VERSION', 0.1 );
define( 'ICL_REP_URL', 'http://api.wpml.lcoal' );
define( 'ICL_REP_PATH', MAIN_DIR . '/vendor/website/api/' );

define( 'ICL_REP_SITE', 'WPML' );
define( 'ICL_REP_HOST_URL', 'http://wpml_org.local/' );
define( 'ICL_REP_HOST_PATH', '/home/amir/websites/types/wordpress' );
define( 'ICL_REP_AUTHOR', 'wpml_org.local' );

define( 'ICL_REP_BUCKET_URL', 'https://s3.amazonaws.com/wpml-products' );
define( 'ICL_REP_REPOSITORY', 'testingRepo' );

define( 'ICL_REP_CREATE_BUCKET_TABLE', false );
define( 'ICL_REP_BUCKET_MIN', 1 );
define( 'ICL_REP_BUCKET_MAX', 100 );
define( 'ICL_REP_CUSTOM_BUCKETS', [ 'testing', 'staging', 'compatibility' ] );


define( "OTGS_INSTALLER_WPML_PRODUCTS", 'http://mocked_wpml_products_url.com/xyz' );
define( "OTGS_INSTALLER_TOOLSET_PRODUCTS", 'http://mocked_toolset_products_url.com/xyz' );

require_once __DIR__ . '/InstallerIntegrationTest.php';
require_once __DIR__ . '/integration/mocker/OTGS_Installer_API_Mocker.php';
require_once __DIR__ . '/integration/mocker/OTGS_Installer_Endpoint_Mock.php';
require_once __DIR__ . '/integration/OTGS_Installer_Integration_Base_Test.php';

require_once ICL_REP_PATH . 'inc/functions-db.php';
$dbhandler = new dbhandler( DBH_USER, DBH_PASSWORD, DBH_NAME, DBH_HOST );
$dbhandler->query( "
                CREATE TABLE IF NOT EXISTS `" . DBH_TABLE_PREFIX . "site_keys` ( 
                    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                    `website_url` varchar(255) NOT NULL,
                    `site_key` varchar(10) NOT NULL,
                    `time_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    `last_used` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    `user_id` bigint(20) NOT NULL,
                    `from_purchase` tinyint(4) NOT NULL,
                    `project_id` BIGINT(20) NULL,
                    PRIMARY KEY (`id`),
                    UNIQUE KEY `site_key` (`site_key`),
                    KEY `user_id` (`user_id`),
                    KEY `from_purchase` (`from_purchase`)
                )
            " );

require_once ICL_REP_PATH . 'inc/CreateBucketTable.php';


require_once MAIN_DIR . '/vendor/otgs/unit-tests-framework/phpunit/bootstrap.php';

