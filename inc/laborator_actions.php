<?php
/**
 *    Oxygen WordPress Theme
 *
 *    Laborator.co
 *    www.laborator.co
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

/**
 * After Setup Theme
 */
function laborator_after_setup_theme() {
	global $_wp_additional_image_sizes;

	// Theme Support
	add_theme_support( 'menus' );
	add_theme_support( 'widgets' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'featured-image' );
	add_theme_support( 'title-tag' );

	// Add theme support for WooCommerce
	add_theme_support( 'woocommerce', array(
		'single_image_width'    => apply_filters( 'oxygen_woocommerce_single_image_width', 700 ),
		'thumbnail_image_width' => apply_filters( 'oxygen_woocommerce_thumbnail_image_width', 420 ),

		'product_grid' => array(
			'default_rows' => 4,
			'min_rows'     => 1,
			'max_rows'     => 20,

			'default_columns' => 3,
			'min_columns'     => 1,
			'max_columns'     => 6,
		),
	) );

	add_theme_support( 'wc-product-gallery-slider' );

	if ( apply_filters( 'oxygen_product_gallery_zoom_enable', true ) ) {
		add_theme_support( 'wc-product-gallery-zoom' );
	}

	if ( apply_filters( 'oxygen_product_gallery_lightbox_enable', true ) ) {
		add_theme_support( 'wc-product-gallery-lightbox' );
	}

	// Theme Textdomain
	load_theme_textdomain( 'oxygen', get_template_directory() . '/languages' );

	// Register Menus
	register_nav_menus( array(
		'main-menu'   => 'Main Menu',
		'top-menu'    => 'Top Menu',
		'footer-menu' => 'Footer Menu'
	) );

	// Header Type Constant
	$header_type = get_data( 'header_type' );

	if ( $header_type == '2-gray' ) {
		define( 'GRAY_MENU', 1 );
		$header_type = 2;
	}

	define( 'HEADER_TYPE', $header_type );
}

add_action( 'after_setup_theme', 'laborator_after_setup_theme', 100 );

/**
 * Base Functionality
 */
function oxygen_init() {
	global $theme_version;

	$theme_obj     = wp_get_theme();
	$theme_version = $theme_obj->get( 'Version' );

	if ( is_child_theme() ) {
		$template_dir  = basename( get_template_directory() );
		$theme_obj     = wp_get_theme( $template_dir );
		$theme_version = $theme_obj->get( 'Version' );
	}

	// Styles
	wp_register_style( 'oxygen-admin', oxygen_get_theme_assets_uri() . '/css/admin.css', null, null );
	wp_register_style( 'boostrap', oxygen_get_theme_assets_uri() . '/css/bootstrap.css', null, null );
	wp_register_style( 'oxygen-main', oxygen_get_theme_assets_uri() . '/css/oxygen.css', null, $theme_version );
	wp_register_style( 'style', get_template_directory_uri() . '/style.css', null, $theme_version );
	wp_register_style( 'entypo', oxygen_get_theme_assets_uri() . '/fonts/entypo/css/fontello.css', null, null );
	wp_register_style( 'font-awesome', oxygen_get_theme_assets_uri() . '/fonts/font-awesome/css/font-awesome.min.css', null, null );
	wp_register_style( 'custom-skin', oxygen_get_theme_assets_uri() . '/css/skin.css', null, $theme_version );

	// Scripts
	wp_register_script( 'bootstrap', oxygen_get_theme_assets_uri() . '/js/bootstrap.min.js', null, null, true );
	wp_register_script( 'oxygen-contact', oxygen_get_theme_assets_uri() . '/js/oxygen-contact.js', null, $theme_version, true );
	wp_register_script( 'oxygen-custom', oxygen_get_theme_assets_uri() . '/js/oxygen-custom.min.js', null, $theme_version, true );

	// Nivo Lightbox
	wp_register_script( 'nivo-lightbox', oxygen_get_theme_assets_uri() . '/js/nivo-lightbox/nivo-lightbox.js', null, null, true );
	wp_register_style( 'nivo-lightbox', oxygen_get_theme_assets_uri() . '/js/nivo-lightbox/nivo-lightbox.css', null, null );
	wp_register_style( 'nivo-lightbox-default', oxygen_get_theme_assets_uri() . '/js/nivo-lightbox/themes/default/default.css', null, null );

	// Perfect Scrollbar
	wp_register_script( 'perfect-scrollbar', oxygen_get_theme_assets_uri() . '/js/perfect-scrollbar/perfect-scrollbar.min.js', null, null, true );
	wp_register_style( 'perfect-scrollbar', oxygen_get_theme_assets_uri() . '/js/perfect-scrollbar/perfect-scrollbar.css', null, null );

	// Slick
	wp_register_script( 'slick', oxygen_get_theme_assets_uri() . '/js/slick/slick.min.js', null, $theme_version, true );
	wp_register_style( 'slick', oxygen_get_theme_assets_uri() . '/js/slick/slick.css', null, $theme_version );
	wp_register_style( 'slick-theme', oxygen_get_theme_assets_uri() . '/js/slick/slick-theme.css', array( 'slick' ), $theme_version );

	// Oxygen Admin
	wp_register_script( 'admin-js', oxygen_get_theme_assets_uri() . '/js/admin/main.min.js', array( 'jquery' ), $theme_version, true );
	wp_register_style( 'admin-css', oxygen_get_theme_assets_uri() . '/css/admin/main.min.css', null, $theme_version );


	// Google Map
	$google_api_key = oxygen_google_api_key();

	wp_register_script( 'google-map', '//maps.google.com/maps/api/js?libraries=geometry&key=' . esc_attr( $google_api_key ), null, null );

	// ACF Google API Key
	add_filter( 'acf/fields/google_map/api', 'oxygen_google_api_key_acf', 10 );

	// Cart ribbon position fix
	if ( ! in_array( (string) get_data( 'cart_ribbon_position' ), array( 'left', 'right' ) ) ) {
		set_theme_mod( 'cart_ribbon_position', 'right' );
	}
}

