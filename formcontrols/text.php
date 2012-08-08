<?php if ( !defined( 'HABARI_PATH' ) ) { die( 'No direct access' ); } ?>
	<input <?php
		echo $control->parameter_map(
			array(
				'title' => array('control_title', 'title'),
				'tabindex', 'size', 'maxlength', 'type', 'placeholder', 'required',
				'id' => 'field',
				'name' => 'field',
			),
			array(
				'value' => Utils::htmlspecialchars( $value ),
			)
		);
		?>>