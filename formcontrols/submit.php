<?php if ( !defined( 'HABARI_PATH' ) ) { die('No direct access'); } ?>
<p class="submit_btn">
	<input type="submit" <?php echo ( isset( $id ) ) ? 'id="'.$id.'"' : ''; ?>
<?php echo ( isset( $class ) ) ? ' class="'.$class.'"' : ''; ?>
<?php echo ( isset( $disabled ) && $disabled ) ? ' disabled' : ''; ?>
<?php echo ( isset( $tabindex ) ) ? ' tabindex="'.$tabindex.'"' : ''; ?> 
name="<?php echo $field; ?>" value="<?php echo htmlspecialchars( $caption ); ?>">
</p>
