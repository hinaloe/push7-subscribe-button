<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Created by IntelliJ IDEA.
 * User: hina
 * Date: 2016/02/09
 * Time: 5:46
 */

/**
 * Push7 Subscribe Button Widget
 */
class Push7_Subscribe_Button_Widget extends WP_Widget {

	/**
	 * @var array
	 */
	private $mode_variation = array();

	/**
	 * Push7_Subscribe_Button_Widget constructor.
	 */
	public function __construct() {
		parent::__construct(
			'push7-subscribe',
			__( 'Push7 Subscribe Button', 'push7-subscribe-button' ),
			array(
				'description' => __( 'Show push7 subscribe button.', 'push7-subscribe-button' ),
			)
		);
		$this->mode_variation = array(
			'r' => __( 'Count on right', 'push7-subscribe-button' ),
			't' => __( 'Vertical', 'push7-subscribe-button' ),
			'n' => __( 'No count', 'push7-subscribe-button' ),
		);

	}

	/**
	 * Widget Front end
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		echo $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}

		$app_id = empty( $instance['appid'] ) ? Push7_Subscribe_Button::get_appid_inc_official() : $instance['appid'];
		$mode   = empty( $instance['mode'] ) ? '' : $instance['mode'];

		wp_enqueue_script( 'push7-subscribe-button' );
		echo Push7_Subscribe_Button::get_official_button( $app_id, $mode );
		echo $args['after_widget'];


	}

	/**
	 * @param array $instance
	 *
	 * @return string
	 */
	public function form( $instance ) {
		$title = isset( $instance['title'] ) ? $instance['title'] : __( 'Subscribe Push Notification', 'push7-subscribe-button' );
		$mode  = ! empty( $instance['mode'] ) ? $instance['mode'] : 'r';
		$appid = ! empty( $instance['appid'] ) ? $instance['appid'] : '';

		?>
		<p>
			<label
				for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title (Option):', 'push7-subscribe-button' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>"
			       name="<?php echo $this->get_field_name( 'title' ); ?>" type="text"
			       value="<?php echo esc_attr( $title ); ?>">

		</p>
		<p>
			<label
				for="<?php echo $this->get_field_id( 'mode' ); ?>"><?php _e( 'Mode:', 'push7-subscribe-button' ); ?></label>
			<select class="widefat" id="<?php echo $this->get_field_id( 'mode' ); ?>"
			        name="<?php echo $this->get_field_name( 'mode' ); ?>">
				<?php foreach ( $this->mode_variation as $m => $name ) :
					?>
					<option value="<?php echo $m; ?>" <?php selected( $mode, $m ); ?>><?php echo $name; ?></option><?php
				endforeach;
				?>
			</select>

		</p>

		<p>
			<label
				for="<?php echo $this->get_field_id( 'appid' ); ?>"><?php _e( 'APPID (Option):', 'push7-subscribe-button' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'appid' ); ?>"
			       name="<?php echo $this->get_field_name( 'appid' ); ?>" type="text"
			       value="<?php echo esc_attr( $appid ); ?>"
			       placeholder="<?php echo esc_attr( Push7_Subscribe_Button::get_appid_inc_official() ); ?>"
			       pattern="<?php echo Push7_Subscribe_Button::APP_ID_PATTERN; ?>"
			>

		</p>

		<?php
		return $instance;
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance          = array();
		$instance['title'] = ! empty( $new_instance['title'] ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['mode']  = in_array( $new_instance['mode'], array_keys( $this->mode_variation ), true ) ? $new_instance['mode'] : 'r';
		$instance['appid'] = preg_match( Push7_Subscribe_Button::APP_ID_PATTERN_PREG, $new_instance['appid'] ) === 1 ? $new_instance['appid'] : '';

		return $instance;
	}
}
