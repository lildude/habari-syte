<?php if ( !defined( 'HABARI_PATH' ) ) { die( 'No direct access' ); } ?>
<p class="comment_box">
<textarea name="<?php echo $field; ?>" id="<?php echo $field; ?>"<?php echo ( isset( $class ) ? " class=\"$class\"" : '' ) . 
	" rows=\"" . ( isset( $rows ) ? $rows : 10 ) . "\" cols=\"" . ( isset( $cols ) ? $cols : 100 );
	if ( isset( $control_title ) ) { echo "\" title=\"$control_title\""; } else { echo ( isset( $title ) ? "\" title=\"$title\"" : '"' ); } if ( isset( $tabindex ) ) { ?> tabindex="<?php echo $tabindex; ?>"<?php } ?> <?php if ( isset( $required ) ) { ?>required=""<?php } ?>><?php 
echo htmlspecialchars( $value ); ?></textarea>
</p>
