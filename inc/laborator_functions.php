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
 * Getter $_GET
 */
function lab_get( $var ) {
	return isset( $_GET[ $var ] ) ? $_GET[ $var ] : ( isset( $_REQUEST[ $var ] ) ? $_REQUEST[ $var ] : '' );
}

/**
 * Getter $_POST
 */
function post( $var ) {
	return isset( $_POST[ $var ] ) ? $_POST[ $var ] : null;
}

/**
 * Getter $_COOKIE
 */
function cookie( $var ) {
	return isset( $_COOKIE[ $var ] ) ? $_COOKIE[ $var ] : null;
}

/**
 * Get element from array by key (fail safe)
 */
function get_array_key( $arr, $key ) {
	if ( ! is_array( $arr ) ) {
		return null;
	}

	return isset( $arr[ $key ] ) ? $arr[ $key ] : null;
}

/**
 * Getter SMOF data
 */
function get_data( $var = '' ) {
	global $smof_data;

	if ( ! function_exists( 'of_get_options' ) ) {
		return null;
	}

	if ( ! empty( $var ) && isset( $smof_data[ $var ] ) ) {
		return apply_filters( "get_data_{$var}", $smof_data[ $var ] );
	}

	return null;
}

$smof_data = of_get_options();

/**
 * Compress Text Function
 */
function compress_text( $buffer ) {
	/* remove comments */
	$buffer = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer );
	/* remove tabs, spaces, newlines, etc. */
	$buffer = str_replace( array( "\r\n", "\r", "\n", "\t", '	', '	', '	' ), '', $buffer );

	return $buffer;
}

/**
 * Breadcrumb
 */
function dimox_breadcrumbs( $echo = true ) {

	/* === OPTIONS === */
	$text['home']     = __( 'Home', 'oxygen' );
	$text['category'] = __( 'Category: "%s"', 'oxygen' );
	$text['search']   = __( 'Search Results for "%s" Query', 'oxygen' );
	$text['tag']      = __( 'Tag: "%s"', 'oxygen' );
	$text['author']   = __( 'Author: %s', 'oxygen' );
	$text['404']      = __( 'Error 404', 'oxygen' );

	$show_current   = 1;
	$show_on_home   = 1;
	$show_home_link = 1;
	$show_title     = 1;
	$delimiter      = ' ';
	$before         = '<a class="active">';
	$after          = '</a>';
	/* === END OF OPTIONS === */

	global $post;

	$home_link    = home_url( '/' );
	$link_before  = '<span>';
	$link_after   = '</span>';
	$link_attr    = '';
	$link         = $link_before . '<a' . $link_attr . ' href="%1$s">%2$s</a>' . $link_after;
	$parent_id    = $parent_id_2 = ( isset( $post->post_parent ) ? $post->post_parent : 0 );
	$frontpage_id = get_option( 'page_on_front' );

	$output = '';

	if ( is_home() || is_front_page() ) {
		if ( $show_on_home == 1 ) {
			$output .= '<div class="breadcrumbs"><a href="' . $home_link . '">' . $text['home'] . '</a></div>';
		}
	} else {

		$output .= '<div class="breadcrumbs">';
		if ( $show_home_link == 1 ) {
			$output .= '<a href="' . $home_link . '">' . $text['home'] . '</a>';
			if ( $frontpage_id == 0 || $parent_id != $frontpage_id ) {
				$output .= $delimiter;
			}
		}

		if ( is_category() ) {
			$this_cat = get_category( get_query_var( 'cat' ), false );
			if ( $this_cat->parent != 0 ) {
				$cats = get_category_parents( $this_cat->parent, true, $delimiter );
				if ( $show_current == 0 ) {
					$cats = preg_replace( "#^(.+)$delimiter$#", "$1", $cats );
				}
				$cats = str_replace( '<a', $link_before . '<a' . $link_attr, $cats );
				$cats = str_replace( '</a>', '</a>' . $link_after, $cats );
				if ( $show_title == 0 ) {
					$cats = preg_replace( '/ title="(.*?)"/', '', $cats );
				}
				$output .= $cats;
			}
			if ( $show_current == 1 ) {
				$output .= $before . sprintf( $text['category'], single_cat_title( '', false ) ) . $after;
			}

		} elseif ( is_search() ) {
			$output .= $before . sprintf( $text['search'], get_search_query() ) . $after;

		} elseif ( is_day() ) {
			$output .= sprintf( $link, get_year_link( get_the_time( 'Y' ) ), get_the_time( 'Y' ) ) . $delimiter;
			$output .= sprintf( $link, get_month_link( get_the_time( 'Y' ), get_the_time( 'm' ) ), get_the_time( 'F' ) ) . $delimiter;
			$output .= $before . get_the_time( 'd' ) . $after;

		} elseif ( is_month() ) {
			$output .= sprintf( $link, get_year_link( get_the_time( 'Y' ) ), get_the_time( 'Y' ) ) . $delimiter;
			$output .= $before . get_the_time( 'F' ) . $after;

		} elseif ( is_year() ) {
			$output .= $before . get_the_time( 'Y' ) . $after;

		} elseif ( is_single() && ! is_attachment() ) {
			if ( get_post_type() != 'post' ) {
				$post_type = get_post_type_object( get_post_type() );
				$slug      = $post_type->rewrite;
				printf( $link, $home_link . '/' . $slug['slug'] . '/', $post_type->labels->singular_name );
				if ( $show_current == 1 ) {
					$output .= $delimiter . $before . get_the_title() . $after;
				}
			} else {
				$cat  = get_the_category();
				$cat  = $cat[0];
				$cats = get_category_parents( $cat, true, $delimiter );
				if ( $show_current == 0 ) {
					$cats = preg_replace( "#^(.+)$delimiter$#", "$1", $cats );
				}
				$cats = str_replace( '<a', $link_before . '<a' . $link_attr, $cats );
				$cats = str_replace( '</a>', '</a>' . $link_after, $cats );
				if ( $show_title == 0 ) {
					$cats = preg_replace( '/ title="(.*?)"/', '', $cats );
				}
				$output .= $cats;
				if ( $show_current == 1 ) {
					$output .= $before . get_the_title() . $after;
				}
			}

		} elseif ( ! is_single() && ! is_page() && get_post_type() != 'post' && ! is_404() ) {
			$post_type = get_post_type_object( get_post_type() );

			if ( function_exists( 'is_product_category' ) && is_product_category() ) {
				global $wp_query;

				$product_category = get_term_by( 'slug', $wp_query->query['product_cat'], 'product_cat' );

				if ( ! $product_category && $wp_query->queried_object instanceof WP_Term ) {
					$product_category = $wp_query->queried_object;
				}

				if ( $product_category ) {
					$output .= '<a href="' . wc_get_page_permalink( 'shop' ) . '">' . get_the_title( wc_get_page_id( 'shop' ) ) . '</a>';

					// Parents
					if ( $product_category && $product_category->parent ) {
						$product_category_parent_1 = get_term_by( 'id', $product_category->parent, 'product_cat' );

						$output .= '<a href="' . get_term_link( $product_category_parent_1 ) . '">' . $product_category_parent_1->name . '</a>' . $after;

						// Sub Parent
						if ( $product_category_parent_1->parent ) {
							$product_category_parent_2 = get_term_by( 'id', $product_category_parent_1->parent, 'product_cat' );

							$output .= '<a href="' . get_term_link( $product_category_parent_2 ) . '">' . $product_category_parent_2->name . '</a>' . $after;
						}
					}

					$output .= $before . $product_category->name . $after;
				}
			} else {
				$output .= $before . $post_type->labels->singular_name . $after;
			}


		} elseif ( is_attachment() ) {
			$parent = get_post( $parent_id );
			$cat    = get_the_category( $parent->ID );
			$cat    = $cat[0];
			if ( $cat ) {
				$cats = get_category_parents( $cat, true, $delimiter );
				$cats = str_replace( '<a', $link_before . '<a' . $link_attr, $cats );
				$cats = str_replace( '</a>', '</a>' . $link_after, $cats );
				if ( $show_title == 0 ) {
					$cats = preg_replace( '/ title="(.*?)"/', '', $cats );
				}
				$output .= $cats;
			}
			printf( $link, get_permalink( $parent ), $parent->post_title );
			if ( $show_current == 1 ) {
				$output .= $delimiter . $before . get_the_title() . $after;
			}

		} elseif ( is_page() && ! $parent_id ) {
			if ( $show_current == 1 ) {
				$output .= $before . get_the_title() . $after;
			}

		} elseif ( is_page() && $parent_id ) {
			if ( $parent_id != $frontpage_id ) {
				$breadcrumbs = array();
				while ( $parent_id ) {
					$page = get_page( $parent_id );
					if ( $parent_id != $frontpage_id ) {
						$breadcrumbs[] = sprintf( $link, get_permalink( $page->ID ), get_the_title( $page->ID ) );
					}
					$parent_id = $page->post_parent;
				}
				$breadcrumbs = array_reverse( $breadcrumbs );
				for ( $i = 0; $i < count( $breadcrumbs ); $i ++ ) {
					$output .= $breadcrumbs[ $i ];
					if ( $i != count( $breadcrumbs ) - 1 ) {
						$output .= $delimiter;
					}
				}
			}
			if ( $show_current == 1 ) {
				if ( $show_home_link == 1 || ( $parent_id_2 != 0 && $parent_id_2 != $frontpage_id ) ) {
					$output .= $delimiter;
				}
				$output .= $before . get_the_title() . $after;

			}

		} elseif ( is_tag() ) {
			$output .= $before . sprintf( $text['tag'], single_tag_title( '', false ) ) . $after;

		} elseif ( is_author() ) {
			global $author;
			$userdata = get_userdata( $author );
			$output   .= $before . sprintf( $text['author'], $userdata->display_name ) . $after;

		} elseif ( is_404() ) {
			$output .= $before . $text['404'] . $after;

		} elseif ( has_post_format() && ! is_singular() ) {
			$output .= get_post_format_string( get_post_format() );
		}

		if ( get_query_var( 'paged' ) ) {
			#if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) $output .= ' (';
			$output .= ' <a class="paged">(' . __( 'Page', 'oxygen' ) . ' ' . get_query_var( 'paged' ) . ')</a>';
			#if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) $output .= ')';
		}

		$output .= '</div>';

	}

	if ( $echo ) {
		echo $output;
	} else {
		return $output;
	}
}

