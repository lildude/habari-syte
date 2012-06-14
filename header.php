<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<title><?php Options::out( 'title' ) ?><?php if ( $request->display_entry && isset( $post ) ) { echo $post->title; } ?></title>
	<meta name="description" content="<?php Options::get('about'); ?>" />
	<meta name="keywords" content="Rodrigo Neri, rigoneri, developer, designer, entrepreneur, instin, myhomework" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="icon" href="<?php Site::out_url( 'theme' ); ?>/imgs/favicon.ico" type="image/x-icon">
	<link rel="shortcut icon" href="<?php Site::out_url( 'theme' ); ?>/imgs/favicon.ico" type="image/x-icon">
	
	<!--[if lt IE 9]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
	<link rel="stylesheet/less" type="text/css" href="<?php echo Site::get_url( 'theme' ); ?>/css/less/styles.less">
	<?php echo $theme->header(); ?>
	
</head>
<body>
<header class="main-header">
	<hgroup>
		<div class="picture">
			<a href="/" rel="home"></a>
		</div>
		<?php // TODO: Make this configurable ?>
		<h1>Rodrigo Neri</h1>
		<h2>iOS & Web Developer + Designer + Entrepreneur Co-Founder of <a href="http://instin.com">Instin</a></h2>
	</hgroup>
  <nav>
	  <?php // TODO: Make these blocks and extensible by other plugins ?>
    <ul class="main-nav">
      <li><a href="/" id="home-link">Home</a></li>
      <li><a href="http://twitter.com/#!/rigoneri" id="twitter-link">Twitter</a></li>
      <li><a href="http://github.com/rigoneri" id="github-link">Github</a></li>
      <li><a href="http://dribbble.com/rigoneri" id="dribbble-link">Dribbble</a></li>
      <li><a href="http://instagr.am/p/Lau1VrEPbt" id="instagram-link">Instagram</a></li>
      <li><a href="mailto:contact@rigoneri.com?subject=Hello" id="contact-link">Contact</a></li>
    </ul>
  </nav>
  <a href="https://github.com/rigoneri/syte" class="fork-me">Fork me on Github</a>
</header>