add_action( 'init', 'oxygen_init', 100 );

/**
 * Laborator Menu Setup
 */
function laborator_menu_setup() {

	// Menu Items
	$nav_menu_locations = get_theme_mod( 'nav_menu_locations' );

	$top_menu_args = array(
		'theme_location' => 'top-menu',
		'container'      => '',
		'menu_class'     => 'sec-nav-menu',
		'depth'          => 1,
		'echo'           => false
	);

	$main_menu_args = array(
		'theme_location' => 'main-menu',
		'container'      => '',
		'menu_class'     => 'nav',
		'echo'           => false
	);

	$main_menu = wp_nav_menu( $main_menu_args );
	$top_menu  = wp_nav_menu( $top_menu_args );

	define( 'OXYGEN_MENU_MAIN', $main_menu );
	define( 'OXYGEN_MENU_TOP', $top_menu );
}

add_action( 'template_redirect', 'laborator_menu_setup' );

/**
 * Widgets Init
 */
function oxygen_widgets_init() {
	// Blog Sidebar
	$blog_sidebar = array(
		'id'   => 'blog_sidebar',
		'name' => 'Blog Sidebar',

		'before_widget' => '<div class="sidebar widget %2$s %1$s">',
		'after_widget'  => '</div>',

		'before_title' => '<h3 class="widget-title">',
		'after_title'  => '</h3>'
	);

	register_sidebar( $blog_sidebar );

	// Footer Sidebar
	$footer_sidebar_columns = 4;

	switch ( get_data( 'footer_widgets_columns' ) ) {
		case "[1/2] Two Columns":
			$footer_sidebar_columns = 6;
			break;

		case "[1/4] Four Columns":
			$footer_sidebar_columns = 3;
			break;

		case "[1/6] Two Columns":
			$footer_sidebar_columns = 2;
			break;
	}

	$footer_sidebar = array(
		'id'   => 'footer_sidebar',
		'name' => 'Footer Sidebar',

		'before_widget' => '<div class="col-sm-' . $footer_sidebar_columns . '"><div class="footer-block %2$s %1$s">',
		'after_widget'  => '</div></div>',

		'before_title' => '<h3 class="widget-title">',
		'after_title'  => '</h3>'
	);

	register_sidebar( $footer_sidebar );

	// WooCommerce Related Widgets
	if ( oxygen_is_plugin_active( 'woocommerce/woocommerce.php' ) ) {

		// Shop Sidebar
		$shop_sidebar = array(
			'id'   => 'shop_sidebar',
			'name' => 'Shop Sidebar',

			'before_widget' => '<div id="%1$s" class="sidebar widget %2$s %1$s">',
			'after_widget'  => '</div>',

			'before_title' => '<h3 class="widget-title">',
			'after_title'  => '</h3>'
		);

		register_sidebar( $shop_sidebar );

		// Shop Footer Sidebar
		$shop_footer_sidebar_colums = 'col-sm-3';

		switch ( get_data( 'shop_sidebar_footer_columns' ) ) {
			case '2':
				$shop_footer_sidebar_colums = 'col-sm-6';
				break;

			case '3':
				$shop_footer_sidebar_colums = 'col-sm-4';
				break;
		}

		$shop_footer_sidebar = array(
			'id'   => 'shop_footer_sidebar',
			'name' => 'Shop Footer Sidebar',

			'before_widget' => '<div class="' . $shop_footer_sidebar_colums . '"><div class="sidebar widget %2$s %1$s">',
			'after_widget'  => '</div></div>',

			'before_title' => '<h3>',
			'after_title'  => '</h3>'
		);

		register_sidebar( $shop_footer_sidebar );
	}

}

add_action( 'widgets_init', 'oxygen_widgets_init', 100 );

/**
 * Enqueue Scritps and Stuff like that
 */
function oxygen_wp_enqueue_scripts() {
	global $theme_version;

	// Styles
	wp_enqueue_style( 'boostrap' );
	wp_enqueue_style( 'oxygen-main' );
	wp_enqueue_style( 'entypo' );
	wp_enqueue_style( 'font-awesome' );

	if ( ! is_child_theme() ) {
		wp_enqueue_style( 'style' );
	}

	// Custom Skin
	$use_skin_type   = get_data( 'use_skin_type' );
	$use_custom_skin = get_data( 'use_custom_skin' );

	if ( $use_custom_skin ) {
		wp_enqueue_style( 'custom-style', oxygen_get_theme_assets_uri() . '/css/custom-skin.css', null, $theme_version );
	} else if ( preg_match( "/([a-z0-9-]+)\.png$/", $use_skin_type, $matched_skin ) ) {
		$registered_skins = array(
			'skin-type-2' => 'blue',
			'skin-type-3' => 'green',
			'skin-type-4' => 'ocean',
			'skin-type-5' => 'orange',
			'skin-type-6' => 'pink',
			'skin-type-7' => 'purple',
			'skin-type-8' => 'turquoise',
		);

		if ( isset( $registered_skins[ $matched_skin[1] ] ) ) {
			$style_name = $registered_skins[ $matched_skin[1] ];

			wp_enqueue_style( 'style-' . $style_name, oxygen_get_theme_assets_uri() . '/css/skins/' . $style_name . '.css' );
		}
	}

	// Scripts
	wp_enqueue_script( array( 'jquery', 'bootstrap' ) );
}

add_action( 'wp_enqueue_scripts', 'oxygen_wp_enqueue_scripts', 100 );

/**
 * Print scripts
 */
function oxygen_wp_print_scripts() {

	?>
    <script type="text/javascript">
		var ajaxurl = ajaxurl || '<?php echo esc_attr( admin_url( 'admin-ajax.php' ) ); ?>';
    </script>
	<?php
}

