<?php if ( !defined( 'HABARI_PATH' ) ) { die( 'No direct access' ); } ?>
<p>
    <input<?php if ( isset( $control_title ) ) { ?> title="<?php echo $control_title; ?>"<?php } else { echo ( isset( $title ) ? " title=\"$title\"" : '' ); } if ( isset( $tabindex ) ) { ?> tabindex="<?php echo $tabindex; ?>"<?php } if ( isset( $size ) ) { ?> size="<?php echo $size; ?>"<?php } ?> type="<?php if ( User::identify()->loggedin ) { echo "hidden"; } else { if ( isset( $type ) ) { echo $type; } else { echo "text"; } } ?>" id="<?php echo $field; ?>" name="<?php echo $field; ?>" value="<?php echo htmlspecialchars( $value ); ?>" <?php if ( isset( $required ) ) { ?>required=""<?php } ?> <?php if ( isset( $placeholder ) ) { ?>placeholder="<?php echo $placeholder;?>"<?php } ?>>
    <?php if ( ! User::identify()->loggedin ) : ?>
	<label<?php if ( isset( $label_title ) ) { ?> title="<?php echo $label_title; ?>"<?php } else { echo ( isset( $title ) ? " title=\"$title\"" : '' ); } ?> for="<?php echo $field; ?>"><?php echo $this->caption; ?></label>
	<?php endif; ?>
</p>