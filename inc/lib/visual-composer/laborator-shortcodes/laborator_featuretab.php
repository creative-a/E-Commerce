<?php
/**
 *    Feature Tab for Visual Composer
 *
 *    Laborator.co
 *    www.laborator.co
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

class WPBakeryShortCode_laborator_featuretab extends WPBakeryShortCode {

	/**
	 * Shortcode content
	 */
	public function content( $atts, $content = null ) {

		// Atts
		$atts = vc_map_get_attributes( $this->getShortcode(), $atts );

		extract( shortcode_atts( array(
			'title'       => '',
			'type'        => '',
			'description' => '',
			'icon'        => '',
			'href'        => '',
			'el_class'    => '',
			'css'         => '',
		), $atts ) );

		$link     = vc_build_link( $href );
		$a_href   = $link['url'];
		$a_title  = $link['title'];
		$a_target = trim( $link['target'] );

		$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'lab_wpb_featuretab wpb_content_element ' . $el_class . vc_shortcode_custom_css_class( $css, ' ' ), $this->settings['base'] );

		ob_start();

		?>
        <a<?php if ( $a_href && $a_href != '#' ): ?> href="<?php echo $a_href; ?>"<?php endif; ?>
                class="feature-tab <?php echo $type == 'icon-left' ? 'feature-tab-type-2' : 'feature-tab-type-1'; ?>"
                target="<?php echo $a_target; ?>">
			<span class="icon">
				<span class="icon-inner">
					<i class="entypo-<?php echo $icon; ?>"></i>
				</span>
			</span>

            <span class="title"><?php echo $title; ?></span>

            <span class="description">
				<?php echo nl2br( $description ); ?>
			</span>
        </a>
		<?php

		$output = ob_get_contents();
		ob_end_clean();

		return $output;
	}
}

// Shortcode Options
$opts = array(
	"name"        => "Feature Tab",
	"description" => 'Good way to list your features.',
	"base"        => "laborator_featuretab",
	"class"       => "vc_laborator_featuretab",
	"icon"        => "icon-lab-featuretab",
	"controls"    => "full",
	"category"    => 'Laborator',
	"params"      => array(

		array(
			"type"        => "textfield",
			"heading"     => "Widget title",
			"param_name"  => "title",
			"value"       => "My superawesome service",
			"description" => "What text use as widget title. Leave blank if no title is needed."
		),

		array(
			"type"        => "dropdown",
			"heading"     => "Block Type",
			"param_name"  => "type",
			"value"       => array(
				"Icon Centered" => 'icon-center',
				"Icon on Left"  => 'icon-left'
			),
			"description" => "Select the type of featured tab box."
		),

		array(
			"type"        => "textarea",
			'admin_label' => true,
			"heading"     => "Text",
			"param_name"  => "description",
			"value"       => "Your brilliant description about this feautre.",
			"description" => "Feature small description."
		),

		array(
			"type"        => "fontelloicon",
			"heading"     => "Icon",
			"param_name"  => "icon",
			"value"       => "heart",
			"description" => "Tab icon to display."
		),

		array(
			"type"        => "vc_link",
			"heading"     => "URL (Link)",
			"param_name"  => "href",
			"description" => "Tab link."
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
#new Laborator_VC_FeatureTab($opts);