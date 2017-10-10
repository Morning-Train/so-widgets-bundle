<?php
/*
Widget Name: Accordion
Description: An accordion to squeeze a lot of content into a small space.
Author: SiteOrigin
Author URI: https://siteorigin.com
*/

class SiteOrigin_Widget_Accordion_Widget extends SiteOrigin_Widget {
	function __construct() {
		
		parent::__construct(
			'sow-accordion',
			__( 'SiteOrigin Accordion', 'so-widgets-bundle' ),
			array(
				'description' => __( 'An accordion widget.', 'so-widgets-bundle' ),
			),
			array(),
			false,
			plugin_dir_path( __FILE__ )
		);
	}
	
	/**
	 * Initialize the accordion widget.
	 */
	function initialize() {
		$this->register_frontend_scripts(
			array(
				array(
					'sow-accordion',
					plugin_dir_url( __FILE__ ) . 'js/accordion' . SOW_BUNDLE_JS_SUFFIX . '.js',
					array( 'jquery' ),
					SOW_BUNDLE_VERSION
				)
			)
		);
	}
	
	function get_widget_form() {
		
		return array(
			'panels' => array(
				'type' => 'repeater',
				'label' => __( 'Panels', 'so-widgets-bundle' ),
				'fields' => array(
					'title' => array(
						'type' => 'text',
						'label' => __( 'Title', 'so-widgets-bundle' ),
					),
					'content_text' => array(
						'type'  => 'tinymce',
						'label' => __( 'Content', 'so-widgets-bundle' ),
					),
					'initial_state' => array(
						'type' => 'radio',
						'label' => __( 'Initial state', 'so-widgets-bundle' ),
						'description' => __( 'Whether this panel should be open or closed when the page first loads.', 'so-widgets-bundle' ),
						'options' => array(
							'open' => __( 'Open', 'so-widgets-bundle' ),
							'closed' => __( 'Closed', 'so-widgets-bundle' ),
						),
						'default' => 'closed',
					),
				),
			),
			'design' => array(
				'type' =>  'section',
				'label' => __( 'Design', 'so-widgets-bundle' ),
				'hide' => true,
				'fields' => array(
					'heading' => array(
						'type' => 'section',
						'label' => __( 'Headings', 'so-widgets-bundle' ),
						'hide' => true,
						'fields' => array(
							'background_color' => array(
								'type' => 'color',
								'label' => __( 'Background color', 'so-widgets-bundle' ),
								'default' => '#828282',
							),
							'background_hover_color' => array(
								'type' => 'color',
								'label' => __( 'Background hover color', 'so-widgets-bundle' ),
								'default' => '#8C8C8C',
							),
							'title_color' => array(
								'type' => 'color',
								'label' => __( 'Title color', 'so-widgets-bundle' ),
								'default' => '#FFFFFF',
							),
							'title_hover_color' => array(
								'type' => 'color',
								'label' => __( 'Title hover color', 'so-widgets-bundle' ),
							),
							'border_color' => array(
								'type' => 'color',
								'label' => __( 'Border color', 'so-widgets-bundle' ),
							),
							'border_hover_color' => array(
								'type' => 'color',
								'label' => __( 'Border hover color', 'so-widgets-bundle' ),
							),
							'border_width' => array(
								'type' => 'measurement',
								'label' => __( 'Border width', 'so-widgets-bundle' ),
							),
						),
					),
					'panels' => array(
						'type' => 'section',
						'label' => __( 'Panels', 'so-widgets-bundle' ),
						'hide' => true,
						'fields' => array(
							'background_color' => array(
								'type' => 'color',
								'label' => __( 'Background color',  'so-widgets-bundle' ),
								'default' => '#F9F9F9',
							),
							'border_color' => array(
								'type' => 'color',
								'label' => __( 'Border color', 'so-widgets-bundle' ),
							),
							'border_width' => array(
								'type' => 'measurement',
								'label' => __( 'Border width', 'so-widgets-bundle' ),
							),
							'margin_bottom' => array(
								'type' => 'measurement',
								'label' => __( 'Bottom margin', 'so-widgets-bundle' ),
								'default' => '10px',
							),
						),
					),
				),
			),
		);
	}
	
	public function get_less_variables( $instance ) {
		$design = $instance['design'];
		
		return array(
			'heading_background_color' => $design['heading']['background_color'],
			'heading_background_hover_color' => $design['heading']['background_hover_color'],
			'title_color' => $design['heading']['title_color'],
			'title_hover_color' => $design['heading']['title_hover_color'],
			'heading_border_color' => $design['heading']['border_color'],
			'heading_border_hover_color' => $design['heading']['border_hover_color'],
			'heading_border_width' => $design['heading']['border_width'],
			'has_heading_border_width' => empty( $design['heading']['border_width'] ) ? 'false' : 'true',
			'panels_background_color' => $design['panels']['background_color'],
			'panels_border_color' => $design['panels']['border_color'],
			'panels_border_width' => $design['panels']['border_width'],
			'has_panels_border_width' => empty( $design['panels']['border_width'] ) ? 'false' : 'true',
			'panels_margin_bottom' => $design['panels']['margin_bottom'],
		);
	}
	
	public function get_template_variables( $instance, $args ) {
		if( empty( $instance ) ) return array();
		
		foreach ( $instance['panels'] as &$panel ) {
			if ( empty( $panel['before_title'] ) ) {
				$panel['before_title'] = '';
			}
			if ( empty( $panel['after_title'] ) ) {
				$panel['after_title'] = '';
			}
		}
		
		if ( empty( $instance['design']['heading']['icon_open'] ) ) {
			$instance['design']['heading']['icon_open'] = 'ionicons-plus';
		}
		
		if ( empty( $instance['design']['heading']['icon_close'] ) ) {
			$instance['design']['heading']['icon_close'] = 'ionicons-minus';
		}
		
		return array(
			'panels' => $instance['panels'],
			'icon_open' => $instance['design']['heading']['icon_open'],
			'icon_close' => $instance['design']['heading']['icon_close'],
		);
	}
	
	public function render_panel_content( $panel, $instance ) {
		$content = wp_kses_post( $panel['content_text'] );
		
		echo apply_filters( 'siteorigin_widgets_accordion_render_panel_content', $content, $panel, $instance );
	}

	function get_form_teaser(){
		if( class_exists( 'SiteOrigin_Premium' ) ) return false;
		return sprintf(
			__( 'Get more customization options and the ability to use widgets and layouts as your accordion content with %sSiteOrigin Premium%s', 'so-widgets-bundle' ),
			'<a href="https://siteorigin.com/downloads/premium/?featured_addon=plugin/accordion" target="_blank">',
			'</a>'
		);
	}
}

siteorigin_widget_register('sow-accordion', __FILE__, 'SiteOrigin_Widget_Accordion_Widget');