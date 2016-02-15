<?php

/**
 * Created by IntelliJ IDEA.
 * User: hina
 * Date: 2016/02/09
 * Time: 5:46
 */
class Push7_Subscribe_Button_Widget extends WP_Widget {

	public function __construct() {
		parent::__construct(
			'push7-subscribe',
			__( 'Push7 Subscribe Button', 'push7-subscribe-button' ),
			array(
				'description' => __( 'Show push7 subscribe button.', 'push7-subscribe-button' ),
			)
		);
	}

	public function widget( $args, $instance ) {

	}


}
