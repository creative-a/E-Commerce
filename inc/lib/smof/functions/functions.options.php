<?php

add_action( 'init','of_options' );

if ( ! function_exists( 'of_options' ) ) {
	
	function of_options() {


/*-----------------------------------------------------------------------------------*/
/* The Options Array */
/*-----------------------------------------------------------------------------------*/

// Set the Options Array
global $of_options;
$of_options = array();

### OXYGEN ###
$of_options[] = array( 	"name" 		=> "Header",
						"type" 		=> "heading"
				);

$of_options[] = array(  "name"   	=> "Site Brand",
						"desc"   	=> "Enter the text that will appear as logo.",
						"id"   		=> "logo_text",
						"std"   	=> get_bloginfo('title'),
						"type"   	=> "text"
					);

$of_options[] = array(
						"desc"   	=> "Upload Brand Logo",
						"id"   		=> "use_uploaded_logo",
						"std"   	=> 0,
						"folds"  	=> 1,
						"on"  		=> "Yes",
						"off"  		=> "No",
						"type"   	=> "switch"
					);

$of_options[] = array(	"name" 		=> "Brand Logo",
						"desc" 		=> "Upload/Choose your custom logo image from gallery if you want to use it instead of the default site title text.",
						"id" 		=> "custom_logo_image",
						"std" 		=> "",
						"type" 		=> "media",
						"mod" 		=> "min",
						"fold" 		=> "use_uploaded_logo"
					);

$of_options[] = array( 	"desc" 		=> "You can set maximum width for the uploaded logo, mostly used when you use upload retina (@2x) logo. Pixels unit.",
						"id" 		=> "custom_logo_max_width",
						"std" 		=> "",
						"plc"		=> "Maximum Logo Width",
						"type" 		=> "text",
						"fold" 		=> "use_uploaded_logo"
				);

$of_options[] = array(	"name"		=> "Mobile Logo",
						"desc" 		=> "Logo to show on mobile devices. The width and height will cut down by 50% to display retina ready logo.",
						"id" 		=> "custom_logo_image_responsive",
						"std" 		=> "",
						"type" 		=> "media",
						"mod" 		=> "min",
						"fold" 		=> "use_uploaded_logo"
					);

$of_options[] = array( 	"desc" 		=> "You can set maximum width for the mobile logo, mostly used when you use upload retina (@2x) logo. Pixels unit.",
						"id" 		=> "custom_logo_image_responsive_width",
						"std" 		=> "",
						"plc"		=> "Maximum Mobile Logo Width",
						"type" 		=> "text",
						"fold" 		=> "use_uploaded_logo"
				);

$of_options[] = array( 	"name" 		=> "Header Type",
						"desc" 		=> "",
						"id" 		=> "header_type",
						"std" 		=> "2",
						"type" 		=> "images",
						"options" 	=> array(
							'1'      => oxygen_get_theme_assets_uri() . '/images/header-type-1.jpg',
							'2'      => oxygen_get_theme_assets_uri() . '/images/header-type-2.jpg',
							'2-gray' => oxygen_get_theme_assets_uri() . '/images/header-type-2-gray.jpg',
							'3'      => oxygen_get_theme_assets_uri() . '/images/header-type-3.jpg',
							'4'      => oxygen_get_theme_assets_uri() . '/images/header-type-4.jpg',
						)
				);

$of_options[] = array( 	"name" 		=> "Sidemenu Options",
						"desc" 		=> "Sidemenu alignment",
						"id" 		=> "sidebar_menu_position",
						"std" 		=> 1,
						"type" 		=> "select",
						"options" 	=> array(
							'left' => 'Left',
							'right' => 'Right',
						)
				);


$of_options[] = array( 	"desc" 		=> "Sub menus<br><small>Expand or collapse sub menu items by default.</small>",
						"id" 		=> "sidebar_menu_links_display",
						"std" 		=> "Collapsed",
						"type" 		=> "select",
						"options" 	=> array("Collapsed", "Expanded")
				);

$of_options[] = array( 	"name" 		=> "Sticky Menu",
						"desc" 		=> "Enable or disable sticky menu (if supported by header type).",
						"id" 		=> "header_sticky_menu",
						"std" 		=> 1,
						"on" 		=> "Enable",
						"off" 		=> "Disable",
						"type" 		=> "switch"
				);

$of_options[] = array( 	"name" 		=> "Search Form in Header",
						"desc" 		=> "Enable or disable search form in the main menu.",
						"id" 		=> "header_menu_search",
						"std" 		=> 1,
						"on" 		=> "Show",
						"off" 		=> "Hide",
						"type" 		=> "switch"
				);

$of_options[] = array( "name" 		=> "Cart Ribbon",
						"desc" 		=> "Show cart ribbon in the page header (right side).",
						"id" 		=> "cart_ribbon_show",
						"std" 		=> 1,
						"on" 		=> "Show",
						"off" 		=> "Hide",
						"type" 		=> "switch",
						"folds"		=> 1
				);

$of_options[] = array( 	#"name" 		=> "Cart Ribbon Image",
						"desc" 		=> "Select a cart ribbon image.",
						"id" 		=> "cart_ribbon_image",
						"std" 		=> oxygen_get_theme_assets_uri() . '/images/cart-icon-1-black.jpg',
						"type" 		=> "tiles",
						"options"	=> array(
							oxygen_get_theme_assets_uri() . '/images/cart-icon-1-black.jpg',
							oxygen_get_theme_assets_uri() . '/images/cart-icon-3-black.jpg',
							oxygen_get_theme_assets_uri() . '/images/cart-icon-2-black.jpg',
							oxygen_get_theme_assets_uri() . '/images/cart-icon-4-black.jpg',
						),
						"fold"		=> "cart_ribbon_show"
				);

$of_options[] = array( 	"desc" 		=> "Cart ribbon position. Only applied for <strong>top menu</strong> header type.",
						"id" 		=> "cart_ribbon_position",
						"std" 		=> 1,
						"type" 		=> "select",
						"options" 	=> array(
							'left' => 'Left',
							'right' => 'Right',
						),
						"fold"		=> "cart_ribbon_show"
				);

$of_options[] = array( "name" 		=> "Header Social Networks",
						"desc" 		=> "Show social networks on top menu container.",
						"id" 		=> "top_menu_social",
						"std" 		=> 0,
						"on" 		=> "Show",
						"off" 		=> "Hide",
						"type" 		=> "switch"
				);

$of_options[] = array(	"desc" 		=> "Show social networks on mobile menu container.",
						"id" 		=> "social_mobile_menu",
						"std" 		=> 0,
						"on" 		=> "Show",
						"off" 		=> "Hide",
						"type" 		=> "switch"
				);

$of_options[] = array( "name" 		=> "Top Menu in Mobile",
						"desc" 		=> "Show top menu on mobile screens.",
						"id" 		=> "top_menu_mobile",
						"std" 		=> 0,
						"on" 		=> "Show",
						"off" 		=> "Hide",
						"type" 		=> "switch"
				);


$of_options[] = array( 	"name" 		=> "Footer",
						"type" 		=> "heading"
				);

$of_options[] = array( 	"name" 		=> "Footer Widgets",
						"desc" 		=> "Show or hide footer widgets.",
						"id" 		=> "footer_widgets",
						"std" 		=> 1,
						"on" 		=> "Show",
						"off" 		=> "Hide",
						"type" 		=> "switch",
						"folds"		=> 1
				);


$of_options[] = array( 	"name" 		=> "Columns Count",
					 	"desc" 		=> "Select the type of footer widgets column to show.",
						"id" 		=> "footer_widgets_columns",
						"std" 		=> "[1/3] Three Columns",
						"type" 		=> "select",
						"options" 	=> array("[1/2] Two Columns", "[1/3] Three Columns", "[1/4] Four Columns", "[1/6] Six Columns"),
						"fold"		=> "footer_widgets"
				);

$of_options[] = array( 	"name" 		=> "Left side footer text",
						"desc" 		=> "Information about your site shown on left column in the footer.<br><small>Example: Copyrights</small>",
						"id" 		=> "footer_text",
						"std" 		=> "&copy; Oxygen WordPress Theme.",
						"type" 		=> "textarea"
				);

$of_options[] = array( 	"name" 		=> "Right side footer text",
						"desc" 		=> "Information about your site shown on right column in the footer.<br><small>Example: Terms and conditions</small>",
						"id" 		=> "footer_text_right",
						"std" 		=> "",
						"type" 		=> "textarea"
				);

$of_options[] = array( 	"name" 		=> "Tracking Code",
						"desc" 		=> "Paste your Google Analytics (or other) tracking code here. This will be added into the footer template.",
						"id" 		=> "google_analytics",
						"std" 		=> "",
						"type" 		=> "textarea"
				);


$of_options[] = array( 	"name" 		=> "Shop Settings",
						"type" 		=> "heading"
				);

$of_options[] = array( 	"name" 		=> "Shop Archive Settings",
						"desc" 		=> "Shop archive title",
						"id" 		=> "shop_title_show",
						"std" 		=> 1,
						"type" 		=> "checkbox"
				);

$of_options[] = array( 	"desc" 		=> "Product sorting and results count",
						"id" 		=> "shop_sorting_show",
						"std" 		=> 1,
						"type" 		=> "checkbox"
				);

$of_options[] = array( 	"desc" 		=> "Product rating",
						"id" 		=> "shop_rating_show",
						"std" 		=> 1,
						"type" 		=> "checkbox"
				);

$of_options[] = array( 	"desc" 		=> "Show <strong>sale</strong> badge",
						"id" 		=> "shop_sale_ribbon_show",
						"std" 		=> 1,
						"type" 		=> "checkbox"
				);

$of_options[] = array( 	"desc" 		=> "Show <strong>featured</strong> badge",
						"id" 		=> "shop_featured_product_ribbon_show",
						"std" 		=> 1,
						"type" 		=> "checkbox"
				);

$of_options[] = array( 	"desc" 		=> "Show <strong>out of stock</strong> badge",
						"id" 		=> "shop_oos_ribbon_show",
						"std" 		=> 1,
						"type" 		=> "checkbox"
				);

$of_options[] = array( 	"desc" 		=> "Product category",
						"id" 		=> "shop_product_category_listing",
						"std" 		=> 1,
						"type" 		=> "checkbox"
				);

$of_options[] = array( 	"desc" 		=> "Product price",
						"id" 		=> "shop_product_price_listing",
						"std" 		=> 1,
						"type" 		=> "checkbox"
				);

$of_options[] = array( 	"desc" 		=> "Add to cart button",
						"id" 		=> "shop_add_to_cart_listing",
						"std" 		=> 1,
						"type" 		=> "checkbox"
				);

$of_options[] = array( 	"desc" 		=> "Product quick view",
						"id" 		=> "shop_quickview",
						"std" 		=> 1,
						"type" 		=> "checkbox"
				);

$of_options[] = array( 	"desc" 		=> "Product thumbnail preview type",
						"id" 		=> "shop_item_preview_type",
						"std" 		=> "Product Gallery Slider",
						"type" 		=> "select",
						"options" 	=> array( 'Product Gallery Slider', 'Second Image on Hover', 'None' )
				);

$of_options[] = array( 	"desc" 		=> "Products per row in mobile devices",
						"id" 		=> "shop_products_mobile_two_per_row",
						"std" 		=> "two",
						"type" 		=> "select",
						"options" 	=> array(
							"one" => "One product per row",
							"two" => "Two products per row",
						)
				);

$woocommerce_image_notice = '';
$woocommerce_image_customizer_link = '';

if ( function_exists( 'wc_get_page_permalink' ) ) {
	
	$woocommerce_image_customizer_link = add_query_arg( array(
		'autofocus' => array(
			'panel' => 'woocommerce',
		),
		'url' => wc_get_page_permalink( 'shop' ),
	 ), admin_url( 'customize.php' ) );
	
	$woocommerce_image_notice = wp_kses( sprintf( 'Looking for the product display options? They can now be found in the Customizer. <a href="%s">Go see them in action here.</a>', esc_url( $woocommerce_image_customizer_link ) ), array(
		'a' => array(
			'href'  => array(),
			'title' => array(),
		),
	) );
}	

$of_options[] = array( 	'name' 		=> 'WooCommerce images',
						'desc' 		=> '',
						'id' 		=> 'wc_images_notice',
						'std' 		=> $woocommerce_image_notice,
						'icon' 		=> true,
						'type' 		=> 'info',
						
						'tab_id'	=> 'shop-settings-loop'
				);

$of_options[] = array( 	'name' 		=> 'Infinite scroll',
						'desc' 		=> '',
						'id' 		=> 'yith_infs_notice',
						'std' 		=> 'You can enable <strong>Infinite Scroll Pagination</strong> by installing and activating <a href="' . admin_url( 'plugin-install.php?s=yith+infinite+scroll&tab=search&type=term' ) . '" target="_blank">YITH Infinite Scroll</a> plugin.',
						'icon' 		=> true,
						'type' 		=> 'info',
						
						'tab_id'	=> 'shop-settings-loop'
				);

$of_options[] = array( 	"name" 		=> "Product Categories",
						"desc" 		=> "Categories per row.",
						"id" 		=> "shop_categories_per_row",
						"std" 		=> "four",
						"type" 		=> "select",
						"options" 	=> array(
							"1"  => "1 category per row",
							"2"  => "2 categories per row",
							"3"  => "3 categories per row",
							"4"  => "4 categories per row",
							"5"  => "5 categories per row",
							"6"  => "6 categories per row",
						)
				);

$of_options[] = array( 	"desc" 		=> "Set how many categories to show in mobile devices",
						"id" 		=> "shop_categories_mobile_per_row",
						"std" 		=> "decide",
						"type" 		=> "select",
						"options" 	=> array(
							"one" => "One category per row",
							"two" => "Two categories per row",
						)
				);

$of_options[] = array( 	"desc" 		=> "Show items count for categories",
						"id" 		=> "shop_category_count",
						"std" 		=> 0,
						"type" 		=> "checkbox"
				);

$of_options[] = array( 	"name" 		=> "Shop Sidebar",
						"desc" 		=> "Listing page sidebar",
						"id" 		=> "shop_sidebar",
						"std" 		=> "Hide Sidebar",
						"type" 		=> "select",
						"options" 	=> array( "Show Sidebar on Left", "Show Sidebar on Right", "Hide Sidebar" )
				);

$of_options[] = array( 	"desc" 		=> "Single product page sidebar",
						"id" 		=> "shop_single_sidebar",
						"std" 		=> "Hide Sidebar",
						"type" 		=> "select",
						"options" 	=> array( "Show Sidebar on Left", "Show Sidebar on Right", "Hide Sidebar" )
				);

$of_options[] = array( 	"desc" 		=> "Show <strong>footer</strong> sidebar",
						"id" 		=> "shop_sidebar_footer",
						"std" 		=> 0,
						"type" 		=> "switch",
						"on"		=> "Show",
						"off"		=> "Hide",
						"folds"		=> 1
				);

$of_options[] = array( 	"desc" 		=> "Set the number of widgets per row to show in <strong>footer</strong> sidebar",
						"id" 		=> "shop_sidebar_footer_columns",
						"std" 		=> "4",
						"type" 		=> "select",
						"options" 	=> array("2", "3", "4",),
						"fold"		=> "shop_sidebar_footer"
				);

$of_options[] = array( 	"name" 		=> "Single Item Settings",
						"desc" 		=> "Show <strong>sale</strong> badge",
						"id" 		=> "shop_single_sale_ribbon_show",
						"std" 		=> 1,
						"type" 		=> "checkbox"
				);

$of_options[] = array( 	"desc" 		=> "Show <strong>featured</strong> badge",
						"id" 		=> "shop_single_featured_ribbon_show",
						"std" 		=> 1,
						"type" 		=> "checkbox"
				);

$of_options[] = array( 	"desc" 		=> "Show <strong>out of stock</strong> badge",
						"id" 		=> "shop_single_oos_ribbon_show",
						"std" 		=> 1,
						"type" 		=> "checkbox"
				);

$of_options[] = array( 	"desc" 		=> "Product <strong>Next-Prev</strong> navigation",
						"id" 		=> "shop_single_next_prev",
						"std" 		=> 1,
						"type" 		=> "checkbox"
				);

$of_options[] = array( 	"desc" 		=> "Product rating (stars)",
						"id" 		=> "shop_single_product_rating",
						"std" 		=> 1,
						"type" 		=> "checkbox"
				);

$of_options[] = array( 	"desc" 		=> "Product category (below the title)",
						"id" 		=> "shop_single_product_category",
						"std" 		=> 1,
						"type" 		=> "checkbox"
				);

$of_options[] = array( 	"desc" 		=> "Product meta (SKU, category and tags)",
						"id" 		=> "shop_single_meta_show",
						"std" 		=> 1,
						"type" 		=> "checkbox"
				);

$of_options[] = array( 	"desc" 		=> "Captions in product images lightbox",
						"id" 		=> "shop_single_lightbox_captions",
						"std" 		=> 1,
						"type" 		=> "checkbox"
				);

$of_options[] = array( 	"desc" 		=> "Related products count",
						"id" 		=> "shop_related_products_per_page",
						"std" 		=> 4,
						"type" 		=> "select",
						"options" 	=> range(12, 1)
				);

$of_options[] = array( 	"desc" 		=> "Related products columns",
						"id" 		=> "shop_related_products_columns",
						"std" 		=> '',
						"type" 		=> "select",
						"options" 	=> array(
							""	 => 'Inherit default from Product Columns',
							'six'    => "6 columns",
							'five'   => "5 columns",
							'four'   => "4 columns", 
							'three'  => "3 columns", 
							'two'    => "2 columns",
						)
				);

$of_options[] = array( 	"desc" 		=> "Auto rotate product images<br><small>Set <strong>-1</strong> to disable auto-rotate.</small>",
						"id" 		=> "shop_single_auto_rotate_image",
						"std" 		=> "",
						"plc"		=> "Default: 5 (seconds)",
						"type" 		=> "text"
				);

$of_options[] = array(	"name" 		=> "Lightbox and Image Zoom",
						"desc" 		=> "Product gallery lightbox",
						"id" 		=> "shop_single_lightbox",
						"on" 		=> "Enable",
						"off" 		=> "Disable",
						"std" 		=> 1,
						"type" 		=> "switch",
					);
					
$of_options[] = array( 	"desc" 		=> "Zoom product image when hovering into the image",
						"id" 		=> "shop_magnifier",
						"std" 		=> 1,
						"on" 		=> "Enable",
						"off" 		=> "Disable",
						"type" 		=> "switch",
						"folds"		=> 1
				);

$of_options[] = array( 	"name" 		=> "Product Sharing",
						"desc" 		=> "Enable or disable sharing the product in popular Social Networks.",
						"id" 		=> "shop_share_product",
						"std" 		=> 0,
						"on" 		=> "Allow Share",
						"off" 		=> "No",
						"type" 		=> "switch",
						"folds"		=> 1
				);

$share_product_networks = array(
			"visible" => array (
				"placebo"	=> "placebo",
				"fb"   	 	=> "Facebook",
				"tw"   	 	=> "Twitter",
				"pi"        => "Pinterest",
				"em"       	=> "Email",
			),

			"hidden" => array (
				"placebo"   => "placebo",
				"lin"       => "LinkedIn",
				"tlr"       => "Tumblr",
				"vk"       	=> "VKontakte",
			),
);

$of_options[] = array( 	"name" 		=> "Share Product Networks",
						"desc" 		=> "Select social networks that you allow users to share the products of your shop.",
						"id" 		=> "shop_share_product_networks",
						"std" 		=> $share_product_networks,
						"type" 		=> "sorter",
						"fold"		=> "shop_share_product"
				);


$of_options[] = array( 	"name"		=> "Catalog Mode",
						"desc" 		=> "Enable catalog mode<br><small>Will disable add to cart functionality.</small>",
						"id" 		=> "shop_catalog_mode",
						"std" 		=> 0,
						"on" 		=> "Yes",
						"off" 		=> "No",
						"type" 		=> "switch",
						"folds"		=> true
				);

$of_options[] = array( 	"desc" 		=> "Hide prices<br><small>Show/hide product prices if there is a price set.</small>",
						"id" 		=> "shop_catalog_mode_hide_prices",
						"std" 		=> 0,
						"type" 		=> "switch",
						"on" 		=> "Yes",
						"off" 		=> "No",
						"fold"		=> "shop_catalog_mode"
				);




$of_options[] = array( 	"name" 		=> "Blog Settings",
						"type" 		=> "heading"
				);

$of_options[] = array( 	"name" 		=> "Toggle Blog Functionality",
						"desc" 		=> "Thumbnails (post featured image)",
						"id" 		=> "blog_thumbnails",
						"std" 		=> 1,
						"type" 		=> "checkbox"
				);

$of_options[] = array( 	"desc" 		=> "Single post thumbnail (featured image)",
						"id" 		=> "blog_single_thumbnails",
						"std" 		=> 1,
						"type" 		=> "checkbox"
				);

$of_options[] = array( 	"desc" 		=> "Thumbnail hover effect",
						"id" 		=> "blog_thumbnail_hover_effect",
						"std" 		=> 1,
						"type" 		=> "checkbox"
				);

$of_options[] = array( 	"desc" 		=> "Author name (shown on posts list)",
						"id" 		=> "blog_author_name",
						"std" 		=> 1,
						"type" 		=> "checkbox"
				);

$of_options[] = array( 	"desc" 		=> "Author info (shown on single post)",
						"id" 		=> "blog_author_info",
						"std" 		=> 1,
						"type" 		=> "checkbox"
				);

$of_options[] = array( 	"desc" 		=> "Category (shown everywhere)",
						"id" 		=> "blog_category",
						"std" 		=> 1,
						"type" 		=> "checkbox"
				);

$of_options[] = array( 	"desc" 		=> "Post date (shown everywhere)",
						"id" 		=> "blog_post_date",
						"std" 		=> 1,
						"type" 		=> "checkbox"
				);

$of_options[] = array( 	"desc" 		=> "Tags (shown on single post)",
						"id" 		=> "blog_tags",
						"std" 		=> 1,
						"type" 		=> "checkbox"
				);

$of_options[] = array( 	"desc" 		=> "Comments number",
						"id" 		=> "blog_comments_count",
						"std" 		=> 1,
						"type" 		=> "checkbox"
				);

$of_options[] = array( 	"name" 		=> "Blog Sidebar",
					 	"desc" 		=> "Set blog sidebar position, you can even hide it.",
						"id" 		=> "blog_sidebar_position",
						"std" 		=> "Right",
						"type" 		=> "select",
						"options" 	=> array("Right", "Left", "Hide")
				);

$of_options[] = array( 	"name" 		=> "Pagination Position",
						"desc" 		=> "Set blog pagination position.",
						"id" 		=> "blog_pagination_position",
						"std" 		=> "Center",
						"type" 		=> "select",
						"options" 	=> array("Left", "Center", "Right")
				);

$of_options[] = array( 	"name" 		=> "Gallery Auto-Switch",
						"desc" 		=> "Set the interval of auto-switch for gallery images (in posts, 0 - disable).",
						"id" 		=> "blog_gallery_autoswitch",
						"std" 		=> "",
						"plc"		=> "Default: 5 (seconds)",
						"type" 		=> "text"
				);

$of_options[] = array( 	"name" 		=> "Featured Image Height",
						"desc" 		=> "Featured image thumbnail height (applied on single post only).",
						"id" 		=> "blog_thumbnail_height",
						"std" 		=> "",
						"plc"		=> "If you set blank, it will generate proportional height.",
						"type" 		=> "text"
				);

$of_options[] = array( 	"name" 		=> "Share Story",
						"desc" 		=> "Enable or disable sharing the story in popular Social Networks.",
						"id" 		=> "blog_share_story",
						"std" 		=> 0,
						"on" 		=> "Allow Share",
						"off" 		=> "No",
						"type" 		=> "switch",
						"folds"		=> 1
				);

$share_story_networks = array(
			"visible" => array (
				"placebo"	=> "placebo",
				"fb"   	 	=> "Facebook",
				"tw"   	 	=> "Twitter",
				"lin"       => "LinkedIn",
				"pi"        => "Pinterest",
				"tlr"       => "Tumblr",
			),

			"hidden" => array (
				"placebo"   => "placebo",
				"pi"       	=> "Pinterest",
				"em"       	=> "Email",
				"vk"       	=> "VKontakte",
			),
);

$of_options[] = array( 	"name" 		=> "Share Story Networks",
						"desc" 		=> "Select social networks that you allow users to share the content of your blog posts.",
						"id" 		=> "blog_share_story_networks",
						"std" 		=> $share_story_networks,
						"type" 		=> "sorter",
						"fold"		=> "blog_share_story"
				);


$of_options[] = array( 	"name" 		=> "Other Settings",
						"type" 		=> "heading"
				);

$of_options[] = array( 	"name"		=> "Search results",
						"desc" 		=> "Set how many rows you want to display on search page.",
						"id" 		=> "search_results_count",
						"std" 		=> 4,
						"type" 		=> "select",
						"options" 	=> range(12, 1)
				);


				
$of_options[] = array( 	'name' 		=> 'Go to Top',
						'desc' 		=> "Show &quot;Go to Top&quot; button when users scroll down to the page",
						'id' 		=> 'go_to_top',
						'std' 		=> 0,
						'on' 		=> 'Show',
						'off' 		=> 'Hide',
						'type' 		=> 'switch',
						'folds'		=> 1,
				);

$of_options[] = array( 	'desc' 		=> "Set number of pixels or percentage of window user needs to scroll when &quot;Go to Top&quot; link will be shown<br><small>If you set value to <strong>footer</strong>, link will appear only when user sees footer.</small>",
						'id' 		=> 'go_to_top_offset',
						'std' 		=> 'footer',
						'plc'		=> "",
						'type' 		=> 'text',
						'fold'		=> 'go_to_top',
				);
					
$of_options[] = array( 	'desc' 		=> 'Box type for "Go to top" button',
						'id' 		=> 'go_to_top_button_type',
						'std' 		=> 'circle',
						'type' 		=> 'select',
						'options' 	=> array(
							'square' => 'Square',
							'circle' => 'Circle',
						),
						'fold'		=> 'go_to_top',
				);

$of_options[] = array( 	'desc' 		=> 'Link alignment',
						'id' 		=> 'go_to_top_alignment',
						'std' 		=> 'bottom-right',
						'type' 		=> 'select',
						'options' 	=> array(
							'bottom-right'   => 'Bottom Right',
							'bottom-left'    => 'Bottom Left',
							'bottom-center'  => 'Bottom Center',
							
							'top-right'      => 'Top Right',
							'top-left'       => 'Top Left',
							'top-center'     => 'Top Center',
						),
						'fold'		=> 'go_to_top',
				);


$of_options[] = array(	"name" 		=> "Favicon",
						"desc" 		=> "Select 64x64 favicon of the PNG format.",
						"id" 		=> "favicon_image",
						"std" 		=> "",
						"type" 		=> "media",
						"mod" 		=> "min"
					);


$of_options[] = array(	"name" 		=> "Apple Touch Icon",
						"desc" 		=> "Required image size 114x114 (png only)",
						"id" 		=> "apple_touch_icon",
						"std" 		=> "",
						"type" 		=> "media",
						"mod" 		=> "min"
					);


$of_options[] = array( 	"name"		=> 'Google Maps API Key',
						"desc" 		=> "Google maps requires unique API key for each site, click here to learn more about generating <a href='https://developers.google.com/maps/documentation/javascript/get-api-key' target='_blank'>Google API Key</a>",
						"id" 		=> "google_maps_api",
						"std" 		=> "",
						"plc"		=> "",
						"type" 		=> "text"
				);


/*$of_options[] = array( 	"name"		=> "Thumbnails Generator",
						"desc" 		=> "Image quality for JPEG thumbnails (higher value = better quality = bigger size).<br /><br /><em>Note: If you change thumbnails quality, current thumbnails will still be at the same quality. <br />The changes will take effect only if you delete all generated thumbnails by <a href='".admin_url()."?lab_img_clear_cache=1' target='_blank' onclick='return confirm(\"Are you sure?\");'>clicking here</a>, they will be created automatically.</em>",
						"id" 		=> "image_resizer_jpeg_quality",
						"std" 		=> 90,
						"type" 		=> "select",
						"options" 	=> range(100, 60)
				);*/



$of_options[] = array( 	"name" 		=> "Typography",
						"type" 		=> "heading"
				);

$font_primary_list = array(
	"Open Sans"           => "Open Sans",
	"PT Sans"             => "PT Sans",
	"Source Sans Pro"     => "Source Sans Pro",
	"Arimo"               => "Arimo",
	"Arvo"                => "Arvo",
	"Roboto Slab"         => "Roboto Slab",
	"Playfair Display"    => "Playfair Display",
	"Montserrat"          => "Montserrat",
	"Raleway"             => "Raleway"
);

$font_secondary_list = array(
	"Arvo"                => "Arvo",
	"Roboto Slab"         => "Roboto Slab",
	"Playfair Display"    => "Playfair Display",
	"Montserrat"          => "Montserrat",
	"Raleway"             => "Raleway",
	"Arimo"               => "Arimo"
);

$sep = array( '----' => '----------' );

$google_fonts_list = array(
	"ABeeZee" => "ABeeZee",
	"Abel" => "Abel",
	"Abhaya Libre" => "Abhaya Libre",
	"Abril Fatface" => "Abril Fatface",
	"Aclonica" => "Aclonica",
	"Acme" => "Acme",
	"Actor" => "Actor",
	"Adamina" => "Adamina",
	"Advent Pro" => "Advent Pro",
	"Aguafina Script" => "Aguafina Script",
	"Akronim" => "Akronim",
	"Aladin" => "Aladin",
	"Aldrich" => "Aldrich",
	"Alef" => "Alef",
	"Alegreya" => "Alegreya",
	"Alegreya SC" => "Alegreya SC",
	"Alegreya Sans" => "Alegreya Sans",
	"Alegreya Sans SC" => "Alegreya Sans SC",
	"Aleo" => "Aleo",
	"Alex Brush" => "Alex Brush",
	"Alfa Slab One" => "Alfa Slab One",
	"Alice" => "Alice",
	"Alike" => "Alike",
	"Alike Angular" => "Alike Angular",
	"Allan" => "Allan",
	"Allerta" => "Allerta",
	"Allerta Stencil" => "Allerta Stencil",
	"Allura" => "Allura",
	"Almarai" => "Almarai",
	"Almendra" => "Almendra",
	"Almendra Display" => "Almendra Display",
	"Almendra SC" => "Almendra SC",
	"Amarante" => "Amarante",
	"Amaranth" => "Amaranth",
	"Amatic SC" => "Amatic SC",
	"Amethysta" => "Amethysta",
	"Amiko" => "Amiko",
	"Amiri" => "Amiri",
	"Amita" => "Amita",
	"Anaheim" => "Anaheim",
	"Andada" => "Andada",
	"Andika" => "Andika",
	"Angkor" => "Angkor",
	"Annie Use Your Telescope" => "Annie Use Your Telescope",
	"Anonymous Pro" => "Anonymous Pro",
	"Antic" => "Antic",
	"Antic Didone" => "Antic Didone",
	"Antic Slab" => "Antic Slab",
	"Anton" => "Anton",
	"Arapey" => "Arapey",
	"Arbutus" => "Arbutus",
	"Arbutus Slab" => "Arbutus Slab",
	"Architects Daughter" => "Architects Daughter",
	"Archivo" => "Archivo",
	"Archivo Black" => "Archivo Black",
	"Archivo Narrow" => "Archivo Narrow",
	"Aref Ruqaa" => "Aref Ruqaa",
	"Arima Madurai" => "Arima Madurai",
	"Arimo" => "Arimo",
	"Arizonia" => "Arizonia",
	"Armata" => "Armata",
	"Arsenal" => "Arsenal",
	"Artifika" => "Artifika",
	"Arvo" => "Arvo",
	"Arya" => "Arya",
	"Asap" => "Asap",
	"Asap Condensed" => "Asap Condensed",
	"Asar" => "Asar",
	"Asset" => "Asset",
	"Assistant" => "Assistant",
	"Astloch" => "Astloch",
	"Asul" => "Asul",
	"Athiti" => "Athiti",
	"Atma" => "Atma",
	"Atomic Age" => "Atomic Age",
	"Aubrey" => "Aubrey",
	"Audiowide" => "Audiowide",
	"Autour One" => "Autour One",
	"Average" => "Average",
	"Average Sans" => "Average Sans",
	"Averia Gruesa Libre" => "Averia Gruesa Libre",
	"Averia Libre" => "Averia Libre",
	"Averia Sans Libre" => "Averia Sans Libre",
	"Averia Serif Libre" => "Averia Serif Libre",
	"B612" => "B612",
	"B612 Mono" => "B612 Mono",
	"Bad Script" => "Bad Script",
	"Bahiana" => "Bahiana",
	"Bahianita" => "Bahianita",
	"Bai Jamjuree" => "Bai Jamjuree",
	"Baloo" => "Baloo",
	"Baloo Bhai" => "Baloo Bhai",
	"Baloo Bhaijaan" => "Baloo Bhaijaan",
	"Baloo Bhaina" => "Baloo Bhaina",
	"Baloo Chettan" => "Baloo Chettan",
	"Baloo Da" => "Baloo Da",
	"Baloo Paaji" => "Baloo Paaji",
	"Baloo Tamma" => "Baloo Tamma",
	"Baloo Tammudu" => "Baloo Tammudu",
	"Baloo Thambi" => "Baloo Thambi",
	"Balthazar" => "Balthazar",
	"Bangers" => "Bangers",
	"Barlow" => "Barlow",
	"Barlow Condensed" => "Barlow Condensed",
	"Barlow Semi Condensed" => "Barlow Semi Condensed",
	"Barriecito" => "Barriecito",
	"Barrio" => "Barrio",
	"Basic" => "Basic",
	"Battambang" => "Battambang",
	"Baumans" => "Baumans",
	"Bayon" => "Bayon",
	"Be Vietnam" => "Be Vietnam",
	"Belgrano" => "Belgrano",
	"Bellefair" => "Bellefair",
	"Belleza" => "Belleza",
	"BenchNine" => "BenchNine",
	"Bentham" => "Bentham",
	"Berkshire Swash" => "Berkshire Swash",
	"Beth Ellen" => "Beth Ellen",
	"Bevan" => "Bevan",
	"Big Shoulders Display" => "Big Shoulders Display",
	"Big Shoulders Text" => "Big Shoulders Text",
	"Bigelow Rules" => "Bigelow Rules",
	"Bigshot One" => "Bigshot One",
	"Bilbo" => "Bilbo",
	"Bilbo Swash Caps" => "Bilbo Swash Caps",
	"BioRhyme" => "BioRhyme",
	"BioRhyme Expanded" => "BioRhyme Expanded",
	"Biryani" => "Biryani",
	"Bitter" => "Bitter",
	"Black And White Picture" => "Black And White Picture",
	"Black Han Sans" => "Black Han Sans",
	"Black Ops One" => "Black Ops One",
	"Blinker" => "Blinker",
	"Bokor" => "Bokor",
	"Bonbon" => "Bonbon",
	"Boogaloo" => "Boogaloo",
	"Bowlby One" => "Bowlby One",
	"Bowlby One SC" => "Bowlby One SC",
	"Brawler" => "Brawler",
	"Bree Serif" => "Bree Serif",
	"Bubblegum Sans" => "Bubblegum Sans",
	"Bubbler One" => "Bubbler One",
	"Buda" => "Buda",
	"Buenard" => "Buenard",
	"Bungee" => "Bungee",
	"Bungee Hairline" => "Bungee Hairline",
	"Bungee Inline" => "Bungee Inline",
	"Bungee Outline" => "Bungee Outline",
	"Bungee Shade" => "Bungee Shade",
	"Butcherman" => "Butcherman",
	"Butterfly Kids" => "Butterfly Kids",
	"Cabin" => "Cabin",
	"Cabin Condensed" => "Cabin Condensed",
	"Cabin Sketch" => "Cabin Sketch",
	"Caesar Dressing" => "Caesar Dressing",
	"Cagliostro" => "Cagliostro",
	"Cairo" => "Cairo",
	"Calligraffitti" => "Calligraffitti",
	"Cambay" => "Cambay",
	"Cambo" => "Cambo",
	"Candal" => "Candal",
	"Cantarell" => "Cantarell",
	"Cantata One" => "Cantata One",
	"Cantora One" => "Cantora One",
	"Capriola" => "Capriola",
	"Cardo" => "Cardo",
	"Carme" => "Carme",
	"Carrois Gothic" => "Carrois Gothic",
	"Carrois Gothic SC" => "Carrois Gothic SC",
	"Carter One" => "Carter One",
	"Catamaran" => "Catamaran",
	"Caudex" => "Caudex",
	"Caveat" => "Caveat",
	"Caveat Brush" => "Caveat Brush",
	"Cedarville Cursive" => "Cedarville Cursive",
	"Ceviche One" => "Ceviche One",
	"Chakra Petch" => "Chakra Petch",
	"Changa" => "Changa",
	"Changa One" => "Changa One",
	"Chango" => "Chango",
	"Charm" => "Charm",
	"Charmonman" => "Charmonman",
	"Chathura" => "Chathura",
	"Chau Philomene One" => "Chau Philomene One",
	"Chela One" => "Chela One",
	"Chelsea Market" => "Chelsea Market",
	"Chenla" => "Chenla",
	"Cherry Cream Soda" => "Cherry Cream Soda",
	"Cherry Swash" => "Cherry Swash",
	"Chewy" => "Chewy",
	"Chicle" => "Chicle",
	"Chilanka" => "Chilanka",
	"Chivo" => "Chivo",
	"Chonburi" => "Chonburi",
	"Cinzel" => "Cinzel",
	"Cinzel Decorative" => "Cinzel Decorative",
	"Clicker Script" => "Clicker Script",
	"Coda" => "Coda",
	"Coda Caption" => "Coda Caption",
	"Codystar" => "Codystar",
	"Coiny" => "Coiny",
	"Combo" => "Combo",
	"Comfortaa" => "Comfortaa",
	"Coming Soon" => "Coming Soon",
	"Concert One" => "Concert One",
	"Condiment" => "Condiment",
	"Content" => "Content",
	"Contrail One" => "Contrail One",
	"Convergence" => "Convergence",
	"Cookie" => "Cookie",
	"Copse" => "Copse",
	"Corben" => "Corben",
	"Cormorant" => "Cormorant",
	"Cormorant Garamond" => "Cormorant Garamond",
	"Cormorant Infant" => "Cormorant Infant",
	"Cormorant SC" => "Cormorant SC",
	"Cormorant Unicase" => "Cormorant Unicase",
	"Cormorant Upright" => "Cormorant Upright",
	"Courgette" => "Courgette",
	"Cousine" => "Cousine",
	"Coustard" => "Coustard",
	"Covered By Your Grace" => "Covered By Your Grace",
	"Crafty Girls" => "Crafty Girls",
	"Creepster" => "Creepster",
	"Crete Round" => "Crete Round",
	"Crimson Pro" => "Crimson Pro",
	"Crimson Text" => "Crimson Text",
	"Croissant One" => "Croissant One",
	"Crushed" => "Crushed",
	"Cuprum" => "Cuprum",
	"Cute Font" => "Cute Font",
	"Cutive" => "Cutive",
	"Cutive Mono" => "Cutive Mono",
	"DM Sans" => "DM Sans",
	"DM Serif Display" => "DM Serif Display",
	"DM Serif Text" => "DM Serif Text",
	"Damion" => "Damion",
	"Dancing Script" => "Dancing Script",
	"Dangrek" => "Dangrek",
	"Darker Grotesque" => "Darker Grotesque",
	"David Libre" => "David Libre",
	"Dawning of a New Day" => "Dawning of a New Day",
	"Days One" => "Days One",
	"Dekko" => "Dekko",
	"Delius" => "Delius",
	"Delius Swash Caps" => "Delius Swash Caps",
	"Delius Unicase" => "Delius Unicase",
	"Della Respira" => "Della Respira",
	"Denk One" => "Denk One",
	"Devonshire" => "Devonshire",
	"Dhurjati" => "Dhurjati",
	"Didact Gothic" => "Didact Gothic",
	"Diplomata" => "Diplomata",
	"Diplomata SC" => "Diplomata SC",
	"Do Hyeon" => "Do Hyeon",
	"Dokdo" => "Dokdo",
	"Domine" => "Domine",
	"Donegal One" => "Donegal One",
	"Doppio One" => "Doppio One",
	"Dorsa" => "Dorsa",
	"Dosis" => "Dosis",
	"Dr Sugiyama" => "Dr Sugiyama",
	"Duru Sans" => "Duru Sans",
	"Dynalight" => "Dynalight",
	"EB Garamond" => "EB Garamond",
	"Eagle Lake" => "Eagle Lake",
	"East Sea Dokdo" => "East Sea Dokdo",
	"Eater" => "Eater",
	"Economica" => "Economica",
	"Eczar" => "Eczar",
	"El Messiri" => "El Messiri",
	"Electrolize" => "Electrolize",
	"Elsie" => "Elsie",
	"Elsie Swash Caps" => "Elsie Swash Caps",
	"Emblema One" => "Emblema One",
	"Emilys Candy" => "Emilys Candy",
	"Encode Sans" => "Encode Sans",
	"Encode Sans Condensed" => "Encode Sans Condensed",
	"Encode Sans Expanded" => "Encode Sans Expanded",
	"Encode Sans Semi Condensed" => "Encode Sans Semi Condensed",
	"Encode Sans Semi Expanded" => "Encode Sans Semi Expanded",
	"Engagement" => "Engagement",
	"Englebert" => "Englebert",
	"Enriqueta" => "Enriqueta",
	"Erica One" => "Erica One",
	"Esteban" => "Esteban",
	"Euphoria Script" => "Euphoria Script",
	"Ewert" => "Ewert",
	"Exo" => "Exo",
	"Exo 2" => "Exo 2",
	"Expletus Sans" => "Expletus Sans",
	"Fahkwang" => "Fahkwang",
	"Fanwood Text" => "Fanwood Text",
	"Farro" => "Farro",
	"Farsan" => "Farsan",
	"Fascinate" => "Fascinate",
	"Fascinate Inline" => "Fascinate Inline",
	"Faster One" => "Faster One",
	"Fasthand" => "Fasthand",
	"Fauna One" => "Fauna One",
	"Faustina" => "Faustina",
	"Federant" => "Federant",
	"Federo" => "Federo",
	"Felipa" => "Felipa",
	"Fenix" => "Fenix",
	"Finger Paint" => "Finger Paint",
	"Fira Code" => "Fira Code",
	"Fira Mono" => "Fira Mono",
	"Fira Sans" => "Fira Sans",
	"Fira Sans Condensed" => "Fira Sans Condensed",
	"Fira Sans Extra Condensed" => "Fira Sans Extra Condensed",
	"Fjalla One" => "Fjalla One",
	"Fjord One" => "Fjord One",
	"Flamenco" => "Flamenco",
	"Flavors" => "Flavors",
	"Fondamento" => "Fondamento",
	"Fontdiner Swanky" => "Fontdiner Swanky",
	"Forum" => "Forum",
	"Francois One" => "Francois One",
	"Frank Ruhl Libre" => "Frank Ruhl Libre",
	"Freckle Face" => "Freckle Face",
	"Fredericka the Great" => "Fredericka the Great",
	"Fredoka One" => "Fredoka One",
	"Freehand" => "Freehand",
	"Fresca" => "Fresca",
	"Frijole" => "Frijole",
	"Fruktur" => "Fruktur",
	"Fugaz One" => "Fugaz One",
	"GFS Didot" => "GFS Didot",
	"GFS Neohellenic" => "GFS Neohellenic",
	"Gabriela" => "Gabriela",
	"Gaegu" => "Gaegu",
	"Gafata" => "Gafata",
	"Galada" => "Galada",
	"Galdeano" => "Galdeano",
	"Galindo" => "Galindo",
	"Gamja Flower" => "Gamja Flower",
	"Gayathri" => "Gayathri",
	"Gentium Basic" => "Gentium Basic",
	"Gentium Book Basic" => "Gentium Book Basic",
	"Geo" => "Geo",
	"Geostar" => "Geostar",
	"Geostar Fill" => "Geostar Fill",
	"Germania One" => "Germania One",
	"Gidugu" => "Gidugu",
	"Gilda Display" => "Gilda Display",
	"Give You Glory" => "Give You Glory",
	"Glass Antiqua" => "Glass Antiqua",
	"Glegoo" => "Glegoo",
	"Gloria Hallelujah" => "Gloria Hallelujah",
	"Goblin One" => "Goblin One",
	"Gochi Hand" => "Gochi Hand",
	"Gorditas" => "Gorditas",
	"Gothic A1" => "Gothic A1",
	"Goudy Bookletter 1911" => "Goudy Bookletter 1911",
	"Graduate" => "Graduate",
	"Grand Hotel" => "Grand Hotel",
	"Gravitas One" => "Gravitas One",
	"Great Vibes" => "Great Vibes",
	"Grenze" => "Grenze",
	"Griffy" => "Griffy",
	"Gruppo" => "Gruppo",
	"Gudea" => "Gudea",
	"Gugi" => "Gugi",
	"Gurajada" => "Gurajada",
	"Habibi" => "Habibi",
	"Halant" => "Halant",
	"Hammersmith One" => "Hammersmith One",
	"Hanalei" => "Hanalei",
	"Hanalei Fill" => "Hanalei Fill",
	"Handlee" => "Handlee",
	"Hanuman" => "Hanuman",
	"Happy Monkey" => "Happy Monkey",
	"Harmattan" => "Harmattan",
	"Headland One" => "Headland One",
	"Heebo" => "Heebo",
	"Henny Penny" => "Henny Penny",
	"Hepta Slab" => "Hepta Slab",
	"Herr Von Muellerhoff" => "Herr Von Muellerhoff",
	"Hi Melody" => "Hi Melody",
	"Hind" => "Hind",
	"Hind Guntur" => "Hind Guntur",
	"Hind Madurai" => "Hind Madurai",
	"Hind Siliguri" => "Hind Siliguri",
	"Hind Vadodara" => "Hind Vadodara",
	"Holtwood One SC" => "Holtwood One SC",
	"Homemade Apple" => "Homemade Apple",
	"Homenaje" => "Homenaje",
	"IBM Plex Mono" => "IBM Plex Mono",
	"IBM Plex Sans" => "IBM Plex Sans",
	"IBM Plex Sans Condensed" => "IBM Plex Sans Condensed",
	"IBM Plex Serif" => "IBM Plex Serif",
	"IM Fell DW Pica" => "IM Fell DW Pica",
	"IM Fell DW Pica SC" => "IM Fell DW Pica SC",
	"IM Fell Double Pica" => "IM Fell Double Pica",
	"IM Fell Double Pica SC" => "IM Fell Double Pica SC",
	"IM Fell English" => "IM Fell English",
	"IM Fell English SC" => "IM Fell English SC",
	"IM Fell French Canon" => "IM Fell French Canon",
	"IM Fell French Canon SC" => "IM Fell French Canon SC",
	"IM Fell Great Primer" => "IM Fell Great Primer",
	"IM Fell Great Primer SC" => "IM Fell Great Primer SC",
	"Iceberg" => "Iceberg",
	"Iceland" => "Iceland",
	"Imprima" => "Imprima",
	"Inconsolata" => "Inconsolata",
	"Inder" => "Inder",
	"Indie Flower" => "Indie Flower",
	"Inika" => "Inika",
	"Inknut Antiqua" => "Inknut Antiqua",
	"Irish Grover" => "Irish Grover",
	"Istok Web" => "Istok Web",
	"Italiana" => "Italiana",
	"Italianno" => "Italianno",
	"Itim" => "Itim",
	"Jacques Francois" => "Jacques Francois",
	"Jacques Francois Shadow" => "Jacques Francois Shadow",
	"Jaldi" => "Jaldi",
	"Jim Nightshade" => "Jim Nightshade",
	"Jockey One" => "Jockey One",
	"Jolly Lodger" => "Jolly Lodger",
	"Jomhuria" => "Jomhuria",
	"Josefin Sans" => "Josefin Sans",
	"Josefin Slab" => "Josefin Slab",
	"Joti One" => "Joti One",
	"Jua" => "Jua",
	"Judson" => "Judson",
	"Julee" => "Julee",
	"Julius Sans One" => "Julius Sans One",
	"Junge" => "Junge",
	"Jura" => "Jura",
	"Just Another Hand" => "Just Another Hand",
	"Just Me Again Down Here" => "Just Me Again Down Here",
	"K2D" => "K2D",
	"Kadwa" => "Kadwa",
	"Kalam" => "Kalam",
	"Kameron" => "Kameron",
	"Kanit" => "Kanit",
	"Kantumruy" => "Kantumruy",
	"Karla" => "Karla",
	"Karma" => "Karma",
	"Katibeh" => "Katibeh",
	"Kaushan Script" => "Kaushan Script",
	"Kavivanar" => "Kavivanar",
	"Kavoon" => "Kavoon",
	"Kdam Thmor" => "Kdam Thmor",
	"Keania One" => "Keania One",
	"Kelly Slab" => "Kelly Slab",
	"Kenia" => "Kenia",
	"Khand" => "Khand",
	"Khmer" => "Khmer",
	"Khula" => "Khula",
	"Kirang Haerang" => "Kirang Haerang",
	"Kite One" => "Kite One",
	"Knewave" => "Knewave",
	"KoHo" => "KoHo",
	"Kodchasan" => "Kodchasan",
	"Kosugi" => "Kosugi",
	"Kosugi Maru" => "Kosugi Maru",
	"Kotta One" => "Kotta One",
	"Koulen" => "Koulen",
	"Kranky" => "Kranky",
	"Kreon" => "Kreon",
	"Kristi" => "Kristi",
	"Krona One" => "Krona One",
	"Krub" => "Krub",
	"Kumar One" => "Kumar One",
	"Kumar One Outline" => "Kumar One Outline",
	"Kurale" => "Kurale",
	"La Belle Aurore" => "La Belle Aurore",
	"Lacquer" => "Lacquer",
	"Laila" => "Laila",
	"Lakki Reddy" => "Lakki Reddy",
	"Lalezar" => "Lalezar",
	"Lancelot" => "Lancelot",
	"Lateef" => "Lateef",
	"Lato" => "Lato",
	"League Script" => "League Script",
	"Leckerli One" => "Leckerli One",
	"Ledger" => "Ledger",
	"Lekton" => "Lekton",
	"Lemon" => "Lemon",
	"Lemonada" => "Lemonada",
	"Lexend Deca" => "Lexend Deca",
	"Lexend Exa" => "Lexend Exa",
	"Lexend Giga" => "Lexend Giga",
	"Lexend Mega" => "Lexend Mega",
	"Lexend Peta" => "Lexend Peta",
	"Lexend Tera" => "Lexend Tera",
	"Lexend Zetta" => "Lexend Zetta",
	"Libre Barcode 128" => "Libre Barcode 128",
	"Libre Barcode 128 Text" => "Libre Barcode 128 Text",
	"Libre Barcode 39" => "Libre Barcode 39",
	"Libre Barcode 39 Extended" => "Libre Barcode 39 Extended",
	"Libre Barcode 39 Extended Text" => "Libre Barcode 39 Extended Text",
	"Libre Barcode 39 Text" => "Libre Barcode 39 Text",
	"Libre Baskerville" => "Libre Baskerville",
	"Libre Caslon Display" => "Libre Caslon Display",
	"Libre Caslon Text" => "Libre Caslon Text",
	"Libre Franklin" => "Libre Franklin",
	"Life Savers" => "Life Savers",
	"Lilita One" => "Lilita One",
	"Lily Script One" => "Lily Script One",
	"Limelight" => "Limelight",
	"Linden Hill" => "Linden Hill",
	"Literata" => "Literata",
	"Liu Jian Mao Cao" => "Liu Jian Mao Cao",
	"Livvic" => "Livvic",
	"Lobster" => "Lobster",
	"Lobster Two" => "Lobster Two",
	"Londrina Outline" => "Londrina Outline",
	"Londrina Shadow" => "Londrina Shadow",
	"Londrina Sketch" => "Londrina Sketch",
	"Londrina Solid" => "Londrina Solid",
	"Long Cang" => "Long Cang",
	"Lora" => "Lora",
	"Love Ya Like A Sister" => "Love Ya Like A Sister",
	"Loved by the King" => "Loved by the King",
	"Lovers Quarrel" => "Lovers Quarrel",
	"Luckiest Guy" => "Luckiest Guy",
	"Lusitana" => "Lusitana",
	"Lustria" => "Lustria",
	"M PLUS 1p" => "M PLUS 1p",
	"M PLUS Rounded 1c" => "M PLUS Rounded 1c",
	"Ma Shan Zheng" => "Ma Shan Zheng",
	"Macondo" => "Macondo",
	"Macondo Swash Caps" => "Macondo Swash Caps",
	"Mada" => "Mada",
	"Magra" => "Magra",
	"Maiden Orange" => "Maiden Orange",
	"Maitree" => "Maitree",
	"Major Mono Display" => "Major Mono Display",
	"Mako" => "Mako",
	"Mali" => "Mali",
	"Mallanna" => "Mallanna",
	"Mandali" => "Mandali",
	"Manjari" => "Manjari",
	"Mansalva" => "Mansalva",
	"Manuale" => "Manuale",
	"Marcellus" => "Marcellus",
	"Marcellus SC" => "Marcellus SC",
	"Marck Script" => "Marck Script",
	"Margarine" => "Margarine",
	"Markazi Text" => "Markazi Text",
	"Marko One" => "Marko One",
	"Marmelad" => "Marmelad",
	"Martel" => "Martel",
	"Martel Sans" => "Martel Sans",
	"Marvel" => "Marvel",
	"Mate" => "Mate",
	"Mate SC" => "Mate SC",
	"Maven Pro" => "Maven Pro",
	"McLaren" => "McLaren",
	"Meddon" => "Meddon",
	"MedievalSharp" => "MedievalSharp",
	"Medula One" => "Medula One",
	"Meera Inimai" => "Meera Inimai",
	"Megrim" => "Megrim",
	"Meie Script" => "Meie Script",
	"Merienda" => "Merienda",
	"Merienda One" => "Merienda One",
	"Merriweather" => "Merriweather",
	"Merriweather Sans" => "Merriweather Sans",
	"Metal" => "Metal",
	"Metal Mania" => "Metal Mania",
	"Metamorphous" => "Metamorphous",
	"Metrophobic" => "Metrophobic",
	"Michroma" => "Michroma",
	"Milonga" => "Milonga",
	"Miltonian" => "Miltonian",
	"Miltonian Tattoo" => "Miltonian Tattoo",
	"Mina" => "Mina",
	"Miniver" => "Miniver",
	"Miriam Libre" => "Miriam Libre",
	"Mirza" => "Mirza",
	"Miss Fajardose" => "Miss Fajardose",
	"Mitr" => "Mitr",
	"Modak" => "Modak",
	"Modern Antiqua" => "Modern Antiqua",
	"Mogra" => "Mogra",
	"Molengo" => "Molengo",
	"Molle" => "Molle",
	"Monda" => "Monda",
	"Monofett" => "Monofett",
	"Monoton" => "Monoton",
	"Monsieur La Doulaise" => "Monsieur La Doulaise",
	"Montaga" => "Montaga",
	"Montez" => "Montez",
	"Montserrat" => "Montserrat",
	"Montserrat Alternates" => "Montserrat Alternates",
	"Montserrat Subrayada" => "Montserrat Subrayada",
	"Moul" => "Moul",
	"Moulpali" => "Moulpali",
	"Mountains of Christmas" => "Mountains of Christmas",
	"Mouse Memoirs" => "Mouse Memoirs",
	"Mr Bedfort" => "Mr Bedfort",
	"Mr Dafoe" => "Mr Dafoe",
	"Mr De Haviland" => "Mr De Haviland",
	"Mrs Saint Delafield" => "Mrs Saint Delafield",
	"Mrs Sheppards" => "Mrs Sheppards",
	"Mukta" => "Mukta",
	"Mukta Mahee" => "Mukta Mahee",
	"Mukta Malar" => "Mukta Malar",
	"Mukta Vaani" => "Mukta Vaani",
	"Muli" => "Muli",
	"Mystery Quest" => "Mystery Quest",
	"NTR" => "NTR",
	"Nanum Brush Script" => "Nanum Brush Script",
	"Nanum Gothic" => "Nanum Gothic",
	"Nanum Gothic Coding" => "Nanum Gothic Coding",
	"Nanum Myeongjo" => "Nanum Myeongjo",
	"Nanum Pen Script" => "Nanum Pen Script",
	"Neucha" => "Neucha",
	"Neuton" => "Neuton",
	"New Rocker" => "New Rocker",
	"News Cycle" => "News Cycle",
	"Niconne" => "Niconne",
	"Niramit" => "Niramit",
	"Nixie One" => "Nixie One",
	"Nobile" => "Nobile",
	"Nokora" => "Nokora",
	"Norican" => "Norican",
	"Nosifer" => "Nosifer",
	"Notable" => "Notable",
	"Nothing You Could Do" => "Nothing You Could Do",
	"Noticia Text" => "Noticia Text",
	"Noto Sans" => "Noto Sans",
	"Noto Sans HK" => "Noto Sans HK",
	"Noto Sans JP" => "Noto Sans JP",
	"Noto Sans KR" => "Noto Sans KR",
	"Noto Sans SC" => "Noto Sans SC",
	"Noto Sans TC" => "Noto Sans TC",
	"Noto Serif" => "Noto Serif",
	"Noto Serif JP" => "Noto Serif JP",
	"Noto Serif KR" => "Noto Serif KR",
	"Noto Serif SC" => "Noto Serif SC",
	"Noto Serif TC" => "Noto Serif TC",
	"Nova Cut" => "Nova Cut",
	"Nova Flat" => "Nova Flat",
	"Nova Mono" => "Nova Mono",
	"Nova Oval" => "Nova Oval",
	"Nova Round" => "Nova Round",
	"Nova Script" => "Nova Script",
	"Nova Slim" => "Nova Slim",
	"Nova Square" => "Nova Square",
	"Numans" => "Numans",
	"Nunito" => "Nunito",
	"Nunito Sans" => "Nunito Sans",
	"Odor Mean Chey" => "Odor Mean Chey",
	"Offside" => "Offside",
	"Old Standard TT" => "Old Standard TT",
	"Oldenburg" => "Oldenburg",
	"Oleo Script" => "Oleo Script",
	"Oleo Script Swash Caps" => "Oleo Script Swash Caps",
	"Open Sans" => "Open Sans",
	"Open Sans Condensed" => "Open Sans Condensed",
	"Oranienbaum" => "Oranienbaum",
	"Orbitron" => "Orbitron",
	"Oregano" => "Oregano",
	"Orienta" => "Orienta",
	"Original Surfer" => "Original Surfer",
	"Oswald" => "Oswald",
	"Over the Rainbow" => "Over the Rainbow",
	"Overlock" => "Overlock",
	"Overlock SC" => "Overlock SC",
	"Overpass" => "Overpass",
	"Overpass Mono" => "Overpass Mono",
	"Ovo" => "Ovo",
	"Oxygen" => "Oxygen",
	"Oxygen Mono" => "Oxygen Mono",
	"PT Mono" => "PT Mono",
	"PT Sans" => "PT Sans",
	"PT Sans Caption" => "PT Sans Caption",
	"PT Sans Narrow" => "PT Sans Narrow",
	"PT Serif" => "PT Serif",
	"PT Serif Caption" => "PT Serif Caption",
	"Pacifico" => "Pacifico",
	"Padauk" => "Padauk",
	"Palanquin" => "Palanquin",
	"Palanquin Dark" => "Palanquin Dark",
	"Pangolin" => "Pangolin",
	"Paprika" => "Paprika",
	"Parisienne" => "Parisienne",
	"Passero One" => "Passero One",
	"Passion One" => "Passion One",
	"Pathway Gothic One" => "Pathway Gothic One",
	"Patrick Hand" => "Patrick Hand",
	"Patrick Hand SC" => "Patrick Hand SC",
	"Pattaya" => "Pattaya",
	"Patua One" => "Patua One",
	"Pavanam" => "Pavanam",
	"Paytone One" => "Paytone One",
	"Peddana" => "Peddana",
	"Peralta" => "Peralta",
	"Permanent Marker" => "Permanent Marker",
	"Petit Formal Script" => "Petit Formal Script",
	"Petrona" => "Petrona",
	"Philosopher" => "Philosopher",
	"Piedra" => "Piedra",
	"Pinyon Script" => "Pinyon Script",
	"Pirata One" => "Pirata One",
	"Plaster" => "Plaster",
	"Play" => "Play",
	"Playball" => "Playball",
	"Playfair Display" => "Playfair Display",
	"Playfair Display SC" => "Playfair Display SC",
	"Podkova" => "Podkova",
	"Poiret One" => "Poiret One",
	"Poller One" => "Poller One",
	"Poly" => "Poly",
	"Pompiere" => "Pompiere",
	"Pontano Sans" => "Pontano Sans",
	"Poor Story" => "Poor Story",
	"Poppins" => "Poppins",
	"Port Lligat Sans" => "Port Lligat Sans",
	"Port Lligat Slab" => "Port Lligat Slab",
	"Pragati Narrow" => "Pragati Narrow",
	"Prata" => "Prata",
	"Preahvihear" => "Preahvihear",
	"Press Start 2P" => "Press Start 2P",
	"Pridi" => "Pridi",
	"Princess Sofia" => "Princess Sofia",
	"Prociono" => "Prociono",
	"Prompt" => "Prompt",
	"Prosto One" => "Prosto One",
	"Proza Libre" => "Proza Libre",
	"Puritan" => "Puritan",
	"Purple Purse" => "Purple Purse",
	"Quando" => "Quando",
	"Quantico" => "Quantico",
	"Quattrocento" => "Quattrocento",
	"Quattrocento Sans" => "Quattrocento Sans",
	"Questrial" => "Questrial",
	"Quicksand" => "Quicksand",
	"Quintessential" => "Quintessential",
	"Qwigley" => "Qwigley",
	"Racing Sans One" => "Racing Sans One",
	"Radley" => "Radley",
	"Rajdhani" => "Rajdhani",
	"Rakkas" => "Rakkas",
	"Raleway" => "Raleway",
	"Raleway Dots" => "Raleway Dots",
	"Ramabhadra" => "Ramabhadra",
	"Ramaraja" => "Ramaraja",
	"Rambla" => "Rambla",
	"Rammetto One" => "Rammetto One",
	"Ranchers" => "Ranchers",
	"Rancho" => "Rancho",
	"Ranga" => "Ranga",
	"Rasa" => "Rasa",
	"Rationale" => "Rationale",
	"Ravi Prakash" => "Ravi Prakash",
	"Red Hat Display" => "Red Hat Display",
	"Red Hat Text" => "Red Hat Text",
	"Redressed" => "Redressed",
	"Reem Kufi" => "Reem Kufi",
	"Reenie Beanie" => "Reenie Beanie",
	"Revalia" => "Revalia",
	"Rhodium Libre" => "Rhodium Libre",
	"Ribeye" => "Ribeye",
	"Ribeye Marrow" => "Ribeye Marrow",
	"Righteous" => "Righteous",
	"Risque" => "Risque",
	"Roboto" => "Roboto",
	"Roboto Condensed" => "Roboto Condensed",
	"Roboto Mono" => "Roboto Mono",
	"Roboto Slab" => "Roboto Slab",
	"Rochester" => "Rochester",
	"Rock Salt" => "Rock Salt",
	"Rokkitt" => "Rokkitt",
	"Romanesco" => "Romanesco",
	"Ropa Sans" => "Ropa Sans",
	"Rosario" => "Rosario",
	"Rosarivo" => "Rosarivo",
	"Rouge Script" => "Rouge Script",
	"Rozha One" => "Rozha One",
	"Rubik" => "Rubik",
	"Rubik Mono One" => "Rubik Mono One",
	"Ruda" => "Ruda",
	"Rufina" => "Rufina",
	"Ruge Boogie" => "Ruge Boogie",
	"Ruluko" => "Ruluko",
	"Rum Raisin" => "Rum Raisin",
	"Ruslan Display" => "Ruslan Display",
	"Russo One" => "Russo One",
	"Ruthie" => "Ruthie",
	"Rye" => "Rye",
	"Sacramento" => "Sacramento",
	"Sahitya" => "Sahitya",
	"Sail" => "Sail",
	"Saira" => "Saira",
	"Saira Condensed" => "Saira Condensed",
	"Saira Extra Condensed" => "Saira Extra Condensed",
	"Saira Semi Condensed" => "Saira Semi Condensed",
	"Saira Stencil One" => "Saira Stencil One",
	"Salsa" => "Salsa",
	"Sanchez" => "Sanchez",
	"Sancreek" => "Sancreek",
	"Sansita" => "Sansita",
	"Sarabun" => "Sarabun",
	"Sarala" => "Sarala",
	"Sarina" => "Sarina",
	"Sarpanch" => "Sarpanch",
	"Satisfy" => "Satisfy",
	"Sawarabi Gothic" => "Sawarabi Gothic",
	"Sawarabi Mincho" => "Sawarabi Mincho",
	"Scada" => "Scada",
	"Scheherazade" => "Scheherazade",
	"Schoolbell" => "Schoolbell",
	"Scope One" => "Scope One",
	"Seaweed Script" => "Seaweed Script",
	"Secular One" => "Secular One",
	"Sedgwick Ave" => "Sedgwick Ave",
	"Sedgwick Ave Display" => "Sedgwick Ave Display",
	"Sevillana" => "Sevillana",
	"Seymour One" => "Seymour One",
	"Shadows Into Light" => "Shadows Into Light",
	"Shadows Into Light Two" => "Shadows Into Light Two",
	"Shanti" => "Shanti",
	"Share" => "Share",
	"Share Tech" => "Share Tech",
	"Share Tech Mono" => "Share Tech Mono",
	"Shojumaru" => "Shojumaru",
	"Short Stack" => "Short Stack",
	"Shrikhand" => "Shrikhand",
	"Siemreap" => "Siemreap",
	"Sigmar One" => "Sigmar One",
	"Signika" => "Signika",
	"Signika Negative" => "Signika Negative",
	"Simonetta" => "Simonetta",
	"Single Day" => "Single Day",
	"Sintony" => "Sintony",
	"Sirin Stencil" => "Sirin Stencil",
	"Six Caps" => "Six Caps",
	"Skranji" => "Skranji",
	"Slabo 13px" => "Slabo 13px",
	"Slabo 27px" => "Slabo 27px",
	"Slackey" => "Slackey",
	"Smokum" => "Smokum",
	"Smythe" => "Smythe",
	"Sniglet" => "Sniglet",
	"Snippet" => "Snippet",
	"Snowburst One" => "Snowburst One",
	"Sofadi One" => "Sofadi One",
	"Sofia" => "Sofia",
	"Song Myung" => "Song Myung",
	"Sonsie One" => "Sonsie One",
	"Sorts Mill Goudy" => "Sorts Mill Goudy",
	"Source Code Pro" => "Source Code Pro",
	"Source Sans Pro" => "Source Sans Pro",
	"Source Serif Pro" => "Source Serif Pro",
	"Space Mono" => "Space Mono",
	"Special Elite" => "Special Elite",
	"Spectral" => "Spectral",
	"Spectral SC" => "Spectral SC",
	"Spicy Rice" => "Spicy Rice",
	"Spinnaker" => "Spinnaker",
	"Spirax" => "Spirax",
	"Squada One" => "Squada One",
	"Sree Krushnadevaraya" => "Sree Krushnadevaraya",
	"Sriracha" => "Sriracha",
	"Srisakdi" => "Srisakdi",
	"Staatliches" => "Staatliches",
	"Stalemate" => "Stalemate",
	"Stalinist One" => "Stalinist One",
	"Stardos Stencil" => "Stardos Stencil",
	"Stint Ultra Condensed" => "Stint Ultra Condensed",
	"Stint Ultra Expanded" => "Stint Ultra Expanded",
	"Stoke" => "Stoke",
	"Strait" => "Strait",
	"Stylish" => "Stylish",
	"Sue Ellen Francisco" => "Sue Ellen Francisco",
	"Suez One" => "Suez One",
	"Sumana" => "Sumana",
	"Sunflower" => "Sunflower",
	"Sunshiney" => "Sunshiney",
	"Supermercado One" => "Supermercado One",
	"Sura" => "Sura",
	"Suranna" => "Suranna",
	"Suravaram" => "Suravaram",
	"Suwannaphum" => "Suwannaphum",
	"Swanky and Moo Moo" => "Swanky and Moo Moo",
	"Syncopate" => "Syncopate",
	"Tajawal" => "Tajawal",
	"Tangerine" => "Tangerine",
	"Taprom" => "Taprom",
	"Tauri" => "Tauri",
	"Taviraj" => "Taviraj",
	"Teko" => "Teko",
	"Telex" => "Telex",
	"Tenali Ramakrishna" => "Tenali Ramakrishna",
	"Tenor Sans" => "Tenor Sans",
	"Text Me One" => "Text Me One",
	"Thasadith" => "Thasadith",
	"The Girl Next Door" => "The Girl Next Door",
	"Tienne" => "Tienne",
	"Tillana" => "Tillana",
	"Timmana" => "Timmana",
	"Tinos" => "Tinos",
	"Titan One" => "Titan One",
	"Titillium Web" => "Titillium Web",
	"Trade Winds" => "Trade Winds",
	"Trirong" => "Trirong",
	"Trocchi" => "Trocchi",
	"Trochut" => "Trochut",
	"Trykker" => "Trykker",
	"Tulpen One" => "Tulpen One",
	"Turret Road" => "Turret Road",
	"Ubuntu" => "Ubuntu",
	"Ubuntu Condensed" => "Ubuntu Condensed",
	"Ubuntu Mono" => "Ubuntu Mono",
	"Ultra" => "Ultra",
	"Uncial Antiqua" => "Uncial Antiqua",
	"Underdog" => "Underdog",
	"Unica One" => "Unica One",
	"UnifrakturCook" => "UnifrakturCook",
	"UnifrakturMaguntia" => "UnifrakturMaguntia",
	"Unkempt" => "Unkempt",
	"Unlock" => "Unlock",
	"Unna" => "Unna",
	"VT323" => "VT323",
	"Vampiro One" => "Vampiro One",
	"Varela" => "Varela",
	"Varela Round" => "Varela Round",
	"Vast Shadow" => "Vast Shadow",
	"Vesper Libre" => "Vesper Libre",
	"Vibes" => "Vibes",
	"Vibur" => "Vibur",
	"Vidaloka" => "Vidaloka",
	"Viga" => "Viga",
	"Voces" => "Voces",
	"Volkhov" => "Volkhov",
	"Vollkorn" => "Vollkorn",
	"Vollkorn SC" => "Vollkorn SC",
	"Voltaire" => "Voltaire",
	"Waiting for the Sunrise" => "Waiting for the Sunrise",
	"Wallpoet" => "Wallpoet",
	"Walter Turncoat" => "Walter Turncoat",
	"Warnes" => "Warnes",
	"Wellfleet" => "Wellfleet",
	"Wendy One" => "Wendy One",
	"Wire One" => "Wire One",
	"Work Sans" => "Work Sans",
	"Yanone Kaffeesatz" => "Yanone Kaffeesatz",
	"Yantramanav" => "Yantramanav",
	"Yatra One" => "Yatra One",
	"Yellowtail" => "Yellowtail",
	"Yeon Sung" => "Yeon Sung",
	"Yeseva One" => "Yeseva One",
	"Yesteryear" => "Yesteryear",
	"Yrsa" => "Yrsa",
	"ZCOOL KuaiLe" => "ZCOOL KuaiLe",
	"ZCOOL QingKe HuangYou" => "ZCOOL QingKe HuangYou",
	"ZCOOL XiaoWei" => "ZCOOL XiaoWei",
	"Zeyada" => "Zeyada",
	"Zhi Mang Xing" => "Zhi Mang Xing",
	"Zilla Slab" => "Zilla Slab",
	"Zilla Slab Highlight" => "Zilla Slab Highlight",
);

asort($font_primary_list);
asort($font_secondary_list);

$font_primary_list      = array_merge(array("none" => "Use default"), $font_primary_list, $sep, $google_fonts_list);
$font_secondary_list    = array_merge(array("none" => "Use default"), $font_secondary_list, $sep, $google_fonts_list);

$of_options[] = array( 	"name" 		=> "Primary Font",
						"desc" 		=> "Select main font to be used in paragraphs and other sections",
						"id" 		=> "font_primary",
						"std" 		=> "Select a font",
						"type" 		=> "select_google_font",
						"preview" 	=> array(
										"text" => "This is how the text looks in the site",
										"size" => "30px"
						),
						"options" 	=> $font_primary_list
				);

$of_options[] = array( 	"name" 		=> "Heading Font",
						"desc" 		=> "Font type that is used on headings",
						"id" 		=> "font_secondary",
						"std" 		=> "Select a font",
						"type" 		=> "select_google_font",
						"preview" 	=> array(
										"text" => "This is how the text looks in the site",
										"size" => "30px"
						),
						"options" 	=> $font_secondary_list
				);


$of_options[] = array( 	"name" 		=> "Base Font Size",
					 	"desc" 		=> "Increase or dececrease overall font size",
						"id" 		=> "font_size_base",
						"std" 		=> "Use default",
						"type" 		=> "select",
						"options" 	=> array("Use default", 10, 11, 12, 13, 14, 15, 16, 17, 18)
				);


$of_options[] = array( 	"name" 		=> "Text Transform on Headings",
					 	"desc" 		=> "Transform the text used on heading, labels and buttons",
						"id" 		=> "font_to_lowercase",
						"std" 		=> "Upper Case",
						"type" 		=> "select",
						"options" 	=> array("Upper Case", "Default Case")
				);


$of_options[] = array( 	"name" 		=> "Custom Fonts",
						"desc" 		=> "",
						"id" 		=> "custom_gf",
						"std" 		=> "<h3 style=\"margin: 0 0 10px;\">Including Custom Fonts</h3>
						If you want to add your custom font to your site please fill the fields below.<br />
						Note that if you add your custom font, the above font won't be imported in your site.",
						"icon" 		=> true,
						"type" 		=> "info"
				);


$of_options[] = array( 	"name" 		=> "Primary Font",
						"desc" 		=> "Primary font URL",
						"id" 		=> "custom_primary_font_url",
						"std" 		=> "",
						"plc"		=> "i.e. " . WP_CONTENT_URL . '/uploads/custom-font.css',
						"type" 		=> "text"
				);


$of_options[] = array( 	"desc" 		=> "Primary font name",
						"id" 		=> "custom_primary_font_name",
						"std" 		=> "",
						"plc"		=> "'Oswald', sans-serif",
						"type" 		=> "text"
				);


$of_options[] = array( 	"name" 		=> "Heading Font",
						"desc" 		=> "Heading font URL",
						"id" 		=> "custom_heading_font_url",
						"std" 		=> "",
						"plc"		=> "i.e. " . WP_CONTENT_URL . '/uploads/custom-font.css',
						"type" 		=> "text"
				);


$of_options[] = array( 	"desc" 		=> "Heading font name",
						"id" 		=> "custom_heading_font_name",
						"std" 		=> "",
						"plc"		=> "'Oswald', sans-serif",
						"type" 		=> "text"
				);



$of_options[] = array( 	"name" 		=> "Theme Styling",
						"type" 		=> "heading"
				);



$of_options[] = array( 	"name" 		=> "Theme Skin",
						"desc" 		=> "Select predefined skins to use with this theme.",
						"id" 		=> "use_skin_type",
						"std" 		=> oxygen_get_theme_assets_uri() . '/images/skin-type-1.png',
						"type" 		=> "tiles",
						"options"	=> array(
							oxygen_get_theme_assets_uri() . '/images/skin-type-1.png',
							oxygen_get_theme_assets_uri() . '/images/skin-type-2.png',
							oxygen_get_theme_assets_uri() . '/images/skin-type-3.png',
							oxygen_get_theme_assets_uri() . '/images/skin-type-4.png',
							oxygen_get_theme_assets_uri() . '/images/skin-type-5.png',
							oxygen_get_theme_assets_uri() . '/images/skin-type-6.png',
							oxygen_get_theme_assets_uri() . '/images/skin-type-7.png',
							oxygen_get_theme_assets_uri() . '/images/skin-type-8.png',
						)
				);

$of_options[] = array(  "name"		=> "Custom Skin Builder",
						"desc"   	=> "Use a custom skin with color picker.",
						"id"   		=> "use_custom_skin",
						"std"   	=> 0,
						"folds"  	=> 1,
						"on"  		=> "Yes",
						"off"  		=> "No",
						"type"   	=> "switch"
					);

$of_options[] = array(	"name"		=> "Select Skin Colors",
						"desc"   	=> "Choose main skin color.",
						"id"   		=> "custom_skin_main_color",
						"std"   	=> '',
						"fold"  	=> 'use_custom_skin',
						"type"   	=> "color"
					);

$of_options[] = array(	"desc"   	=> "Choose menu link color.",
						"id"   		=> "custom_skin_menu_link_color",
						"std"   	=> '#333333',
						"fold"  	=> 'use_custom_skin',
						"type"   	=> "color"
					);

$is_writtable_custom_skin = '';

if ( ! is_writable( get_template_directory() . '/assets/less/custom-skin.less' ) ) {
	$is_writtable_custom_skin = '<div title="Location:'."\n" . oxygen_get_theme_assets_uri() . '/less/custom-skin.css" style="color: #c00; padding-top: 10px;">Warning: <strong>custom-skin.css</strong> is not writable, skin cannot be compiled!</div> ';
}

$of_options[] = array(	"desc"   	=> "Choose background color." . $is_writtable_custom_skin,
						"id"   		=> "custom_skin_background_color",
						"std"   	=> '#EEEEEE',
						"fold"  	=> 'use_custom_skin',
						"type"   	=> "color"
					);


$of_options[] = array( 	"name" 		=> "Custom CSS",
						"desc" 		=> "",
						"id" 		=> "custom_css_feature",
						"std" 		=> "<h3 style=\"margin: 0 0 10px;\">Custom CSS in a New Interface</h3>
						We have created a better interface for adding your custom CSS which is more flexible and includes syntax highlighting.
						<br />
						<br />
						<a href=\"admin.php?page=laborator_custom_css\" class=\"button\">Go to new Custom CSS Editor</a>",
						"icon" 		=> true,
						"type" 		=> "info"
				);

/*
$of_options[] = array( 	"name" 		=> "Custom CSS",
						"desc" 		=> "Apply your own custom CSS to all site pages.<br /><br />CSS is automatically wrapped with &lt;style&gt;&lt;/style&gt; tags.",
						"id" 		=> "custom_css_general",
						"std" 		=> "",
						"type" 		=> "textarea"
				);


$of_options[] = array( 	"name" 		=> "Media Queries CSS",
						"desc" 		=> "Large Screen<br />For screen width: <strong>1200px</strong> - <strong>larger size</strong>.",
						"id" 		=> "custom_css_general_lg",
						"std" 		=> "",
						"type" 		=> "textarea"
				);


$of_options[] = array( 	"desc" 		=> "Laptop<br />For screen width: <strong>992px</strong> - <strong>larger size</strong>.",
						"id" 		=> "custom_css_general_md",
						"std" 		=> "",
						"type" 		=> "textarea"
				);


$of_options[] = array( 	"desc" 		=> "Tablet<br />For screen width: <strong>768px</strong> - <strong>991px</strong>.",
						"id" 		=> "custom_css_general_sm",
						"std" 		=> "",
						"type" 		=> "textarea"
				);


$of_options[] = array( 	"desc" 		=> "Mobile<br />For screen width: <strong>480px</strong> - <strong>767px</strong>.",
						"id" 		=> "custom_css_general_xs",
						"std" 		=> "",
						"type" 		=> "textarea"
				);


$of_options[] = array( 	"desc" 		=> "Mobile<br />For screen width: <strong>0px</strong> - <strong>479px</strong>.",
						"id" 		=> "custom_css_general_xxs",
						"std" 		=> "",
						"type" 		=> "textarea"
				);
*/


$of_options[] = array( 	"name" 		=> "Social Networks",
						"type" 		=> "heading"
				);

$social_networks_ordering = array(
			"visible" => array (
				"placebo"	=> "placebo",
				"fb"   	 	=> "Facebook",
				"tw"   	 	=> "Twitter",
			),

			"hidden" => array (
				"placebo"   => "placebo",
				"lin"       => "LinkedIn",
				"yt"        => "YouTube",
				"vm"        => "Vimeo",
				"drb"       => "Dribbble",
				"ig"        => "Instagram",
				"pi"        => "Pinterest",
				"vk"        => "VKontakte",
				"tu"        => "Tumblr",
			),
);

$of_options[] = array( 	"name" 		=> "Social Networks Ordering",
						"desc" 		=> "Set the appearing order of social networks in the footer. To use social networks links list copy this shortcode: <code style='font-size: 10px; display: inline-block; margin-top: 10px;'>[lab_social_networks]</code>",
						"id" 		=> "social_order",
						"std" 		=> $social_networks_ordering,
						"type" 		=> "sorter"
				);

$of_options[] = array( 	"name" 		=> "Social Networks",
						"desc" 		=> "Facebook",
						"id" 		=> "social_network_link_fb",
						"std" 		=> "",
						"plc"		=> "https://facebook.com/username",
						"type" 		=> "text"
				);

$of_options[] = array( 	"name" 		=> "",
						"desc" 		=> "Twitter",
						"id" 		=> "social_network_link_tw",
						"std" 		=> "",
						"plc"		=> "https://twitter.com/username",
						"type" 		=> "text"
				);

$of_options[] = array( 	"name" 		=> "",
						"desc" 		=> "LinkedIn",
						"id" 		=> "social_network_link_lin",
						"std" 		=> "",
						"plc"		=> "https://linkedin.com/in/username",
						"type" 		=> "text"
				);

$of_options[] = array( 	"name" 		=> "",
						"desc" 		=> "YouTube",
						"id" 		=> "social_network_link_yt",
						"std" 		=> "",
						"plc"		=> "https://youtube.com/username",
						"type" 		=> "text"
				);

$of_options[] = array( 	"name" 		=> "",
						"desc" 		=> "Vimeo",
						"id" 		=> "social_network_link_vm",
						"std" 		=> "",
						"plc"		=> "https://vimeo.com/username",
						"type" 		=> "text"
				);

$of_options[] = array( 	"name" 		=> "",
						"desc" 		=> "Dribbble",
						"id" 		=> "social_network_link_drb",
						"std" 		=> "",
						"plc"		=> "https://dribbble.com/username",
						"type" 		=> "text"
				);

$of_options[] = array( 	"name" 		=> "",
						"desc" 		=> "Instagram",
						"id" 		=> "social_network_link_ig",
						"std" 		=> "",
						"plc"		=> "https://instagram.com/username",
						"type" 		=> "text"
				);

$of_options[] = array( 	"name" 		=> "",
						"desc" 		=> "Pinterest",
						"id" 		=> "social_network_link_pi",
						"std" 		=> "",
						"plc"		=> "https://pinterest.com/username",
						"type" 		=> "text"
				);

$of_options[] = array( 	"name" 		=> "",
						"desc" 		=> "VKontakte",
						"id" 		=> "social_network_link_vk",
						"std" 		=> "",
						"plc"		=> "https://vk.com/username",
						"type" 		=> "text"
				);

$of_options[] = array( 	"name" 		=> "",
						"desc" 		=> "Tumblr",
						"id" 		=> "social_network_link_tu",
						"std" 		=> "",
						"plc"		=> "https://username.tumblr.com",
						"type" 		=> "text"
				);

### END: OXYGEN ###


// Backup Options
$of_options[] = array( 	"name" 		=> "Backup Options",
						"type" 		=> "heading",
						"icon"		=> ADMIN_IMAGES . "icon-slider.png"
				);
$of_options[] = array( 	"name" 		=> "Backup and Restore Options",
						"id" 		=> "of_backup",
						"std" 		=> "",
						"type" 		=> "backup",
						"desc" 		=> 'You can use the two buttons below to backup your current options, and then restore it back at a later time. This is useful if you want to experiment on the options but would like to keep the old settings in case you need it back.',
				);

$of_options[] = array( 	"name" 		=> "Transfer Theme Options Data",
						"id" 		=> "of_transfer",
						"std" 		=> "",
						"type" 		=> "transfer",
						"desc" 		=> 'You can tranfer the saved options data between different installs by copying the text inside the text box. To import data from another install, replace the data in the text box with the one from another install and click "Import Options".',
				);

$of_options[] = array( 	"name" 		=> "Changelog",
						"type" 		=> "heading",
				);

	}//End function: of_options()
}//End chack if function exists: of_options()
