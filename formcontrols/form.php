<?php if ( !defined( 'HABARI_PATH' ) ) { die('No direct access'); } ?>
	<?php 
    if ( Session::has_messages() ) {
		echo '<div>',Session::messages_out(),'</div>';
    }
	?>
	<form id="<?php echo $id; ?>" method="post" action="<?php echo $action; ?>"	enctype="<?php echo $enctype; ?>" <?php echo $onsubmit; ?>>
	<input type="hidden" name="FormUI" value="<?php echo $salted_name; ?>">
	<?php if ( User::identify()->loggedin ) : ?>
		<p>Logged in as <a href="<?php URL::out( 'admin', 'page=user&user=' . User::identify()->username ); ?>"><?php echo User::identify()->displayname; ?></a> [ <a href="<?php Site::out_url( 'habari' ); ?>/user/logout">Logout</a> ]</p>
	<?php endif; ?>
	<?php echo $pre_out; ?>
	<?php echo $controls; ?>
	</form>
