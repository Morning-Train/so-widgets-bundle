<?php

/**
 * Class SiteOrigin_Widget_Field_Text
 */
abstract class SiteOrigin_Widget_Field_Text_Input_Base extends SiteOrigin_Widget_Field {

	/**
	 * A string to display before any text has been input.
	 *
	 * @access protected
	 * @var string
	 */
	protected $placeholder;
	/**
	 * If true, this field will not be editable.
	 *
	 * @access protected
	 * @var bool
	 */
	protected $readonly;
	/**
	 * The CSS classes to be applied to the rendered text input.
	 *
	 * @access protected
	 * @var array
	 */
	protected $input_classes;

	public function __construct( $base_name, $element_id, $element_name, $field_options ){
		parent::__construct( $base_name, $element_id, $element_name, $field_options );

		if( isset( $field_options['placeholder'] ) ) $this->placeholder = $field_options['placeholder'];
		if( isset( $field_options['readonly'] ) ) $this->readonly = $field_options['readonly'];

		$this->input_classes = array( 'widefat', 'siteorigin-widget-input' );
	}


	protected function render_field( $value, $instance ) {
		?>
		<input type="text" name="<?php echo $this->element_name ?>" id="<?php echo $this->element_id ?>"
		         value="<?php echo esc_attr( $value ) ?>"
		         <?php $this->render_input_classes() ?>
			<?php if ( ! empty( $this->placeholder ) ) echo 'placeholder="' . $this->placeholder . '"' ?>
			<?php if( ! empty( $this->readonly ) ) echo 'readonly' ?> />
		<?php
	}

	protected function render_input_classes() {
		if( !empty( $this->input_classes ) ) {
			?>class="<?php echo implode( ' ', array_map( 'sanitize_html_class', $this->input_classes ) ) ?>"<?php
		}
	}

	protected function sanitize_field_input( $value ) {
		$sanitized_value = wp_kses_post( $value );
		$sanitized_value = balanceTags( $sanitized_value , true );
		return $sanitized_value;
	}
}