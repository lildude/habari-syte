<?php if ( !defined( 'HABARI_PATH' ) ) { die( 'No direct access' ); } ?>
<p class="comment_box">
<textarea <?php
	echo $control->parameter_map(
		array(
			'title' => array('control_title', 'title'),
			'tabindex', 'class', 'required', 'cols', 'rows',
			'id' => 'field',
			'name' => 'field',
		)
	);
	?>><?php echo htmlspecialchars( $value ); ?></textarea></p>