add_action( 'wp_print_scripts', 'oxygen_wp_print_scripts', 100 );

/**
 * Deprecated Oxygen CSS
 */
function _deprecated_oxygen_custom_css() {
	$custom_css_general     = get_data( 'custom_css_general' );
	$custom_css_general_lg  = get_data( 'custom_css_general_lg' );
	$custom_css_general_md  = get_data( 'custom_css_general_md' );
	$custom_css_general_sm  = get_data( 'custom_css_general_sm' );
	$custom_css_general_xs  = get_data( 'custom_css_general_xs' );
	$custom_css_general_xxs = get_data( 'custom_css_general_xxs' );

	$custom_css = '<style>';

	if ( $custom_css_general ) {
		$custom_css .= PHP_EOL . $custom_css_general . PHP_EOL;
	}


	if ( $custom_css_general_md ) {
		$custom_css .= PHP_EOL . '@media screen and (min-width:992px){ ' . PHP_EOL . $custom_css_general_md . PHP_EOL . '}' . PHP_EOL;
	}

	if ( $custom_css_general_lg ) {
		$custom_css .= PHP_EOL . '@media screen and (min-width:1200px){ ' . PHP_EOL . $custom_css_general_lg . PHP_EOL . '}' . PHP_EOL;
	}

	if ( $custom_css_general_sm ) {
		$custom_css .= PHP_EOL . '@media screen and (min-width:768px) and (max-width:991px){ ' . PHP_EOL . $custom_css_general_sm . PHP_EOL . '}' . PHP_EOL;
	}

	if ( $custom_css_general_xs ) {
		$custom_css .= PHP_EOL . '@media screen and (min-width:480px) and (max-width:767px){ ' . PHP_EOL . $custom_css_general_xs . PHP_EOL . '}' . PHP_EOL;
	}

	if ( $custom_css_general_xxs ) {
		$custom_css .= PHP_EOL . '@media screen and (max-width:479px){ ' . PHP_EOL . $custom_css_general_xxs . PHP_EOL . '}' . PHP_EOL;
	}

	$custom_css .= '</style>';

	if ( $custom_css != '<style></style>' ) {
		echo compress_text( $custom_css );
	}
}

/**
 * WP Head scripts and other resources
 */
function oxygen_wp_head() {

	// Load font
	oxygen_load_font_style();

	// Custom CSS
	_deprecated_oxygen_custom_css();
}

add_action( 'wp_enqueue_scripts', 'oxygen_wp_head', 100 );

/**
 * WP Footer scripts and other resources
 */
function oxygen_wp_footer() {
	// Custom.js
	wp_enqueue_script( 'oxygen-custom' );

	// Tracking Code
	echo get_data( 'google_analytics' );
}

add_action( 'wp_footer', 'oxygen_wp_footer' );

/**
 * Admin print styles
 */
function oxygen_admin_print_styles() {

	?>
    <style>
        #toplevel_page_laborator_options .wp-menu-image {
            background: url(<?php echo get_template_directory_uri(); ?>/assets/images/laborator-icon.png) no-repeat 11px 8px !important;
            background-size: 16px !important;
        }

        #toplevel_page_laborator_options .wp-menu-image:before {
            display: none;
        }

        #toplevel_page_laborator_options .wp-menu-image img {
            display: none;
        }

        #toplevel_page_laborator_options:hover .wp-menu-image, #toplevel_page_laborator_options.wp-has-current-submenu .wp-menu-image {
            background-position: 11px -24px !important;
        }

    </style>
	<?php
}

add_action( 'admin_print_styles', 'oxygen_admin_print_styles', 100 );

/**
 * Laborator Menu Page
 */
function laborator_menu_page() {
	add_menu_page( 'Laborator', 'Laborator', 'edit_theme_options', 'laborator_options', 'laborator_main_page' );

	if ( lab_get( 'page' ) == 'laborator_options' ) {
		wp_redirect( admin_url( 'themes.php?page=theme-options' ) );
	}
}

add_action( 'admin_menu', 'laborator_menu_page', 10 );

/**
 * Redirect to theme options when accessing Laborator link in admin panel
 */
function laborator_main_page() {

	?>
    <div class="wrap">Redirecting...</div>
    <script type="text/javascript">
        window.location = '<?php echo admin_url( 'themes.php?page=theme-options' ); ?>';
    </script>
<?php
}

/**
 * Redirect to Theme Options
 */
function laborator_options() {
    wp_redirect( admin_url( 'themes.php?page=theme-options' ) );
}

/**
 * Documentation Page in Laborator menu item
 */
function laborator_menu_documentation() {
    add_submenu_page( 'laborator_options', 'Documentation', 'Help', 'edit_theme_options', 'laborator_docs', 'laborator_documentation_page' );
}

add_action( 'admin_menu', 'laborator_menu_documentation', 100 );

/**
 * Documentation page template
 */
function laborator_documentation_page() {
    get_template_part( 'inc/admin-tpls/page-theme-documentation' );
}

/**
 * Admin Enqueue Only
 */
function oxygen_admin_enqueue_scripts() {
    wp_enqueue_style( 'oxygen-admin' );
    wp_enqueue_style( 'admin-css' );
    wp_enqueue_script( 'admin-js' );
}

add_action( 'admin_enqueue_scripts', 'oxygen_admin_enqueue_scripts' );

/**
 * Register Theme Plugins
 */
