<?php if ( !defined( 'HABARI_PATH' ) ) { die( 'No direct access' ); } ?>
	<?php 
    if ( Session::has_messages() ) {
		echo '<div>',Session::messages_out(),'</div>';
    }
	?>
	<form <?php
	$fixed = array(
		'method' => 'POST',
	);
	if($form->disabled) {
		$fixed['disabled'] = 'disabled';
	}
	echo $form->parameter_map(
		array(
			'class',
			'id',
			'action',
			'enctype',
			'accept-charset' => 'accept_charset',
			'onsubmit',
		),
		$fixed
	); ?>>
	<input type="hidden" name="FormUI" value="<?php echo $salted_name; ?>">
	<?php if ( User::identify()->loggedin ) : ?>
		<p>Logged in as <a href="<?php URL::out( 'admin', 'page=user&user=' . User::identify()->username ); ?>"><?php echo User::identify()->displayname; ?></a> [ <a href="<?php Site::out_url( 'habari' ); ?>/user/logout">Logout</a> ]</p>
	<?php endif; ?>
	<?php echo $pre_out; ?>
	<?php echo $controls; ?>
	</form>
