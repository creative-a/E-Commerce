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

// New
if ( function_exists( 'acf_add_local_field_group' ) ):

	acf_add_local_field_group( array(
		'key'                   => 'group_5bab44d065340',
		'title'                 => 'Other Post Settings',
		'fields'                => array(
			array(
				'key'               => 'field_5af414bf869e0',
				'label'             => 'Post Slider Images',
				'name'              => 'post_slider_images',
				'type'              => 'gallery',
				'instructions'      => 'Create images gallery slider (featured image will not be included)',
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => array(
					'width' => '',
					'class' => '',
					'id'    => '',
				),
				'preview_size'      => 'thumbnail',
				'library'           => 'all',
				'min'               => 0,
				'max'               => 0,
				'min_width'         => 0,
				'min_height'        => 0,
				'min_size'          => 0,
				'max_width'         => 0,
				'max_height'        => 0,
				'max_size'          => 0,
				'mime_types'        => '',
				'insert'            => 'append',
			),
		),
		'location'              => array(
			array(
				array(
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => 'post',
				),
			),
		),
		'menu_order'            => 0,
		'position'              => 'normal',
		'style'                 => 'default',
		'label_placement'       => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen'        => array(),
		'active'                => 1,
		'description'           => '',
	) );

endif;

// Old
if ( function_exists( "register_field_group" ) ) {
	register_field_group( array(
		'id'         => 'acf_contact',
		'title'      => 'Contact',
		'fields'     => array(
			array(
				'key'   => 'field_534e2594a8849',
				'label' => 'Address',
				'name'  => '',
				'type'  => 'tab',
			),
			array(
				'key'           => 'field_535517327a491',
				'label'         => 'Route Finder',
				'name'          => 'enable_route_finder',
				'type'          => 'select',
				'instructions'  => 'This will allow users to get Map directions to your store based on their location or the address they enter. <br>Note: Route locator works only if you have appropriate API key that has Google Directions API activated (which is <a href="https://developers.google.com/maps/documentation/directions/usage-and-billing" target="_blank">not free</a>).',
				'choices'       => array(
					'both'     => 'Allow (Address + Location)',
					'address'  => 'Allow (Browse by address only)',
					'location' => 'Allow (Browse by location only)',
					'hide'     => 'Disable route finder',
				),
				'default_value' => 'both',
				'allow_null'    => 0,
				'multiple'      => 0,
			),
			array(
				'key'           => 'field_534e2f54a2f08',
				'label'         => 'Address TItle',
				'name'          => 'address_title',
				'type'          => 'text',
				'default_value' => 'Our Address',
				'placeholder'   => '',
				'prepend'       => '',
				'append'        => '',
				'formatting'    => 'html',
				'maxlength'     => '',
			),
			array(
				'key'           => 'field_534e2f91a2f09',
				'label'         => 'Address',
				'name'          => 'address',
				'type'          => 'wysiwyg',
				'default_value' => '',
				'toolbar'       => 'full',
				'media_upload'  => 'yes',
			),
			array(
				'key'   => 'field_534e300fa2f0a',
				'label' => 'Map Location',
				'name'  => '',
				'type'  => 'tab',
			),
			array(
				'key'          => 'field_534d8bdf5030e',
				'label'        => 'Map Location',
				'instructions' => '<div class="wpb_element_wrapper">
	<div class="wpb_element_wrapper vc_message_box vc_message_box-square vc_message_box-solid vc_color-info" style="padding: 0.5em;">
		<small>Google maps requires unique API key for each site, click here to learn more about generating <a href="https://developers.google.com/maps/documentation/javascript/get-api-key" style="color: #fff !important;" target="_blank">Google API Key</a>.<br>
		API key should be set in Theme Options > Other Settings</small>
	</div>
</div>',
				'name'         => 'map_location',
				'type'         => 'google_map',
				'center_lat'   => '',
				'center_lng'   => '',
				'zoom'         => '',
				'height'       => '',
			),
			array(
				'key'           => 'field_5395eefaf3107',
				'label'         => 'Location Label',
				'name'          => 'location_label',
				'type'          => 'text',
				'instructions'  => 'How do you label this primary location.',
				'default_value' => '',
				'placeholder'   => '',
				'prepend'       => '',
				'append'        => '',
				'formatting'    => 'html',
				'maxlength'     => '',
			),
			array(
				'key'          => 'field_5395e0f44f768',
				'label'        => 'More Locations',
				'name'         => 'more_locations',
				'type'         => 'repeater',
				'instructions' => 'You can add more locations of your stores/contact points (optional)',
				'sub_fields'   => array(
					array(
						'key'          => 'field_5395e11d4f769',
						'label'        => 'Map Location',
						'name'         => 'map_location',
						'type'         => 'google_map',
						'column_width' => '',
						'center_lat'   => '',
						'center_lng'   => '',
						'zoom'         => '',
						'height'       => 250,
					),
					array(
						'key'           => 'field_5395ed9003621',
						'label'         => 'Label',
						'name'          => 'label',
						'type'          => 'text',
						'instructions'  => 'How do you name this location/point',
						'column_width'  => '',
						'default_value' => '',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'html',
						'maxlength'     => '',
					),
				),
				'row_min'      => '',
				'row_limit'    => '',
				'layout'       => 'row',
				'button_label' => 'Add Location to Map',
			),
			array(
				'key'           => 'field_534e9bc47082b',
				'label'         => 'Map Type',
				'name'          => 'map_type',
				'type'          => 'select',
				'instructions'  => 'Select map type you want to display in this page.',
				'choices'       => array(
					'roadmap'   => 'Roadmap',
					'satellite' => 'Satellite',
					'hybrid'    => 'Hybrid',
					'street'    => 'Street View',
				),
				'default_value' => 'satellite',
				'allow_null'    => 0,
				'multiple'      => 0,
			),
			array(
				'key'           => 'field_534e9fda06901',
				'label'         => 'Enable Map Type Switcher',
				'name'          => 'map_enable_type_switcher',
				'type'          => 'true_false',
				'message'       => 'Enable',
				'default_value' => 1,
			),
			array(
				'key'               => 'field_534eafa175f99',
				'label'             => 'Allowed Map Types',
				'name'              => 'map_allowed_map_types',
				'type'              => 'checkbox',
				'instructions'      => 'Select allowed map types for map type switcher.',
				'conditional_logic' => array(
					'status'   => 1,
					'rules'    => array(
						array(
							'field'    => 'field_534e9fda06901',
							'operator' => '==',
							'value'    => '1',
						),
					),
					'allorany' => 'all',
				),
				'choices'           => array(
					'roadmap'   => 'Roadmap',
					'satellite' => 'Satellite',
					'street'    => 'Street View',
				),
				'default_value'     => 'roadmap
	satellite
	street',
				'layout'            => 'vertical',
			),
			array(
				'key'               => 'field_534eae0ee40c7',
				'label'             => 'Street View - Heading',
				'name'              => 'street_view_heading',
				'type'              => 'number',
				'instructions'      => '<strong>Street View Map Option</strong> - Defines the rotation angle around the camera locus in degrees relative from true north. Headings are measured clockwise (90 degrees is true east)',
				'conditional_logic' => array(
					'status'   => 1,
					'rules'    => array(
						array(
							'field'    => 'field_534e9fda06901',
							'operator' => '==',
							'value'    => '1',
						),
					),
					'allorany' => 'all',
				),
				'default_value'     => 0,
				'placeholder'       => '',
				'prepend'           => '',
				'append'            => '',
				'min'               => '',
				'max'               => '',
				'step'              => '',
			),
			array(
				'key'               => 'field_534eae6de40c8',
				'label'             => 'Street View - Pitch',
				'name'              => 'street_view_pitch',
				'type'              => 'number',
				'instructions'      => '<strong>Street View Map Option</strong> - Defines the angle variance "up" or "down" from the camera\'s initial default pitch, which is often (but not always) flat horizontal.',
				'conditional_logic' => array(
					'status'   => 1,
					'rules'    => array(
						array(
							'field'    => 'field_534e9fda06901',
							'operator' => '==',
							'value'    => '1',
						),
					),
					'allorany' => 'all',
				),
				'default_value'     => 0,
				'placeholder'       => '',
				'prepend'           => '',
				'append'            => '',
				'min'               => '',
				'max'               => '',
				'step'              => '',
			),
			array(
				'key'           => 'field_534e2790b488a',
				'label'         => 'Map Zoom Level',
				'name'          => 'map_zoom_level',
				'type'          => 'number',
				'instructions'  => 'Scale from 1-20',
				'default_value' => 14,
				'placeholder'   => '',
				'prepend'       => '',
				'append'        => '',
				'min'           => 1,
				'max'           => 20,
				'step'          => 1,
			),
			array(
				'key'           => 'field_534e8b23a22cf',
				'label'         => 'Tilt Map to 45°',
				'name'          => 'map_fourtyfive_degree',
				'type'          => 'true_false',
				'instructions'  => 'Note: This setting works for certain countries only and map zoom level 16+',
				'message'       => '',
				'default_value' => 0,
			),
			array(
				'key'          => 'field_534e86f767a19',
				'label'        => 'Map Pin Image',
				'name'         => 'map_pin',
				'type'         => 'image',
				'instructions' => 'Upload custom Pin image for map (optional)',
				'save_format'  => 'url',
				'preview_size' => 'thumbnail',
				'library'      => 'all',
			),
			array(
				'key'           => 'field_acoo5ihf0a',
				'label'         => 'Retina Pin',
				'name'          => 'map_pin_retina',
				'type'          => 'true_false',
				'instructions'  => 'If you want to use this as retina pin, check this button.',
				'message'       => '',
				'default_value' => 0,
			),
			array(
				'key'           => 'field_534e9d08cd6d3',
				'label'         => 'Shift Map Pin',
				'name'          => 'map_panby',
				'type'          => 'text',
				'instructions'  => 'You can shift the pin from the center of the screen using this field. Example values: <strong>0,200</strong>, <strong>-70,40</strong> or <strong>80</strong> (this will shift only the x axis)',
				'default_value' => '',
				'placeholder'   => '(x,y)',
				'prepend'       => '',
				'append'        => '',
				'formatting'    => 'html',
				'maxlength'     => '',
			),
			array(
				'key'   => 'field_534e2586a8848',
				'label' => 'Contact Form',
				'name'  => '',
				'type'  => 'tab',
			),
			array(
				'key'           => 'field_534e2e6433d02',
				'label'         => 'Contact Form TItle',
				'name'          => 'contact_form_title',
				'type'          => 'text',
				'default_value' => 'Contact Form',
				'placeholder'   => '',
				'prepend'       => '',
				'append'        => '',
				'formatting'    => 'html',
				'maxlength'     => '',
			),
			array(
				'key'           => 'field_534e25a6a884a',
				'label'         => 'Available Fields',
				'name'          => 'available_fields',
				'type'          => 'checkbox',
				'choices'       => array(
					'name'    => 'Name',
					'email'   => 'E-mail',
					'phone'   => 'Phone',
					'message' => 'Message',
				),
				'default_value' => 'name
	email
	message',
				'layout'        => 'horizontal',
			),
			array(
				'key'           => 'field_534e25daa884b',
				'label'         => 'Required Fields',
				'name'          => 'required_fields',
				'type'          => 'checkbox',
				'choices'       => array(
					'name'    => 'Name',
					'email'   => 'E-mail',
					'phone'   => 'Phone',
					'message' => 'Message',
				),
				'default_value' => 'name
	message',
				'layout'        => 'horizontal',
			),
			array(
				'key'           => 'field_534e26233b1fb',
				'label'         => 'Success Message',
				'name'          => 'success_message',
				'type'          => 'textarea',
				'default_value' => '<h4>Your email has been sent!</h4>
	Thank you for contacting us. We will respond to you as soon as possible.',
				'placeholder'   => '',
				'maxlength'     => '',
				'rows'          => '',
				'formatting'    => 'html',
			),
			array(
				'key'           => 'field_534e26463b1fc',
				'label'         => 'Email Notifications',
				'name'          => 'email_notifications',
				'type'          => 'email',
				'instructions'  => 'You can set different email to receive contact form notifications. Default email is <strong>admin email</strong>.',
				'default_value' => '',
				'placeholder'   => '',
				'prepend'       => '',
				'append'        => '',
			),
			array(
				'key'           => 'field_5346vmdo3dy7d',
				'label'         => 'Privacy policy',
				'name'          => 'privacy_text',
				'type'          => 'textarea',
				'instructions'  => 'Optionally add text about your site privacy policy to show when submitting the form. You can include links as well.',
				'default_value' => '',
				'placeholder'   => 'Example: This form collects your name and email so that we can subscribe you to our newsletter. Check our [oxygen_privacy_policy title="privacy policy"] to learn how we protect and manage your submitted data.',
				'maxlength'     => '',
				'rows'          => '',
				'formatting'    => 'html',
			),
			array(
				'key'   => 'field_534e7fb1295e9',
				'label' => 'Layout',
				'name'  => '',
				'type'  => 'tab',
			),
			array(
				'key'           => 'field_534e7ffe295ea',
				'label'         => 'Contact Page Blocks',
				'name'          => 'contact_page_blocks',
				'type'          => 'select',
				'instructions'  => 'Select visible blocks and their order.',
				'choices'       => array(
					'address_contact' => 'Address + Contact Form',
					'contact_address' => 'Contact Form + Address',
					'address'         => 'Address Only',
					'contact'         => 'Contact Form Only',
				),
				'default_value' => 'address_contact',
				'allow_null'    => 0,
				'multiple'      => 0,
			),
		),
		'location'   => array(
			array(
				array(
					'param'    => 'page_template',
					'operator' => '==',
					'value'    => 'contact.php',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options'    => array(
			'position'       => 'normal',
			'layout'         => 'default',
			'hide_on_screen' => array(
				0 => 'the_content',
				1 => 'revisions',
			),
		),
		'menu_order' => 0,
	) );
	register_field_group( array(
		'id'         => 'acf_slider-settings',
		'title'      => 'Slider Settings',
		'fields'     => array(
			array(
				'key'   => 'field_537d368a897fc',
				'label' => 'Select Slider',
				'name'  => 'revslider_id',
				'type'  => 'revslider',
			),
		),
		'location'   => array(
			array(
				array(
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => 'page',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options'    => array(
			'position'       => 'normal',
			'layout'         => 'default',
			'hide_on_screen' => array(),
		),
		'menu_order' => 0,
	) );
	register_field_group( array(
		'id'         => 'acf_testimonials',
		'title'      => 'Testimonials',
		'fields'     => array(
			array(
				'key'           => 'field_53c7c52a19326',
				'label'         => 'Link to Author',
				'name'          => 'link_to_author',
				'type'          => 'text',
				'default_value' => '',
				'placeholder'   => '',
				'prepend'       => '',
				'append'        => '',
				'formatting'    => 'none',
				'maxlength'     => '',
			),
			array(
				'key'           => 'field_53c7c54319327',
				'label'         => 'Open in New Window',
				'name'          => 'open_in_new_window',
				'type'          => 'true_false',
				'instructions'  => 'If there is typed any link, this option will take effect.',
				'message'       => '',
				'default_value' => 0,
			),
		),
		'location'   => array(
			array(
				array(
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => 'testimonial',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options'    => array(
			'position'       => 'normal',
			'layout'         => 'default',
			'hide_on_screen' => array(),
		),
		'menu_order' => 0,
	) );
}