function oxygen_register_required_plugins() {

    $plugins = array(

        array(
            'name'     => 'Advanced Custom Fields PRO',
            'slug'     => 'advanced-custom-fields-pro',
            'source'   => get_template_directory() . '/inc/thirdparty-plugins/advanced-custom-fields-pro.zip',
            'required' => false,
            'version'  => '5.8.11',
        ),

        array(
            'name'               => 'WooCommerce',
            'slug'               => 'woocommerce',
            'required'           => true,
            'version'            => '',
            'force_activation'   => false,
            'force_deactivation' => false,
            'external_url'       => ''
        ),

        array(
            'name'               => 'Slider Revolution',
            'slug'               => 'revslider',
            'source'             => get_template_directory() . '/inc/thirdparty-plugins/revslider.zip',
            'required'           => false,
            'version'            => '6.2.10',
            'force_activation'   => false,
            'force_deactivation' => false,
        ),

        array(
            'name'               => 'WPBakery Page Builder',
            'slug'               => 'js_composer',
            'source'             => get_template_directory() . '/inc/thirdparty-plugins/js_composer.zip',
            'required'           => true,
            'version'            => '6.2',
            'force_activation'   => false,
            'force_deactivation' => false,
            'external_url'       => ''
        ),

        array(
            'name'     => 'Envato Market (Theme Updater)',
            'slug'     => 'envato-market',
            'version'  => '2.0',
            'source'   => 'https://envato.github.io/wp-envato-market/dist/envato-market.zip',
            'required' => false
        ),

        array(
            'name'     => 'HubSpot All-In-One Marketing – Forms, Popups, Live Chat',
            'slug'     => 'leadin',
            'required' => false,
        ),
    );

    $plugins[] = array(
        'name'               => 'YITH WooCommerce Wishlist',
        'slug'               => 'yith-woocommerce-wishlist',
        'required'           => false,
        'version'            => '',
        'force_activation'   => false,
        'force_deactivation' => false,
    );

    $config = array(
        'id'           => 'oxygen',
        'default_path' => '',
        'parent_slug'  => 'themes.php',
        'menu'         => 'install-required-plugins',
        'has_notices'  => true,
        'is_automatic' => false,
    );

    tgmpa( $plugins, $config );

    // Fix issues with Update package not available
    add_filter( 'upgrader_pre_download', 'oxygen_tgmpa_fix_bundled_sources', 1000, 3 );
}

add_action( 'tgmpa_register', 'oxygen_register_required_plugins', 100 );

/**
 * Update ACF Pro Message
 */
function aurum_acfpr_update_message() {
    global $tgmpa;

    if ( $tgmpa->does_plugin_require_update( 'advanced-custom-fields-pro' ) ) {
        echo '<br>&mdash;<br>This is theme bundled plugin and all updates are free of charge, you can install updates only on <a href="' . admin_url( 'themes.php?page=install-required-plugins' ) . '">Appearance > Install Plugins</a>.';
    }
}

add_action( 'in_plugin_update_message-advanced-custom-fields-pro/acf.php', 'aurum_acfpr_update_message', 100 );

/**
 * Fix TGMPA bundled sources
 */
function oxygen_tgmpa_fix_bundled_sources( $return, $package, $upgrader ) {
    global $tgmpa;

    $skin = $upgrader->skin;

    // Make sure it is a plugin
    if ( $skin instanceof Plugin_Upgrader_Skin ) {
        $plugin_slug = dirname( $skin->plugin );

        if ( isset( $tgmpa->plugins[ $plugin_slug ] ) ) {
            $plugin = $tgmpa->plugins[ $plugin_slug ];

            if ( ! empty( $plugin['source'] ) ) {
                return $plugin['source'];
            }
        }
    }

    return $return;
}

/**
 * Set Visual Composer As Theme Mode
 */
function oxygen_visual_composer_init() {
    vc_set_as_theme();
}

add_action( 'vc_before_init', 'oxygen_visual_composer_init' );

/**
 * Ajax Contact Form
 */
function laborator_cf_process() {
    $resp = array( 'success' => false );

    $verify = post( 'verify' );

    $id      = post( 'id' );
    $name    = post( 'name' );
    $email   = post( 'email' );
    $phone   = post( 'phone' );
    $message = post( 'message' );

    $field_names = array(
        'name'    => __( 'Name', 'oxygen' ),
        'email'   => __( 'E-mail', 'oxygen' ),
        'phone'   => __( 'Phone Number', 'oxygen' ),
        'message' => __( 'Message', 'oxygen' ),
    );

    $resp['re'] = $verify;

    if ( wp_verify_nonce( $verify, 'contact-form' ) ) {
        $admin_email = get_option( 'admin_email' );
        $ip          = $_SERVER['REMOTE_ADDR'];

        if ( $id ) {
            $custom_receiver = get_post_meta( $id, 'email_notifications', true );

            if ( is_email( $custom_receiver ) ) {
                $admin_email = $custom_receiver;
            }
        }

        $email_subject = "[" . get_bloginfo( 'name' ) . "] New contact form message submitted.";
        $email_message = "New message has been submitted on your website contact form. IP Address: {$ip}\n\n=====\n\n";

        $fields = array(
            'name',
            'email',
            'phone',
            'message'
        );

        foreach ( $fields as $key ) {
            $val = post( $key );

            $field_label = isset( $field_names[ $key ] ) ? $field_names[ $key ] : ucfirst( $key );

            $email_message .= "{$field_label}:\n" . ( $val ? $val : '/' ) . "\n\n";
        }

        $email_message .= "=====\n\nThis email has been automatically sent from Contact Form.";

        $headers = array();

        if ( $email ) {
            $headers[] = "Reply-To: {$name} <{$email}>";
        }

        wp_mail( $admin_email, $email_subject, $email_message, $headers );

        $resp['success'] = true;
    }

    echo json_encode( $resp );
    exit;
}

add_action( 'wp_ajax_cf_process', 'laborator_cf_process' );
add_action( 'wp_ajax_nopriv_cf_process', 'laborator_cf_process' );

/**
 * Calculate the route
 */
function laborator_calc_route() {
    $json_encoded = wp_remote_get( lab_get( 'route_path' ) );
    $resp         = json_decode( wp_remote_retrieve_body( $json_encoded ) );

    echo json_encode( $resp );
    exit;
}

