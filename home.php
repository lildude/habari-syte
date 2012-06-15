<?php  if ( !defined( 'HABARI_PATH' ) ) { die('No direct access'); } 
$theme->display( 'header' ); ?>

<?php /* Uncomment this if and when we get the json loading of posts working 
<section class="main-section blog-section" id="blog-posts">
  <span class="loading">loading blog posts ...</span>
</section>
*/ ?>

<section class="main-section blog-section" id="blog-posts">
	<?php foreach ( $posts as $post ) : ?>
	<article class="post">
		<header>
			<h1 class="post-title">
				<a href="<?php echo $post->permalink; ?>" title="<?php echo $post->title; ?>"><?php echo $post->title_out; ?></a>
				<?php if ( $post->comments->approved->count ) : // Only show comment count if there are comments ?>
				<span class="post-commentslink">(<a href="<?php echo $post->permalink; ?>#comments" title="Comments on this post"><?php echo $post->comments->approved->count . _n( ' Comment', ' Comments', $post->comments->approved->count ); ?></a>)</span>
				<?php endif; ?>
			</h1>
			<div class="post-meta">
			<?php if ( $request->display_page === false ) : ?>
				<time datetime="<?php echo $post->pubdate->text_format('{c}'); ?>" pubdate><?php $post->pubdate->out('d M y'); ?></time><span class="post-author visuallyhidden"> by <?php echo $post->author->displayname; ?></span>
				<span class="post-tags"><?php echo ( count( $post->tags ) > 0 ) ? $post->tags_out : " - None - "; ?></span>
			<?php endif; ?>
				<?php if ( $loggedin ) : ?><span class="post-edit"><a href="<?php echo $post->editlink; ?>" title="edit">&#x2386;</a></span><?php endif; ?>
			</div>
		</header>
		<?php // TODO: This should probably go in a plugin and added using a block
		$xDaysAgo = HabariDateTime::date_create()->modify( '-7  days' );
		//if ( $request->display_entry === true && ( $theme->isFromSearchEngine() || ( $xDaysAgo > $post->pubdate ) ) ) : // Display advert using Google DFP code 
		?>
		<div class="post-content">
			<?php echo $post->content_out; ?>
		</div>
	</article>
	<?php endforeach; ?>
</section>	
<?php $theme->display( 'footer' ); ?>