/**
 * Share Network Story
 */
function share_story_network_link( $network, $id, $simptips = true ) {

	$networks = array(
		'fb' => array(
			'url'     => 'https://www.facebook.com/sharer.php?u=' . urlencode( get_permalink() ),
			'tooltip' => __( 'Share on Facebook', 'oxygen' ),
			'icon'    => 'facebook'
		),

		'tw' => array(
			'url'     => 'https://twitter.com/share?text=' . urlencode( get_the_title() ) . '&amp;url=' . urlencode( get_permalink() ),
			'tooltip' => __( 'Share on Twitter', 'oxygen' ),
			'icon'    => 'twitter'
		),

		'tlr' => array(
			'url'     => 'https://www.tumblr.com/share/link?url=' . urlencode( get_permalink() ) . '&name=' . urlencode( get_the_title() ) . '&description=' . urlencode( get_the_excerpt() ),
			'tooltip' => __( 'Share on Tumblr', 'oxygen' ),
			'icon'    => 'tumblr'
		),

		'lin' => array(
			'url'     => 'https://linkedin.com/shareArticle?mini=true&amp;url=' . get_permalink() . '&amp;title=' . urlencode( get_the_title() ),
			'tooltip' => __( 'Share on LinkedIn', 'oxygen' ),
			'icon'    => 'linkedin'
		),

		'pi' => array(
			'url'     => 'https://pinterest.com/pin/create/button/?url=' . get_permalink() . '&amp;description=' . urlencode( get_the_title() ) . '&amp;' . ( $id ? ( 'media=' . wp_get_attachment_url( get_post_thumbnail_id( $id ) ) ) : '' ),
			'tooltip' => __( 'Share on Pinterest', 'oxygen' ),
			'icon'    => 'pinterest'
		),

		'vk' => array(
			'url'     => 'https://vkontakte.ru/share.php?url=' . get_permalink(),
			'tooltip' => __( 'Share on VKontakte', 'oxygen' ),
			'icon'    => 'vk'
		),

		'em' => array(
			'url'     => 'mailto:?subject=' . urlencode( get_the_title() ) . '&amp;body=' . get_permalink(),
			'tooltip' => __( 'Share via Email', 'oxygen' ),
			'icon'    => 'envelope'
		),
	);

	if ( ! isset( $networks[ $network ] ) ) {
	    return;
    }

	$network_entry = $networks[ $network ];

	?>
    <a class="<?php echo $network_entry['icon'] . ' ';
	echo $simptips ? 'simptip-position-top simptip-fade' : ''; ?>"
       data-tooltip="<?php echo $network_entry['tooltip']; ?>" href="<?php echo $network_entry['url']; ?>"
       target="_blank">
        <i class="fa fa-<?php echo $network_entry['icon']; ?>"></i>
    </a>
	<?php
}

/**
 * Fontello Icon List
 */
