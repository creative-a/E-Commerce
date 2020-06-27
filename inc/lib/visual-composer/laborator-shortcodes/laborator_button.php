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

class WPBakeryShortCode_Laborator_Button extends WPBakeryShortCode {

	/**
	 * Shortcode content
	 */
	public function content( $atts, $content = null ) {

		global $lab_button_ids;

		// Atts
		$atts = vc_map_get_attributes( $this->getShortcode(), $atts );

		extract( $atts );


		if ( ! isset( $lab_button_ids ) || ! $lab_button_ids ) {
			$lab_button_ids = 0;
		}

		$lab_button_ids ++;
		$btn_index = "btn-index-{$lab_button_ids}";

		// Link
		$link = vc_build_link( $link );

		// Element Class
		$class     = $this->getExtraClass( $el_class );
		$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class, $this->settings[ 'base' ], $atts );

		$css_class = "laborator-btn btn {$btn_index} btn-type-{$type} {$css_class}";

		if ( $type == 'outlined-bg' || $type == 'fliphover' ) {
			$css_class .= ' btn-type-outlined';
		}

		if ( $button_bg != 'custom' ) {
			$css_class .= " {$button_bg}";
		}

		$css_class .= " {$size}";

		# Custom Button Color
		$button_css_normal = $button_css_hover = '';

		if ( $button_bg == 'custom' ) {
			switch ( $type ) {
				case "outlined":

					if ( $button_bg_custom ) {
						$button_css_normal .= 'border-color:' . $button_bg_custom . ';';

						if ( ! $button_txt_custom ) {
							$button_css_normal .= 'color:' . $button_bg_custom . ';';
						}
					}

					if ( $button_txt_custom ) {
						$button_css_normal .= 'color:' . $button_txt_custom . ';';
					}

					if ( $button_bg_hover_custom ) {
						$button_css_hover .= 'border-color:' . $button_bg_hover_custom . ';';
					}

					if ( $button_txt_hover_custom ) {
						$button_css_hover .= 'color:' . $button_txt_hover_custom . ';';
					}
					break;

				default:

					if ( $button_bg_custom ) {
						$button_css_normal .= 'background-color:' . $button_bg_custom . ';';
					}

					if ( $button_txt_custom ) {
						$button_css_normal .= 'color:' . $button_txt_custom . ';';
					}

					if ( $button_bg_hover_custom ) {
						$button_css_hover .= 'background-color:' . $button_bg_hover_custom . ';';
					}

					if ( $button_txt_hover_custom ) {
						$button_css_hover .= 'color:' . $button_txt_hover_custom . ';';
					}
			}
		}

		ob_start();
		?>
        <a id="<?php echo $btn_index; ?>" href="<?php echo esc_url( $link[ 'url' ] ); ?>"
           title="<?php echo esc_attr( $link[ 'title' ] ); ?>" target="<?php echo esc_attr( trim( $link[ 'target' ] ) ); ?>"
           class="<?php echo esc_attr( $css_class ) . vc_shortcode_custom_css_class( $css, ' ' ); ?>"><?php echo esc_html( $title ); ?></a>
		<?php

		if ( $button_css_normal || $button_css_hover ):

			?>
            <style>
                <?php if ( $button_css_normal ) : ?>
                #<?php echo $btn_index; ?>.btn {
                <?php echo $button_css_normal; ?>
                }

                <?php endif; ?>

                <?php if($button_css_hover): ?>
                #<?php echo $btn_index; ?>.btn:hover {
                <?php echo $button_css_hover; ?>
                }

                <?php endif; ?>
            </style>
		<?php

		endif;

		$btn = ob_get_clean();

		return $btn;

	}
}


# Element Information
$lab_vc_element_path = dirname( __FILE__ ) . '/';
$lab_vc_element_url  = site_url( str_replace( ABSPATH, '', $lab_vc_element_path ) );
$lab_vc_element_icon = $lab_vc_element_url . '../assets/images/laborator.png';

$colors_arr = array(
	'Primary'  => 'btn-primary',
	'Black'    => 'btn-black',
	'Blue'     => 'btn-blue',
	'Dark Red' => 'btn-dark-red',
	'Green'    => 'btn-green',
	'Yellow'   => 'btn-warning',
	'White'    => 'btn-white',
	'Gray'     => 'btn-gray',
);

vc_map( array(
	'base'        => 'laborator_button',
	'name'        => 'Button',
	"description" => "Insert button link",
	'category'    => 'Laborator',
	'icon'        => $lab_vc_element_icon,
	'params'      => array(
		array(
			'type'        => 'textfield',
			'heading'     => 'Button Title',
			'param_name'  => 'title',
			'admin_label' => true,
			'value'       => ''
		),
		array(
			'type'       => 'vc_link',
			'heading'    => 'Button Link',
			'param_name' => 'link',
		),
		array(
			'type'        => 'dropdown',
			'heading'     => 'Button Type',
			'param_name'  => 'type',
			'std'         => 'default',
			'admin_label' => true,
			'value'       => array(
				'Standard' => 'standard',
				'Outlined' => 'outlined',
			),
			'description' => 'Select between two button types.'
		),
		array(
			'type'        => 'dropdown',
			'heading'     => 'Button Size',
			'param_name'  => 'size',
			'std'         => 'btn-md',
			'value'       => array(
				'Small'  => 'btn-sm',
				'Medium' => 'btn-md',
				'Large'  => 'btn-lg',
			),
			'description' => 'Select button size: S, M, L.'
		),
		array(
			'type'        => 'dropdown',
			'heading'     => 'Background Color',
			'param_name'  => 'button_bg',
			'admin_label' => true,
			'value'       => array_merge( $colors_arr, array( 'Custom color' => 'custom' ) ),
			'std'         => 'btn-primary',
			'description' => 'Select button background (and/or border) color.'
		),
		array(
			'type'        => 'colorpicker',
			'heading'     => 'Custom Background Color',
			'param_name'  => 'button_bg_custom',
			'description' => 'Custom background color for button.',
			'dependency'  => array(
				'element' => 'button_bg',
				'value'   => array( 'custom' )
			),
		),
		array(
			'type'        => 'colorpicker',
			'heading'     => 'Custom Text Color',
			'param_name'  => 'button_txt_custom',
			'description' => 'Custom text color for button.',
			'dependency'  => array(
				'element' => 'button_bg',
				'value'   => array( 'custom' )
			),
		),
		array(
			'type'        => 'colorpicker',
			'heading'     => 'Custom Background Hover Color',
			'param_name'  => 'button_bg_hover_custom',
			'description' => 'Custom background/border hover color for button (where applied).',
			'dependency'  => array(
				'element' => 'button_bg',
				'value'   => array( 'custom' )
			),
		),
		array(
			'type'        => 'colorpicker',
			'heading'     => 'Custom Text Hover Color',
			'param_name'  => 'button_txt_hover_custom',
			'description' => 'Custom text hover color for button (where applied).',
			'dependency'  => array(
				'element' => 'button_bg',
				'value'   => array( 'custom' )
			),
		),
		array(
			'type'        => 'textfield',
			'heading'     => 'Extra class name',
			'param_name'  => 'el_class',
			'description' => 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.'
		),
		array(
			'type'       => 'css_editor',
			'heading'    => 'Css',
			'param_name' => 'css',
			'group'      => 'Design options'
		)
	)
) );