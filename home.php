<?php  if ( !defined( 'HABARI_PATH' ) ) { die('No direct access'); } 
$theme->display( 'header' ); ?>

<?php /* Uncomment this if and when we get the json loading of posts working 
<section class="main-section blog-section" id="blog-posts">
  <span class="loading">loading blog posts ...</span>
</section>
*/ ?>
<section class="main-section blog-section" id="blog-posts">
	<?php foreach ( $posts as $post ) : ?>
	<article class="post" id="<?php echo $post->id; ?>">
		<hgroup>
			<h2><a href="<?php echo $post->permalink; ?>" title="<?php echo $post->title; ?>"><?php echo $post->title_out; ?>&nbsp;</a></h2>
			<h3><a href="#<?php echo $post->id; ?>" class=""><?php $post->pubdate->out('d M y'); ?></a></h3>
		</hgroup>
		<?php echo $post->content_out; ?>
		<?php if ( count( $post->tags ) > 0 ) : ?>
		<footer>
			<h4>Tags</h4>
			<?php echo $post->tags_out; ?>
		</footer>
		<?php endif; ?>
		<section id="comments">
			<a href="<?php echo $post->permalink; ?>#comments"><h4><?php echo $post->comments->moderated->count . _n( ' Comment', ' Comments', $post->comments->moderated->count ); ?></h4></a>
			<?php if ( $request->display_entry ) $theme->display( 'comments' ); ?>
		</section>
	</article>
	<?php endforeach; ?>
</section>	
<?php $theme->display( 'footer' ); ?>