function fontello_icon_list() {
	return array(
		'plus',
		'minus',
		'info',
		'left-thin',
		'up-thin',
		'right-thin',
		'down-thin',
		'level-up',
		'level-down',
		'switch',
		'infinity',
		'plus-squared',
		'minus-squared',
		'home',
		'keyboard',
		'erase',
		'pause',
		'fast-forward',
		'fast-backward',
		'to-end',
		'to-start',
		'hourglass',
		'stop',
		'up-dir',
		'play',
		'right-dir',
		'down-dir',
		'left-dir',
		'adjust',
		'cloud',
		'star',
		'star-empty',
		'cup',
		'menu',
		'moon',
		'heart-empty',
		'heart',
		'note',
		'note-beamed',
		'layout',
		'flag',
		'tools',
		'cog',
		'attention',
		'flash',
		'record',
		'cloud-thunder',
		'tape',
		'flight',
		'mail',
		'pencil',
		'feather',
		'check',
		'cancel',
		'cancel-circled',
		'cancel-squared',
		'help',
		'quote',
		'plus-circled',
		'minus-circled',
		'right',
		'direction',
		'forward',
		'ccw',
		'cw',
		'left',
		'up',
		'down',
		'list-add',
		'list',
		'left-bold',
		'right-bold',
		'up-bold',
		'down-bold',
		'user-add',
		'help-circled',
		'info-circled',
		'eye',
		'tag',
		'upload-cloud',
		'reply',
		'reply-all',
		'code',
		'export',
		'print',
		'retweet',
		'comment',
		'chat',
		'vcard',
		'address',
		'location',
		'map',
		'compass',
		'trash',
		'doc',
		'doc-text-inv',
		'docs',
		'doc-landscape',
		'archive',
		'rss',
		'share',
		'basket',
		'shareable',
		'login',
		'logout',
		'volume',
		'resize-full',
		'resize-small',
		'popup',
		'publish',
		'window',
		'arrow-combo',
		'chart-pie',
		'language',
		'air',
		'database',
		'drive',
		'bucket',
		'thermometer',
		'down-circled',
		'left-circled',
		'right-circled',
		'up-circled',
		'down-open',
		'left-open',
		'right-open',
		'up-open',
		'down-open-mini',
		'left-open-mini',
		'right-open-mini',
		'up-open-mini',
		'down-open-big',
		'left-open-big',
		'right-open-big',
		'up-open-big',
		'progress-0',
		'progress-1',
		'progress-2',
		'progress-3',
		'back-in-time',
		'network',
		'inbox',
		'install',
		'lifebuoy',
		'mouse',
		'dot',
		'dot-2',
		'dot-3',
		'suitcase',
		'flow-cascade',
		'flow-branch',
		'flow-tree',
		'flow-line',
		'flow-parallel',
		'brush',
		'paper-plane',
		'magnet',
		'gauge',
		'traffic-cone',
		'cc',
		'cc-by',
		'cc-nc',
		'cc-nc-eu',
		'cc-nc-jp',
		'cc-sa',
		'cc-nd',
		'cc-pd',
		'cc-zero',
		'cc-share',
		'cc-remix',
		'github',
		'github-circled',
		'flickr',
		'flickr-circled',
		'vimeo',
		'vimeo-circled',
		'twitter',
		'twitter-circled',
		'facebook',
		'facebook-circled',
		'facebook-squared',
		'pinterest',
		'pinterest-circled',
		'tumblr',
		'tumblr-circled',
		'linkedin',
		'linkedin-circled',
		'dribbble',
		'dribbble-circled',
		'stumbleupon',
		'stumbleupon-circled',
		'lastfm',
		'lastfm-circled',
		'rdio',
		'rdio-circled',
		'spotify',
		'spotify-circled',
		'qq',
		'instagram',
		'dropbox',
		'evernote',
		'flattr',
		'skype',
		'skype-circled',
		'renren',
		'sina-weibo',
		'paypal',
		'picasa',
		'soundcloud',
		'mixi',
		'behance',
		'google-circles',
		'vkontakte',
		'smashing',
		'db-shape',
		'sweden',
		'logo-db',
		'picture',
		'globe',
		'leaf',
		'graduation-cap',
		'mic',
		'palette',
		'ticket',
		'video',
		'target',
		'music',
		'trophy',
		'thumbs-up',
		'thumbs-down',
		'bag',
		'user',
		'users',
		'lamp',
		'alert',
		'water',
		'droplet',
		'credit-card',
		'monitor',
		'briefcase',
		'floppy',
		'cd',
		'folder',
		'doc-text',
		'calendar',
		'chart-line',
		'chart-bar',
		'clipboard',
		'attach',
		'bookmarks',
		'book',
		'book-open',
		'phone',
		'megaphone',
		'upload',
		'download',
		'box',
		'newspaper',
		'mobile',
		'signal',
		'camera',
		'shuffle',
		'loop',
		'arrows-ccw',
		'light-down',
		'light-up',
		'mute',
		'sound',
		'battery',
		'search',
		'key',
		'lock',
		'lock-open',
		'bell',
		'bookmark',
		'link',
		'back',
		'flashlight',
		'chart-area',
		'clock',
		'rocket',
		'block'
	);
}

/**
 * Parse font family name
 */
function oxygen_parse_font_family_name() {
	$args         = func_get_args();
	$font_family  = array();
	$native_fonts = array( 'Helvetica', 'Arial', 'sans-serif' );

	foreach ( $args as $font_name ) {
		if ( ! empty( $font_name ) && is_string( $font_name ) ) {
			if ( ! in_array( $font_name, $native_fonts ) ) {
				$font_name = '"' . $font_name . '"';
			}

			$font_family[] = $font_name;
		}
	}

	return implode( ', ', $font_family );
}

/**
 * Load Font Style
 */
