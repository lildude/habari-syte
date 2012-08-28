<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<title><?php Options::out( 'title' ); ?> <?php if ( $request->display_entry && isset( $post ) ) { echo " :: {$post->title}"; } ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php if ( User::get_by_name( 'admin' )->info->imageurl != '' ) : ?>
	<link rel="icon" href="<?php echo User::get_by_name( 'admin' )->info->imageurl; ?>" type="image/png"> 
	<link rel="shortcut icon" href="<?php echo User::get_by_name( 'admin' )->info->imageurl; ?>" type="image/png">
<?php else : ?>
	<link rel="icon" href="<?php Site::out_url( 'theme' ); ?>/imgs/favicon.ico" type="image/x-icon"> 
	<link rel="shortcut icon" href="<?php Site::out_url( 'theme' ); ?>/imgs/favicon.ico" type="image/x-icon">
<?php endif; ?>
<?php echo $theme->header(); ?>
	
</head>
<body>
<header class="main-header">
	<hgroup>
		<div class="picture">
			<a href="<?php Site::out_url( 'habari' ); ?>/" rel="home"></a>
		</div>
		<h1><?php Options::out( 'title' ); ?></h1>
		<h2><?php Options::out( 'tagline' ); ?></h2>
	</hgroup>
  <nav>
    <ul class="main-nav">
		<li <?php if ( $request->display_home ) { echo 'class="sel"'; } ?>><a href="<?php Site::out_url( 'habari' ); ?>/" id="home-link"><?php echo _t( 'Home', 'syte'); ?></a></li>
		<?php echo $theme->area( 'sidebar' ); ?>
    </ul>
  </nav>
  <?php /* <a href="https://github.com/rigoneri/syte" class="fork-me">Fork me on Github</a> */ ?>
</header>