add_action( 'wp_ajax_laborator_calc_route', 'laborator_calc_route' );
add_action( 'wp_ajax_nopriv_laborator_calc_route', 'laborator_calc_route' );


	/**
	 * Fav Icon
	 */
	function oxygen_favicon() {
	$favicon_image    = get_data( 'favicon_image' );
	$apple_touch_icon = get_data( 'apple_touch_icon' );

	if ( $favicon_image || $apple_touch_icon ) {
	if ( is_ssl() ) {
		if ( $favicon_image ) {
			$favicon_image = str_replace( 'http:', 'https:', $favicon_image );
		}

		if ( $apple_touch_icon ) {
			$apple_touch_icon = str_replace( 'http:', 'https:', $apple_touch_icon );
		}
	}

	?><!-- Favicons -->
	<?php if ( $favicon_image ) : ?>
    <link rel="shortcut icon" href="<?php echo $favicon_image; ?>">
<?php endif; ?>
	<?php if ( $apple_touch_icon ) : ?>
    <link rel="apple-touch-icon" href="<?php echo esc_attr( $apple_touch_icon ); ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo esc_attr( $apple_touch_icon ); ?>">
<?php endif; ?>
	<?php
}
}

add_action( 'wp_head', 'oxygen_favicon', 100 );

/**
 * Testimonials Post type
 */
function oxygen_testimonials_postype() {
	register_post_type( 'testimonial',
		array(
			'labels'      => array(
				'name'          => 'Testimonials',
				'singular_name' => 'Testimonial',
			),
			'public'      => true,
			'has_archive' => true,
			'supports'    => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
			'menu_icon'   => 'dashicons-testimonial'
		)
	);
}

add_action( 'init', 'oxygen_testimonials_postype', 100 );

/**
 * Theme Options Link in Admin Bar
 */
function oxygen_modify_admin_bar( $wp_admin_bar ) {
	list( $plugin_updates, $updates_notification ) = oxygen_get_plugin_updates_requires();
	$icon = '<i class="wp-menu-image dashicons-before dashicons-admin-generic laborator-admin-bar-menu"></i>';

	$wp_admin_bar->add_menu( array(
		'id'    => 'laborator-options',
		'title' => $icon . wp_get_theme(),
		'href'  => home_url(),
		'meta'  => array( 'target' => '_blank' )
	) );

	$wp_admin_bar->add_menu( array(
		'parent' => 'laborator-options',
		'id'     => 'laborator-options-sub',
		'title'  => 'Theme Options',
		'href'   => admin_url( 'themes.php?page=theme-options' )
	) );

	if ( $plugin_updates > 0 ) {
		$wp_admin_bar->add_menu( array(
			'parent' => 'laborator-options',
			'id'     => 'install-plugins',
			'title'  => 'Update Plugins' . $updates_notification,
			'href'   => admin_url( 'themes.php?page=install-required-plugins' )
		) );
	}

	$wp_admin_bar->add_menu( array(
		'parent' => 'laborator-options',
		'id'     => 'laborator-custom-css',
		'title'  => 'Custom CSS',
		'href'   => admin_url( 'admin.php?page=laborator_custom_css' ),
	) );

	$wp_admin_bar->add_menu( array(
		'parent' => 'laborator-options',
		'id'     => 'laborator-demo-content-importer',
		'title'  => 'Demo Content Install',
		'href'   => admin_url( 'admin.php?page=laborator_demo_content_installer' ),
	) );

	$wp_admin_bar->add_menu( array(
		'parent' => 'laborator-options',
		'id'     => 'laborator-help',
		'title'  => 'Theme Help',
		'href'   => admin_url( 'admin.php?page=laborator_docs' ),
	) );

	$wp_admin_bar->add_menu( array(
		'parent' => 'laborator-options',
		'id'     => 'laborator-themes',
		'title'  => 'Browse Our Themes',
		'href'   => 'https://1.envato.market/bgEJb',
		'meta'   => array( 'target' => '_blank' )
	) );
}

add_action( 'admin_bar_menu', 'oxygen_modify_admin_bar', 150 );

/**
 * Theme Options Link Styling
 */
function mab_admin_print_styles() {
	?>
    <style>

    .laborator-admin-bar-menu {
        position: relative !important;
        display: inline-block;
        width: 16px !important;
        height: 16px !important;
        background: url(<?php echo get_template_directory_uri(); ?>/assets/images/laborator-icon.png) no-repeat 0px 0px !important;
        background-size: 16px !important;
        margin-right: 8px !important;
        top: 3px !important;
    }

    .rtl .laborator-admin-bar-menu {
        margin-left: 8px !important;
        margin-right: 0;
    }

    #wp-admin-bar-laborator-options:hover .laborator-admin-bar-menu {
        background-position: 0 -32px !important;
    }

    .laborator-admin-bar-menu:before {
        display: none !important;
    }

    #toplevel_page_laborator_options .wp-menu-image {
        background: url(<?php echo get_template_directory_uri(); ?>/assets/images/laborator-icon.png) no-repeat 11px 8px !important;
        background-size: 16px !important;
    }

    #toplevel_page_laborator_options .wp-menu-image:before {
        display: none;
    }

    #toplevel_page_laborator_options .wp-menu-image img {
        display: none;
    }

    #toplevel_page_laborator_options:hover .wp-menu-image, #toplevel_page_laborator_options.wp-has-current-submenu .wp-menu-image {
        background-position: 11px -24px !important;
    }

    </style><?php
}

add_action( 'admin_print_styles', 'mab_admin_print_styles' );
add_action( 'wp_print_styles', 'mab_admin_print_styles' );

/**
 * Plugin Updates Admin Menu Link
 */