function oxygen_load_font_style() {
	global $custom_styles;

	$api_url = 'https://fonts.googleapis.com/css?family=';

	$font_variants = '300italic,400italic,700italic,300,400,700';

	$primary_font   = 'Roboto:400,400italic,500,900,900italic,700italic,700,500italic,300italic,300,100italic,100';
	$secondary_font = 'Roboto+Condensed:300italic,400italic,700italic,300,400,700';

	$google_font_display = apply_filters( 'oxygen_google_font_display', '&display=swap' );

	// Custom Font
	$_font_primary   = get_data( 'font_primary' );
	$_font_secondary = get_data( 'font_secondary' );

	if ( $_font_primary != 'none' && $_font_primary != 'Use default' ) {
		$primary_font_replaced = true;
		$primary_font          = $_font_primary . ':' . $font_variants . $google_font_display;
	}

	if ( $_font_secondary != 'none' && $_font_secondary != 'Use default' ) {
		$secondary_font_replaced = true;
		$secondary_font          = $_font_secondary . ':' . $font_variants . $google_font_display;
	}

	$to_lowercase = get_data( 'font_to_lowercase' ) == 'Default Case' ? true : false;

	$base_font_size = get_data( 'font_size_base' );
	$base_font_size = is_numeric( $base_font_size ) && $base_font_size >= 10 ? $base_font_size : null;

	$custom_primary_font_url  = get_data( 'custom_primary_font_url' );
	$custom_primary_font_name = get_data( 'custom_primary_font_name' );

	$custom_heading_font_url  = get_data( 'custom_heading_font_url' );
	$custom_heading_font_name = get_data( 'custom_heading_font_name' );

	if ( $custom_primary_font_url && $custom_primary_font_name ) {
		$primary_font_replaced = true;
		$primary_font          = $custom_primary_font_url;
		$_font_primary         = $custom_primary_font_name;
	}

	if ( $custom_heading_font_url && $custom_heading_font_name ) {
		$secondary_font_replaced = true;
		$secondary_font          = $custom_heading_font_url;
		$_font_secondary         = $custom_heading_font_name;
	}

	wp_enqueue_style( 'primary-font', strstr( $primary_font, "://" ) ? $primary_font : ( $api_url . $primary_font ) );
	wp_enqueue_style( 'heading-font', strstr( $secondary_font, "://" ) ? $secondary_font : ( $api_url . $secondary_font ) );

	ob_start();

	?>
    <style>
    <?php if ( isset( $primary_font_replaced ) ) : ?>
    .primary-font, body, p, .single-post #comments .comment-body .comment-content {
        font-family: <?php echo oxygen_parse_font_family_name( $_font_primary, 'Helvetica', 'Arial', 'sans-serif' ); ?>;
    }

    <?php endif; ?>

    <?php if ( isset( $secondary_font_replaced ) ) : ?>
    .heading-font, .dropdown, .select-wrapper .select-placeholder, .contact-store .address-content p, .nav, .navbar-blue, .top-first .left-widget, body h1, body h2, body h3, body h4, body h5, body h6, h1, h2, h3, h4, h5, h6, h7, a, label, th, .oswald, .banner .button_outer .button_inner .banner-content strong, nav.pagination .nav-links .page-numbers, footer.footer_widgets .widget_laborator_subscribe #subscribe_now, footer.footer_widgets .widget_search #searchsubmit, footer .footer_main .footer-nav ul li a, footer .footer_main .footer-columns, .header-cart .cart-items .no-items, .header-cart .cart-items .woocommerce-mini-cart__empty-message, .header-cart .cart-items .cart-item .details .price-quantity, .search-results-header .row .search-box input, .sidebar h3, .widget_recent_reviews .product_list_widget li .reviewer, .price_slider_wrapper .price_slider_amount .button, .widget_shopping_cart_content .buttons .button, .blog .blog-post .blog_content h2, .blog .blog-post .blog_content .post-meta .blog_date, .blog .single_post .post_img .loading, .blog .single_post .post_details .author_text, .blog .single_post .post-content h1, .blog .single_post .post-content h2, .blog .single_post .post-content h3, .blog .single_post .post-content h4, .blog .single_post .post-content h5, .blog .single_post .post-content blockquote, .blog .single_post .post-content blockquote p, .blog .single_post .post-content blockquote cite, .single-post #comments h3, .single-post #comments .comment-body--details, .single-post .comment-reply-title, .ribbon .ribbon-content, .btn, .tooltip, .price, .amount, .cart-sub-total, .page-container .wpb_content_element blockquote strong, .page-container .lab_wpb_products_carousel.products-hidden .products-loading .loader strong, .page-container .lab_wpb_blog_posts .blog-posts .blog-post .post .date, .page-container .vc_separator.double-bordered-thick h4, .page-container .vc_separator.double-bordered-thin h4, .page-container .vc_separator.double-bordered h4, .page-container .vc_separator.one-line-border h4, .lab_wpb_banner_2 .title, .lab_wpb_testimonials .testimonials-inner .testimonial-entry .testimonial-blockquote, .woocommerce .woocommerce-products-header .small-title, .woocommerce .woocommerce-result-count, .woocommerce .products .type-product .adding-to-cart .loader strong, .woocommerce .products .type-cross-sells, .woocommerce .shop-categories .product-category .woocommerce-loop-category__title span, .woocommerce.single-product .woocommerce-tabs .tabs > li a, .woocommerce.single-product .woocommerce-tabs .description-tab h1, .woocommerce.single-product .woocommerce-tabs .description-tab h2, .woocommerce.single-product .woocommerce-tabs .description-tab h3, .woocommerce.single-product .woocommerce-tabs .description-tab h4, .woocommerce.single-product .woocommerce-tabs .description-tab h5, .woocommerce.single-product .woocommerce-tabs .description-tab blockquote, .woocommerce.single-product .woocommerce-tabs .description-tab blockquote p, .woocommerce.single-product .woocommerce-tabs .description-tab blockquote cite, .woocommerce.single-product .woocommerce-Reviews .woocommerce-Reviews-title, .woocommerce.single-product .woocommerce-Reviews .commentlist .comment_container .meta, .woocommerce.single-product #review_form .comment-reply-title, .woocommerce .summary .product_meta, .woocommerce .summary .price, .woocommerce .summary .stock, .woocommerce .quantity input.qty, .woocommerce .cart-wrapper .cart-collaterals h2, .woocommerce .cart-wrapper .cart-collaterals .cart-coupon .coupon .button, .woocommerce .cart_totals .shop_table td, .woocommerce .cart_totals .shop_table th, .woocommerce .shop_table.cart .cart_item, .woocommerce .shop_table.woocommerce-checkout-review-order-table td, .woocommerce .shop_table.woocommerce-checkout-review-order-table th, .woocommerce .shop_table.order_details td, .woocommerce .shop_table.order_details th, .woocommerce .shop_table.woocommerce-orders-table tr, .woocommerce .shipping-calculator-container .shipping-calculator-form .button, .woocommerce form.woocommerce-checkout #place_order, .woocommerce .section-title, .woocommerce-order-received .woocommerce-order .woocommerce-order-overview li, .woocommerce-account .account-wrapper .woocommerce-MyAccount-navigation ul, .woocommerce-notice, .lab_wpb_lookbook_carousel .lookbook-carousel .product-item .lookbook-hover-info .lookbook-inner-content .title, .lab_wpb_lookbook_carousel .lookbook-carousel .product-item .lookbook-hover-info .lookbook-inner-content .price-and-add-to-cart .price > .amount, .lab_wpb_lookbook_carousel .lookbook-carousel .product-item .lookbook-hover-info .lookbook-inner-content .price-and-add-to-cart .price ins, .woocommerce .login-form-wrapper h2, .woocommerce form.woocommerce-checkout #order_review_heading, .woocommerce form.woocommerce-checkout .woocommerce-billing-fields h3, .woocommerce form.woocommerce-checkout .woocommerce-shipping-fields h3, .woocommerce form.woocommerce-checkout .checkout-payment-method-title h3, .woocommerce .addresses .woocommerce-column__title, .woocommerce .addresses .title h3, .woocommerce #customer_login.col2-set .woocommerce-column__title, .woocommerce #customer_login.col2-set .title h3, .woocommerce #customer_login.col2-set h2, .woocommerce-order-received .woocommerce-order .bacs-wrapper .wc-bacs-bank-details-heading, .woocommerce-order-received .woocommerce-order .bacs-wrapper .wc-bacs-bank-details-account-name, .woocommerce-order-received .woocommerce-order .woocommerce-order-details .woocommerce-order-details__title, .woocommerce-account .woocommerce-order-details .woocommerce-order-details__title, .woocommerce-account .woocommerce-EditAccountForm fieldset legend, .woocommerce-order-pay #order_review h1, .woocommerce-order-pay #order_review .checkout-payment-method-title h3, .woocommerce-edit-address .woocommerce-MyAccount-content form > h3, .woocommerce-info, .woocommerce-message, .woocommerce-error {
        font-family: <?php echo oxygen_parse_font_family_name( $_font_secondary, 'Helvetica', 'Arial', 'sans-serif' ); ?>;
    }

    <?php endif; ?>

    <?php if ( $to_lowercase ) : ?>
    .to-uppercase, .dropdown .dropdown-toggle, .button-tiny, .select-wrapper .select-placeholder, .widget .widget-title h1, .widget .widget-item .cart_top_detail h4, .main-sidebar ul.nav a, .top-first, .oxygen-top-menu > .wrapper > .top-menu > .main .tl-header .sec-nav .sec-nav-menu > li a, .oxygen-top-menu > .wrapper > .main-menu-top > .main .main-menu-env .nav > li > a, .oxygen-top-menu > .wrapper > .main-menu-top > .main .main-menu-env .nav > li .sub-menu > li > a, .oxygen-top-menu > .wrapper > .top-menu-centered > .main .navs .main-menu-env .nav > li > a, .oxygen-top-menu > .wrapper > .top-menu-centered > .main .navs .main-menu-env .nav > li .sub-menu > li > a, .oxygen-top-menu > .wrapper > .top-menu-centered > .main .navs .sec-nav-menu > li a, .capital, .block-pad h1, .block-pad h2, .block-pad h3, .block-pad h4, .block-pad h5, .block-pad h6, .block-pad h1, .twleve, .banner .button_outer .button_inner .banner-content strong, .btn.btn-mini, .btn-group.open .btn-grey li a, .fluid-dark-button, .alert h2, .alert h3, .alert h4, .alert h5, .alert a.alert-link, label, .form-elements .contact-form-submit .contact-send, .feature-tab.feature-tab-type-1 .title, .feature-tab.feature-tab-type-2 .title, .slider_wrapper h5, ul.pagination li a, ul.page-numbers li a, ul.pagination li span, ul.page-numbers li span, nav.pagination .nav-links .next, nav.pagination .nav-links .prev, footer.footer_widgets .col h1, footer.footer_widgets .tagcloud a, footer.footer_widgets ul, footer.footer_widgets h3, footer.footer_widgets h4, footer.footer_widgets .col h2, footer.footer_widgets .widget_laborator_subscribe #subscribe_now.btn-mini, footer.footer_widgets .widget_search #searchsubmit.btn-mini, footer .footer_main .footer-nav ul li a, footer .footer_main .footer-columns, .header-cart .cart-items .no-items, .header-cart .cart-items .woocommerce-mini-cart__empty-message, .header-cart .cart-items .cart-item .details .title, .header-cart .btn-block, .header-cart .cart-sub-total, .search-results-header .row .results-text, .search-results-header .row .search-box input, body .search-results .search-entry .title, .sidebar h3, .sidebar ul li, .widget_tag_cloud .tagcloud a, .widget_product_tag_cloud .tagcloud a, .price_slider_wrapper .price_slider_amount .button, .price_slider_wrapper .price_slider_amount .button.btn-mini, .product_list_widget li a, .widget_shopping_cart_content .total, .widget_shopping_cart_content .buttons .button, .widget_shopping_cart_content .buttons .button.btn-mini, .widget_rss ul li .rss-date, .widget_calendar #wp-calendar caption, .widget_calendar #wp-calendar #prev, .widget_calendar #wp-calendar #next, .blog .blog-post .blog-img.hover-effect a .hover em, .blog .blog-post .blog_content h2, .blog .blog-post .blog_content h3, .blog .blog-post .blog_content .post-meta .blog_date, .blog .blog-post .blog_content .post-meta .comment_text, .blog .single_post .post_img .loading, .blog .single_post .post_details > h1, .blog .single_post .post_details > h3, .blog .single_post .post_details .author_text, .blog .single_post .post-content h1, .blog .single_post .post-content h2, .blog .single_post .post-content h3, .blog .single_post .post-content h4, .blog .single_post .post-content h5, .share-post h3, .single-post #comments h3, .single-post #comments .comment-body .comment-author, .single-post #comments .comment-body .comment-metadata, .single-post #comments .page-numbers, .single-post .comment-reply-title, .single-post .comment-respond .comment-form .form-submit .submit, .ribbon .ribbon-content, .tooltip, .not-found .center div h2, .not-found .center div a, .toggle-info-blocks, .page-container .wpb_text_column h1, .page-container .wpb_text_column h2, .page-container .wpb_text_column h3, .page-container .wpb_text_column h4, .page-container .wpb_text_column h5, .page-container .wpb_text_column h6, .page-container .wpb_tabs.wpb_content_element .wpb_tour_tabs_wrapper .wpb_tabs_nav li a, .page-container .vc_tta-tabs.vc_tta-style-theme-styled .vc_tta-tabs-list li a, .page-container .wpb_accordion.wpb_content_element .wpb_accordion_wrapper .wpb_accordion_section .wpb_accordion_header, .page-container .vc_tta-accordion.vc_tta-style-theme-styled .vc_tta-panel .vc_tta-panel-heading, .page-container .wpb_content_element blockquote strong, .page-container .lab_wpb_banner .banner-call-button a, .page-container .lab_wpb_blog_posts .blog-posts .blog-post .image a .hover-readmore, .page-container .lab_wpb_blog_posts .blog-posts .blog-post .post h3, .page-container .lab_wpb_blog_posts .blog-posts .blog-post .post .date, .page-container .lab_wpb_blog_posts .more-link .btn, .page-container .vc_separator.double-bordered-thick h4, .page-container .vc_separator.double-bordered-thin h4, .page-container .vc_separator.double-bordered h4, .page-container .vc_separator.one-line-border h4, .lab_wpb_banner_2 .title, .woocommerce .woocommerce-products-header, .woocommerce .woocommerce-result-count, .woocommerce .products .type-product .product-images .quick-view a, .woocommerce .products .type-product .product-description, .woocommerce .products .type-cross-sells .product-details .product-title, .woocommerce .products .type-cross-sells .product-add-to-cart .button, .woocommerce .shop-categories .product-category .woocommerce-loop-category__title span, .woocommerce.single-product .woocommerce-tabs .tabs > li a, .woocommerce.single-product .woocommerce-tabs .tab-title, .woocommerce.single-product .woocommerce-tabs .description-tab h1, .woocommerce.single-product .woocommerce-tabs .description-tab h2, .woocommerce.single-product .woocommerce-tabs .description-tab h3, .woocommerce.single-product .woocommerce-tabs .description-tab h4, .woocommerce.single-product .woocommerce-tabs .description-tab h5, .woocommerce.single-product .woocommerce-Reviews .woocommerce-Reviews-title, .woocommerce.single-product .woocommerce-Reviews .commentlist .comment_container .meta, .woocommerce.single-product #review_form .comment-reply-title, .woocommerce.single-product #review_form .comment-form .submit, .woocommerce .summary .product_title, .woocommerce .summary .product_title + .posted_in, .woocommerce .summary .product_meta, .woocommerce .summary .stock, .woocommerce .summary .variations_form .variations .reset_variations, .woocommerce .up, .woocommerce .cart-wrapper .cart-collaterals h2, .woocommerce .cart-wrapper .cart-collaterals .cart-update-buttons .button, .woocommerce .cart-wrapper .cart-collaterals .cart-coupon .coupon .button, .woocommerce .shop_table.cart thead, .woocommerce .shop_table.cart .product-name > a, .woocommerce .shop_table.woocommerce-checkout-review-order-table thead, .woocommerce .shop_table.order_details thead tr, .woocommerce .shop_table.woocommerce-orders-table tr, .woocommerce .shop_table.wishlist_table tbody tr .product-stock-status span, .woocommerce .shipping-calculator-container .shipping-calculator-form .button, .woocommerce form.woocommerce-checkout #place_order, .woocommerce .addresses .edit, .woocommerce .section-title, .woocommerce-account .account-title, .woocommerce-account .account-wrapper .woocommerce-MyAccount-navigation ul, .woocommerce-account .woocommerce-pagination--without-numbers .button, .product-quickview .view-product, .woocommerce-notice .button, #yith-wcwl-form .wishlist-title h2, .loader strong, .lab_wpb_lookbook_carousel .lookbook-header h2, .lab_wpb_lookbook_carousel .lookbook-carousel .product-item .lookbook-hover-info .lookbook-inner-content .posted_in, .lab_wpb_lookbook_carousel .lookbook-carousel .product-item .lookbook-hover-info .lookbook-inner-content .title, .lab_wpb_lookbook_carousel .lookbook-carousel .product-item .lookbook-hover-info .lookbook-inner-content .price-and-add-to-cart .add-to-cart-btn, .mobile-menu .nav > li > a, .mobile-menu .nav ul > li > a, .mobile-menu .cart-items, .woocommerce form.woocommerce-checkout #order_review_heading, .woocommerce form.woocommerce-checkout .woocommerce-billing-fields h3, .woocommerce form.woocommerce-checkout .woocommerce-shipping-fields h3, .woocommerce form.woocommerce-checkout .checkout-payment-method-title h3, .woocommerce .addresses .woocommerce-column__title, .woocommerce .addresses .title h3, .woocommerce #customer_login.col2-set .woocommerce-column__title, .woocommerce #customer_login.col2-set .title h3, .woocommerce #customer_login.col2-set h2, .woocommerce-order-received .woocommerce-order .bacs-wrapper .wc-bacs-bank-details-heading, .woocommerce-order-received .woocommerce-order .bacs-wrapper .wc-bacs-bank-details-account-name, .woocommerce-order-received .woocommerce-order .woocommerce-order-details .woocommerce-order-details__title, .woocommerce-account .woocommerce-order-details .woocommerce-order-details__title, .woocommerce-account .woocommerce-EditAccountForm fieldset legend, .woocommerce-order-pay #order_review h1, .woocommerce-order-pay #order_review .checkout-payment-method-title h3, .woocommerce-edit-address .woocommerce-MyAccount-content form > h3, .woocommerce-info .button, .woocommerce-message .button, .woocommerce-error .button {
        text-transform: none;
    }

    <?php endif; ?>

    <?php if ( $base_font_size ) : ?>
    .paragraph-font-size, p, .select-wrapper .select-placeholder, .main-sidebar ul.nav .sub-menu li > a, .top-first .breadcrumbs, .top-first .breadcrumbs > span:last-child, .oxygen-top-menu > .wrapper > .top-menu > .main .tl-header .sec-nav .sec-nav-menu > li a, .oxygen-top-menu > .wrapper > .main-menu-top > .main .main-menu-env .nav > li > a, .oxygen-top-menu > .wrapper > .main-menu-top > .main .main-menu-env .nav > li .sub-menu > li > a, .oxygen-top-menu > .wrapper > .main-menu-top > .main .main-menu-env .nav > li.has-sub > a:after, .oxygen-top-menu > .wrapper > .main-menu-top > .main .main-menu-env .nav > li.menu-item-has-children > a:after, .oxygen-top-menu > .wrapper > .top-menu-centered > .main .navs .main-menu-env .nav > li > a, .oxygen-top-menu > .wrapper > .top-menu-centered > .main .navs .main-menu-env .nav > li .sub-menu > li > a, .oxygen-top-menu > .wrapper > .top-menu-centered > .main .navs .main-menu-env .nav > li.has-sub > a:after, .oxygen-top-menu > .wrapper > .top-menu-centered > .main .navs .main-menu-env .nav > li.menu-item-has-children > a:after, .oxygen-top-menu > .wrapper > .top-menu-centered > .main .navs .sec-nav-menu > li a, .accordion .accordion-body, .drop-down .form-dropdown li a, .feature-tab.feature-tab-type-1 .description, .feature-tab.feature-tab-type-2 .description, footer.footer_widgets p, body .search-results .search-entry .title, .widget_recent_comments .recentcomments, .widget_recent_comments .recentcomments a, .widget_recent_comments .recentcomments a.url, .widget_text .textwidget, .widget_shopping_cart_content .total .amount, .widget_rss ul li .rssSummary, .blog .blog-post .blog_content p, .blog .blog-post .blog_content .post-meta, .blog .single_post .post_details, .blog .single_post .post_details > h3, .blog .single_post .post_details .author_about, .page-container .wpb_tabs.wpb_content_element .wpb_tour_tabs_wrapper .wpb_tabs_nav li a, .page-container .vc_tta-tabs.vc_tta-style-theme-styled .vc_tta-tabs-list li a, .page-container .wpb_accordion.wpb_content_element .wpb_accordion_wrapper .wpb_accordion_section .wpb_accordion_header, .page-container .wpb_alert, .page-container .wpb_content_element blockquote, .page-container .lab_wpb_blog_posts .blog-posts .blog-post .post .content p, .woocommerce .summary .stock, .woocommerce .summary .variations_form .variations .label label, .woocommerce .summary .group_table .woocommerce-grouped-product-list-item__price del, .woocommerce .summary .group_table .woocommerce-grouped-product-list-item__price del .amount, .woocommerce .quantity input.qty, .loader strong, .lab_wpb_lookbook_carousel .lookbook-carousel .product-item .lookbook-hover-info .lookbook-inner-content .posted_in, .mobile-menu .cart-items span {
        font-size: <?php echo $base_font_size; ?>px;
    }

    <?php endif; ?>
    </style><?php

	$custom_styles = ob_get_clean();

	add_action( 'wp_print_scripts', 'oxygen_print_custom_styles' );
}

