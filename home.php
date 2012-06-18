<?php  if ( !defined( 'HABARI_PATH' ) ) { die('No direct access'); } 
$theme->display( 'header' ); ?>

<?php /* Uncomment this if and when we get the json loading of posts working 
<section class="main-section blog-section" id="blog-posts">
  <span class="loading">loading blog posts ...</span>
</section>
*/ ?>
<?php /* implement this:
 <article id="{{ id }}">
  <hgroup>
    <h2><a href="/post/{{ id }}">{{ title }}&nbsp;</a></h2>
    <h3><a href="#{{ id }}">{{ formated_date }}</a></h3>
  </hgroup>
  {{{ body }}}
  {{#if tags}}
  <footer>
    <h4>Tags</h4>
    <ul class="tags">
      {{#each tags}}
      <li><a href="/tags/{{ this }}">{{ this }}</a></li>
      {{/each}}
    </ul>
  </footer>
  {{/if}}
</article>

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
			<?php $post->tags_out; ?>
		</footer>
		<?php endif; ?>
	</article>
	<?php endforeach; ?>
</section>	
<?php $theme->display( 'footer' ); ?>