function laborator_menu_page_plugin_updates() {

	// Updates Notification
	list( $plugin_updates, $updates_notification ) = oxygen_get_plugin_updates_requires();

	if ( $plugin_updates > 0 ) {
		add_submenu_page( 'laborator_options', 'Update Plugins', 'Update Plugins' . $updates_notification, 'edit_theme_options', 'install-required-plugins', 'laborator_null_function' );
	}
}

add_action( 'admin_menu', 'laborator_menu_page_plugin_updates' );

/**
 * Get number of theme assgined plugin updates
 */
function oxygen_get_plugin_updates_requires() {
	global $tgmpa;

	// Plugin Updates Notification
	$plugin_updates       = 0;
	$updates_notification = '';

	if ( $tgmpa instanceof TGM_Plugin_Activation && ! $tgmpa->is_tgmpa_complete() ) {
		// Plugins
		$plugins = $tgmpa->plugins;

		foreach ( $tgmpa->plugins as $slug => $plugin ) {
			if ( $tgmpa->is_plugin_active( $slug ) && true == $tgmpa->does_plugin_have_update( $slug ) ) {
				$plugin_updates ++;
			}
		}
	}

	if ( $plugin_updates > 0 ) {
		$updates_notification = " <span class=\"update-plugins\"><span class=\"lab-update-badge update-count\">{$plugin_updates}</span></span>";
	}

	return array( $plugin_updates, $updates_notification );
}

/**
 * Replace Download Link For Visual Composer
 */
function vc_remove_update_message() {
	remove_all_actions( 'in_plugin_update_message-js_composer/js_composer.php' );
}

add_action( 'init', 'vc_remove_update_message' );

/**
 * Change plugin update link for Visual Composer (WPBakery)
 */
function lab_vc_update_message() {
	echo '<style type="text/css" media="all">tr#wpbakery-visual-composer + tr.plugin-update-tr a.thickbox + em { display: none; }</style>';
	echo '<a href="' . admin_url( 'themes.php?page=install-required-plugins' ) . '">Check for available update.</a>';
}

add_action( 'in_plugin_update_message-js_composer/js_composer.php', 'lab_vc_update_message' );

/**
 * Get Google API Key
 */
function oxygen_google_api_key() {
	return apply_filters( 'oxygen_google_api_key', get_data( 'google_maps_api' ) );
}

/**
 * Apply Google API key to ACF
 *
 * @type filter
 */
function oxygen_google_api_key_acf() {
	$api = oxygen_google_api_key();

	return array(
		'libraries' => ( is_admin() ? 'places,' : '' ) . 'geometry',
		'key'       => $api
	);
}

/**
 * Custom Skin Regeneration
 */
if ( get_data( 'use_custom_skin' ) && trim( @file_get_contents( get_template_directory() . '/assets/css/custom-skin.css' ) ) == '' ) {
	oxygen_custom_skin_compile( array(
		'link-color'       => get_data( 'custom_skin_main_color' ),
		'menu-link'        => get_data( 'custom_skin_menu_link_color' ),
		'background-color' => get_data( 'custom_skin_background_color' ),
	) );
}

/**
 * Open Graph Meta
 */
function oxygen_wp_head_open_graph_meta() {

	// Only show if open graph meta is allowed
	if ( ! apply_filters( 'oxygen_open_graph_meta', true ) ) {
		return;
	}

	// Do not show open graph meta on single posts
	if ( ! is_singular() ) {
		return;
	}

	$image = '';

	// Use current queried object
	setup_postdata( get_queried_object() );

	if ( has_post_thumbnail() ) {
		$featured_image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'original' );
		$image          = esc_attr( $featured_image[0] );
	}

	// Excerpt, clean styles
	$excerpt = laborator_clean_excerpt( get_the_excerpt(), true );

	?>
    <meta property="og:type" content="article"/>
    <meta property="og:title" content="<?php echo esc_attr( get_the_title() ); ?>"/>
    <meta property="og:url" content="<?php echo esc_url( get_permalink() ); ?>"/>
    <meta property="og:site_name" content="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"/>
    <meta property="og:description" content="<?php echo esc_attr( $excerpt ); ?>"/>

	<?php if ( '' != $image ) : ?>
        <meta property="og:image" content="<?php echo $image; ?>"/>
	<?php endif;

	wp_reset_postdata();
}

add_action( 'wp_head', 'oxygen_wp_head_open_graph_meta', 5 );

/**
 * Slider Revolution set as Theme
 */
if ( function_exists( 'set_revslider_as_theme' ) ) {
	set_revslider_as_theme();
}

/**
 * Remove slider revolution notice
 */
function oxygen_admin_notices_remove_revslider_notice() {

	// Revolution slider
	if ( class_exists( 'RevSliderAdmin' ) ) {
		remove_action( 'after_plugin_row_revslider/revslider.php', array(
			'RevSliderAdmin',
			'show_purchase_notice'
		), 10, 3 );
	}
}

add_action( 'admin_notices', 'oxygen_admin_notices_remove_revslider_notice', 1000 );

/**
 * Search results count
 */
function oxygen_search_results_count( $query ) {
	if ( ! is_admin() && $query->is_main_query() && $query->is_search() ) {
		$cols          = 3;
		$results_count = apply_filters( 'oxygen_search_results_per_page', $cols * absint( get_data( 'search_results_count' ) ), $cols );
		$query->set( 'posts_per_page', $results_count );
	}
}

add_action( 'pre_get_posts', 'oxygen_search_results_count', 100 );

/**
 * Upgrade to 4.8
 */