/**
 * Print Custom Styles
 */
function oxygen_print_custom_styles() {
	global $custom_styles;
	echo compress_text( $custom_styles );
}

/**
 * Compile Custom Skin
 */
function oxygen_custom_skin_compile( $vars = array(), $file = 'less/custom-skin.less' ) {
	$result = false;

	include_once get_template_directory() . '/inc/lib/lessc.inc.php';

	$file = get_template_directory() . '/assets/' . $file;

	$file_contents = file_get_contents( $file ) . PHP_EOL;
	$file_contents .= file_get_contents( get_template_directory() . '/assets/less/skin-structure.less' );

	foreach ( $vars as $var => $value ) {
		if ( ! preg_match( "/#[a-f0-9]{3}([a-f0-9]{3})?/i", $value ) ) {
			$value = '#000';
		}

		$file_contents = preg_replace( "/(@{$var})\s*:\s*\{value\}/i", "$1: $value", $file_contents );
	}

	$less = new lessc;
	$css  = $less->compile( $file_contents );

	if ( $fp = fopen( str_replace( array( '/less/', '.less' ), array( '/css/', '.css' ), $file ), 'w' ) ) {
		fwrite( $fp, $css );
		fclose( $fp );

		$result = true;
	}

	return $result;
}

/**
 * Remove Width and Height attribute
 */
