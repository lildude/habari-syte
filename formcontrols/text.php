<?php if ( !defined( 'HABARI_PATH' ) ) { die( 'No direct access' ); } ?>
	<?php /* <input<?php if ( isset( $control_title ) ) { ?> title="<?php echo $control_title; ?>"
	<?php } else { echo ( isset( $title ) ? " title=\"$title\"" : '' ); } 
	if ( isset( $tabindex ) ) { ?> tabindex="<?php echo $tabindex; ?>"
	<?php } if ( isset( $size ) ) { ?> size="<?php echo $size; ?>"<?php } ?> 
	type="<?php if ( User::identify()->loggedin ) { echo "hidden"; } else { if ( isset( $type ) ) { echo $type; } else { echo "text"; } } ?>" 
	id="<?php echo $field; ?>" name="<?php echo $field; ?>" value="<?php echo htmlspecialchars( $value ); ?>" 
	<?php if ( isset( $required ) ) { ?>required=""<?php } ?> 
	<?php if ( isset( $placeholder ) ) { ?>placeholder="<?php echo $placeholder;?>"<?php } ?>>
	*/ ?>
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