function oxygen_upgrade_to_4_8() {
	global $wpdb;

	if ( ! get_option( 'oxygen_upgrade_4_8', false ) ) {

		// Store supported payment methods in theme options
		$supported_payment_methods = get_option( 'laborator_supported_payments' );

		if ( is_array( $supported_payment_methods ) && count( $supported_payment_methods ) ) {
			$supported_payment_methods_str = '<ul class="payment-methods">' . PHP_EOL;

			foreach ( $supported_payment_methods as $payment_method ) {
				$image = get_array_key( $payment_method, 'p_image_1' );

				if ( ! empty( $image['original'] ) ) {
					$link   = get_array_key( $payment_method, 'link' );
					$target = get_array_key( $payment_method, 'blank_page' ) ? '_blank' : '_self';
					$name   = get_array_key( $payment_method, 'name' );

					$image_path = ABSPATH . '/' . $image['original'];
					$image_url  = site_url( $image['original'] );

					$width = $height = '';

					if ( $image_size = @getimagesize( $image_path ) ) {
						$width  = $image_size[0] / 2;
						$height = $image_size[1] / 2;
					}

					// Hover image
					$hover_image = '';
					$hover       = '';

					if ( ! empty( $payment_method['p_image_2'] ) ) {
						$hover_width = $hover_height = '';

						if ( $hover_image_size = @getimagesize( ABSPATH . $payment_method['p_image_2']['original'] ) ) {
							$hover_width  = $hover_image_size[0] / 2;
							$hover_height = $hover_image_size[1] / 2;
						}

						$hover_image_url = site_url( $payment_method['p_image_2']['original'] );
						$hover_image     = "\n\t\t<img src=\"$hover_image_url\" width=\"$hover_width\" height=\"$hover_height\" class=\"hover-img\" >";
						$hover           = ' hover';
					}

					$s                             = '$s';
					$d                             = '$d';
					$supported_payment_methods_str .= sprintf( "<li>\n\t<a href=\"%1$s\" target=\"%2$s\" class=\"payment-slide$hover\">\n\t\t<img src=\"%3$s\" alt=\"%4$s\" width=\"%5$d\" height=\"%6$d\" class=\"normal-img\" />%7$s\n\t</a>\n</li>\n", esc_url( $link ), $target, $image_url, esc_attr( $name ), $width, $height, $hover_image );
				}
			}

			$supported_payment_methods_str .= '</ul>';
			set_theme_mod( 'footer_text_right', $supported_payment_methods_str );
		}

		// Transfer theme options
		set_theme_mod( 'cart_ribbon_position', get_data( 'cart_ribbon_position' ) ? 'left' : 'right' );
		set_theme_mod( 'sidebar_menu_position', get_data( 'sidebar_menu_position' ) ? 'left' : 'right' );

		// Run this once
		update_option( 'oxygen_upgrade_4_8', true );
	}
}

add_action( 'init', 'oxygen_upgrade_to_4_8' );

/**
 * WooCommerce 3.3+ migration
 */
if ( class_exists( 'WC' ) && version_compare( WC()->version, '3.3', '>=' ) && false == get_option( 'oxygen_woocommerce_3_4_transfer_image_sizes', false ) ) {

	function _get_aspect_ratio( $a, $b ) {
		// sanity check
		if ( $a <= 0 || $b <= 0 ) {
			return array( 0, 0 );
		}
		$total = $a + $b;
		for ( $i = 1; $i <= 40; $i ++ ) {
			$arx = $i * 1.0 * $a / $total;
			$brx = $i * 1.0 * $b / $total;
			if ( $i == 40 || ( abs( $arx - round( $arx ) ) <= 0.02 && abs( $brx - round( $brx ) ) <= 0.02 ) ) {
				// Accept aspect ratios within a given tolerance
				return array( round( $arx ), round( $brx ) );
			}
		}
	}

	function oxygen_woocommerce_3_4_transfer_image_sizes() {

		// Whether to resize or not
		$do_resize = false;

		// WooCommerce Thumbnail
		if ( ( $shop_catalog_image_size = get_theme_mod( 'shop_loop_image_size' ) ) ) {

			// {width}x{height} format
			if ( preg_match( '#^(?<width>[0-9]+)x(?<height>[0-9]+)(x(?<cropped>0|1))?$#', $shop_catalog_image_size, $shop_catalog_image_size_matches ) ) {
				$width    = $shop_catalog_image_size_matches['width'];
				$height   = $shop_catalog_image_size_matches['height'];
				$cropping = ! isset( $shop_catalog_image_size_matches['cropped'] ) || $shop_catalog_image_size_matches['cropped'];

				$ratio = _get_aspect_ratio( $width, $height );

				update_option( 'woocommerce_thumbnail_cropping', $cropping ? 'custom' : 'uncropped' );
				update_option( 'woocommerce_thumbnail_cropping_custom_width', $ratio[0] );
				update_option( 'woocommerce_thumbnail_cropping_custom_height', $ratio[1] );

				$do_resize = true;
			} // Crop width only
			else if ( is_numeric( $shop_catalog_image_size ) ) {
				update_option( 'woocommerce_thumbnail_cropping', 'uncropped' );
				update_option( 'woocommerce_thumbnail_cropping_custom_width', $shop_catalog_image_size );

				$do_resize = true;
			}
		}

		// Request image regeneration
		if ( $do_resize && class_exists( 'WC_Regenerate_Images' ) ) {
			WC_Regenerate_Images::queue_image_regeneration();
		}

		// Initialize lightbox and zoom options
		set_theme_mod( 'shop_single_lightbox', 1 );
		set_theme_mod( 'shop_magnifier', 1 );

		// WooCommerce columns
		$shop_product_columns = get_data( 'shop_products_per_column' );

		update_option( 'woocommerce_catalog_columns', oxygen_get_number_from_word( 'decide' == $shop_product_columns ? 4 : $shop_product_columns ) );

		if ( preg_match( '#[0-9]+#', get_data( 'shop_products_per_page' ), $matches ) ) {
			update_option( 'woocommerce_catalog_rows', $matches[0] );
		}

		// Run this once
		update_option( 'oxygen_woocommerce_3_4_transfer_image_sizes', true );
	}

	add_action( 'woocommerce_init', 'oxygen_woocommerce_3_4_transfer_image_sizes' );
}