function remove_wh( $html ) {
	$html = preg_replace( '/(width|height)=\"\d*\"\s/', "", $html );

	return $html;
}

/**
 * Show Thumbnail
 */
function laborator_show_thumbnail( $post_id, $thumb_size = '' ) {
	return remove_wh( wp_get_attachment_image( get_post_thumbnail_id( $post_id ), $thumb_size ) );
}

/**
 * Translate Post ID
 */
function translate_id( $base_id, $type = 'page' ) {
	global $wpdb, $sitepress;

	if ( ! defined( 'ICL_LANGUAGE_CODE' ) || ! method_exists( $sitepress, 'get_default_language' ) ) {
		return $base_id;
	}

	$default_language = $sitepress->get_default_language();
	$current_language = ICL_LANGUAGE_CODE;

	if ( $current_language != $default_language ) {
		return icl_object_id( $base_id, $type );
	}

	return $base_id;
}

/**
 * Null function
 */
function laborator_null_function() {
	return null;
}

/**
 * Laborator Excerpt Clean
 */
function laborator_clean_excerpt( $content, $strip_tags = false ) {
	$content = preg_replace( '#<style.*?>(.*?)</style>#i', '', $content );
	$content = preg_replace( '#<script.*?>(.*?)</script>#i', '', $content );

	return $strip_tags ? strip_tags( $content ) : $content;
}

/**
 * Enqueue Slick
 */
function oxygen_enqueue_slick_carousel() {
	wp_enqueue_script( 'slick' );
	wp_enqueue_style( 'slick-theme' );
}

/**
 * Image placeholder
 */
function oxygen_get_attachment_image( $attachment_id, $size = 'original', $icon = null, $attr = array(), $placeholder_attr = array() ) {
	$image = $image_el = $image_placeholder = $image_placeholder_style = $image_url = $width = $height = '';

	// Image
	if ( ! $image = wp_get_attachment_image( $attachment_id, $size, $icon, $attr ) ) {
		return '';
	}

	// Style attribute
	$style = array();

	// Image element
	$image_el = array();

	// Parse atts
	$image_atts = wp_parse_args( shortcode_parse_atts( $image ), array(
		'width'  => 1,
		'height' => 1,
	) );

	// Lazy loading
	if ( apply_filters( 'oxygen_get_attachment_image_lazy_load', true ) ) {
		$image_atts['data-src'] = $image_atts['src'];
		$image_atts['class']    .= ' lazyload';
		unset( $image_atts['src'] );
	}

	// Padding bottom
	$aspect_ratio = number_format( $image_atts['height'] / $image_atts['width'] * 100, 6 );
	$style[]      = sprintf( 'padding-bottom:%s%%', $aspect_ratio );

	// Build image elememnt
	foreach ( $image_atts as $key => $value ) {

		if ( is_numeric( $key ) ) {
			unset( $image_atts[ $key ] );
		} else {
			$image_el[] = esc_attr( $key ) . '="' . esc_attr( $value ) . '"';
		}
	}

	// Open and closing tag
	array_unshift( $image_el, '<img' );
	$image_el[] = '/>';

	$image_el = implode( ' ', $image_el );

	// Placeholder element
	$placeholder_atts = array(
		'class' => 'image-placeholder',
		'style' => implode( ';', $style ),
	);

	// Add placeholder attributes
	$placeholder_attr = is_array( $placeholder_attr ) ? $placeholder_attr : array( $placeholder_attr );

	foreach ( $placeholder_attr as $key => $value ) {
		// Placeholder class
		if ( 'class' == $key ) {
			$placeholder_atts['class'] .= ' ' . $value;
		} // Placeholder style
		else if ( 'style' == $key ) {
			$placeholder_atts['style'] .= ';' . $value;
		} // Any other attribute
		else {
			$placeholder_atts[ $key ] = $value;
		}
	}

	// Build placeholder element
	$placeholder_el = array();

	foreach ( $placeholder_atts as $key => $value ) {

		if ( is_numeric( $key ) ) {
			unset( $placeholder_atts[ $key ] );
		} else {
			$placeholder_el[] = esc_attr( $key ) . '="' . esc_attr( $value ) . '"';
		}
	}

	// Open and closing tag
	array_unshift( $placeholder_el, '<span' );
	$placeholder_el[] = '>';

	$placeholder_el = implode( ' ', $placeholder_el );

	return $placeholder_el . $image_el . '</span>';
}

/**
 * Aspect Ratio Calculator
 */
function oxygen_calculate_aspect_ratio( $width, $height ) {
	return number_format( $height / $width * 100, 8 );
}

/**
 * Wrap image with image placeholder element
 */
