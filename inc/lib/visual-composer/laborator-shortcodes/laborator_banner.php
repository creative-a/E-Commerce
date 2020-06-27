<?php
/**
 *    Text Banner Shortcode for Visual Composer
 *
 *    Laborator.co
 *    www.laborator.co
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

class WPBakeryShortCode_laborator_banner extends WPBakeryShortCode {

	/**
	 * Content
	 */
	public function content( $atts, $content = null ) {

		// Atts
		$atts = vc_map_get_attributes( $this->getShortcode(), $atts );

		extract( shortcode_atts( array(
			'title'       => '',
			'description' => '',
			'href'        => '',
			'color'       => '',
			'type'        => '',
			'el_class'    => '',
			'css'         => '',
		), $atts ) );

		$link     = vc_build_link( $href );
		$a_href   = $link['url'];
		$a_title  = $link['title'];
		$a_target = trim( $link['target'] );

		switch ( $color ) {
			case 'black':
				$el_class .= ' banner-black';
				break;

			case 'red':
				$el_class .= ' banner-default';
				break;

			case 'dark-red':
				$el_class .= ' banner-dark-red';
				break;

			default:
				$el_class .= ' banner-white';
		}

		if ( $type == 'button-left-text-right' ) {
			$el_class .= ' button-right';
		} else if ( $type == 'text-button-center' ) {
			$el_class .= ' text-button-center';
		}

		$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'lab_wpb_banner wpb_content_element banner ' . $el_class . vc_shortcode_custom_css_class( $css, ' ' ), $this->settings['base'] );

		ob_start();

		?>
        <div class="<?php echo $css_class; ?>">
            <div class="button_outer">
                <div class="button_middle">
                    <div class="button_inner">

						<?php if ( $type == 'button-left-text-right' ): ?>
							<?php if ( $a_title ): ?>
                                <div class="banner-call-button">
                                    <a href="<?php echo $a_href; ?>" class="btn"
                                       target="<?php echo $a_target; ?>"><?php echo $a_title; ?></a>
                                </div>
							<?php endif; ?>
						<?php endif; ?>

                        <div class="banner-content">
                            <strong><?php echo $title; ?></strong>

							<?php if ( $description ): ?>
                                <span><?php echo $description; ?></span>
							<?php endif; ?>
                        </div>

						<?php if ( ! in_array( $type, array( 'button-left-text-right' ) ) ): ?>
							<?php if ( $a_title ): ?>
                                <div class="banner-call-button">
                                    <a href="<?php echo $a_href; ?>" class="btn"
                                       target="<?php echo $a_target; ?>"><?php echo $a_title; ?></a>
                                </div>
							<?php endif; ?>
						<?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
		<?php

		$output = ob_get_contents();
		ob_end_clean();

		return $output;
	}
}

// Shortcode Options
$opts = array(
	"name"        => "Text Banner",
	"description" => 'Include a Call to Action banner.',
	"base"        => "laborator_banner",
	"class"       => "vc_laborator_banner",
	"icon"        => "icon-lab-banner",
	"controls"    => "full",
	"category"    => 'Laborator',
	"params"      => array(

		array(
			"type"        => "textfield",
			"heading"     => "Widget title",
			"param_name"  => "title",
			"value"       => "",
			"description" => "What text use as widget title. Leave blank if no title is needed."
		),

		array(
			"type"        => "textfield",
			'admin_label' => true,
			"heading"     => "Text",
			"param_name"  => "description",
			"value"       => "Free shipping over $125 for international orders",
			"description" => "Banner content."
		),

		array(
			"type"        => "vc_link",
			"heading"     => "URL (Link)",
			"param_name"  => "href",
			"description" => "Button link."
		),

		array(
			"type"        => "dropdown",
			"heading"     => "Banner Color",
			"param_name"  => "color",
			"value"       => array(
				"White"    => 'white',
				"Black"    => 'black',
				"Red"      => 'red',
				"Dark Red" => 'dark-red',
			),
			"description" => "Select the type of banner."
		),

		array(
			"type"        => "dropdown",
			"heading"     => "Banner Type",
			"param_name"  => "type",
			"value"       => array(
				"Text (left) + Button (right)" => 'text-left-button-right',
				"Button (left) + Text (right)" => 'button-left-text-right',
				"Text + Button (Center)"       => 'text-button-center',
			),
			"description" => "Select the type of banner."
		),

		array(
			"type"        => "textfield",
			"heading"     => "Extra class name",
			"param_name"  => "el_class",
			"value"       => "",
			"description" => "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file."
		),

		array(
			"type"       => "css_editor",
			"heading"    => 'Css',
			"param_name" => "css",
			"group"      => 'Design options'
		)
	)
);

// Add & init the shortcode
if ( function_exists( 'vc_map' ) ) {
	vc_map( $opts );
} else {
	wpb_map( $opts );
}
#new Laborator_VC_Banner($opts);