/**
 * Go to top
 */
if ( ! function_exists( 'oxygen_go_to_top_link' ) ) {

	function oxygen_go_to_top_link() {

		if ( get_data( 'go_to_top' ) ) {

			$offset      = get_data( 'go_to_top_offset' );
			$button_type = get_data( 'go_to_top_button_type' );
			$alignment   = get_data( 'go_to_top_alignment' );

			// Offset type
			$type = 'pixels';

			if ( strpos( $offset, '%' ) ) {
				$type = 'percentage';
			} else if ( 'footer' == trim( strtolower( $offset ) ) ) {
				$type = 'footer';
			}

			// Classes
			$classes = array( 'go-to-top' );

			$classes[] = 'go-to-top--' . $alignment;

			if ( 'circle' == $button_type ) {
				$classes[] = 'go-to-top--rounded';
			}

			?>
            <a <?php oxygen_class_attr( $classes ); ?> href="#top" data-offset-type="<?php echo $type; ?>"
                                                       data-offset-value="<?php echo 'footer' != $offset ? intval( $offset ) : esc_attr( $offset ); ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <path d="M0 16.67l2.829 2.83 9.175-9.339 9.167 9.339 2.829-2.83-11.996-12.17z"/>
                </svg>
            </a>
			<?php

		}
	}
}

add_action( 'wp_footer', 'oxygen_go_to_top_link', 100 );


/**
 * Warn users to install ACF5 Pro
 *
 * @type action
 */
function oxygen_acf5_warning_init() {
	$is_using_acf4 = function_exists( 'acf' ) ? version_compare( acf()->version, '4.4.12', '<=' ) : false;

	if ( $is_using_acf4 && 'oxygen-install-plugins' !== lab_get( 'page' ) ) {
		add_action( 'admin_notices', 'oxygen_acf5_warning_display', 1000 );

		// Plugin disable and enable
		if ( post( 'oxygen_acf4_deactivate' ) && current_user_can( 'manage_options' ) ) {
			$acf4_plugin = 'advanced-custom-fields/acf.php';
			deactivate_plugins( array(
				$acf4_plugin,
				'acf-flexible-content/acf-flexible-content.php',
				'acf-gallery/acf-gallery.php',
				'acf-repeater/acf-repeater.php',
			) );
			die( did_action( "deactivate_{$acf4_plugin}" ) ? '1' : '-1' );
		}
	}

	// Activate ACF5 Pro
	if ( post( 'oxygen_acf5_activate' ) && current_user_can( 'manage_options' ) ) {
		$acf5_plugin = 'advanced-custom-fields-pro/acf.php';
		$all_plugins = apply_filters( 'all_plugins', get_plugins() );
		$success     = - 1;

		// Install and activate the plugin
		if ( ! isset( $all_plugins[ $acf5_plugin ] ) ) {

			if ( ! class_exists( 'Plugin_Upgrader', false ) ) {
				require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
			}

			// Plugin file
			$download_url = TGM_Plugin_Activation::get_instance()->get_download_url( 'advanced-custom-fields-pro' );

			$skin_args = array(
				'type'   => 'upload',
				'title'  => "ACF Pro",
				'url'    => '',
				'nonce'  => 'install-plugin_advanced-custom-fields-pro',
				'plugin' => '',
				'api'    => '',
				'extra'  => array(),
			);

			$skin = new Plugin_Installer_Skin( $skin_args );

			// Create a new instance of Plugin_Upgrader.
			$upgrader = new Plugin_Upgrader( $skin );
			$upgrader->install( $download_url );
			$success = 1;

			// Update list of activated plugins
			$all_plugins = apply_filters( 'all_plugins', get_plugins() );
		}

		// Plugin exists, simply activate it
		if ( isset( $all_plugins[ $acf5_plugin ] ) ) {
			activate_plugins( $acf5_plugin );
			if ( did_action( 'activated_plugin' ) ) {
				$success = 1;
			}
		}

		die( (string) $success );
	}
}

function oxygen_acf5_warning_display() {
	$install_button = '<button type="button" class="button" id="oxygen-acf5-pro-install-button"><i class="loading"></i> Deactivate ACF4 &amp; Install ACF5 Pro</button>';
	$acf_warning    = sprintf( 'You are currently using <strong>Advanced Custom Fields &ndash; %s</strong> which will not be supported in the upcoming updates of Kalium!<br><br>Please install and activate <strong>Advanced Custom Fields 5 (Pro)</strong> plugin which is bundled with the theme <em>(free of charge)</em> either by installing from <a href="%s">Appearance &gt; Install Plugins</a> or clicking the button below which will deactivate previous version and install/activate ACF5 Pro automatically: <br><br>%s<br><br><em>Note: ACF4 and its addons will not be deleted (<a href="https://d.pr/i/RbEchZ" target="_blank">see here</a>), however we recommend you to delete them after installing ACF5 Pro.</em>', acf()->version, esc_url( admin_url( 'themes.php?page=tgmpa-install-plugins' ) ), $install_button );

	?>
    <div class="laborator-notice notice notice-warning">
		<?php echo wpautop( $acf_warning ); ?>
    </div>
	<?php
}

add_action( 'admin_init', 'oxygen_acf5_warning_init', 10 );

/**
 * Frontend Edit modify admin bar link title
 *
 * @param WP_Admin_Bar $wp_admin_bar
 */
function oxygen_admin_bar_button_frontend_edit( $wp_admin_bar ) {
	if ( $node = $wp_admin_bar->get_node( 'vc_inline-admin-bar-link' ) ) {
		$node->title = 'Edit Frontend';
		$wp_admin_bar->add_node( get_object_vars( $node ) );
	}
}

add_action( 'admin_bar_menu', 'oxygen_admin_bar_button_frontend_edit', 1001 );