function oxygen_image_placeholder_wrap_element( $image ) {
	$ratio = '';

	// If its not an image, do not process
	if ( false === strpos( $image, '<img' ) ) {
		return $image;
	}

	// Generate aspect ratio
	if ( preg_match_all( '#(width|height)=(\'|")?(?<dimensions>[0-9]+)(\'|")?#i', $image, $image_dimensions ) ) {
	    // If image height is not present, try to retrieve it from requested file
		if ( 1 == count( $image_dimensions['dimensions'] ) && ( $image_urls = wp_extract_urls( $image ) ) ) {
			$image_url = $image_urls[0];
			$image_file_path = oxygen_get_absolute_path() . ltrim( str_replace( site_url(), '', $image_url ), '/' );

			if ( file_exists( $image_file_path ) && ( $image_size = @getimagesize( $image_file_path ) ) ) {
				$image_dimensions['dimensions'] = array( $image_size[0], $image_size[1] );
			}
		}

	    if ( 2 == count( $image_dimensions['dimensions'] ) ) {
		    $ratio = 'padding-bottom:' . oxygen_calculate_aspect_ratio( $image_dimensions['dimensions'][0], $image_dimensions['dimensions'][1] ) . '%';
	    }
	}

	// Lazy loading
	if ( apply_filters( 'oxygen_get_attachment_image_lazy_load', true ) ) {
		if ( preg_match( '(class=(\'|")[^"\']+)', $image, $class_attr ) ) {
			$image = str_replace( $class_attr[0], $class_attr[0] . ' lazyload', $image );
		} else {
			$image = str_replace( '<img', '<img class="lazyload"', $image );
		}
	}

	return sprintf( '<span class="image-placeholder" style="%2$s">%1$s</span>', $image, $ratio );
}

/**
 * Default Value Set for Visual Composer Loop Parameter Type
 */
function oxygen_vc_loop_param_set_default_value( & $query, $field, $value = '' ) {

	if ( ! preg_match( '/(\|?)' . preg_quote( $field ) . ':/', $query ) ) {
		$query .= "|{$field}:{$value}";
	}

	return ltrim( '|', $query );
}

/**
 * Return single value in WP Hook
 */
function oxygen_hook_return_value( $value ) {
	$returnable = new Oxygen_WP_Hook_Value( $value );

	return array( $returnable, 'returnValue' );
}

/**
 * Merge array value in WP Hook
 */
function oxygen_hook_merge_array_value( $value, $key = '' ) {
	$returnable              = new Oxygen_WP_Hook_Value();
	$returnable->array_value = $value;
	$returnable->array_key   = $key;

	return array( $returnable, 'mergeArrayValue' );
}

/**
 * Call user function in WP Hook
 */
function oxygen_hook_call_user_function( $function_name ) {

	// Function arguments
	$function_args = func_get_args();

	// Remove the function name argument
	array_shift( $function_args );

	$returnable                = new Oxygen_WP_Hook_Value();
	$returnable->function_name = $function_name;
	$returnable->function_args = $function_args;

	return array( $returnable, 'callUserFunction' );
}

/**
 * Check if plugin is active
 */
function oxygen_is_plugin_active( $plugin ) {
	$active_plugins          = apply_filters( 'active_plugins', get_option( 'active_plugins', array() ) );
	$active_sitewide_plugins = apply_filters( 'active_sitewide_plugins', get_site_option( 'active_sitewide_plugins', array() ) );
	$plugins                 = array_merge( $active_plugins, $active_sitewide_plugins );

	return in_array( $plugin, $plugins ) || isset( $plugins[ $plugin ] );
}

/**
 * Get theme assets url
 */
function oxygen_get_theme_assets_uri() {
	return get_template_directory_uri() . '/assets';
}

/**
 * Get field from ACF (with fallback)
 */
function oxygen_get_field( $field_id, $post_id = null, $format_value = true ) {
	global $post;

	if ( function_exists( 'get_field' ) || oxygen_is_plugin_active( 'advanced-custom-fields/acf.php' ) ) {
		return get_field( $field_id, $post_id, $format_value );
	}

	if ( is_numeric( $post_id ) ) {
		$post = get_post( $post_id );
	}

	if ( $post instanceof WP_Post ) {
		return $post->$field_id;
	}

	return null;
}

/**
 * Check if shop is supported
 */
function oxygen_is_shop_supported() {
	return oxygen_is_plugin_active( 'woocommerce/woocommerce.php' ) || class_exists( 'WooCommerce' );
}

/**
 * Convert an english word to number
 */
function oxygen_get_number_from_word( $word ) {

	if ( is_numeric( $word ) ) {
		return $word;
	}

	switch ( $word ) {
		case 'ten'     :
			return 10;
			break;
		case 'nine'  :
			return 9;
			break;
		case 'eight' :
			return 8;
			break;
		case 'seven' :
			return 7;
			break;
		case 'six'     :
			return 6;
			break;
		case 'five'  :
			return 5;
			break;
		case 'four'     :
			return 4;
			break;
		case 'three' :
			return 3;
			break;
		case 'two'     :
			return 2;
			break;
		case 'one'     :
			return 1;
			break;
	}

	return 0;
}

/**
 * Oxygen breakcrumb
 */
if ( ! function_exists( 'oxygen_breadcrumb' ) ) {

	function oxygen_breadcrumb( $return = false ) {
		$breadcrumb = '';

		// NavXT Breadcrumb
		if ( function_exists( 'bcn_display' ) ) {
			$breadcrumb = sprintf( '<div class="breadcrumb">%s</div>', bcn_display( true ) );
		} // WooCommerce Breadcrumb
		else if ( function_exists( 'woocommerce_breadcrumb' ) ) {
			ob_start();
			$args = array(
				'delimiter' => '<span class="sep">&raquo;</span>'
			);

			woocommerce_breadcrumb( $args );
			$breadcrumb = ob_get_clean();
		} // Built-in breadcrumb function
		else {
			$breadcrumb = dimox_breadcrumbs( false );
		}

		if ( $return ) {
			return $breadcrumb;
		}

		echo $breadcrumb;
	}
}

/**
 * Show classes attribute array
 */
if ( ! function_exists( 'oxygen_class_attr' ) ) {

	function oxygen_class_attr( $classes, $echo = true ) {

		if ( ! is_array( $classes ) ) {
			$classes = array( $classes );
		}

		$class = sprintf( 'class="%s"', implode( ' ', array_map( 'esc_attr', $classes ) ) );

		if ( $echo ) {
			echo $class;

			return '';
		}

		return $class;
	}
}

/**
 * Blog comment callback
 */
if ( ! function_exists( 'oxygen_blog_comment_callback' ) ) {

	function oxygen_blog_comment_callback( $comment, $args, $depth ) {

		$avatar = get_avatar( $comment, $args['avatar_size'] );

		?>
        <div <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">

        <div class="comment-body">

            <div class="comment-body--avatar">
				<?php echo oxygen_image_placeholder_wrap_element( $avatar ); ?>
            </div>

            <div class="comment-body--details">

                <div class="comment-author vcard">
					<?php
					/* translators: %s: comment author link */
					printf( __( '%s <span class="says">says:</span>' ),
						sprintf( '<b class="fn">%s</b>', get_comment_author_link( $comment ) )
					);
					?>
                </div>

                <div class="comment-metadata">
                    <a href="<?php echo esc_url( get_comment_link( $comment, $args ) ); ?>">
                        <time datetime="<?php comment_time( 'c' ); ?>">
							<?php
							/* translators: 1: comment date, 2: comment time */
							printf( __( '%1$s at %2$s' ), get_comment_date( '', $comment ), get_comment_time() );
							?>
                        </time>
                    </a>

					<?php
					comment_reply_link( array_merge( $args, array(
						'add_below' => 'comment',
						'depth'     => $depth,
						'max_depth' => $args['max_depth'],
						'before'    => '<div class="reply">',
						'after'     => '</div>'
					) ) );
					?>

					<?php edit_comment_link( __( 'Edit' ), '<span class="edit-link">', '</span>' ); ?>
                </div>

				<?php if ( '0' == $comment->comment_approved ) : ?>
                    <div class="clear"></div>
                    <p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'oxygen' ); ?></p>
				<?php endif; ?>

                <div class="comment-content">
					<?php comment_text(); ?>
                </div>

            </div>
        </div>

		<?php
	}
}

/**
 * Blog comment end-callback
 */
if ( ! function_exists( 'oxygen_blog_comment_end_callback' ) ) {

	function oxygen_blog_comment_end_callback( $comment, $args, $depth ) {
		?>
        </div>
		<?php
	}
}

/**
 * Get absolute path of WordPress installation.
 */
function oxygen_get_absolute_path() {
    return apply_filters( 'oxygens_abs_path', ABSPATH );
}

/**
 * Get relative path of a file from URL that points to wp-content/ directory
 */
function oxygen_get_relative_path( $url ) {
	$wp_content_dirname = wp_basename( WP_CONTENT_DIR );

	if ( preg_match( '/\/' . preg_quote( $wp_content_dirname ) . '\/.*/', $url, $matches ) ) {
		return '/' . ltrim( $matches[0], '/' );
	}

	return '';
}

/**
 * Resize an image based on width and return the height
 */
function oxygen_resize_width_get_height( $width, $height, $new_width ) {
	$ratio = $new_width / $width;

	return absint( round( $height * $ratio ) );
}

/**
 * Get logo element
 */
if ( ! function_exists( 'oxygen_get_logo_element' ) ) {

	function oxygen_get_logo_element( $url, $width = 0, $height = 0, $classes = '', $force_min_width = false ) {
		$logo        = '';
		$classes_arr = array( 'logo-element' );

		// Image logo
		if ( $width && $height ) {
			$classes      = explode( ' ', $classes );
			$classes_arr  = array_merge( $classes_arr, $classes );
			$aspect_ratio = 100 * ( $height / $width );

			$force_min_width = $force_min_width ? "min-width:{$width}px;" : '';

			$image = sprintf( '<span style="padding-bottom:%d%%"><img src="%s" width="%d" height="%d" alt="%s" /></span>', $aspect_ratio, $url, $width, $height, '' );
			$logo  = sprintf( '<span %1$s style="width:%2$dpx;max-width:%2$dpx;%4$s">%3$s</span>', oxygen_class_attr( $classes_arr, false ), $width, $image, $force_min_width );
		} // Textual logo
		else {
			$classes     = explode( ' ', is_string( $width ) ? $width : $classes );
			$classes_arr = array_merge( $classes_arr, $classes );

			$logo = sprintf( '<span %1$s>%2$s</span>', oxygen_class_attr( $classes_arr, false ), $url );
		}

		return $logo;
	}
}

/**
 * Get logo
 */
if ( ! function_exists( 'oxygen_get_logo' ) ) {

	function oxygen_get_logo( $force_min_width = false ) {
		$logo = '';

		// Use uploaded logo
		if ( get_data( 'use_uploaded_logo' ) ) {
			$logo_image           = get_data( 'custom_logo_image' );
			$logo_image_max_width = get_data( 'custom_logo_max_width' );

			$logo_image_mobile           = get_data( 'custom_logo_image_responsive' );
			$logo_image_mobile_max_width = get_data( 'custom_logo_image_responsive_width' );

			// Logo image
			if ( $logo_image_relative = oxygen_get_relative_path( $logo_image ) ) {
				$logo_image_path = oxygen_get_absolute_path() . $logo_image_relative;
				$logo_image_url  = site_url( $logo_image_relative );

				// Check if file exists
				if ( file_exists( oxygen_get_absolute_path() . $logo_image_relative ) ) {
					$logo_image_size   = apply_filters( 'oxygen_logo_image_size', @getimagesize( $logo_image_path ), $logo_image_path );
					$logo_image_width  = $logo_image_size[0];
					$logo_image_height = $logo_image_size[1];

					// When logo is uploaded in a max size but no max width is specified
					if ( $logo_image_width > 700 && ! $logo_image_max_width ) {
						$logo_image_max_width = 200;
					}

					if ( is_numeric( $logo_image_max_width ) && $logo_image_max_width > 0 ) {
						$logo_image_width  = $logo_image_max_width;
						$logo_image_height = oxygen_resize_width_get_height( $logo_image_size[0], $logo_image_size[1], $logo_image_max_width );
					}

					$logo .= oxygen_get_logo_element( $logo_image_url, $logo_image_width, $logo_image_height, 'general', $force_min_width );
					$general_logo_set = true;
				}
			}

			// Mobile logo image
			if ( $logo_image_mobile_relative = oxygen_get_relative_path( $logo_image_mobile ) ) {
				$logo_image_mobile_path = oxygen_get_absolute_path() . $logo_image_mobile_relative;
				$logo_image_mobile_url  = site_url( $logo_image_mobile_relative );

				// Check if file exists
				if ( file_exists( oxygen_get_absolute_path() . $logo_image_mobile_relative ) ) {
					$logo_image_mobile_size   = apply_filters( 'oxygen_logo_image_size_mobile', @getimagesize( $logo_image_mobile_path ), $logo_image_mobile_path );
					$logo_image_mobile_width  = $logo_image_mobile_size[0];
					$logo_image_mobile_height = $logo_image_mobile_size[1];

					// When logo is uploaded in a max size but no max width is specified
					if ( $logo_image_mobile_width > 700 && ! $logo_image_mobile_max_width ) {
						$logo_image_mobile_max_width = 200;
					}

					if ( is_numeric( $logo_image_mobile_max_width ) && $logo_image_mobile_max_width > 0 ) {
						$logo_image_mobile_width  = $logo_image_mobile_max_width;
						$logo_image_mobile_height = oxygen_resize_width_get_height( $logo_image_mobile_size[0], $logo_image_mobile_size[1], $logo_image_mobile_max_width );
					}

					// If general logo is not set then show textual logo
					if ( ! isset( $general_logo_set ) ) {
						$logo .= oxygen_get_logo_element( get_data( 'logo_text' ), 'textual general' );
                    }

					$logo            .= oxygen_get_logo_element( $logo_image_mobile_url, $logo_image_mobile_width, $logo_image_mobile_height, 'mobile', $force_min_width );
					$logo            .= '<style>@media screen and (max-width: 768px){ .logo-element.general { display: none; } .logo-element.mobile { display: inline-block; } }</style>';
					$mobile_logo_set = true;
				}
			}

			// Max width on mobile
			if ( ! isset( $mobile_logo_set ) && is_numeric( $logo_image_mobile_max_width ) && $logo_image_mobile_max_width > 0 ) {
				$logo = sprintf( '<style>@media screen and (max-width: 768px) { .logo-element.general { max-width: %dpx !important; } }</style>', $logo_image_mobile_max_width ) . $logo;
			}
		}

		// Textual logo
		if ( ! $logo ) {
			$logo .= oxygen_get_logo_element( get_data( 'logo_text' ), 'textual' );
		}

		return apply_filters( 'oxygen_get_logo', sprintf( '<span class="brand-logo">%s</span>', $logo ), $force_min_width );
	}
}

/**
 * Get revolution slider alias for current page
 */
function oxygen_get_page_revslider_id() {
	$revslider_id = oxygen_get_field( 'revslider_id' );
	
	if ( class_exists( 'RevSlider' ) ) {
		$slider = new RevSlider();
		$sliders = $slider->getArrSliders();
		$slider_exists = false;
		
		foreach ( $sliders as $slider ) {
			if ( $revslider_id == $slider->getAlias() ) {
				$slider_exists = true;
				break;
			}
		}
		
		if ( ! $slider_exists ) {
			foreach ( $sliders as $slider ) {
				if ( ! empty( $revslider_id ) && strpos( $slider->getTitle(), $revslider_id ) !== false ) {
					$revslider_id = $slider->getAlias();
					break;
				}
			}
		}
	}
	
	return $revslider_id;
}
